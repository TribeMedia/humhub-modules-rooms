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
}