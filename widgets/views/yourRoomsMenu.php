<div class="well sidebar-nav">
    <ul class="nav nav-list">
        <li class="nav-header">Your rooms</li>
        <li class="<?php if ($this->getController()->id == 'dashboard'): ?>active<?php endif; ?>"><a href="<?php echo $this->createUrl('//dashboard/index') ?>">All Rooms</a></li>

        <?php foreach ($usersRooms as $room): ?>

            <li class="<?php
            if ($room->guid == $currentRoomGuid) {
                echo "active";
            }
            ?>"><a href="<?php echo $this->createUrl('//rooms/room', array('sguid' => $room->guid)); ?>"><?php print CHtml::encode(Helpers::trimText($room->name, 35)); ?></a></li>
        <?php endforeach; ?>
    </ul>
    <br>
    <a href="<?php echo $this->createUrl('//rooms/room/create') ?>" class="btn"><i class="fa fa-plus"></i> New Room</a>
</div>
