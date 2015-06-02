<?php
/**
 * Created by PhpStorm.
 * User: gqadonis
 * Date: 5/26/15
 * Time: 12:01 AM
 */

class RoomModelMembershipBehavior extends CActiveRecordBehavior
{

    private $_roomOwner = null;

    /**
     * Checks if given Userid is Member of this Room.
     *
     * @param type $userId
     * @return type
     */
    public function isMember($userId = "")
    {

        // Take current userid if none is given
        if ($userId == "")
            $userId = Yii::app()->user->id;

        $membership = $this->getMembership($userId);

        if ($membership != null && $membership->status == RoomMembership::STATUS_MEMBER)
            return true;

        return false;
    }

    /**
     * Checks if given Userid is Admin of this Room.
     *
     * If no UserId is given, current UserId will be used
     *
     * @param type $userId
     * @return type
     */
    public function isAdmin($userId = "")
    {

        if ($userId == 0)
            $userId = Yii::app()->user->id;

        if (Yii::app()->user->isAdmin())
            return true;

        if ($this->isRoomOwner($userId))
            return true;

        $membership = $this->getMembership($userId);

        if ($membership != null && $membership->admin_role == 1 && $membership->status == RoomMembership::STATUS_MEMBER)
            return true;

        return false;
    }

    /**
     * Sets Owner for this workspace
     *
     * @param type $userId
     * @return type
     */
    public function setRoomOwner($userId = "")
    {

        if ($userId == 0)
            $userId = Yii::app()->user->id;

        $this->setAdmin($userId);

        $this->getOwner()->created_by = $userId;
        $this->getOwner()->save();

        $this->_roomOwner = null;

        return true;
    }

    /**
     * Gets Owner for this workspace
     *
     * @return type
     */
    public function getRoomOwner()
    {

        if ($this->_roomOwner != null) {
            return $this->_roomOwner;
        }

        $this->_roomOwner = User::model()->findByPk($this->getOwner()->created_by);
        return $this->_roomOwner;
    }

    /**
     * Is given User owner of this Room
     */
    public function isRoomOwner($userId = "")
    {
        if ($userId == "")
            $userId = Yii::app()->user->id;

        if ($this->getRoomOwner()->id == $userId) {
            return true;
        }

        return false;
    }

    /**
     * Sets Owner for this workspace
     *
     * @param type $userId
     * @return type
     */
    public function setAdmin($userId = "")
    {

        if ($userId == 0)
            $userId = Yii::app()->user->id;

        $membership = $this->getMembership($userId);
        if ($membership != null) {
            $membership->admin_role = 1;
            $membership->save();
            return true;
        }
        return false;
    }

    /**
     * Returns the RoomMembership Record for this Room
     *
     * If none Record is found, null is given
     */
    public function getMembership($userId = "")
    {
        if ($userId == "")
            $userId = Yii::app()->user->id;

        $rCacheId = 'RoomMembership_' . $userId . "_" . $this->getOwner()->id;
        $rCacheRes = RuntimeCache::Get($rCacheId);

        if ($rCacheRes != null)
            return $rCacheRes;

        $dbResult = RoomMembership::model()->findByAttributes(array('user_id' => $userId, 'room_id' => $this->getOwner()->id));
        RuntimeCache::Set($rCacheId, $dbResult);

        return $dbResult;
    }

    /**
     * Invites a not registered member to this space
     *
     * @param type $email
     * @param type $originatorUserId
     */
    public function inviteMemberByEMail($email, $originatorUserId)
    {

        // Invalid E-Mail
        $validator = new CEmailValidator;
        if (!$validator->validateValue($email))
            return false;

        // User already registered
        $user = User::model()->findByAttributes(array('email' => $email));
        if ($user != null)
            return false;

        $userInvite = UserInvite::model()->findByAttributes(array('email' => $email));

        // No invite yet
        if ($userInvite == null) {
            // Invite EXTERNAL user
            $userInvite = new UserInvite();
            $userInvite->email = $email;
            $userInvite->source = UserInvite::SOURCE_INVITE;
            $userInvite->user_originator_id = $originatorUserId;
            $userInvite->room_invite_id = $this->getOwner()->id;
            $userInvite->save();
            $this->sendInviteMail($userInvite);

            // There is a pending registration
            // Steal it und send mail again
            // Unfortunately there a no multiple workspace invites supported
            // so we take the last one
        } else {
            $userInvite->user_originator_id = $originatorUserId;
            $userInvite->room_invite_id = $this->getOwner()->id;
            $userInvite->save();
            //$userInvite->sendInviteMail();
            $this->sendInviteMail($userInvite);
        }
        return true;
    }

    public function sendInviteMail($userInvite) {

        // Switch to systems default language
        Yii::app()->language = HSetting::Get('defaultLanguage');

        $message = new HMailMessage();
        $message->view = "application.modules.rooms.views.mails.UserInviteRoom";
        $message->addFrom(HSetting::Get('systemEmailAddress', 'mailing'), HSetting::Get('systemEmailName', 'mailing'));
        $message->addTo($userInvite->email);
        $message->subject = Yii::t('RoomsModule.views_mails_UserInviteRoom', 'Room Invite');
        $message->setBody(array(
            'originator' => $userInvite->userOriginator,
            'originatorName' => $userInvite->userOriginator->displayName,
            'token' => $userInvite->token,
            'roomName' => $this->getOwner()->name,
        ), 'text/html');
        Yii::app()->mail->send($message);

        // Switch back to users language
        if (Yii::app()->user->language !== "") {
            Yii::app()->language = Yii::app()->user->language;
        }
    }

    /**
     * Requests Membership
     *
     * @param type $userId
     * @param type $message
     */
    public function requestMembership($userId, $message = "")
    {

        // Add Membership
        $membership = new RoomMembership;
        $membership->room_id = $this->getOwner()->id;
        $membership->user_id = $userId;
        $membership->status = RoomMembership::STATUS_APPLICANT;
        $membership->invite_role = 0;
        $membership->admin_role = 0;
        $membership->share_role = 0;
        $membership->request_message = $message;
        $membership->save();

        RoomApprovalRequestNotification::fire($userId, $this->getOwner());
    }

    /**
     * Returns the Admins of this Room
     */
    public function getAdmins()
    {

        $admins = array();

        $adminMemberships = RoomMembership::model()->findAllByAttributes(array('room_id' => $this->getOwner()->id, 'admin_role' => 1));

        foreach ($adminMemberships as $admin) {
            $admins[] = $admin->user;
        }

        return $admins;
    }

    /**
     * Invites a registered user to this space
     *
     * If user is already invited, retrigger invitation.
     * If user is applicant approve it.
     *
     * @param type $userId
     * @param type $originatorUserId
     */
    public function inviteMember($userId, $originatorUserId)
    {

        $membership = $this->getMembership($userId);

        if ($membership != null) {

            // User is already member
            if ($membership->status == RoomMembership::STATUS_MEMBER) {
                return;
            }

            // User requested already membership, just approve him
            if ($membership->status == RoomMembership::STATUS_APPLICANT) {
                $membership->addMember(Yii::app()->user->id);
                return;
            }

            // Already invite, reinvite him
            if ($membership->status == RoomMembership::STATUS_INVITED) {
                // Remove existing notification
                RoomInviteNotification::remove($userId, $this->getOwner());
            }
        } else {
            $membership = new RoomMembership;
        }


        $membership->room_id = $this->getOwner()->id;
        $membership->user_id = $userId;
        $membership->originator_user_id = $originatorUserId;

        $membership->status = RoomMembership::STATUS_INVITED;
        $membership->invite_role = 0;
        $membership->admin_role = 0;
        $membership->share_role = 0;

        $membership->save();

        RoomInviteNotification::fire($originatorUserId, $userId, $this->getOwner());
    }

    /**
     * Adds an member to this space.
     *
     * This can happens after an clicking "Request Membership" Link
     * after Approval or accepting an invite.
     *
     * @param type $userId
     */
    public function addMember($userId)
    {

        $user = User::model()->findByPk($userId);
        $membership = $this->getMembership($userId);

        if ($membership == null) {
            // Add Membership
            $membership = new RoomMembership;
            $membership->room_id = $this->getOwner()->id;
            $membership->user_id = $userId;
            $membership->status = RoomMembership::STATUS_MEMBER;
            $membership->invite_role = 0;
            $membership->admin_role = 0;
            $membership->share_role = 0;

            $userInvite = UserInvite::model()->findByAttributes(array('email' => $user->email));
            if ($userInvite !== null && $userInvite->source == UserInvite::SOURCE_INVITE) {
                RoomInviteAcceptedNotification::fire($userInvite->user_originator_id, $user, $this->getOwner());
            }
        } else {

            // User is already member
            if ($membership->status == RoomMembership::STATUS_MEMBER) {
                return true;
            }

            // User requested membership
            if ($membership->status == RoomMembership::STATUS_APPLICANT) {
                RoomApprovalRequestAcceptedNotification::fire(Yii::app()->user->id, $user, $this->getOwner());
            }

            // User was invited
            if ($membership->status == RoomMembership::STATUS_INVITED) {
                RoomInviteAcceptedNotification::fire($membership->originator_user_id, $user, $this->getOwner());
            }

            // Update Membership
            $membership->status = RoomMembership::STATUS_MEMBER;
        }
        $membership->save();

        // Create Wall Activity for that
        $activity = new Activity;
        $activity->content->room_id = $this->getOwner()->id;
        $activity->content->visibility = Content::VISIBILITY_PRIVATE;
        $activity->content->created_by = $this->getOwner()->id;
        $activity->created_by = $userId;
        $activity->type = "ActivityRoomMemberAdded";
        $activity->save();
        $activity->fire();

        // Members can't also follow the space
        $this->getOwner()->unfollow($userId);

        // Cleanup Notifications
        RoomInviteNotification::remove($userId, $this->getOwner());
        RoomApprovalRequestNotification::remove($userId, $this->getOwner());
    }

    /**
     * Remove Membership
     *
     * @param $userId UserId of User to Remove
     */
    public function removeMember($userId = "")
    {

        if ($userId == "")
            $userId = Yii::app()->user->id;

        $user = User::model()->findByPk($userId);
        $membership = $this->getMembership($userId);


        if ($this->isRoomOwner($userId)) {
            return false;
        }

        if ($membership == null) {
            return true;
        }

        // If was member, create a activity for that
        if ($membership->status == RoomMembership::STATUS_MEMBER) {
            $activity = new Activity;
            $activity->content->room_id = $this->getOwner()->id;
            $activity->content->visibility = Content::VISIBILITY_PRIVATE;
            $activity->type = "ActivityRoomMemberRemoved";
            $activity->created_by = $userId;
            $activity->save();
            $activity->fire();
        }

        // Was invited, but declined the request
        if ($membership->status == RoomMembership::STATUS_INVITED) {
            RoomInviteDeclinedNotification::fire($membership->originator_user_id, $user, $this->getOwner());
        }

        foreach (RoomMembership::model()->findAllByAttributes(array(
            'user_id' => $userId,
            'room_id' => $this->getOwner()->id,
        )) as $membership) {
            $membership->delete();
        }

        // Cleanup Notifications
        RoomApprovalRequestNotification::remove($userId, $this->getOwner());
        RoomInviteNotification::remove($userId, $this->getOwner());
        RoomApprovalRequestNotification::remove($userId, $this->getOwner());
    }

}