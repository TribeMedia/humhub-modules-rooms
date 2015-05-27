<li class="dropdown">
    <a href="#" id="room-menu" class="dropdown-toggle" data-toggle="dropdown">
        <!-- start: Show space image and name if chosen -->
        <?php if (Yii::app()->params['currentRoom']) { ?>
            <img
                src="<?php echo Yii::app()->params['currentRoom']->getProfileImage()->getUrl(); ?>"
                width="32" height="32" alt="32x32" data-src="holder.js/24x24"
                style="width: 32px; height: 32px; margin-right: 3px; margin-top: 3px;" class="img-rounded"/>
        <?php } ?>

        <?php
        if (Yii::app()->params['currentRoom']) {
        } else {
            echo '<i class="fa fa-dot-circle-o"></i><br>' . Yii::t('RoomsModule.widgets_views_spaceChooser', 'My rooms');
        }
        ?>
        <!-- end: Show space image and name if chosen -->
        <b class="caret"></b>
    </a>
    <ul class="dropdown-menu" id="space-menu-dropdown">
        <li>
            <form action="" class="dropdown-controls"><input type="text" id="room-menu-search"
                                                             class="form-control"
                                                             autocomplete="off"
                                                             placeholder="<?php echo Yii::t('RoomsModule.widgets_views_spaceChooser','Search'); ?>">

                <div class="search-reset" id="room-search-reset"><i
                        class="fa fa-times-circle"></i></div>
            </form>
        </li>

        <li class="divider"></li>
        <li>
            <ul class="media-list notLoaded" id="room-menu-rooms">
                <li id="loader_rooms">
                    <div class="loader">
                        <div class="sk-spinner sk-spinner-three-bounce">
                            <div class="sk-bounce1"></div>
                            <div class="sk-bounce2"></div>
                            <div class="sk-bounce3"></div>
                        </div>
                    </div>
                </li>
            </ul>
        </li>

            <li>
                <div class="dropdown-footer">
                    <?php
                    echo CHtml::link(Yii::t('RoomsModule.widgets_views_spaceChooser', 'Create new room'), $this->createUrl('//rooms/room/create'), array('class' => 'btn btn-info col-md-12', 'data-toggle' => 'modal', 'data-target' => '#globalModal'));
                    ?>
                </div>
            </li>

    </ul>
</li>

<script type="text/javascript">

    // set niceScroll to RoomChooser menu
    $("#room-menu-rooms").niceScroll({
        cursorwidth: "7",
        cursorborder: "",
        cursorcolor: "#555",
        cursoropacitymax: "0.2",
        railpadding: {top: 0, right: 3, left: 0, bottom: 0}
    });

</script>
