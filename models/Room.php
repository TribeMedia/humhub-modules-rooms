<?php
/**
 * Created by PhpStorm.
 * User: gqadonis
 * Date: 5/25/15
 * Time: 11:08 PM
 */

class Room extends HActiveRecordContentContainer implements ISearchable
 {

    // Join Policies
    const JOIN_POLICY_NONE = 0; // No Self Join Possible
    const JOIN_POLICY_APPLICATION = 1; // Invitation and Application Possible
    const JOIN_POLICY_FREE = 2; // Free for All
    // Visibility
    const VISIBILITY_NONE = 0; // Always invisible
    const VISIBILITY_REGISTERED_ONLY = 1; // Only for registered members
    const VISIBILITY_ALL = 2; // Visible for all (also guests)
    // Status
    const STATUS_DISABLED = 0; // Disabled
    const STATUS_ENABLED = 1; // Enabled
    const STATUS_ARCHIVED = 2; // Archived

    public $ownerUsernameSearch;

    /**
     * Add mix-ins to this model
     *
     * @return type
     */
    public function behaviors()
    {
        return array(
            'HGuidBehavior' => array(
                'class' => 'application.behaviors.HGuidBehavior',
            ),
            'RoomSettingBehavior' => array(
                'class' => 'application.modules.rooms.behaviors.RoomSettingBehavior',
            ),
            'RoomModelMembershipBehavior' => array(
                'class' => 'application.modules.rooms.behaviors.RoomModelMembershipBehavior',
            ),
            'RoomModelModulesBehavior' => array(
                'class' => 'application.modules.rooms.behaviors.RoomModelModulesBehavior',
            ),
        );
    }

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Room the static model class
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
        return 'room';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {

        $rules = array();

        if ($this->scenario == 'edit') {
            $rules = array(
                array('name', 'required'),
                array('name', 'unique', 'caseSensitive' => false, 'className' => 'Room', 'message' => '{attribute} "{value}" is already in use! '),
                array('website', 'url'),
                array('description, tags', 'safe'),
                array('join_policy', 'in', 'range' => array(0, 1, 2)),
                array('visibility', 'checkVisibility'),
                array('visibility', 'in', 'range' => array(0, 1, 2)),
            );

            if (Yii::app()->user->isAdmin() && HSetting::Get('enabled', 'authentication_ldap')) {
                $rules[] = array('ldap_dn', 'length', 'max' => 255);
            }

            return $rules;
        }

        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name', 'required'),
            array('wall_id, join_policy, visibility, auto_add_new_members, created_by, updated_by', 'numerical', 'integerOnly' => true),
            array('name, website', 'length', 'max' => 45),
            array('ldap_dn', 'length', 'max' => 255),
            array('website', 'url'),
            array('name', 'unique', 'caseSensitive' => false, 'className' => 'Room', 'message' => '{attribute} "{value}" is already in use! '),
            array('join_policy', 'in', 'range' => array(0, 1, 2)),
            array('visibility', 'in', 'range' => array(0, 1, 2)),
            array('status', 'in', 'range' => array(0, 1, 2)),
            array('tags, description, created_at, updated_at, guid', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, wall_id, name, description, website, join_policy, visibility, tags, created_at, created_by, updated_at, updated_by, ownerUsernameSearch', 'safe', 'on' => 'search'),
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
            // Active Invites
            'userInvites' => array(self::HAS_MANY, 'UserInvite', 'room_invite_id'),
            // List of room applicants
            'applicants' => array(self::HAS_MANY, 'RoomMembership', 'room_id', 'condition' => 'status=' . RoomMembership::STATUS_APPLICANT),
            // Approved Membership Only
            'memberships' => array(self::HAS_MANY, 'RoomMembership', 'room_id',
                'condition' => 'memberships.status=' . RoomMembership::STATUS_MEMBER,
                'order' => 'admin_role DESC, share_role DESC'
            ),
            // Approved Membership Only
            'membershipsLimited' => array(self::HAS_MANY, 'RoomMembership', 'room_id',
                'condition' => 'status=' . RoomMembership::STATUS_MEMBER,
                'order' => 'admin_role DESC, share_role DESC',
                'limit' => 50,
            ),
            'wall' => array(self::BELONGS_TO, 'Wall', 'wall_id'),
            'createdBy' => array(self::BELONGS_TO, 'User', 'created_by'),
            'updatedBy' => array(self::BELONGS_TO, 'User', 'updated_by'),
            'owner' => array(self::BELONGS_TO, 'User', 'updated_by'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'wall_id' => 'Wall',
            'name' => Yii::t('RoomsModule.models_Room', 'Name'),
            'description' => Yii::t('RoomsModule.models_Room', 'Description'),
            'website' => Yii::t('RoomsModule.models_Room', 'Website URL (optional)'),
            'join_policy' => Yii::t('RoomsModule.models_Room', 'Join Policy'),
            'ldap_dn' => Yii::t('RoomsModule.models_Room', 'Ldap DN'),
            'visibility' => Yii::t('RoomsModule.models_Room', 'Visibility'),
            'status' => Yii::t('RoomsModule.models_Room', 'Status'),
            'tags' => Yii::t('RoomsModule.models_Room', 'Tags'),
            'created_at' => Yii::t('RoomsModule.models_Room', 'Created At'),
            'created_by' => Yii::t('RoomsModule.models_Room', 'Created By'),
            'updated_at' => Yii::t('RoomsModule.models_Room', 'Updated At'),
            'updated_by' => Yii::t('RoomsModule.models_Room', 'Updated by'),
            'ownerUsernameSearch' => Yii::t('RoomsModule.models_Room', 'Owner'),
        );
    }

    /**
     * Scopes
     *
     */
    public function scopes()
    {
        return array(
            // Coming soon
            'active' => array(
                'condition' => $this->getTableAlias() . '.status=' . self::STATUS_ENABLED,
            ),
            'visible' => array(
                'condition' => $this->getTableAlias() . '.visibility != ' . Room::VISIBILITY_NONE,
            ),
            'recently' => array(
                'order' => $this->getTableAlias() . '.created_at DESC',
                'limit' => 10,
            ),
        );
    }

    /**
     * Parameterized Scope for Recently
     *
     * @param type $limit
     * @return User
     */
    public function recently($limit = 10)
    {
        $this->getDbCriteria()->mergeWith(array(
            'order' => 'created_at DESC',
            'limit' => $limit,
        ));
        return $this;
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        $criteria = new CDbCriteria;
        $criteria->compare('id', $this->id);
        $criteria->compare('wall_id', $this->wall_id);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('description', $this->description, true);
        $criteria->compare('website', $this->website, true);
        $criteria->compare('join_policy', $this->join_policy);
        $criteria->compare('t.visibility', $this->visibility);
        $criteria->compare('tags', $this->tags, true);
        $criteria->compare('created_at', $this->created_at, true);
        $criteria->compare('created_by', $this->created_by);
        $criteria->compare('updated_at', $this->updated_at, true);
        $criteria->compare('updated_by', $this->updated_by);

        $criteria->compare('owner.username', $this->ownerUsernameSearch, true);
        $criteria->join = 'JOIN user owner ON (owner.id=t.created_by)';

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * After Save Addons
     */
    protected function afterSave()
    {

        if ($this->status != self::VISIBILITY_NONE) {
            Yii::app()->search->update($this);
        } else {
            Yii::app()->search->delete($this);
        }

        $userId = $this->created_by;

        if ($this->isNewRecord) {
            // Create new wall record for this room
            $wall = new Wall();
            $wall->object_model = 'Room';
            $wall->object_id = $this->id;
            $wall->save();
            $this->wall_id = $wall->id;
            $this->wall = $wall;
            Room::model()->updateByPk($this->id, array('wall_id' => $wall->id));

            // Auto add creator as admin
            $membership = new RoomMembership();
            $membership->room_id = $this->id;
            $membership->user_id = $userId;
            $membership->status = RoomMembership::STATUS_MEMBER;
            $membership->invite_role = 1;
            $membership->admin_role = 1;
            $membership->share_role = 1;
            $membership->save();

            $activity = new Activity;
            $activity->content->created_by = $userId;
            $activity->content->room_id = $this->id;
            $activity->content->user_id = $userId;
            $activity->content->visibility = Content::VISIBILITY_PUBLIC;
            $activity->created_by = $userId;
            $activity->type = "ActivityRoomCreated";
            $activity->save();
            $activity->fire();
        }

        Yii::app()->cache->delete('userRooms_' . $userId);

        parent::afterSave();
    }

    /**
     * Before deletion of a Room
     */
    protected function beforeDelete()
    {

        foreach (RoomSetting::model()->findAllByAttributes(array('room_id' => $this->id)) as $roomSetting) {
            $roomSetting->delete();
        }

        // Disable all enabled modules
        foreach ($this->getAvailableModules() as $moduleId => $module) {
            if ($this->isModuleEnabled($moduleId)) {
                $this->disableModule($moduleId);
            }
        }

        Yii::app()->search->delete($this);

        $this->getProfileImage()->delete();

        // Remove all Follwers
        //UserFollow::model()->deleteAllByAttributes(array('object_id' => $this->id, 'object_model' => 'Room'));

        //Delete all memberships:
        //First select, then delete - done to make sure that RoomsMembership::beforeDelete() is triggered
        $roomMemberships = RoomMembership::model()->findAllByAttributes(array('room_id' => $this->id));
        foreach ($roomMemberships as $roomMembership) {
            $roomMembership->delete();
        }

        UserInvite::model()->deleteAllByAttributes(array('room_invite_id' => $this->id));

        // Delete all content objects of this room
        foreach (Content::model()->findAllByAttributes(array('room_id' => $this->id)) as $content) {
            $content->delete();
        }

        // When this room is used in a group as default room, delete the link
        foreach (Group::model()->findAllByAttributes(array('room_id' => $this->id)) as $group) {
            $group->room_id = "";
            $group->save();
        }

        Wall::model()->deleteAllByAttributes(array('id' => $this->wall_id));

        return parent::beforeDelete();
    }

    /**
     * Indicates that this user can join this room
     *
     * @param $userId User Id of User
     */
    public function canJoin($userId = "")
    {
        if (Yii::app()->user->isGuest) {
            return false;
        }

        // Take current userid if none is given
        if ($userId == "")
            $userId = Yii::app()->user->id;

        // Checks if User is already member
        if ($this->isMember($userId))
            return false;

        // No one can join
        if ($this->join_policy == self::JOIN_POLICY_NONE)
            return false;

        return true;
    }

    /**
     * Indicates that this user can join this room without permission
     *
     * @param $userId User Id of User
     */
    public function canJoinFree($userId = "")
    {
        // Take current userid if none is given
        if ($userId == "")
            $userId = Yii::app()->user->id;

        // Checks if User is already member
        if ($this->isMember($userId))
            return false;

        // No one can join
        if ($this->join_policy == self::JOIN_POLICY_FREE)
            return true;

        return false;
    }

    /**
     * Check if current user can write to this room
     *
     * @param type $userId
     * @return type
     */
    public function canWrite($userId = "")
    {

        // No writes allowed for archived rooms
        if ($this->status == Room::STATUS_ARCHIVED)
            return false;

        // Take current userid if none is given
        if ($userId == "")
            $userId = Yii::app()->user->id;

        // User needs to be member to post
        if ($this->isMember($userId))
            return true;

        return false;
    }

    /**
     * Checks if given user can invite people to this room
     *
     * @param type $userId
     * @return type
     */
    public function canInvite($userId = "")
    {
        if (Yii::app()->user->isGuest) {
            return false;
        }

        if ($userId == 0)
            $userId = Yii::app()->user->id;

        $membership = $this->getMembership($userId);

        if ($membership != null && $membership->invite_role == 1 && $membership->status == RoomMembership::STATUS_MEMBER)
            return true;

        if ($this->isAdmin($userId)) {
            return true;
        }

        return false;
    }

    /**
     * Checks if given user can share content.
     * Shared Content is public and is visible also for non members of the room.
     *
     * @param type $userId
     * @return type
     */
    public function canShare($userId = "")
    {

        if ($userId == "")
            $userId = Yii::app()->user->id;

        $membership = $this->getMembership($userId);


        if ($membership != null && $membership->share_role == 1 && $membership->status == RoomMembership::STATUS_MEMBER)
            return true;

        return false;
    }

    /**
     * Returns an array of informations used by search subsystem.
     * Function is defined in interface ISearchable
     *
     * @return Array
     */
    public function getSearchAttributes()
    {
        return array(
            'title' => $this->name,
            'tags' => $this->tags,
            'description' => $this->description,
        );
    }

    /**
     * Returns the Search Result Output
     */
    public function getSearchResult()
    {
        return Yii::app()->getController()->widget('application.modules.rooms.widgets.RoomSearchResultWidget', array('room' => $this), true);
    }

    /**
     * Counts all Content Items related to this room except of Activities.
     * Additonally Comments (normally ContentAddon) will be included.
     */
    public function countItems()
    {

        $count = 0;
        $count += Content::model()->countByAttributes(array('room_id' => $this->id), 'object_model != :activityModel', array(':activityModel' => 'Activity'));
        $count += $this->getCommentCount();

        return $count;
    }

    /**
     * Sets Comments Count for this room
     */
    public function getCommentCount()
    {
        $cacheId = "roomCommentCount_" . $this->id;
        $cacheValue = Yii::app()->cache->get($cacheId);

        if ($cacheValue === false) {
            $newCacheValue = Comment::model()->countByAttributes(array('room_id' => $this->id));
            Yii::app()->cache->set($cacheId, $newCacheValue, HSetting::Get('expireTime', 'cache'));
            return $newCacheValue;
        } else {
            return $cacheValue;
        }
    }

    /**
     * Returns an array with assigned Tags
     */
    public function getTags()
    {

        // split tags string into individual tags
        return preg_split("/[;,# ]+/", $this->tags);
    }

    /**
     * Archive this Room
     */
    public function archive()
    {
        $this->status = self::STATUS_ARCHIVED;
        $this->save();
    }

    /**
     * Unarchive this Room
     */
    public function unarchive()
    {
        $this->status = self::STATUS_ENABLED;
        $this->save();
    }

    /**
     * Returns the url to the room.
     *
     * @param array $parameters
     * @return string url
     */
    public function getUrl($parameters = array())
    {
        return $this->createUrl('//rooms/view/', $parameters);
    }

    /**
     * Creates an url in room scope.
     * (Adding sguid parameter to identify current room.)
     * See CController createUrl() for more details.
     *
     * @since 0.9
     * @param type $route the URL route.
     * @param type $params additional GET parameters.
     * @param type $ampersand the token separating name-value pairs in the URL.
     */
    public function createUrl($route, $params = array(), $ampersand = '&')
    {
        if (!isset($params['sguid'])) {
            $params['sguid'] = $this->guid;
        }

        if (Yii::app()->getController() !== null) {
            return Yii::app()->getController()->createUrl($route, $params, $ampersand);
        } else {
            return Yii::app()->createUrl($route, $params, $ampersand);
        }
    }

    /**
     * Validator for visibility
     *
     * Used in edit scenario to check if the user really can create rooms
     * on this visibility.
     *
     * @param type $attribute
     * @param type $params
     */
    public function checkVisibility($attribute, $params)
    {
        if (!Yii::app()->user->canCreatePublicSpaces() && ($this->$attribute == 1 || $this->$attribute == 2)) {
            $this->addError($attribute, Yii::t('RoomsModule.models_Room', 'You cannot create public visible rooms!'));
        }

        if (!Yii::app()->user->canCreatePrivateSpaces() && $this->$attribute == 0) {
            $this->addError($attribute, Yii::t('RoomsModule.models_Room', 'You cannot create private visible rooms!'));
        }
    }

    /**
     * Returns display name (title) of room
     *
     * @since 0.11.0
     * @return string
     */
    public function getDisplayName()
    {
        return $this->name;
    }

    public function canAccessPrivateContent(User $user = null)
    {
        return ($this->isMember());
    }

    public function getWallOut()
    {
        return Yii::app()->getController()->widget('application.modules.rooms.widgets.RoomWallWidget', array('room' => $this), true);
    }
 }