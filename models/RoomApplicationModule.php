<?php
/**
 * Created by PhpStorm.
 * User: gqadonis
 * Date: 5/28/15
 * Time: 2:50 AM
 */

class RoomApplicationModule extends HActiveRecord
{

    private static $_states = array();

    const STATE_DISABLED = 0;
    const STATE_ENABLED = 1;
    const STATE_FORCE_ENABLED = 2;
    const STATES_CACHE_ID_PREFIX = 'room_module_states_';

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return SpaceApplicationModule the static model class
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
        return 'room_module';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('module_id, room_id', 'required'),
            array('room_id, state', 'numerical', 'integerOnly' => true),
            array('module_id', 'length', 'max' => 255),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'room' => array(self::BELONGS_TO, 'Room', 'room_id'),
        );
    }

    public function beforeSave()
    {

        if ($this->room_id == "") {
            $this->room_id = 0;
        }

        Yii::app()->cache->delete(self::STATES_CACHE_ID_PREFIX . $this->room_id);

        return parent::beforeSave();
    }

    public function beforeDelete()
    {
        Yii::app()->cache->delete(self::STATES_CACHE_ID_PREFIX . $this->room_id);

        return parent::beforeDelete();
    }

    /**
     * Returns an array of moduleId and the their states (enabled, disabled, force enabled)
     * for given space id. If space id is 0 or empty, the default states will be returned.
     *
     * @param int $roomId
     * @return array State of Module Ids
     */
    public static function getStates($roomId = 0)
    {
        if (isset(self::$_states[$roomId])) {
            return self::$_states[$roomId];
        }

        $states = Yii::app()->cache->get(self::STATES_CACHE_ID_PREFIX . $roomId);
        if ($states === false) {
            $states = array();
            foreach (RoomApplicationModule::model()->findAllByAttributes(array('room_id' => $roomId)) as $roomModule) {
                $states[$roomModule->module_id] = $roomModule->state;
            }
            Yii::app()->cache->set(self::STATES_CACHE_ID_PREFIX . $roomId, $states);
        }

        self::$_states[$roomId] = $states;

        return self::$_states[$roomId];
    }

}
