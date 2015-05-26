<?php
/**
 * Created by PhpStorm.
 * User: gqadonis
 * Date: 5/25/15
 * Time: 11:44 PM
 */

class AdminController extends Controller {

    /**
     * @var String Admin Sublayout
     */
    //public $subLayout = "application.modules.rooms.views.room._layout";
    public $subLayout = "application.modules_core.admin.views._layout";

    /**
     * Shows all available spaces
     */
    public function actionIndex()
    {

        $model = new Room('search');

        if (isset($_GET['Room']))
            $model->attributes = $_GET['Room'];


        $this->render('index', array(
            'model' => $model
        ));
    }

    public function actionSettings() {

    }
}