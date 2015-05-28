<div class="container space-layout-container">
    <div class="row">
        <div class="col-md-12">
            <?php $this->widget('application.modules.rooms.widgets.RoomHeaderWidget', array('room' => $this->getRoom())); ?>
        </div>
    </div>
    <div class="row">

            <div class="col-md-10 layout-content-container">
                <?php echo $content; ?>
            </div>

    </div>
</div>
