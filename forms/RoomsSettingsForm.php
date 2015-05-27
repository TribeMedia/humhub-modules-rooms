<?php
/**
 * Created by PhpStorm.
 * User: gqadonis
 * Date: 5/27/15
 * Time: 2:48 AM
 */

class RoomsSettingsForm extends CFormModel
{

    public $defaultVisibility;
    public $defaultJoinPolicy;

    /**
     * Declares the validation rules.
     */
    public function rules()
    {
        return array(
            array('defaultVisibility, defaultJoinPolicy', 'safe'),
        );
    }

    /**
     * Declares customized attribute labels.
     * If not declared here, an attribute would have a label that is
     * the same as its name with the first letter in upper case.
     */
    public function attributeLabels()
    {
        return array(
            'defaultVisibility' => Yii::t('AdminModule.forms_SpaceSettingsForm', 'Default Visibility'),
            'defaultJoinPolicy' => Yii::t('AdminModule.forms_SpaceSettingsForm', 'Default Join Policy'),
        );
    }

}
