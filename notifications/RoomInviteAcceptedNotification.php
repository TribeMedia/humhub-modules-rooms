<?php
/**
 * Created by PhpStorm.
 * User: gqadonis
 * Date: 5/25/15
 * Time: 11:11 PM
 */

class RoomInviteAcceptedNotification extends Notification {

    // Path to Web View of this Notification
    public $webView = "rooms.views.notifications.inviteAccepted";
    // Path to Mail Template for this notification
    public $mailView = "application.modules.rooms.views.notifications.inviteAccepted_mail";

    public static function fire($invitorUserId, $invitedUser, $room) {

        // Send Notification to owner
        $notification = new Notification();
        $notification->class = "RoomInviteAcceptedNotification";
        $notification->user_id = $invitorUserId;
        $notification->room_id = $room->id;

        $notification->source_object_model = "User";
        $notification->source_object_id = $invitedUser->id;

        $notification->target_object_model = "Room";
        $notification->target_object_id = $room->id;

        if(!isset(Yii::app()->user->id))
            $notification->created_by = $invitedUser->id;

        $notification->save();
    }

    public function redirectToTarget() {
        Yii::app()->getController()->redirect($this->room->getUrl());
    }

}

?>
