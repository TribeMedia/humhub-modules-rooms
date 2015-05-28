<?php
/**
 * Created by PhpStorm.
 * User: gqadonis
 * Date: 5/26/15
 * Time: 1:44 AM
 */

class RoomRequestMembershipForm extends CFormModel {

    public $room_id;
    public $message;

    /**
     * Declares the validation rules.
     */
    public function rules() {
        return array(
            array('message', 'required'),
        );
    }

    /**
     * Declares customized attribute labels.
     * If not declared here, an attribute would have a label that is
     * the same as its name with the first letter in upper case.
     */
    public function attributeLabels() {
        return array(
            'message' => Yii::t('RoomsModule.forms_RoomMembershipForm', 'Application message'),
        );
    }

}