<?php
/**
 * Created by PhpStorm.
 * User: gqadonis
 * Date: 5/27/15
 * Time: 4:48 PM
 */

class RoomMembershipButtonWidget extends HWidget
{

    public $room;

    public function run()
    {
        $membership = $this->room->getMembership();

        $this->render('membershipButton', array(
            'room' => $this->room,
            'membership' => $membership
        ));
    }

}
