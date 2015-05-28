<div class="panel panel-default">
    <div class="panel-heading">
        <?php echo Yii::t('RoomsModule.views_admin_delete', '<strong>Delete</strong> room'); ?>
    </div>
    <div class="panel-body">
        <p><?php echo Yii::t('RoomsModule.views_admin_delete', 'Are you sure, that you want to delete this room? All published content will be removed!'); ?></p>
        <p><?php echo Yii::t('RoomsModule.views_admin_delete', 'Please provide your password to continue!'); ?></p><br>

        <?php
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'room-delete-form',
            'enableAjaxValidation' => false,
        ));
        ?>

        <div class="form-group">
            <?php echo $form->labelEx($model, 'currentPassword'); ?>
            <?php echo $form->passwordField($model, 'currentPassword', array('class' => 'form-control', 'rows' => '6')); ?>
            <?php echo $form->error($model, 'currentPassword'); ?>
        </div>

        <hr>
        <?php echo CHtml::submitButton(Yii::t('RoomsModule.views_admin_delete', 'Delete'), array('class' => 'btn btn-danger')); ?>

        <?php $this->endWidget(); ?>
    </div>
</div>


