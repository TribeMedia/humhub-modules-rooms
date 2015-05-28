<?php
/**
 * Created by PhpStorm.
 * User: gqadonis
 * Date: 5/26/15
 * Time: 1:50 AM
 */

class RoomMenuWidget extends MenuWidget
{

    public $room;
    public $template = "application.widgets.views.leftNavigation";

    public function init()
    {

        // Reckon the current controller is a valid room controller
        // (Needs to implement the RoomControllerBehavior)
        $roomGuid = Yii::app()->getController()->getRoom()->guid;

        $this->addItemGroup(array(
            'id' => 'modules',
            'label' => Yii::t('RoomsModule.widgets_RoomMenuWidget', '<strong>Room</strong> menu'),
            'sortOrder' => 100,
        ));

        $this->addItem(array(
            'label' => Yii::t('RoomsModule.widgets_RoomMenuWidget', 'Enter'),
            'group' => 'modules',
            'url' => Yii::app()->createUrl('//rooms/view', array('sguid' => $roomGuid)),
            'icon' => '<i class="fa fa-bars"></i>',
            'sortOrder' => 100,
            'isActive' => (Yii::app()->controller->id == "view" && Yii::app()->controller->action->id == "index" && Yii::app()->controller->module->id == "rooms"),
        ));

#        $this->addItem(array(
#            'label' => Yii::t('SpaceModule.widgets_SpaceMenuWidget', 'Members'),
#            'url' => Yii::app()->createUrl('//space/space/members', array('sguid'=>$spaceGuid)),
#            'sortOrder' => 200,
#            'isActive' => (Yii::app()->controller->id == "space" && Yii::app()->controller->action->id == "members"),
#        ));
#        $this->addItem(array(
#            'label' => Yii::t('SpaceModule.widgets_SpaceMenuWidget', 'Admin'),
#            'url' => Yii::app()->createUrl('//space/admin', array('sguid'=>$spaceGuid)),
#            'sortOrder' => 9999,
#            'isActive' => (Yii::app()->controller->id == "admin" && Yii::app()->controller->action->id == "index"),
#        ));


        parent::init();
    }

}

?>
