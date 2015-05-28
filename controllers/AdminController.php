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

    /**
     * Members Administration Action
     */
    public function actionMembers()
    {

        $membersPerPage = 10;
        $room = $this->getRoom();

        // User Role Management
        if (isset($_POST['users'])) {

            $users = Yii::app()->request->getParam('users');

            // Loop over all users in Form
            foreach ($users as $userGuid) {
                // Get informations
                if (isset($_POST['user_' . $userGuid])) {
                    $userSettings = Yii::app()->request->getParam('user_' . $userGuid);

                    $user = User::model()->findByAttributes(array('guid' => $userGuid));
                    if ($user != null) {

                        // No changes on the Owner
                        if ($room->isRoomOwner($user->id))
                            continue;

                        $membership = RoomMembership::model()->findByAttributes(array('user_id' => $user->id, 'room_id' => $room->id));
                        if ($membership != null) {
                            $membership->invite_role = (isset($userSettings['inviteRole']) && $userSettings['inviteRole'] == 1) ? 1 : 0;
                            $membership->admin_role = (isset($userSettings['adminRole']) && $userSettings['adminRole'] == 1) ? 1 : 0;
                            $membership->share_role = (isset($userSettings['shareRole']) && $userSettings['shareRole'] == 1) ? 1 : 0;
                            $membership->save();
                        }
                    }
                }
            }

            // Change owner if changed
            if ($room->isRoomOwner()) {
                $owner = $room->getRoomOwner();

                $newOwnerId = Yii::app()->request->getParam('ownerId');

                if ($newOwnerId != $owner->id) {
                    if ($room->isMember($newOwnerId)) {
                        $room->setSpaceOwner($newOwnerId);

                        // Redirect to current room
                        $this->redirect($this->createUrl('admin/members', array('sguid' => $this->getRoom()->guid)));
                    }
                }
            }

            Yii::app()->user->setFlash('data-saved', Yii::t('SpaceModule.controllers_AdminController', 'Saved'));
        } // Updated Users


        $criteria = new CDbCriteria;
        $criteria->condition = "1";

        // Allow User Searches
        $search = Yii::app()->request->getQuery('search');
        if ($search != "") {
            $criteria->join = "LEFT JOIN user ON memberships.user_id = user.id ";
            $criteria->condition .= " AND (";
            $criteria->condition .= ' user.username LIKE :search';
            $criteria->condition .= ' OR user.email like :search';
            $criteria->condition .= " ) ";
            $criteria->params = array(':search' => '%' . $search . '%');
        }

        //ToDo: Better Counting
        $allMemberCount = count($room->memberships($criteria));

        $pages = new CPagination($allMemberCount);
        $pages->setPageSize($membersPerPage);
        $pages->applyLimit($criteria);

        $members = $room->memberships($criteria);

        $invited_members = RoomMembership::model()->findAllByAttributes(array('room_id' => $room->id, 'status' => RoomMembership::STATUS_INVITED));

        $this->render('members', array(
            'room' => $room,
            'members' => $members, // must be the same as $item_count
            'invited_members' => $invited_members,
            'item_count' => $allMemberCount,
            'page_size' => $membersPerPage,
            'search' => $search,
            'pages' => $pages,
        ));
    }

    /**
     * User Manage Users Page, Reject Member Request Link
     */
    public function actionMembersRejectApplicant()
    {

        $this->forcePostRequest();

        $room = $this->getRoom();
        $userGuid = Yii::app()->request->getParam('userGuid');
        $user = User::model()->findByAttributes(array('guid' => $userGuid));

        if ($user != null) {
            $room->removeMember($user->id);

            SpaceApprovalRequestDeclinedNotification::fire(Yii::app()->user->id, $user, $room);
        }

        $this->redirect($room->getUrl());
    }

    /**
     * User Manage Users Page, Approve Member Request Link
     */
    public function actionMembersApproveApplicant()
    {

        $this->forcePostRequest();

        $room = $this->getRoom();
        $userGuid = Yii::app()->request->getParam('userGuid');
        $user = User::model()->findByAttributes(array('guid' => $userGuid));

        if ($user != null) {
            $membership = $room->getMembership($user->id);
            if ($membership != null && $membership->status == RoomMembership::STATUS_APPLICANT) {
                $room->addMember($user->id);
            }
        }


        $this->redirect($room->getUrl());
    }

    /**
     * Removes a Member
     */
    public function actionRemoveMember()
    {
        $this->forcePostRequest();

        $room = $this->getRoom();
        $userGuid = Yii::app()->request->getParam('userGuid');
        $user = User::model()->findByAttributes(array('guid' => $userGuid));

        if ($room->isRoomOwner($user->id)) {
            throw new CHttpException(500, 'Owner cannot be removed!');
        }

        $room->removeMember($user->id);

        // Redirect  back to Administration page
        $this->htmlRedirect($this->createUrl('//room/admin/members', array('sguid' => $room->guid)));
    }

    /**
     * Handle the profile image upload
     */
    public function actionImageUpload()
    {
        $room = $this->getRoom();
        $model = new UploadProfileImageForm();
        $json = array();

        //$model->image = CUploadedFile::getInstance($model, 'image');
        $files = CUploadedFile::getInstancesByName('roomfiles');
        $file = $files[0];
        $model->image = $file;

        if ($model->validate()) {

            $json['error'] = false;

            $profileImage = new ProfileImage($room->guid);
            $profileImage->setNew($model->image);

            $json['name'] = "";
            $json['url'] = $profileImage->getUrl();
            $json['size'] = $model->image->getSize();
            $json['deleteUrl'] = "";
            $json['deleteType'] = "";
        } else {
            $json['error'] = true;
            $json['errors'] = $model->getErrors();
        }

        return $this->renderJson(array('files' => $json));
    }

    /**
     * Crops the profile image of the user
     */
    public function actionCropImage()
    {
        $room = $this->getRoom();

        $model = new CropProfileImageForm;
        $profileImage = new ProfileImage($room->guid);

        if (isset($_POST['CropProfileImageForm'])) {
            $_POST['CropProfileImageForm'] = Yii::app()->input->stripClean($_POST['CropProfileImageForm']);
            $model->attributes = $_POST['CropProfileImageForm'];
            if ($model->validate()) {
                $profileImage->cropOriginal($model->cropX, $model->cropY, $model->cropH, $model->cropW);
                $this->htmlRedirect();
            }
        }

        $output = $this->renderPartial('cropImage', array('model' => $model, 'profileImage' => $profileImage, 'room' => $room));
        Yii::app()->clientScript->render($output);
        echo $output;
        Yii::app()->end();
    }


    /**
     * Handle the banner image upload
     */
    public function actionBannerImageUpload()
    {

        $room = $this->getRoom();
        $model = new UploadProfileImageForm();
        $json = array();

        $files = CUploadedFile::getInstancesByName('bannerfiles');
        $file = $files[0];
        $model->image = $file;

        if ($model->validate()) {

            $json['error'] = false;

            $profileImage = new ProfileBannerImage($room->guid);
            $profileImage->setNew($model->image);

            $json['name'] = "";
            $json['url'] = $profileImage->getUrl();
            $json['size'] = $model->image->getSize();
            $json['deleteUrl'] = "";
            $json['deleteType'] = "";
        } else {
            $json['error'] = true;
            $json['errors'] = $model->getErrors();
        }


        return $this->renderJson(array('files' => $json));
    }

    /**
     * Crops the banner image
     */
    public function actionCropBannerImage()
    {
        $room = $this->getRoom();

        $model = new CropProfileImageForm;
        $profileImage = new ProfileBannerImage($room->guid);

        if (isset($_POST['CropProfileImageForm'])) {
            $_POST['CropProfileImageForm'] = Yii::app()->input->stripClean($_POST['CropProfileImageForm']);
            $model->attributes = $_POST['CropProfileImageForm'];
            if ($model->validate()) {
                $profileImage->cropOriginal($model->cropX, $model->cropY, $model->cropH, $model->cropW);
                $this->htmlRedirect();
            }
        }

        $output = $this->renderPartial('cropBannerImage', array('model' => $model, 'profileImage' => $profileImage, 'room' => $room));
        Yii::app()->clientScript->render($output);
        echo $output;
        Yii::app()->end();
    }

    /**
     * Deletes the profile image or profile banner
     */
    public function actionDeleteProfileImage()
    {
        $this->forcePostRequest();

        $room = $this->getRoom();
        //$room->getProfileImage()->delete();

        $type = Yii::app()->request->getParam('type', 'profile');

        $json = array('type' => $type);

        $image = NULL;
        if ($type == 'profile') {
            $image = new ProfileImage($room->guid, 'default_room');
        } elseif ($type == 'banner') {
            $image = new ProfileBannerImage($room->guid);
        }

        if ($image) {
            $image->delete();
            $json['defaultUrl'] = $image->getUrl();
        }

        $this->renderJson($json);
    }

    /**
     * Modules Administration Action
     */
    public function actionModules()
    {
        $room = $this->getRoom();
        $this->render('modules', array('availableModules' => $this->getRoom()->getAvailableModules()));
    }

    public function actionEnableModule()
    {

        $this->forcePostRequest();

        $room = $this->getRoom();
        $moduleId = Yii::app()->request->getParam('moduleId', "");

        if (!$this->getRoom()->isModuleEnabled($moduleId)) {
            $this->getRoom()->enableModule($moduleId);
        }

        $this->redirect($this->createUrl('admin/modules', array('sguid' => $this->getRoom()->guid)));
    }

    public function actionDisableModule()
    {

        $this->forcePostRequest();

        $room = $this->getRoom();
        $moduleId = Yii::app()->request->getParam('moduleId', "");

        if ($room->isModuleEnabled($moduleId) && $room->canDisableModule($moduleId)) {
            $this->getRoom()->disableModule($moduleId);
        }

        $this->redirect($this->createUrl('admin/modules', array('sguid' => $this->getRoom()->guid)));
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