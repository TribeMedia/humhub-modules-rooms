<div class="panel panel-default">
    <div class="panel-heading"><?php echo Yii::t('RoomsModule.views_space_settings', '<strong>Rooms</strong> Settings'); ?></div>
    <div class="panel-body">
        <ul class="nav nav-pills">
            <li><a
                    href="<?php echo $this->createUrl('index'); ?>"><?php echo Yii::t('RoomsModule.views_space_index', 'Overview'); ?></a>
            </li>
            <li class="active">
                <a href="<?php echo $this->createUrl('settings'); ?>"><?php echo Yii::t('RoomsModule.views_space_index', 'Settings'); ?></a>
            </li>
        </ul>
        <p />

        <p>
            <?php echo Yii::t('RoomsModule.views_space_index', 'Define here default settings for new rooms.'); ?>
        </p>

        <br />

        <?php
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'rooms-settings-form',
            'enableAjaxValidation' => false,
        ));
        ?>

        <?php echo $form->errorSummary($model); ?>

        <div class="form-group">
            <?php echo $form->labelEx($model, 'defaultJoinPolicy'); ?>
            <?php $joinPolicies = array(0 => Yii::t('RoomsModule.base', 'Only by invite'), 1 => Yii::t('RoomsModule.base', 'Invite and request'), 2 => Yii::t('RoomsModule.base', 'Everyone can enter')); ?>
            <?php echo $form->dropDownList($model, 'defaultJoinPolicy', $joinPolicies, array('class' => 'form-control', 'id' => 'join_policy_dropdown', 'hint' => Yii::t('RoomsModule.views_admin_edit', 'Choose the kind of membership you want to provide for this room.'))); ?>
        </div>

        <div class="form-group">
            <?php echo $form->labelEx($model, 'defaultVisibility'); ?>
            <?php
            $visibilities = array(
                0 => Yii::t('RoomsModule.base', 'Private (Invisible)'),
                1 => Yii::t('RoomsModule.base', 'Public (Visible)')
                /* 2 => Yii::t('SpaceModule.base', 'Visible for all') */
            );
            ?>
            <?php echo $form->dropDownList($model, 'defaultVisibility', $visibilities, array('class' => 'form-control', 'id' => 'join_visibility_dropdown', 'hint' => Yii::t('RoomsModule.views_admin_edit', 'Choose the security level for this room to define the visibleness.'))); ?>
            <?php echo $form->error($model, 'defaultVisibility'); ?>
        </div>
        <hr>

        <?php echo CHtml::submitButton(Yii::t('RoomsModule.views_space_settings', 'Save'), array('class' => 'btn btn-primary')); ?>
        <!-- show flash message after saving -->
        <?php $this->widget('application.widgets.DataSavedWidget'); ?>

        <?php $this->endWidget(); ?>

    </div>
</div>








