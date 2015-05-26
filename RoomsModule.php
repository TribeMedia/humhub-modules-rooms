<?php
/**
 * Created by PhpStorm.
 * User: gqadonis
 * Date: 5/25/15
 * Time: 11:08 PM
 */

class RoomsModule extends HWebModule
{

    public function init()
    {
        $this->setImport(array(
            'rooms.models.*',
            'rooms.forms.*',
            'rooms.controllers.*',
            'rooms.widgets.*',
        ));
    }

    /**
     * On rebuild of the search index, rebuild all space records
     *
     * @param type $event
     */
    public static function onSearchRebuild($event)
    {
        foreach (Room::model()->findAll() as $obj) {
            if ($obj->visibility != Space::VISIBILITY_NONE) {
                Yii::app()->search->add($obj);
            }
        }
    }

    /**
     * On User delete, also delete his space related stuff
     *
     * @param type $event
     */
    public static function onUserDelete($event)
    {

        $user = $event->sender;

        // Check if the user owns some spaces
        foreach (RoomMembership::GetUserRooms($user->id) as $room) {
            if ($room->isRoomOwner($user->id)) {
                throw new CHttpException(500, Yii::t('RoomsModule.base', 'Could not delete user who is a room owner! Name of Room: {roomName}', array('roomName' => $room->name)));
            }
        }

        // Cancel all space memberships
        foreach (RoomMembership::model()->findAllByAttributes(array('user_id' => $user->id)) as $membership) {
            $membership->room->removeMember($user->id);
        }

        // Cancel all space invites by the user
        foreach (RoomMembership::model()->findAllByAttributes(array('originator_user_id' => $user->id, 'status' => SpaceMembership::STATUS_INVITED)) as $membership) {
            $membership->room->removeMember($membership->user_id);
        }

        return true;
    }

}
