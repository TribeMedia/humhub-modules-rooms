<?php
/**
 * Created by PhpStorm.
 * User: gqadonis
 * Date: 5/27/15
 * Time: 6:58 PM
 */

class LController extends Controller {

    public function actionIndex()
    {

        $criteria = new CDbCriteria();
        if (HSetting::Get('roomOrder', 'room') == 0) {
            $criteria->order = 'name ASC';
        } else {
            $criteria->order = 'last_visit DESC';
        }

        $memberships = RoomMembership::model()->with('room')->findAllByAttributes(array(
            'user_id' => Yii::app()->user->id,
            'status' => RoomMembership::STATUS_MEMBER,
        ), $criteria);

        $this->renderPartial('index', array('memberships' => $memberships), false, true);
    }
}