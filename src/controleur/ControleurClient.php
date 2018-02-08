<?php

namespace garagesolidaire\controleur;

class ControleurAdministrateur{

	public function afficherItem($id){
		$i=Item->get();
		$vue = new VueClient($i->toArray());
		$vue->render(2);
	}

}
