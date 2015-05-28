<?php
/**
 * Created by PhpStorm.
 * User: gqadonis
 * Date: 5/28/15
 * Time: 3:20 AM
 */

class RoomModuleBehavior extends CBehavior
{

    /**
     * Returns current room by context
     *
     * @return Room
     */
    public function getRoom()
    {
        return Yii::app()->getController()->getRoom();
    }

    /**
     * Checks if this module is enabled on given room.
     *
     * @param Room $room
     * @return boolean
     */
    public function isRoomModuleEnabled(Room $room = null)
    {
        if ($room == null) {
            $room = $this->getRoom();
        }

        return $room->isModuleEnabled($this->getOwner()->getId());
    }

    /**
     * Returns module name for rooms of your module.
     * You may want to overwrite it in your module.
     *
     * @return String
     */
    public function getRoomModuleName()
    {
        return $this->getOwner()->getName();
    }

    /**
     * Returns module description for rooms of your module.
     * You may want to overwrite it in your module.
     *
     * @return String
     */
    public function getRoomModuleDescription()
    {
        return $this->getOwner()->getDescription();
    }

    /**
     * Returns module config url for rooms of your module.
     * You may want to overwrite it in your module.
     *
     * @return String
     */
    public function getRoomModuleConfigUrl(Room $room)
    {
        return "";
    }

    /**
     * Returns the module image for room admins.
     * You may want to overwrite with an special room image.
     *
     * @return String
     */
    public function getRoomModuleImage()
    {
        return $this->getOwner()->getImage();
    }

    /**
     * Enables this module on given room
     *
     * @param Room $room
     */
    public function enableRoomModule(Room $room)
    {

    }

    /**
     * Disables this module on given room
     *
     * You may want to overwrite this function and delete e.g. created
     * content objects.
     *
     * @param Room $room
     */
    public function disableRoomModule(Room $room)
    {

    }

    /**
     * Returns a list of all rooms where this RoomModule is
     * enabled.
     *
     * @return Array Room
     */
    public function getRoomModuleRooms()
    {
        $rooms = array();

        foreach (Room::model()->findAll() as $s) {
            if ($s->isModuleEnabled($this->owner->getId())) {
                $rooms[] = $s;
            }
        }

        return $rooms;
    }

}
