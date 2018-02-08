<?php

namespace garagesolidaire\controleur;

use garagesolidaire\vue\VueAdministrateur as VueAdministrateur;
use garagesolidaire\models\Authentication as Authentification;
use garagesolidaire\models\UserInfo as UserInfo;
use garagesolidaire\models as Model;

class ControleurAdministrateur{

  public function afficherReservation(){

    $reservs = Model\Reservation::where("etat","=","reserve")->get();
    $vue = new VueAdministrateur($reservs->toArray());
    $vue->render(VueAdministrateur::AFF_RESERV);
  }

  public function acceptReservation($id){

      $reserv = Model\Reservation::where("id","=",$id)->first();
      $reserv->etat = "confirmer";
      $reserv->save();

      $app = \Slim\Slim::getInstance();
      $app->redirect($app->urlFor("reservation-list"));

  }

  public function afficherModuleAdmin(){
    if(isset($_SESSION['userid']) && $_SESSION['rang']>0){
      $vue=new VueAdministrateur();
      $vue->render(15);
    }
  }

  public function afficherAjoutItem(){
    if(isset($_SESSION['userid']) && $_SESSION['rang']>0){
      $vue=new VueAdministrateur();
      $vue->render(16);
    }
  }

  public function afficherAjoutCateg(){
    if(isset($_SESSION['userid']) && $_SESSION['rang']>0){
      $vue=new VueAdministrateur();
      $vue->render(17);
    }
  }

  public function ajouterItem($nom,$desc){
    if(isset($_SESSION['userid']) && $_SESSION['rang']>0){
      $n=filter_var($nom,FILTER_SANITIZE_STRING);
      $d=filter_var($desc,FILTER_SANITIZE_STRING);
      Item::insert($nom,$desc);
    }
    $vue=new VueAdministrateur();
    $vue->render(15);
  }

  public function ajouterCateg($nom,$desc){
    if(isset($_SESSION['userid']) && $_SESSION['rang']>0){
      $n=filter_var($nom,FILTER_SANITIZE_STRING);
      $d=filter_var($desc,FILTER_SANITIZE_STRING);
      Categorie::insert($nom,$desc);
    }
    $vue=new VueAdministrateur();
    $vue->render(15);
  }

  public function declineReservation($id){
    $reserv = Model\Reservation::where("id","=",$id)->first();
    $reserv->etat = "annuler";
    $reserv->save();

    $app = \Slim\Slim::getInstance();
    $app->redirect($app->urlFor("reservation-list"));
  }
}
