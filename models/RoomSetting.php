<?php
/**
 * Created by PhpStorm.
 * User: gqadonis
 * Date: 5/25/15
 * Time: 11:13 PM
 */

class RoomSetting extends HActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return RoomSetting the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'room_setting';
    }

    /**
     * Returns the Cache ID for this RoomSetting Entry
     *
     * @return String
     */
    public function getCacheId()
    {
        return "RoomSetting_" . $this->space_id . "_" . $this->name . "_" . $this->module_id;
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('room_id, created_by, updated_by', 'numerical', 'integerOnly' => true),
            array('module_id, name', 'length', 'max' => 100),
            array('value', 'length', 'max' => 255),
            array('created_at, updated_at', 'safe'),
        );
    }

    public function beforeSave()
    {
        Yii::app()->cache->delete($this->getCacheId());
        RuntimeCache::Remove($this->getCacheId());

        return parent::beforeSave();
    }

    public function beforeDelete()
    {
        Yii::app()->cache->delete($this->getCacheId());
        RuntimeCache::Remove($this->getCacheId());

        return parent::beforeDelete();
    }

    /**
     * Add or update an Room setting
     *
     * @param type $roomId
     * @param type $name
     * @param type $value
     * @param type $moduleId
     */
    public static function Set($roomId, $name, $value, $moduleId = "core")
    {

        if ($moduleId == "") {
            $moduleId = "core";
        }

        $record = self::GetRecord($roomId, $name, $moduleId);
        $record->value = $value;
        $record->name = $name;
        $record->module_id = $moduleId;
        $record->module_id = $moduleId;

        if ($value == "") {
            if (!$record->isNewRecord) {
                $record->delete();
            }
        } else {
            $record->save();
        }
    }

    /**
     * Returns an Room Setting
     *
     * @param Stringn $roomId
     * @param Strign $name
     * @param Strign $moduleId
     * @param String $defaultValue
     *
     * @return type
     */
    public static function Get($roomId, $name, $moduleId = "core", $defaultValue = "")
    {
        $record = self::GetRecord($roomId, $name, $moduleId);

        if ($record->isNewRecord) {
            return $defaultValue;
        }

        return $record->value;
    }

    /**
     * Returns a settings record by Name and Module Id
     * The result is cached.
     *
     * @param type $roomId
     * @param type $name
     * @param type $moduleId
     * @return \HSetting
     */
    private static function GetRecord($roomId, $name, $moduleId = "core")
    {

        if ($moduleId == "") {
            $moduleId = "core";
        }

        $cacheId = 'RoomSetting_' . $roomId . '_' . $name . '_' . $moduleId;

        // Check if stored in Runtime Cache
        if (RuntimeCache::Get($cacheId) !== false) {
            return RuntimeCache::Get($cacheId);
        }

        // Check if stored in Cache
        $cacheValue = Yii::app()->cache->get($cacheId);
        if ($cacheValue !== false) {
            return $cacheValue;
        }

        $condition = "";
        $params = array('name' => $name, 'room_id' => $roomId);

        if ($moduleId != "") {
            $params['module_id'] = $moduleId;
        } else {
            $condition = "module_id IS NULL";
        }

        $record = SpaceSetting::model()->findByAttributes($params, $condition);

        if ($record == null) {
            $record = new RoomSetting;
            $record->room_id = $roomId;
            $record->module_id = $moduleId;
            $record->name = $name;
        } else {
            $expireTime = 3600;
            if ($record->name != 'expireTime' && $record->module_id != "cache")
                $expireTime = HSetting::Get('expireTime', 'cache');

            Yii::app()->cache->set($cacheId, $record, $expireTime);
            RuntimeCache::Set($cacheId, $record);
        }

        return $record;
    }
}