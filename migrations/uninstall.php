<?php
/**
 * Created by PhpStorm.
 * User: gqadonis
 * Date: 5/25/15
 * Time: 11:09 PM
 */

class uninstall extends ZDbMigration {
    public function up()
    {
        $this->dropTable('room_membership');
        $this->dropTable('room');
    }
    public function down()
    {
        echo "uninstall does not support migration down.\n";
        return false;
    }
}