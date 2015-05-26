<?php
/**
 * Created by PhpStorm.
 * User: gqadonis
 * Date: 5/26/15
 * Time: 12:05 AM
 */

class RoomUrlRule extends CBaseUrlRule
{

    public $connectionId = 'db';

    public function createUrl($manager, $route, $params, $ampersand)
    {

        if (isset($params['sguid'])) {
            if ($route == 'rooms/room' || $route == 'rooms/room/index') {
                $route = "home";
            }
            $url = "s/" . urlencode($params['sguid']) . "/" . $route;
            unset($params['sguid']);
            $url = rtrim($url . '/' . $manager->createPathInfo($params, '/', '/'), '/');
            return $url;
        }

        return false;
    }

    public function parseUrl($manager, $request, $pathInfo, $rawPathInfo)
    {
        if (substr($pathInfo, 0, 2) == "s/") {
            $parts = explode('/', $pathInfo, 3);
            if (isset($parts[1])) {
                $room = Room::model()->findByAttributes(array('guid' => $parts[1]));

                if ($room !== null) {

                    $_GET['sguid'] = $room->guid;
                    if (!isset($parts[2]) || substr($parts[2], 0, 4) == 'home') {
                        $temp = 1;
                        return 'rooms/room/index'. str_replace('home', '', $parts[2], $temp);
                    } else {
                        return $parts[2];
                    }
                } else {
                    throw new CHttpException('404', Yii::t('RoomsModule.components_RoomUrlRule', 'Room not found!'));
                }
            }
        }
        return false;
    }

}
