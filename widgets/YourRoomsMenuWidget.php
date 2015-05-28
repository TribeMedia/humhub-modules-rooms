<?php
/**
 * Created by PhpStorm.
 * User: gqadonis
 * Date: 5/28/15
 * Time: 3:34 AM
 */

class YourRoomsMenuWidget extends HWidget {

    public function run() {

        $currentRoom = null;
        $currentRoomGuid = "";

        if (isset(Yii::app()->params['currentRoom']) && Yii::app()->params['currentRoom'] != null) {
            $currentRoom = Yii::app()->params['currentRoom'];
            $currentRoomGuid = $currentRoom->guid;
        }

        $this->render('yourRoomsMenu', array(
            'currentRoom' => $currentRoom,
            'currentRoomGuid' => $currentRoomGuid,
            'usersRooms' => RoomMembership::GetUserRooms(),
        ));
    }

}

?>
