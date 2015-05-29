<?php $this->beginContent('application.modules_core.notification.views.notificationLayoutMail', array('notification' => $notification)); ?>
<?php echo Yii::t('RoomsModule.views_notifications_invite', '{userName} invited you to the room {roomName}', array(
    '{userName}' => '<strong>' . CHtml::encode($creator->displayName) . '</strong>',
    '{roomName}' => '<strong>' . CHtml::encode($targetObject->name) . '</strong>'
)); ?>
<?php $this->endContent(); ?>