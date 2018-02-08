<?php
require_once 'vendor/autoload.php' ;
use \garagesolidaire\controleur\ControleurClient;
use \Illuminate\Database\Capsule\Manager as DB;


use \Slim\Slim;
use garagesolidaire\controleur\GestionAccueil;
use garagesolidaire\controleur\GestionCompte;


$db=new DB();
$db->addConnection(parse_ini_file('./conf/conf.ini'));
$db->setAsGlobal();
$db->bootEloquent();

session_start();
$app = new Slim() ;

//----------------------Pages-d-accueil--------------//
$app->get('/', function () {
  $c = new GestionAccueil();
  $c -> afficheAccueil();
})->name("accueil");

$app->get('/afficher/items/categorie/:num',function($num){
	$control=new ControleurClient();
	$control->afficheritemscategorie($num);
})->name("afficher-item");

$app->get('/afficher/planning/graph/:num',function($num){
	$control=new ControleurClient();
	$control->afficherPlanningGraphique($num);
});

$app->get('/contact', function () {
  $c = new GestionAccueil();
  $c -> afficheContact();
})->name("contact");

$app->get('/about', function () {
  $c = new GestionAccueil();
  $c -> afficheAbout();
})->name("about");

$app->get('/help', function () {
  $c = new GestionAccueil();
  $c -> afficheHelp();
})->name("help");

// Redirection Erreur 404
$app->notFound(function () use ($app) {
  $c = new GestionAccueil();
  $c -> error404();
});

$app->get('/afficher/item/:id',function($id){
	$control = new ControleurClient();
	$control->afficherItem($id);
})->name('item');

$app->get('/afficher/categories',function(){
	$control=new ControleurClient();
	$control->afficherCategories();
})->name("aff-categorie");


$app->get('/afficher/creation/reservation/:id',function($id){
	$control=new ControleurClient();
	$control->afficherCreationReservation($id);
})->name("creation-reservation");


$app->get('/afficherlisteutilisateurs',function(){
	$control=new ControleurClient();
	$control->afficherListeUtilisateurs();
});

//-----------------------------Formulaire-de-connexion-et-deconnexion-compte----------//
$app->get('/connexion', function () {
  $c = new GestionCompte();
  $c -> afficheConnexion();
})->name("connexion");

$app->post('/connexion', function () {
    $c = new GestionCompte();
    $c->etablirConnection();
});

$app->get('/deconnexion', function () {
  $c = new GestionCompte();
  $c -> deconnecter();
})->name("deconnexion");

$app->get('/user',function () {
  $c = new GestionCompte();
  $c->afficherPanel();
})->name( 'aff-user' );

//----------------------Formulaire-Inscription-Compte------//
$app->get('/inscription', function () {
  $c = new GestionCompte();
  $c -> afficheInscription();
})->name("inscription");

$app->post('/inscription', function () {
  $c = new GestionCompte();
  $c -> ajouterUtilisateur();
});



$app->get('/user/delete', function () {
    $c = new GestionCompte();
    $c->supprimerCompte();
})->name('supprimer-compte');

$app->post('/user/modifier-compte', function () {
    $c = new GestionCompte();
    $c->afficherModifierCompte();
})->name('modifier-compte');

$app->get('/user/change-mdp', function () {
    $c = new GestionCompte();
    $c->afficherChangerMotDePasse();
})->name("modifier-mdp");

$app->post('/validerreservation/:id', function($id) {
    $c = new ControleurClient();
    $c->validerReservation($_POST['jourdeb'],$_POST['jourfin'],$_POST['heuredeb'],$_POST['heurefin'],$id);
})->name("valid-reserv");

$app->get('/afficherplanningreservationitem/:num',function($num){
	$control=new ControleurClient();
	$control->afficherPlanningReservationItem($num);
})->name("reservationitem");

$app->get('/reservation/' , function () {

})->name("reservation");

$app->run();
