<?php

echo CHtml::link(Yii::t('RoomsModule.widgets_views_inviteButton', 'Invite'), $room->createUrl('//rooms/rooms/invite'), array('class' => 'btn btn-primary', 'data-toggle' => 'modal', 'data-target' => '#globalModal'));
