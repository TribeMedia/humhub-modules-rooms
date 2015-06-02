<div class="modal-dialog modal-dialog-extra-small animated pulse" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title" id="myModalLabel"><?php
                if($status==RoomMembership::STATUS_INVITED)
                    echo Yii::t('RoomsModule.views_room_statusInvite', 'User has been invited.');
                if($status==RoomMembership::STATUS_MEMBER)
                    echo Yii::t('RoomsModule.views_room_statusInvite', 'User has become a member.');
                if(!$status)
                    echo Yii::t('RoomsModule.views_room_statusInvite', 'User has not been invited.');
                ?></h4>
        </div>
        <div class="modal-body text-center">

        </div>
        <div class="modal-footer">

            <button type="button" class="btn btn-primary"
                    data-dismiss="modal"><?php echo Yii::t('RoomsModule.views_room_statusInvite', 'Ok'); ?></button>

        </div>
    </div>
</div>