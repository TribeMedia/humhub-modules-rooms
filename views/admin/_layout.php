<div class="container">
    <div class="row">
        <div class="col-md-2">
            <!-- show space menu widget -->
            <?php $this->widget('application.modules.rooms.widgets.RoomMenuWidget', array()); ?>
            <!-- show space admin menu widget -->
            <?php $this->widget('application.modules.rooms.widgets.RoomAdminMenuWidget', array()); ?>
        </div>
        <div class="col-md-7">
            <?php echo $content; ?>
        </div>
        <div class="col-md-3">
            <!-- show sidebar widget for spaces -->

            <?php $this->widget('application.modules.rooms.widgets.RoomSidebarWidget'); ?>
            <!--            --><?php //$this->widget('application.modules_core.space.widgets.SpaceSidebarWidget', array(
            //                'widgets' => array(
            //                    array('application.modules_core.activity.ActivityStreamWidget', array('type' => 'workspace', 'guid' => $this->getSpace()->guid), array('sortOrder' => 100)),
            //                )
            //            )); ?>
        </div>
    </div>
</div>