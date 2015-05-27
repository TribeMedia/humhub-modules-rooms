<?php
/**
 * Created by PhpStorm.
 * User: gqadonis
 * Date: 5/25/15
 * Time: 11:08 PM
 */

class RoomsEvents {

    public static function onAdminMenuInit($event)
    {
        $event->sender->addItem(array(
            'label' => Yii::t('RoomsModule.base', 'Rooms'),
            'url' => Yii::app()->createUrl('//rooms/roomlist'),
            'group' => 'manage',
            'icon' => '<i class="fa fa-file-code-o"></i>',
            'isActive' => (Yii::app()->controller->module && Yii::app()->controller->module->id == 'rooms' && Yii::app()->controller->id == 'roomlist'),
            'sortOrder' => 300,
        ));

        // Check for Admin Menu Pages to insert
    }

    public static function onTopMenuInit($event)
    {
        //$event->sender->addWidget('application.modules.rooms.widgets.RoomChooserWidget');


    }
}