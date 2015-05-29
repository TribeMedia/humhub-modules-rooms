<?php
/**
 * Created by PhpStorm.
 * User: gqadonis
 * Date: 5/25/15
 * Time: 11:12 PM
 */

class RoomApprovalRequestNotification extends Notification {

    public $webView = "rooms.views.notifications.approvalRequest";
    public $mailView = "application.modules.rooms.views.notifications.approvalRequest_mail";

    public static function fire($userId, $room) {

        // Get Approval Users
        $admins = $room->getAdmins();

        $user = User::model()->findByPk($userId);

        // Send them a notification
        foreach ($admins as $admin) {

            // Send Notification to owner
            $notification = new Notification();
            $notification->class = "RoomApprovalRequestNotification";
            $notification->user_id = $admin->id;
            $notification->space_id = $room->id;

            $notification->source_object_model = "User";
            $notification->source_object_id = $user->id;

            $notification->target_object_model = "Room";
            $notification->target_object_id = $room->id;

            $notification->save();
        }
    }

    /**
     * Remove notification after member was approved/declined or canceled the
     * request.
     *
     * @param type $userId
     * @param type $workspace
     */
    public static function remove($userId, $room) {

        $notifications = Notification::model()->findAllByAttributes(array(
            'class' => 'RoomApprovalRequestNotification',
            'target_object_model' => 'Room',
            'target_object_id' => $room->id,
            'source_object_model' => 'User',
            'source_object_id' => $userId
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
