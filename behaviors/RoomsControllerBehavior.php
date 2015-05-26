<?php
/**
 * Created by PhpStorm.
 * User: gqadonis
 * Date: 5/25/15
 * Time: 11:54 PM
 */

class RoomsControllerBehavior extends CBehavior {

    public $room = null;

    /**
     * Returns the current selected space by parameter guid
     *
     * If space doesnt exists or there a no permissions and exception
     * will thrown.
     *
     * @return Room
     * @throws CHttpException
     */
    public function getRoom()
    {

        if ($this->room != null) {
            return $this->room;
        }

        // Get Space GUID by parameter
        $guid = Yii::app()->request->getQuery('sguid');
        if ($guid == "") {
            // Workaround for older version
            $guid = Yii::app()->request->getQuery('guid');
        }

        // Try Load the room
        $this->room = Room::model()->findByAttributes(array('guid' => $guid));
        if ($this->room == null)
            throw new CHttpException(404, Yii::t('RoomsModule.behaviors_RoomsControllerBehavior', 'Room not found!'));

        $this->checkAccess();

        // Store current space to stash
        Yii::app()->params['currentRoom'] = $this->room;

        return $this->room;
    }

    public function checkAccess()
    {

        if ($this->room->visibility != Space::VISIBILITY_ALL && Yii::app()->user->isGuest) {
            throw new CHttpException(401, Yii::t('RoomsModule.behaviors_RoomsControllerBehavior', 'You need to login to view contents of this room!'));
        }

        // Save users last action on this space
        $membership = $this->room->getMembership(Yii::app()->user->id);
        if ($membership != null) {
            $membership->updateLastVisit();
        } else {

            // Super Admin can always enter
            if (!Yii::app()->user->isAdmin()) {
                // Space invisible?
                if ($this->room->visibility == Room::VISIBILITY_NONE) {
                    // Not Space Member
                    throw new CHttpException(404, Yii::t('RoomsModule.behaviors_RoomControllerBehavior', 'Room is invisible!'));
                }
            }
        }

        // Delete all pending notifications for this space
        $notifications = Notification::model()->findAllByAttributes(array('room_id' => $this->room->id, 'user_id' => Yii::app()->user->id), 'seen != 1');
        foreach ($notifications as $n) {
            // Ignore Approval Notifications
            if ($n->class == "RoomApprovalRequestNotification" || $n->class == "RoomInviteNotification") {
                continue;
            }
            $n->seen = 1;
            $n->save();
        }
    }

    /**
     * Create a room url
     *
     * @deprecated since version 0.9
     * @param type $route
     * @param type $params
     * @param type $ampersand
     * @return type
     */
    public function createRoomUrl($route, $params = array(), $ampersand = '&')
    {
        return $this->room->createUrl($route, $params, $ampersand);
    }
}