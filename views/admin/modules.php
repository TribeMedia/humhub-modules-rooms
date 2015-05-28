<div class="panel panel-default">
    <div class="panel-heading">
        <?php echo Yii::t('RoomsModule.views_admin_modules', '<strong>Room</strong> Modules'); ?>
    </div>
    <div class="panel-body">

        <?php if (count($availableModules) == 0): ?>
            <p><?php echo Yii::t('RoomsModule.views_admin_modules', 'Currently there are no modules available for this room!'); ?></p>
        <?php else: ?>
            <?php echo Yii::t('RoomsModule.views_admin_modules', 'Enhance this room with modules.'); ?><br>
        <?php endif; ?>


        <?php foreach ($availableModules as $moduleId => $module): ?>
            <hr>
            <div class="media">
                <img class="media-object img-rounded pull-left" data-src="holder.js/64x64" alt="64x64"
                     style="width: 64px; height: 64px;"
                     src="<?php echo $module->getRoomsModuleImage(); ?>">

                <div class="media-body">
                    <h4 class="media-heading"><?php echo $module->getRoomsModuleName(); ?>
                        <?php if ($this->getRoom()->isModuleEnabled($moduleId)) : ?>
                            <small><span class="label label-success"><?php echo Yii::t('RoomsModule.views_admin_modules', 'Activated'); ?></span></small>
                        <?php endif; ?>
                    </h4>

                    <p><?php echo $module->getRoomModuleDescription(); ?></p>
                    <?php if ($this->getRoom()->isModuleEnabled($moduleId)) : ?>

                        <?php if ($this->getRoom()->canDisableModule($moduleId)): ?>
                            <?php echo HHtml::postLink(Yii::t('RoomsModule.views_admin_modules', 'Disable'), array('//rooms/admin/disableModule', 'moduleId' => $moduleId, 'sguid' => $this->getRoom()->guid), array('class' => 'btn btn-sm btn-primary', 'confirm' => Yii::t('RoomsModule.views_admin_modules', 'Are you sure? *ALL* module data for this room will be deleted!'))); ?>
                        <?php endif; ?>

                        <?php if ($module->getRoomModuleConfigUrl($this->getRoom()) != "") : ?>
                            <?php
                            echo CHtml::link(
                                Yii::t('RoomsModule.views_admin_modules', 'Configure'), $module->getRoomModuleConfigUrl($this->getRoom()), array('class' => 'btn btn-default')
                            );
                            ?>
                        <?php endif; ?>
                    <?php else: ?>
                        <?php echo HHtml::postLink(Yii::t('RoomsModule.views_admin_modules', 'Enable'), array('//rooms/admin/enableModule', 'moduleId' => $moduleId, 'sguid' => $this->getRoom()->guid), array('class' => 'btn btn-sm btn-primary')); ?>
                    <?php endif; ?>
                </div>
            </div>
            <!-- Start: Module update message for the future -->
            <!--            <br>
                        <div class="alert alert-warning">
                            New Update for this module is available! <a href="#">See details</a>
                        </div>-->
            <!-- End: Module update message for the future -->

        <?php endforeach; ?>

    </div>
</div>