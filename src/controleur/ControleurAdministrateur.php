<?php

namespace garagesolidaire\controleur;

use garagesolidaire\vue\VueAdministrateur as VueAdministrateur;
use garagesolidaire\models\Authentication as Authentification;
use garagesolidaire\models\UserInfo as UserInfo;
use garagesolidaire\models as Model;

class ControleurAdministrateur{

  public function afficherReservations(){

    $reservs = Model\Reservation::where("etat","=","")->et();
    $vue = new VueAdministrateur(null);
    $vue->render(VueAdministrateur::AFF_RESERV);
  }
}
