<?php
/**
 * Created by PhpStorm.
 * User: gqadonis
 * Date: 5/26/15
 * Time: 12:03 AM
 */

class RoomSettingBehavior extends CActiveRecordBehavior
{

    /**
     * Get an SpaceSetting Value
     *
     * @param String $name of setting
     * @param String $moduleId of setting
     * @param String $default value when no setting exists
     * @return String
     */
    public function getSetting($name, $moduleId = "core", $default = "")
    {
        return RoomSetting::Get($this->getOwner()->id, $name, $moduleId, $default);
    }

    /**
     * Sets an SpaceSetting
     *
     * @param String $name
     * @param String $value
     * @param String $moduleId
     */
    public function setSetting($name, $value, $moduleId = "")
    {
        RoomSetting::Set($this->getOwner()->id, $name, $value, $moduleId);
    }

}
