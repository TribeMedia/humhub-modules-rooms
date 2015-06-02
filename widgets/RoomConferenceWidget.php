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
        $assetjqueryuiPrefix = Yii::app()->assetManager->publish(dirname(__FILE__) . '/../resources/conference/jqueryui', true, 0, defined('YII_DEBUG'));
        $assetLibsPrefix = Yii::app()->assetManager->publish(dirname(__FILE__) . '/../resources/conference/libs', true, 0, defined('YII_DEBUG'));
        $assetStrophePrefix = Yii::app()->assetManager->publish(dirname(__FILE__) . '/../resources/conference/libs/strophe', true, 0, defined('YII_DEBUG'));

        Yii::app()->clientScript->setJavascriptVariable('confRoomName', $this->room->name);
        //Yii::app()->clientScript->registerScriptFile($assetPrefix . '/require.js');
        Yii::app()->clientScript->registerScriptFile($assetjqueryuiPrefix . '/jquery-ui.min.js');
        //Yii::app()->clientScript->registerScriptFile($assetStrophePrefix . '/strophe.min.js');
        //Yii::app()->clientScript->registerScriptFile($assetStrophePrefix . '/strophe.disco.min.js');
        //Yii::app()->clientScript->registerScriptFile($assetStrophePrefix . '/strophe.caps.jsonly.min.js');
        Yii::app()->clientScript->registerScriptFile($assetLibsPrefix . '/bootbox.min.js');
        //Yii::app()->clientScript->registerScriptFile($assetLibsPrefix . '/jquery-migrate-1.2.1.js');
        //Yii::app()->clientScript->registerScriptFile($assetLibsPrefix . '/tooltip.js');
        //Yii::app()->clientScript->registerScriptFile($assetLibsPrefix . '/popover.js');
        //Yii::app()->clientScript->registerScriptFile($assetLibsPrefix . '/toastr.js');
        Yii::app()->clientScript->registerScriptFile($assetPrefix . '/adapter.js');
        Yii::app()->clientScript->registerScriptFile($assetPrefix . '/janus.js');
        Yii::app()->clientScript->registerScriptFile($assetPrefix . '/mcu.js');
        //Yii::app()->clientScript->registerScriptFile($assetPrefix . '/interface_config.js');
        //Yii::app()->clientScript->registerScriptFile($assetPrefix . '/app.bundle.js');
        //Yii::app()->clientScript->registerScriptFile($assetPrefix . '/analytics.js');
        //Yii::app()->clientScript->registerCssFile($assetPrefix . '/font.css?v=5');
        //Yii::app()->clientScript->registerCssFile($assetPrefix . '/toastr.css?v=1');
        //Yii::app()->clientScript->registerCssFile($assetPrefix . '/main.css?v=29', 'screen');
        //Yii::app()->clientScript->registerCssFile($assetPrefix . '/videolayout_default.css?v=13', 'screen', array('id' => 'videolayout_default'));
        //Yii::app()->clientScript->registerCssFile($assetPrefix . '/jquery-impromptu.css');
        //Yii::app()->clientScript->registerCssFile($assetPrefix . '/modaldialog.css');
        //Yii::app()->clientScript->registerCssFile($assetPrefix . '/notice.css');
        //Yii::app()->clientScript->registerCssFile($assetPrefix . '/popup-menu.css');
        //Yii::app()->clientScript->registerCssFile($assetPrefix . '/login_menu.css');
        //Yii::app()->clientScript->registerCssFile($assetPrefix . '/popover.css');
        //Yii::app()->clientScript->registerCssFile($assetPrefix . '/jitsi_popover.css');
        //Yii::app()->clientScript->registerCssFile($assetPrefix . '/contact_list.css');
        //Yii::app()->clientScript->registerCssFile($assetPrefix . '/chat.css');
        Yii::app()->clientScript->registerCssFile($assetjqueryuiPrefix . '/jquery-ui.min.css');
        Yii::app()->clientScript->registerCssFile($assetjqueryuiPrefix . '/jquery-ui.structure.min.css');
        Yii::app()->clientScript->registerCssFile($assetjqueryuiPrefix . '/jquery-ui.theme.min.css');
        //Yii::app()->clientScript->registerCssFile($assetPrefix . '/welcome_page.css');
        //Yii::app()->clientScript->registerCssFile($assetPrefix . '/settings_menu.css');
        Yii::app()->clientScript->registerScriptFile($assetLibsPrefix . '/jquery-impromptu.js');
        Yii::app()->clientScript->registerScriptFile($assetLibsPrefix . '/jquery.autosize.js');
    }

    public function run()
    {
        $this->render('janusConference', array('room' => $this->room));
    }
}

?>