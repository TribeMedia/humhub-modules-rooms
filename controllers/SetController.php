<?php
/**
 * Created by PhpStorm.
 * User: gqadonis
 * Date: 5/27/15
 * Time: 4:02 PM
 */

class SetController extends Controller {

    public $subLayout = "application.modules_core.admin.views._layout";

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

    public function actionIndex()
    {

        $model = new Room('search');

        if (isset($_GET['Room']))
            $model->attributes = $_GET['Room'];


        $this->render('index', array(
            'model' => $model
        ));
    }

    public function actionSettings()
    {
        $form = new RoomsSettingsForm;
        $form->defaultJoinPolicy = HSetting::Get('defaultJoinPolicy', 'room');
        $form->defaultVisibility = HSetting::Get('defaultVisibility', 'room');

        // uncomment the following code to enable ajax-based validation
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'rooms-settings-form') {
            echo CActiveForm::validate($form);
            Yii::app()->end();
        }

        if (isset($_POST['RoomsSettingsForm'])) {
            $_POST['RoomsSettingsForm'] = Yii::app()->input->stripClean($_POST['RoomsSettingsForm']);
            $form->attributes = $_POST['RoomsSettingsForm'];

            if ($form->validate()) {
                HSetting::Set('defaultJoinPolicy', $form->defaultJoinPolicy, 'room');
                HSetting::Set('defaultVisibility', $form->defaultVisibility, 'room');

                // set flash message
                Yii::app()->user->setFlash('data-saved', Yii::t('RoomsModule.controllers_SpaceController', 'Saved'));
                $this->redirect($this->createUrl('settings'));
            }
        }

        $this->render('settings', array('model' => $form));
    }
}