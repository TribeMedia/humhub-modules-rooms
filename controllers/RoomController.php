<?php
/**
 * Created by PhpStorm.
 * User: gqadonis
 * Date: 5/27/15
 * Time: 4:23 AM
 */

class RoomController extends Controller {

    public $subLayout = "application.modules.rooms.views.room._layout";

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


    /**
     * Creates a new Space
     */
    public function actionCreate()
    {

        if (!Yii::app()->user->canCreateSpace()) {
            throw new CHttpException(400, 'You are not allowed to create rooms!');
        }

        $model = new Room('edit');

        // Ajax Validation
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'room-create-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        if (isset($_POST['Room'])) {
            $_POST['Room'] = Yii::app()->input->stripClean($_POST['Room']);

            $model->attributes = $_POST['Room'];

            if ($model->validate() && $model->save()) {

                // Save in this user variable, that the workspace was new created
                Yii::app()->user->setState('ws', 'created');

                // Redirect to the new created Room
                $this->htmlRedirect($model->getUrl());
            }
        }

        $this->renderPartial('create', array('model' => $model), false, true);
    }

}