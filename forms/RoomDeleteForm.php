<?php
/**
 * Created by PhpStorm.
 * User: gqadonis
 * Date: 5/26/15
 * Time: 1:43 AM
 */

class RoomDeleteForm extends CFormModel {

    public $currentPassword;

    /**
     * Declares the validation rules.
     */
    public function rules() {
        return array(
            array('currentPassword', 'required'),
            array('currentPassword', 'CheckPasswordValidator'),
        );
    }

    /**
     * Declares customized attribute labels.
     * If not declared here, an attribute would have a label that is
     * the same as its name with the first letter in upper case.
     */
    public function attributeLabels() {
        return array(
            'currentPassword' => Yii::t('RoomsModule.forms_RoomDeleteForm', 'Your password'),
        );
    }

}
