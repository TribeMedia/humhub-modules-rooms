<?php
/**
 * Created by PhpStorm.
 * User: gqadonis
 * Date: 5/28/15
 * Time: 3:40 AM
 */

class BrowseController extends Controller
{

    public $subLayout = "application.modules.rooms.views.browse._layout";

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return array(
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'users' => array('@'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Returns a room list by json
     *
     * It can be filtered by by keyword.
     */
    public function actionSearchJson()
    {

        $keyword = Yii::app()->request->getParam('keyword', "");
        $page = (int) Yii::app()->request->getParam('page', 1);
        $limit = (int) Yii::app()->request->getParam('limit', HSetting::Get('paginationSize'));

        $searchResultSet = Yii::app()->search->find($keyword, [
            'model' => 'Space',
            'page' => $page,
            'pageSize' => $limit
        ]);

        $json = array();
        foreach ($searchResultSet->getResultInstances() as $room) {
            $roomInfo = array();
            $roomInfo['guid'] = $room->guid;
            $roomInfo['title'] = CHtml::encode($room->name);
            $roomInfo['tags'] = CHtml::encode($room->tags);
            $roomInfo['image'] = $room->getProfileImage()->getUrl();
            $roomInfo['link'] = $room->getUrl();

            $json[] = $roomInfo;
        }

        print CJSON::encode($json);
        Yii::app()->end();
    }

}

?>