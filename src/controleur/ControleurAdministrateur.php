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
}
