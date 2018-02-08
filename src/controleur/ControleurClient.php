<?php

namespace garagesolidaire\controleur;
use \garagesolidaire\models\Item;
use \garagesolidaire\vue\VueClient;
use garagesolidaire\models\Categorie;

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
		$vue->render(3);
	}


}
