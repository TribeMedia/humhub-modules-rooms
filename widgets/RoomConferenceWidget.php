<?php
/**
 * Created by PhpStorm.
 * User: gqadonis
 * Date: 5/28/15
 * Time: 5:22 AM
 */

class RoomConferenceWidget extends HWidget {

    public $room;

    public function init() {

        $assetPrefix = Yii::app()->assetManager->publish(dirname(__FILE__) . '/../resources/conference', true, 0, defined('YII_DEBUG'));
    }

    public function run()
    {
        $this->render('roomConference', array('room' => $this->room));
    }
}

?>