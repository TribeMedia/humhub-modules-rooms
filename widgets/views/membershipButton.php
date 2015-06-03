<?php
if ($membership === null) {
    if ($room->canJoin()) {
        if ($room->join_policy == Room::JOIN_POLICY_APPLICATION) {
            echo HHtml::link(Yii::t('RoomsModule.widgets_views_membershipButton', 'Request membership'), $room->createUrl('//rooms/rooms/requestMembershipForm'), array('class' => 'btn btn-primary', 'data-toggle' => 'modal', 'data-target' => '#globalModal'));
        } else {
            echo HHtml::link(Yii::t('RoomsModule.widgets_views_membershipButton', 'Become member'), $room->createUrl('//rooms/rooms/requestMembership'), array('class' => 'btn btn-primary'));
        }
    }
} elseif ($membership->status == RoomMembership::STATUS_INVITED) {
    echo HHtml::link(Yii::t('RoomsModule.widgets_views_membershipButton', 'Accept Invite'), $room->createUrl('//rooms/membership/inviteAccept'), array('class' => 'btn btn-primary'));
    echo HHtml::link(Yii::t('RoomsModule.widgets_views_membershipButton', 'Deny Invite'), $room->createUrl('//rooms/rooms/revokeMembership'), array('class' => 'btn btn-primary'));
} elseif ($membership->status == RoomMembership::STATUS_APPLICANT) {
    echo HHtml::link(Yii::t('RoomsModule.widgets_views_membershipButton', 'Cancel pending membership application'), $room->createUrl('//rooms/rooms/revokeMembership'), array('class' => 'btn btn-primary'));
} else {
    if (!$room->isRoomOwner()) {
        echo CHtml::link(Yii::t('RoomsModule.widgets_views_membershipButton', "Cancel membership"), $this->createUrl('//rooms/rooms/revokeMembership', array('sguid' => $room->guid)), array('class' => 'btn btn-danger'));
    }
}