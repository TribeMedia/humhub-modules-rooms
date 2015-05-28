<?php
/**
 * Created by PhpStorm.
 * User: gqadonis
 * Date: 5/28/15
 * Time: 4:26 AM
 */

class ViewController extends Controller {

    public $subLayout = "application.modules.rooms.views.view._layout";

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
     * Add mix-ins to this model
     *
     * @return type
     */
    public function behaviors()
    {
        return array(
            'ProfileControllerBehavior' => array(
                'class' => 'application.modules.rooms.behaviors.RoomsControllerBehavior',
            ),
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

    public function actionIndex()
    {
        $this->pageTitle = $this->getRoom()->name;
        $this->render('index', array());
    }
}