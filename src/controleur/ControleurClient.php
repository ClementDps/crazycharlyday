<?php

namespace garagesolidaire\controleur;
use \garagesolidaire\models\Item;
use \garagesolidaire\vue\VueClient;

class ControleurClient{

	public function afficherItem($id){
		$i=Item::find($id);
		if(isset($i)){
			$vue = new VueClient($i->toArray());
			$vue->render(2);
		}
	}

}
