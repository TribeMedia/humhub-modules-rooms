<?php
/**
 * Created by PhpStorm.
 * User: gqadonis
 * Date: 6/2/15
 * Time: 4:15 PM
 */

class MembershipController extends Controller {

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
                'users' => array("@", (HSetting::Get('allowGuestAccess', 'authentication_internal')) ? "?" : "@"),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
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
     * Invite New Members to this workspace
     */
    public function actionInvite()
    {

        $room = $this->getRoom();

        // Check Permissions to Invite
        if (!$room->canInvite()) {
            throw new CHttpException(403, 'Access denied - You cannot invite members!');
        }

        $model = new RoomInviteForm();
        $model->room = $room;

        if (isset($_POST['RoomInviteForm'])) {

            $_POST['RoomInviteForm'] = Yii::app()->input->stripClean($_POST['RoomInviteForm']);
            $model->attributes = $_POST['RoomInviteForm'];

            if ($model->validate()) {

                // check if both invite inputs are empty
                if ($model->invite == "" && $model->inviteExternal == "") {

                } else {

                    // Invite existing members
                    foreach ($model->getInvites() as $user) {
                        $room->inviteMember($user->id, Yii::app()->user->id);
                        $statusInvite = $room->getMembership($user->id)->status;
                    }

                    if (HSetting::Get('internalUsersCanInvite', 'authentication_internal')) {
                        // Invite non existing members
                        foreach ($model->getInvitesExternal() as $email) {
                            $statusInvite = ($room->inviteMemberByEMail($email, Yii::app()->user->id)) ? RoomMembership::STATUS_INVITED : false;
                        }
                    }

                    // close modal
                    //$this->renderModalClose();

                    $output = $this->renderPartial('statusInvite', array('status' => $statusInvite));
                    Yii::app()->clientScript->render($output);
                    echo $output;
                    Yii::app()->end();
                }
            }
        }

        $output = $this->renderPartial('invite', array('model' => $model, 'room' => $room));
        Yii::app()->clientScript->render($output);
        echo $output;
        Yii::app()->end();
    }

}