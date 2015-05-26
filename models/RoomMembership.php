<?php
/**
 * Created by PhpStorm.
 * User: gqadonis
 * Date: 5/25/15
 * Time: 11:13 PM
 */

class RoomMembership extends HActiveRecord {

    const STATUS_INVITED = 1;
    const STATUS_APPLICANT = 2;
    const STATUS_MEMBER = 3;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return SpaceSetting the static model class
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
        return 'room_membership';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('room_id, user_id', 'required'),
            array('room_id, user_id, status, invite_role, admin_role, share_role, created_by, updated_by', 'numerical', 'integerOnly' => true),
            array('originator_user_id', 'length', 'max' => 45),
            array('request_message, last_visit, created_at, updated_at', 'safe'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'user' => array(self::BELONGS_TO, 'User', 'user_id'),
            'room' => array(self::BELONGS_TO, 'Room', 'room_id'),

        );
    }

    /**
     * Before saving this record.
     *
     * @return type
     */
    protected function beforeSave()
    {
        Yii::app()->cache->delete('userRooms_' . $this->user_id);
        return parent::beforeSave();
    }

    /**
     * Before delete record
     *
     * @return type
     */
    protected function beforeDelete()
    {
        Yii::app()->cache->delete('userRooms_' . $this->user_id);
        return parent::beforeDelete();
    }

    /**
     * Update last visit
     */
    public function updateLastVisit()
    {
        $this->last_visit = new CDbExpression('NOW()');
        $this->saveAttributes(array('last_visit'));
    }

    /**
     * Counts all new Items for this membership
     */
    public function countNewItems($since = "")
    {

        $count = 0;

        $connection = Yii::app()->db;

        // Count new Wall Entries
        $sql = "SELECT COUNT(*) FROM wall_entry " .
            "LEFT JOIN content ON wall_entry.content_id = content.id " .
            "WHERE content.object_model!='Activity' AND wall_entry.wall_id=:wall_id AND wall_entry.created_at>:last_visit";

        $wallId = $this->room->wall_id;
        $lastVisit = $this->last_visit;
        $command = $connection->createCommand($sql);
        $command->bindParam(":wall_id", $wallId);
        $command->bindParam(":last_visit", $lastVisit);
        $count += $command->queryScalar();

        // Count new comments
        $sql = "SELECT COUNT(*) FROM comment WHERE room_id=:room_id AND created_at>:last_visit";
        $roomId = $this->room->id;
        $lastVisit = $this->last_visit;
        $command = $connection->createCommand($sql);
        $command->bindParam(":room_id", $roomId);
        $command->bindParam(":last_visit", $lastVisit);
        $count += $command->queryScalar();

        return $count;
    }

    /**
     * Returns a list of all spaces of the given userId
     *
     * @param type $userId
     */
    public static function GetUserRooms($userId = "")
    {

        // Take current userid if none is given
        if ($userId == "")
            $userId = Yii::app()->user->id;

        $cacheId = "userRooms_" . $userId;
        $cacheValue = Yii::app()->cache->get($cacheId);
        $orderSetting = HSetting::Get('roomOrder', 'room');

        if ($cacheValue === false) {
            $criteria = new CDbCriteria();

            if ($orderSetting == 0) {
                $criteria->order = 'name ASC';
            } else {
                $criteria->order = 'last_visit DESC';
            }

            $rooms = array();
            $memberships = RoomMembership::model()->with('room')->findAllByAttributes(array(
                'user_id' => $userId,
                'status' => RoomMembership::STATUS_MEMBER,
            ), $criteria);

            foreach ($memberships as $membership) {
                $rooms[] = $membership->room;
            }

            Yii::app()->cache->set($cacheId, $rooms, HSetting::Get('expireTime', 'cache'));
            return $rooms;
        } else {
            return $cacheValue;
        }
    }

}