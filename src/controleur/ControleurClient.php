<?php

namespace garagesolidaire\controleur;
use \garagesolidaire\models\Item;
use \garagesolidaire\models\Categorie;
use \garagesolidaire\vue\VueClient;
use \garagesolidaire\models\User;
use garagesolidaire\models\Reservation;

class ControleurClient{

	public function afficherItem($id){
		$i=Item::find($id);
		if(isset($i)){
			$vue = new VueClient($i->toArray());
			$vue->render(2);
		}
	}

	public function afficherCategories(){
		$categorie = Categorie::all();
		$vue = new VueClient($categorie->toArray());
		$vue->render(1);
	}



	public function afficheritemscategorie($num){
		$categ=Categorie::where('id','=',$num)->get();
		$items=Item::where('id_categ','=',$num)->get();
		$tab=array();
		$tab['c']=$categ;
		$tab['i']=$items;
		$v=new VueClient($tab);
		$v->render(3);
	}
	
	public function afficherListeUtilisateurs(){
		$utilisateurs = User::all();
		$vue = new VueClient($utilisateurs->toArray());
		$vue->render(10);
	}
	
	public function afficherPlanningReservationItem($num){
		$tab = Reservation::where('idItem','=',$num)->orderBy("jourDeb")->get();
		$vue = new VueClient($tab->toArray());
		$vue->render(11);
	}
	
	
	
	
}
