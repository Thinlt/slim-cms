<?php
/**
 * Slim framework
 * Author: Tit - Robert - Thinlt
 */
require __DIR__.'/app/bootstrap.php';
$app = new \App(require 'etc/config.php');

$role = new \Model\Admin\Role();
$role->newRole('Administrator', array('sources'=>'all'));

$admin = new \Model\Admin\User();
$admin->newUser('admin', 'admin123@', 'Tit', $role);

echo "Install complete";

