<?php
/**
 * Created by PhpStorm.
 * User: gqadonis
 * Date: 5/25/15
 * Time: 11:36 PM
 */

class RoomMemberWidget extends HWidget
{

    public $room;

    public function run()
    {
        $this->render('roomMembers', array('room' => $this->room));
    }

}

?>