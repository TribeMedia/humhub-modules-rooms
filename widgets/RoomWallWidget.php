<?php

class RoomWallWidget extends HWidget
{

    public $room;

    public function run()
    {
        $this->render('roomWall', array('room' => $this->room));
    }

}

?>