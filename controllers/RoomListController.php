<?php
/**
 * Created by PhpStorm.
 * User: gqadonis
 * Date: 5/27/15
 * Time: 4:02 PM
 */

class RoomListController extends Controller {

    public $subLayout = "application.modules_core.admin.views._layout";

    public function actionIndex()
    {

        $model = new Room('search');

        if (isset($_GET['Room']))
            $model->attributes = $_GET['Room'];


        $this->render('index', array(
            'model' => $model
        ));
    }
}