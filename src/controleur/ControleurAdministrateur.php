<?php

namespace garagesolidaire\controleur;


use garagesolidaire\vue\VueAdministrateur as VueAdministrateur;
use garagesolidaire\models\Authentication as Authentification;
use garagesolidaire\models\UserInfo as UserInfo;
use garagesolidaire\models as Model;
use garagesolidaire\models\Item;

class ControleurAdministrateur{


  public function afficherReservations(){
    \garagesolidaire\controleur\GestionCompte::checkAdmin();

    $reservs = Model\Reservation::where("etat","=","")->get();
    $vue = new VueAdministrateur(null);
    $vue->render(VueAdministrateur::AFF_RESERV);
  }

  	public function items(){
      \garagesolidaire\controleur\GestionCompte::checkAdmin();

		$tab = Item::all();
		$vue = new VueAdministrateur($tab->toArray());
		$vue->render(10);
	}

	public function modifierItem($id){
    \garagesolidaire\controleur\GestionCompte::checkAdmin();

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
    \garagesolidaire\controleur\GestionCompte::checkAdmin();

    $reservs = Model\Reservation::where("etat","=","reserve")->get();
    $vue = new VueAdministrateur($reservs->toArray());
    $vue->render(VueAdministrateur::AFF_RESERV);
  }

  public function acceptReservation($id){
    \garagesolidaire\controleur\GestionCompte::checkAdmin();

      $reserv = Model\Reservation::where("id","=",$id)->first();
      $reserv->etat = "confirmer";
      $reserv->save();

      $app = \Slim\Slim::getInstance();
      $app->redirect($app->urlFor("reservation-list"));

  }

  public function afficherModuleAdmin(){
    \garagesolidaire\controleur\GestionCompte::checkAdmin();

    if(isset($_SESSION['userid']) && $_SESSION['rang']>0){
      $vue=new VueAdministrateur();
      $vue->render(15);
    }
  }

  public function afficherAjoutItem(){
    \garagesolidaire\controleur\GestionCompte::checkAdmin();

    if(isset($_SESSION['userid']) && $_SESSION['rang']>0){
      $vue=new VueAdministrateur();
      $vue->render(16);
    }
  }

  public function afficherAjoutCateg(){
    \garagesolidaire\controleur\GestionCompte::checkAdmin();

    if(isset($_SESSION['userid']) && $_SESSION['rang']>0){
      $vue=new VueAdministrateur();
      $vue->render(17);
    }
  }

  public function ajouterItem($nom,$desc){
    \garagesolidaire\controleur\GestionCompte::checkAdmin();

    if(isset($_SESSION['userid']) && $_SESSION['rang']>0){
      $n=filter_var($nom,FILTER_SANITIZE_STRING);
      $d=filter_var($desc,FILTER_SANITIZE_STRING);
      Item::insert($nom,$desc);
    }
    $vue=new VueAdministrateur();
    $vue->render(15);
  }

  public function ajouterCateg($nom,$desc){
    \garagesolidaire\controleur\GestionCompte::checkAdmin();

    if(isset($_SESSION['userid']) && $_SESSION['rang']>0){
      $n=filter_var($nom,FILTER_SANITIZE_STRING);
      $d=filter_var($desc,FILTER_SANITIZE_STRING);
      Categorie::insert($nom,$desc);
    }
    $vue=new VueAdministrateur();
    $vue->render(15);
  }

  public function declineReservation($id){
    \garagesolidaire\controleur\GestionCompte::checkAdmin();
    
    $reserv = Model\Reservation::where("id","=",$id)->first();
    $reserv->etat = "annuler";
    $reserv->save();

    $app = \Slim\Slim::getInstance();
    $app->redirect($app->urlFor("reservation-list"));
  }

}
