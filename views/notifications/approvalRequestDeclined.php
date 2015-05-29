<?php $this->beginContent('application.modules_core.notification.views.notificationLayout', array('notification' => $notification, 'iconClass' => 'fa fa-minus-circle approval declined')); ?>
<?php echo Yii::t('RoomsModule.views_notifications_approvalRequestDeclined', '{userName} declined your membership request for the room {roomName}', array(
    '{userName}' => '<strong>' . CHtml::encode($creator->displayName) . '</strong>',
    '{roomName}' => '<strong>' . CHtml::encode($targetObject->name) . '</strong>'
)); ?>
<?php $this->endContent(); ?>