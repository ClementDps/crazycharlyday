<?php

namespace garagesolidaire\controleur;
use \garagesolidaire\models\Item;
use \garagesolidaire\models\Categorie;
use \garagesolidaire\vue\VueClient;

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

	public function afficherConnexion(){
		$vue=new VueClient([]);
		$vue->afficherConnexion();
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

	public function afficherPlanningGraphique($num){
		$r=Reservation::where('idItem','=',$num)->get();
		$vue=new VueClient($r);
		$vue->render(4);
	}
}
