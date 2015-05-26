<?php
/**
 * Created by PhpStorm.
 * User: gqadonis
 * Date: 5/25/15
 * Time: 11:43 PM
 */

class RoomsController extends ContentContainerController {

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return array(
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'users' => array("@", (HSetting::Get('allowGuestAccess', 'authentication_internal')) ? "?" : "@"),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    public function actions()
    {
        return array(
            'stream' => array(
                'class' => 'application.modules_core.wall.ContentContainerStreamAction',
                'mode' => BaseStreamAction::MODE_NORMAL,
                'contentContainer' => $this->getRoom()
            ),
        );
    }

    /**
     * Generic Start Action for Profile
     */
    public function actionIndex()
    {
        $this->pageTitle = $this->getRoom()->name;
        $this->render('index', array());
    }

    /**
     * Provides a searchable user list of all workspace members in json.
     *
     */
    public function actionSearchMemberJson()
    {

        $room = $this->getRoom();

        if (!$room->isMember()) {
            throw new CHttpException(404, Yii::t('RoomsModule.controllers_RoomsController', 'This action is only available for workspace members!'));
        }

        $maxResults = 10;
        $results = array();
        $keyword = Yii::app()->request->getParam('keyword');
        $keyword = Yii::app()->input->stripClean($keyword);


        // Build Search Condition
        $params = array();
        $condition = "room_membership.status=" . RoomMembership::STATUS_MEMBER;
        $condition .= " AND room_id=" . $room->id;
        $parts = explode(" ", $keyword);
        $i = 0;
        foreach ($parts as $part) {
            $i++;
            $condition .= " AND (u.email LIKE :match{$i} OR "
                . "u.username LIKE :match{$i} OR "
                . "p.firstname LIKE :match{$i} OR "
                . "p.lastname LIKE :match{$i} OR "
                . "p.title LIKE :match{$i})";

            $params[':match' . $i] = "%" . $part . "%";
        }

        $sql = "SELECT DISTINCT u.* FROM room_membership
                LEFT JOIN user u ON u.id=room_membership.user_id
                LEFT JOIN profile p ON p.user_id=u.id
                WHERE " . $condition . " LIMIT 0," . $maxResults;

        $users = User::model()->findAllBySql($sql, $params);

        foreach ($users as $user) {
            $userInfo['guid'] = $user->guid;
            $userInfo['displayName'] = CHtml::encode($user->displayName);
            $userInfo['email'] = $user->email;
            $userInfo['image'] = $user->getProfileImage()->getUrl();
            $userInfo['link'] = $user->getProfileUrl();
            $results[] = $userInfo;
        }

        print CJSON::encode($results);
        Yii::app()->end();
    }

    /**
     * Requests Membership for this Space
     */
    public function actionRequestMembership()
    {

        $room = $this->getRoom();

        if (!$room->canJoin(Yii::app()->user->id))
            throw new CHttpException(500, Yii::t('RoomsModule.controllers_RoomsController', 'You are not allowed to join this space!'));

        if ($room->join_policy == Space::JOIN_POLICY_APPLICATION) {
            // Redirect to Membership Request Form
            return $this->redirect($this->createUrl('//rooms/room/requestMembershipForm', array('sguid' => $this->getRoom()->guid)));
        }

        $room->addMember(Yii::app()->user->id);
        return $this->htmlRedirect($room->getUrl());
    }

    /**
     * Requests Membership Form for this Space
     * (If a message is required.)
     *
     */
    public function actionRequestMembershipForm()
    {

        $room = $this->getRoom();

        // Check if we have already some sort of membership
        if (Yii::app()->user->isGuest || $room->getMembership(Yii::app()->user->id) != null) {
            throw new CHttpException(500, Yii::t('RoomsModule.controllers_RoomsController', 'Could not request membership!'));
        }

        $model = new SpaceRequestMembershipForm;

        if (isset($_POST['ajax']) && $_POST['ajax'] === 'workspace-apply-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        if (isset($_POST['SpaceRequestMembershipForm'])) {

            $_POST['SpaceRequestMembershipForm'] = Yii::app()->input->stripClean($_POST['SpaceRequestMembershipForm']);

            $model->attributes = $_POST['SpaceRequestMembershipForm'];

            if ($model->validate()) {

                $room->requestMembership(Yii::app()->user->id, $model->message);

                $output = $this->renderPartial('requestMembershipSave', array('model' => $model, 'workspace' => $room));
                Yii::app()->clientScript->render($output);
                echo $output;
                Yii::app()->end();
                return;
            }
        }

        $output = $this->renderPartial('requestMembership', array('model' => $model, 'space' => $room));
        Yii::app()->clientScript->render($output);
        echo $output;
        Yii::app()->end();
    }

    /**
     * Revokes Membership for this workspace
     */
    public function actionRevokeMembership()
    {

        $room = $this->getRoom();

        if ($room->isSpaceOwner()) {
            throw new CHttpException(500, Yii::t('RoomsModule.controllers_RoomsController', 'As owner you cannot revoke your membership!'));
        }

        $room->removeMember();
        $this->redirect($this->createUrl('//'));
    }

    /**
     * Invite New Members to this workspace
     */
    public function actionInvite()
    {

        $room = $this->getRoom();

        // Check Permissions to Invite
        if (!$room->canInvite()) {
            throw new CHttpException(403, 'Access denied - You cannot invite members!');
        }

        $model = new SpaceInviteForm();
        $model->space = $room;

        if (isset($_POST['SpaceInviteForm'])) {

            $_POST['SpaceInviteForm'] = Yii::app()->input->stripClean($_POST['SpaceInviteForm']);
            $model->attributes = $_POST['SpaceInviteForm'];

            if ($model->validate()) {

                // check if both invite inputs are empty
                if ($model->invite == "" && $model->inviteExternal == "") {

                } else {

                    // Invite existing members
                    foreach ($model->getInvites() as $user) {
                        $room->inviteMember($user->id, Yii::app()->user->id);
                        $statusInvite = $room->getMembership($user->id)->status;
                    }

                    if (HSetting::Get('internalUsersCanInvite', 'authentication_internal')) {
                        // Invite non existing members
                        foreach ($model->getInvitesExternal() as $email) {
                            $statusInvite = ($room->inviteMemberByEMail($email, Yii::app()->user->id)) ? RoomMembership::STATUS_INVITED : false;
                        }
                    }

                    // close modal
                    //$this->renderModalClose();

                    $output = $this->renderPartial('statusInvite', array('status' => $statusInvite));
                    Yii::app()->clientScript->render($output);
                    echo $output;
                    Yii::app()->end();
                }
            }
        }

        $output = $this->renderPartial('invite', array('model' => $model, 'space' => $room));
        Yii::app()->clientScript->render($output);
        echo $output;
        Yii::app()->end();
    }

    /**
     * When a user clicks on the Accept Invite Link, this action is called.
     * After this the user should be member of this workspace.
     */
    public function actionInviteAccept()
    {

        // Get Current Space
        $room = $this->getRoom();

        // Load Pending Membership
        $membership = RoomMembership::model()->findByAttributes(array('user_id' => Yii::app()->user->id, 'room_id' => $room->id));
        if ($membership == null) {
            throw new CHttpException(404, Yii::t('RoomsModule.controllers_RoomsController', 'There is no pending invite!'));
        }

        // Check there are really an Invite
        if ($membership->status == RoomMembership::STATUS_INVITED) {
            $room->addMember(Yii::app()->user->id);
            //SpaceInviteAcceptedNotification::fire($membership->originator_user_id, Yii::app()->user, $room);
        }

        $this->redirect($room->getUrl());
    }
}