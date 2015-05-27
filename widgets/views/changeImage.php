<div class="panel panel-default">
    <div class="panel-heading"><?php echo Yii::t('RoomsModule.widgets_views_changeImage', 'Current room image'); ?></div>
    <div class="panel-body">
        <img src="<?php echo $this->getController()->getRoom()->getProfileImage()->getUrl(); ?>" alt=""/><br><br>
        <?php echo CHtml::link(Yii::t('RoomsModule.widgets_views_changeImage', "Change image"), $this->createUrl('//rooms/admin/changeImage', array('sguid' => $this->getController()->getRoom()->guid)), array('class' => 'btn btn-primary')); ?>

    </div>
</div>
<br/>
