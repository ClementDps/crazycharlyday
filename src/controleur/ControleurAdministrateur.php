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

  public function declineReservation($id){
    $reserv = Model\Reservation::where("id","=",$id)->first();
    $reserv->etat = "annuler";
    $reserv->save();

    $app = \Slim\Slim::getInstance();
    $app->redirect($app->urlFor("reservation-list"));
  }
}
