<?php
/**
 * Created by PhpStorm.
 * User: gqadonis
 * Date: 5/25/15
 * Time: 11:11 PM
 */

class RoomInviteNotification extends Notification {

    public $webView = "rooms.views.notifications.invite";
    public $mailView = "application.modules.rooms.views.notifications.invite_mail";

    public static function fire($originatorUserId, $userId, $room) {


        $user = User::model()->findByPk($userId);
        $originator = User::model()->findByPk($originatorUserId);

        // Send Notification to owner
        $notification = new Notification();
        $notification->class = "SpaceInviteNotification";
        $notification->user_id = $user->id;
        $notification->room_id = $room->id;

        $notification->source_object_model = "User";
        $notification->source_object_id = $originator->id;

        $notification->target_object_model = "Room";
        $notification->target_object_id = $room->id;

        $notification->save();
    }

    /**
     * Remove notification after member had approved/declined the invite
     *
     * @param type $userId
     * @param type $room
     */
    public static function remove($userId, $room) {

        $notifications = Notification::model()->findAllByAttributes(array(
            'class' => 'RoomInviteNotification',
            'target_object_model' => 'Room',
            'target_object_id' => $room->id,
            'user_id' => $userId
        ));

        foreach ($notifications as $notification) {
            $notification->delete();
        }
    }

    public function redirectToTarget() {

        $room = $this->getTargetObject();
        Yii::app()->getController()->redirect($room->getUrl());
    }

}

?>
