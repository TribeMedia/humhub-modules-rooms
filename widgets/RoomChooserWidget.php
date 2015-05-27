<?php

/**
 * Created by PhpStorm.
 * User: GQAdonis
 * Date: 17.12.13
 * Time: 12:49
 */
class RoomChooserWidget extends HWidget
{

    public function init()
    {
        // publish resource files
        $assetPrefix = Yii::app()->assetManager->publish(dirname(__FILE__) . '/../resources', true, 0, defined('YII_DEBUG'));
        Yii::app()->clientScript->setJavascriptVariable('scRoomsListUrl', $this->createUrl('//rooms/list', array('ajax' => 1)));
        Yii::app()->clientScript->registerScriptFile($assetPrefix . '/roomchooser.js');
    }

    /**
     * Displays / Run the Widgets
     */
    public function run()
    {
        if (Yii::app()->user->isGuest)
            return;

        $this->render('roomChooser', array());
    }

}

?>