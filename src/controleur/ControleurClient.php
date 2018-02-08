<?php

namespace garagesolidaire\controleur;

class ControleurAdministrateur{

	public function afficherItem($id){
		$i=Item::find($id);
		$vue = new VueClient($i->toArray());
		$vue->render(2);
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
}
