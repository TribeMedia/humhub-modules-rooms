<?php
/**
 * Created by PhpStorm.
 * User: gqadonis
 * Date: 5/26/15
 * Time: 3:18 AM
 */

class m151524_234726_initial extends EDbMigration {
    public function up() {
        $this->createTable('room', array(
            'id' => 'pk',
            'guid' => 'varchar(45) DEFAULT NULL',
            'wall_id' => 'int(11) DEFAULT NULL',
            'name' => 'varchar(45) NOT NULL',
            'description' => 'text DEFAULT NULL',
            'website' => 'varchar(45) DEFAULT NULL',
            'join_policy' => 'tinyint(4) DEFAULT NULL',
            'visibility' => 'tinyint(4) DEFAULT NULL',
            'status' => 'tinyint(4) NOT NULL DEFAULT \'1\'',
            'tags' => 'text DEFAULT NULL',
            'created_at' => 'datetime DEFAULT NULL',
            'created_by' => 'int(11) DEFAULT NULL',
            'updated_at' => 'datetime DEFAULT NULL',
            'updated_by' => 'int(11) DEFAULT NULL',
        ), '');

        $this->createTable('room_membership', array(
            'room_id' => 'int(11) NOT NULL',
            'user_id' => 'int(11) NOT NULL',
            'originator_user_id' => 'varchar(45) DEFAULT NULL',
            'status' => 'tinyint(4) DEFAULT NULL',
            'request_message' => 'text DEFAULT NULL',
            'last_visit' => 'datetime DEFAULT NULL',
            'invite_role' => 'tinyint(4) DEFAULT NULL',
            'admin_role' => 'tinyint(4) DEFAULT NULL',
            'share_role' => 'tinyint(4) DEFAULT NULL',
            'created_at' => 'datetime DEFAULT NULL',
            'created_by' => 'int(11) DEFAULT NULL',
            'updated_at' => 'datetime DEFAULT NULL',
            'updated_by' => 'int(11) DEFAULT NULL',
        ), '');
        $this->addPrimaryKey('pk_room_membership', 'room_membership', 'room_id,user_id');

        $this->insert('setting', array('name'=>'defaultVisibility', 'module_id'=>'rooms', 'value'=>'1'));
        $this->insert('setting', array('name'=>'defaultJoinPolicy', 'module_id'=>'rooms', 'value'=>'1'));
    }
}