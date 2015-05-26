<div class="container space-layout-container">
    <div class="row">
        <div class="col-md-12">
            <?php $this->widget('application.modules.rooms.widgets.RoomHeaderWidget', array('room' => $this->getRoom())); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-2 layout-nav-container">
            <?php $this->widget('application.modules.rooms.widgets.RoomMenuWidget', array('room' => $this->getRoom())); ?>
            <?php $this->widget('application.modules.rooms.widgets.RoomAdminMenuWidget', array('room' => $this->getRoom())); ?>
            <br/>
        </div>

        <?php if (isset($this->hideSidebar) && $this->hideSidebar) : ?>
            <div class="col-md-10 layout-content-container">
                <?php echo $content; ?>
            </div>
        <?php else: ?>
            <div class="col-md-7 layout-content-container">
                <?php echo $content; ?>
            </div>
            <div class="col-md-3 layout-sidebar-container">
                <?php
                $this->widget('application.modules.rooms.widgets.RoomSidebarWidget', array(
                    'widgets' => array(
                        array('application.modules_core.activity.widgets.ActivityStreamWidget', array('contentContainer' => $this->getRoom(), 'streamAction' => '//room/room/stream'), array('sortOrder' => 100)),
                        array('application.modules.rooms.widgets.RoomMemberWidget', array('room' => $this->getRoom()), array('sortOrder' => 200)),
                    )
                ));
                ?>
            </div>
        <?php endif; ?>
    </div>
</div>
