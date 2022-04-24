<?php

$userAccess = include __DIR__.DIRECTORY_SEPARATOR.'user.php';
return array_merge($userAccess, array(
    '/admin/index',
    '/admin/admin',
    '/admin/tambah-user',
    '/admin/'
));