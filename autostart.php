<?php

Yii::app()->moduleManager->register(array(
    'id' => 'rooms',
    'class' => 'application.modules.rooms.RoomsModule',
    'import' => array(
        'application.modules.rooms.behaviors.*',
        'application.modules.rooms.widgets.*',
        'application.modules.rooms.models.*',
        'application.modules.rooms.notifications.*',
        'application.modules.rooms.*',
    ),

    // Events to Catch
    'events' => array(
        array('class' => 'AdminMenuWidget', 'event' => 'onInit', 'callback' => array('RoomsEvents', 'onAdminMenuInit')),
        array('class' => 'TopMenuWidget', 'event' => 'onInit', 'callback' => array('RoomsEvents', 'onTopMenuInit')),
        array('class' => 'User', 'event' => 'onBeforeDelete', 'callback' => array('RoomsModule', 'onUserDelete')),
        array('class' => 'HSearchComponent', 'event' => 'onRebuild', 'callback' => array('RoomsModule', 'onSearchRebuild')),
    ),
));
?>