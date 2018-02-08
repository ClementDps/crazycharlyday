<?php
require_once 'vendor/autoload.php' ;

$db=new DB();
$db->addConnection(parse_ini_file('/conf/conf.ini'));
$db->setAsGlobal();
$db->bootEloquent();
session_start();
$app = new \Slim\Slim() ;


// Redirection Erreur 404 
// $app->notFound(function () use ($app) {
//   $app->redirect($app->urlFor('list-not-found'));
// });

$app->run();
