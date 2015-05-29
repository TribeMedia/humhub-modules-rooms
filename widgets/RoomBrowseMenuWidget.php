<?php
/**
 * Created by PhpStorm.
 * User: gqadonis
 * Date: 5/29/15
 * Time: 2:38 AM
 */

class RoomBrowseMenuWidget extends MenuWidget {

    public $template = "application.widgets.views.leftNavigation";

    public function init() {

        $this->addItemGroup(array(
            'id' => 'browse',
            'label' => Yii::t('RoomsModule.widgets_RoomsBrowseMenuWidget', 'Rooms'),
            'sortOrder' => 100,
        ));


        $this->addItem(array(
            'label' => Yii::t('RoomsModule.widgets_RoomsBrowseMenuWidget', 'My Room List'),
            'url' => Yii::app()->createUrl('//rooms/browse', array()),
            'sortOrder' => 100,
            'isActive' => (Yii::app()->controller->id == "roombrowse" && Yii::app()->controller->action->id == "index"),
        ));

        $this->addItem(array(
            'label' => Yii::t('RoomsModule.widgets_RoomsBrowseMenuWidget', 'My room summary'),
            'url' => Yii::app()->createUrl('//dashboard', array()),
            'sortOrder' => 100,
            'isActive' => (Yii::app()->controller->id == "roombrowse" && Yii::app()->controller->action->id == "index"),
        ));


        $this->addItem(array(
            'label' => Yii::t('RoomsModule.widgets_RoomsBrowseMenuWidget', 'Rooms directory'),
            'url' => Yii::app()->createUrl('//community/rooms', array()),
            'sortOrder' => 200,
            'isActive' => (Yii::app()->controller->id == "roombrowse" && Yii::app()->controller->action->id == "index"),
        ));


#        $this->addItem(array(
#            'label' => Yii::t('RoomsModule.widgets_RoomsBrowseMenuWidget', 'Members'),
#            'url' => Yii::app()->createUrl('//space/space/members', array('sguid'=>$spaceGuid)),
#            'sortOrder' => 200,
#            'isActive' => (Yii::app()->controller->id == "space" && Yii::app()->controller->action->id == "members"),
#        ));
#        $this->addItem(array(
#            'label' => Yii::t('RoomsModule.widgets_RoomsBrowseMenuWidget', 'Admin'),
#            'url' => Yii::app()->createUrl('//space/admin', array('sguid'=>$spaceGuid)),
#            'sortOrder' => 9999,
#            'isActive' => (Yii::app()->controller->id == "admin" && Yii::app()->controller->action->id == "index"),
#        ));


        parent::init();
    }

}

?>
