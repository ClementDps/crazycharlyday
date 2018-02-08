<?php

namespace garagesolidaire\controleur;


use garagesolidaire\vue\VueAdministrateur as VueAdministrateur;
use garagesolidaire\models\Authentication as Authentification;
use garagesolidaire\models\UserInfo as UserInfo;
use garagesolidaire\models as Model;
use garagesolidaire\models\Item;

class ControleurAdministrateur{


  public function afficherReservations(){
    $reservs = Model\Reservation::where("etat","=","")->get();
    $vue = new VueAdministrateur(null);
    $vue->render(VueAdministrateur::AFF_RESERV);
  }
  
  	public function items(){
		$tab = Item::all();
		$vue = new VueAdministrateur($tab->toArray());
		$vue->render(10);
	}
	
	public function modifierItem($id){
		$tab = Item::find($id);
		$vue = new VueAdministrateur($tab->toArray());
		$vue->render(11);
	}
	
	public function validationModificationItem($nom,$desc,$idcateg,$id){
		$n=filter_var($nom,FILTER_SANITIZE_STRING);
		$d=filter_var($desc,FILTER_SANITIZE_STRING);
		$c=filter_var($idcateg,FILTER_SANITIZE_NUMBER_INT);
		Item::mettreAjour($id,$n,$d,$c);
		$this->items();
	}

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
	

