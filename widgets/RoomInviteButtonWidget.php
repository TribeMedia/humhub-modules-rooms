<?php
/**
 * Created by PhpStorm.
 * User: gqadonis
 * Date: 5/27/15
 * Time: 4:48 PM
 */

class RoomInviteButtonWidget extends HWidget
{

    public $room;

    public function run()
    {
        if (!$this->room->canInvite()) {
            return;
        }

        $this->render('inviteButton', array(
            'room' => $this->room,
        ));
    }

}
