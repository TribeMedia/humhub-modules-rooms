<?php
/**
 * Created by PhpStorm.
 * User: gqadonis
 * Date: 5/26/15
 * Time: 1:50 AM
 */

class RoomAdminMenuWidget extends MenuWidget
{

    public $room;
    public $template = "application.widgets.views.leftNavigation";

    public function init()
    {

        /**
         * Backward compatibility - try to auto load space based on current
         * controller.
         */
        if ($this->room === null) {
            $this->room = Yii::app()->getController()->getRoom();
        }


        $this->addItemGroup(array(
            'id' => 'admin',
            'label' => Yii::t('RoomsModule.widgets_RoomAdminMenuWidget', '<strong>Room</strong> preferences'),
            'sortOrder' => 100,
        ));

        // check user rights
        if ($this->space->isAdmin()) {
            $this->addItem(array(
                'label' => Yii::t('RoomsModule.widgets_RoomAdminMenuWidget', 'General'),
                'group' => 'admin',
                'url' => $this->space->createUrl('//rooms/admin/edit'),
                'icon' => '<i class="fa fa-cogs"></i>',
                'sortOrder' => 100,
                'isActive' => (Yii::app()->controller->id == "admin" && Yii::app()->controller->action->id == "edit"),
            ));
        }

        // check user rights
        if ($this->space->isAdmin()) {
            $this->addItem(array(
                'label' => Yii::t('RoomsModule.widgets_RoomAdminMenuWidget', 'Members'),
                'group' => 'admin',
                'url' => $this->space->createUrl('//rooms/admin/members'),
                'icon' => '<i class="fa fa-group"></i>',
                'sortOrder' => 200,
                'isActive' => (Yii::app()->controller->id == "admin" && Yii::app()->controller->action->id == "members"),
            ));
        }

        // check user rights
        if ($this->space->isAdmin()) {
            $this->addItem(array(
                'label' => Yii::t('RoomsModule.widgets_RoomAdminMenuWidget', 'Modules'),
                'group' => 'admin',
                'url' => $this->space->createUrl('//rooms/admin/modules'),
                'icon' => '<i class="fa fa-rocket"></i>',
                'sortOrder' => 300,
                'isActive' => (Yii::app()->controller->id == "admin" && Yii::app()->controller->action->id == "modules"),
            ));
        }

        parent::init();
    }

}

?>
