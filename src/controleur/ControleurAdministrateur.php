<?php

namespace garagesolidaire\controleur;

use \garagesolidaire\vue\VueAdministrateur;

class ControleurAdministrateur{

	public function items(){
		$tab = Item::all();
		$vue = new VueAdministrateur($tab->toArray());
		$vue->render(10);
	}

}
