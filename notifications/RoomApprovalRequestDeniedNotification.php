<?php
/**
 * Created by PhpStorm.
 * User: gqadonis
 * Date: 5/25/15
 * Time: 11:13 PM
 */

class RoomApprovalRequestDeclinedNotification extends Notification {

    public $webView = "rooms.views.notifications.approvalRequestDeclined";
    public $mailView = "application.modules.rooms.views.notifications.approvalRequestDeclined_mail";

    public static function fire($approverUserId, $requestor, $room) {

        // Send Notification to owner
        $notification = new Notification();
        $notification->class = "RoomApprovalRequestDeclinedNotification";
        $notification->user_id = $requestor->id;
        $notification->space_id = $room->id;

        $notification->source_object_model = "User";
        $notification->source_object_id = $approverUserId;

        $notification->target_object_model = "Room";
        $notification->target_object_id = $room->id;

        $notification->save();
    }

    public function redirectToTarget() {

        $room = $this->getTargetObject();
        Yii::app()->getController()->redirect($room->getUrl());
    }

}

?>
