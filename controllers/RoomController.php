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

    public function actionIndex()
    {
        $this->pageTitle = $this->getRoom()->name;
        $this->render('index', array());
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
                Yii::app()->user->setState('r', 'created');

                // Redirect to the new created Room
                $this->htmlRedirect($model->getUrl());
            }
        }

        $this->renderPartial('create', array('model' => $model), false, true);
    }

    /**
     * Archives a workspace
     */
    public function actionArchive()
    {
        $this->forcePostRequest();
        $this->ownerOnly();
        $room = $this->getRoom();
        $room->archive();
        $this->htmlRedirect($this->createUrl('//rooms/admin/edit', array('sguid' => $room->guid)));
    }

    /**
     * UnArchives a workspace
     */
    public function actionUnArchive()
    {
        $this->forcePostRequest();
        $this->ownerOnly();
        $room = $this->getRoom();
        $room->unarchive();
        $this->htmlRedirect($this->createUrl('//rooms/admin/edit', array('sguid' => $room->guid)));
    }

    /**
     * Deletes this Space
     */
    public function actionDelete()
    {
        $this->ownerOnly();
        $room = $this->getRoom();
        $model = new RoomDeleteForm;
        if (isset($_POST['RoomDeleteForm'])) {
            $model->attributes = $_POST['RoomDeleteForm'];

            if ($model->validate()) {
                $room->delete();
                $this->htmlRedirect($this->createUrl('//'));
            }
        }
        $this->render('delete', array('model' => $model, 'room' => $room));
    }

    /**
     * Request only allowed for room admins
     */
    public function adminOnly()
    {
        if (!$this->getRoom()->isAdmin())
            throw new CHttpException(403, 'Access denied - Space Administrator only!');
    }

    /**
     * Request only allowed for room owner
     */
    public function ownerOnly()
    {
        $room = $this->getRoom();

        if (!$room->isRoomOwner() && !Yii::app()->user->isAdmin())
            throw new CHttpException(403, 'Access denied - Space Owner only!');
    }

}