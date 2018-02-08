<?php
require_once 'vendor/autoload.php' ;

$db=new DB();
$db->addConnection(parse_ini_file('./src/conf/conf.ini'));
$db->setAsGlobal();
$db->bootEloquent();
session_start();
$app = new \Slim\Slim() ;


$app->run();