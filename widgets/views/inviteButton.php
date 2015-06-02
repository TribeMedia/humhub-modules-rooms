<?php

echo CHtml::link(Yii::t('RoomsModule.widgets_views_inviteButton', 'Invite'), $room->createUrl('//rooms/membership/invite'), array('class' => 'btn btn-primary', 'data-toggle' => 'modal', 'data-target' => '#globalModal'));
