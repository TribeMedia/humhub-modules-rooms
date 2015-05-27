<?php
/**
 * Created by PhpStorm.
 * User: gqadonis
 * Date: 5/27/15
 * Time: 4:37 PM
 */

class RoomHeaderWidget extends HWidget
{

    public $room;

    public function init()
    {
        // Only include uploading javascripts if user is space admin
        if ($this->room->isAdmin()) {
            $assetPrefix = Yii::app()->assetManager->publish(dirname(__FILE__) . '/../resources', true, 0, defined('YII_DEBUG'));
            Yii::app()->clientScript->registerScriptFile($assetPrefix . '/roomHeaderImageUpload.js');

            Yii::app()->clientScript->setJavascriptVariable('profileImageUploaderUrl', $this->room->createUrl('//rooms/admin/imageUpload'));
            Yii::app()->clientScript->setJavascriptVariable('profileHeaderUploaderUrl', $this->room->createUrl('//rooms/admin/bannerImageUpload'));
        }
    }

    public function run()
    {
        $this->render('roomHeader', array(
            'room' => $this->room,
        ));
    }

}

?>