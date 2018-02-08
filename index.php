<?php
require_once 'vendor/autoload.php' ;
use \garagesolidaire\controleur\ControleurClient;
use \garagesolidaire\controleur\ControleurAdministrateur;
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
})->name("afficher-palanning-graph");

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
})->name("afficher-utilisateurs");

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

$app->get('/error/forbidden', function (){
  $c = new GestionCompte();
  $c -> afficheNonAccess();
});

$app->get('/error/no_connected', function (){
  $c = new GestionCompte();
  $c -> afficheNonConnection();
})->name("no-connection");

$app->get('/user/delete', function () {
    $c = new GestionCompte();
    $c->supprimerCompte();
})->name('supprimer-compte');

$app->get('/user/modifier-compte', function () {
    $c = new GestionCompte();
    $c->afficherModifCompte();
})->name('modifier-compte');

$app->post('/user/modifier-compte', function () {
    $c = new GestionCompte();
    $c->modifCompte();
});

$app->get('/user/change-mdp', function () {
    $c = new GestionCompte();
    $c->afficherChangerMotDePasse();
})->name("modifier-mdp");

$app->post('/user/change-mdp', function () {
    $c = new GestionCompte();
    $c->changerMotDePasse();
});

$app->post('/validerreservation/:id', function($id) {
    $c = new ControleurClient();
    $c->validerReservation($_POST['jourdeb'],$_POST['jourfin'],$_POST['heuredeb'],$_POST['heurefin'],$id);
})->name("valid-reserv");


$app->get('/mesreservations', function () {
    $c = new ControleurClient();
    $c->mesReservations();
})->name("mes-reservations");

$app->get('/afficherplanningreservationitem/:id',function($id){
	$control=new ControleurClient();
	$control->afficherPlanningReservationItem($id);
})->name("reservationitem");


$app->get('/reservation/:id' , function ($id) {
  $control=new ControleurClient();
  $control->afficherReservation($id);
})->name("reservation");


$app->post('/ajoutercommentaire/:id',function($id){
	$control=new ControleurClient();
	$control->ajouterCommentaire($id,$_POST['message']);
})->name("ajouter-commentaire");

$app->get('/afficheritems',function(){
	$control=new ControleurAdministrateur();
	$control->items();
})->name("afficher-tous-items");



$app->get('/list/reservation/' , function () {
  $c = new ControleurAdministrateur();
  $c->afficherReservation();
})->name("reservation-list");

$app->post('/list/reservation/accept/:id' , function ($id) {
  $c = new ControleurAdministrateur();
  $c->acceptReservation($id);
})->name("reservation-accept");

$app->post('/list/reservation/decline/:id' , function ($id) {
  $c = new ControleurAdministrateur();
  $c->declineReservation($id);
})->name("reservation-decline");


$app->get('/afficherplanningreservationuser/:id',function($id){
	$control=new ControleurClient();
	$control->afficherPlanningUser($id);
})->name("reservation-user");

$app->get('/moduleadministrateur',function(){
  $control=new ControleurAdministrateur();
  $control->afficherModuleAdmin();
})->name("module-admin");

$app->get('/afficherajoutitem',function(){
  $control=new ControleurAdministrateur();
  $control->afficherAjoutItem();
})->name("afficher-ajoutItem");

$app->get('/afficherajoutecateg',function(){
  $control=new ControleurAdministrateur();
  $control->afficherAjoutCateg();
})->name("afficher-ajoutCateg");

$app->get('/modifierItem/:id',function($id){
	$control=new ControleurAdministrateur();
	$control->modifierItem($id);
})->name("modifierItem");

$app->post('/validationModificationItem/:id',function($id){
	$control=new ControleurAdministrateur();
	$control->validationModificationItem($_POST['nom'],$_POST['desc'],$_POST['categorie'],$id);
})->name("validermodifitem");


$app->post('/annulerreservation/:id',function($id){
	$control=new ControleurClient();
	$control->annulerReservation($id);
})->name("annuler-reservation");


$app->post('/ajouteritem',function($id){
	$control=new ControleurAdministrateur();
	$control->ajouterItem($_POST['nom'],$_POST['desc']);
})->name("ajouter-item");

$app->post('/ajoutercateg',function($id){
	$control=new ControleurAdministrateur();
	$control->ajouterCateg($_POST['nom'],$_POST['desc']);
})->name("ajouter-categ");


$app->post('/payerRes/:id',function($id){
	$control=new ControleurClient();
	$control->payerReservation($id);
})->name("payer-reservation");

$app->post('/noter/:id',function($id){
	$control=new ControleurClient();
	$control->noterReservation($id);
})->name("noter-item");


$app->run();
