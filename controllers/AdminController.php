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
    public $subLayout = "application.modules.rooms.views.room._layout";
    //public $subLayout = "application.modules_core.admin.views._layout";

    /**
     * Shows all available rooms
     */
    /*public function actionIndex()
    {

        $model = new Room('search');

        if (isset($_GET['Room']))
            $model->attributes = $_GET['Room'];


        $this->render('index', array(
            'model' => $model
        ));
    }*/

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
     * First Admin Action to display
     */
    public function actionIndex()
    {
        $this->redirect($this->createUrl('edit', array('sguid' => $this->getRoom()->guid)));
    }

    public function actionSettings()
    {
        $form = new RoomsSettingsForm;
        $form->defaultJoinPolicy = HSetting::Get('defaultJoinPolicy', 'space');
        $form->defaultVisibility = HSetting::Get('defaultVisibility', 'space');

        // uncomment the following code to enable ajax-based validation
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'rooms-settings-form') {
            echo CActiveForm::validate($form);
            Yii::app()->end();
        }

        if (isset($_POST['RoomsSettingsForm'])) {
            $_POST['RoomsSettingsForm'] = Yii::app()->input->stripClean($_POST['RoomsSettingsForm']);
            $form->attributes = $_POST['RoomsSettingsForm'];

            if ($form->validate()) {
                HSetting::Set('defaultJoinPolicy', $form->defaultJoinPolicy, 'space');
                HSetting::Set('defaultVisibility', $form->defaultVisibility, 'space');

                // set flash message
                Yii::app()->user->setFlash('data-saved', Yii::t('RoomsModule.controllers_SpaceController', 'Saved'));
                $this->redirect($this->createUrl('settings'));
            }
        }

        $this->render('settings', array('model' => $form));
    }

    /**
     * Space Edit Form
     *
     * @todo Add Owner Switch Box for the Owner only!
     */
    public function actionEdit()
    {

        $model = $this->getRoom();
        $model->scenario = 'edit';

        // Ajax Validation
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'room-edit-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        if (isset($_POST['Room'])) {
            $_POST['Room'] = Yii::app()->input->stripClean($_POST['Room']);
            $model->attributes = $_POST['Room'];
            if ($model->validate()) {
                $model->save();
                Yii::app()->user->setFlash('data-saved', Yii::t('SpaceModule.controllers_AdminController', 'Saved'));
                $this->redirect($model->createUrl('admin/edit'));
            }
        }

        $this->render('edit', array('model' => $model));
    }
}