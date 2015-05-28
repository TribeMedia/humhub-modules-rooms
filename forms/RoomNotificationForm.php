<?php
/**
 * Created by PhpStorm.
 * User: gqadonis
 * Date: 5/26/15
 * Time: 1:44 AM
 */

class RoomNotificationForm extends CFormModel {

    public $strength;
    public $scope;

    /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */
    public function rules() {
        return array(
            array('strength, scope', 'safe'),
        );
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels() {
        return array(
            'strength' => Yii::t('RoomsModule.forms_RoomNotificationForm', 'Strength'),
            'scope' => Yii::t('RoomsModule.forms_RoomNotificationForm', 'Scope'),
        );
    }

}
