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

	public function afficherPlanningGraphique($num){
		$r=Reservation::where('idItem','=',$num)->get();
		$vue=new VueClient($r);
		$vue->render();
	}

public function afficherCreationReservation($id){
	//if(isset($_SESSION['userid'])){
		$vue=new VueClient($id);
		$vue->render(4);
	//}
}

public function validerReservation($jdeb,$jfin,$hdeb,$hfin,$id){
	if(isset($_SESSION['userid']) && isset($_POST['valider_reservation']) && $_POST['valider_reservation']=='valid_reservation'){
	if($jdeb<$jfin || ($jdeb==$jfin && $hdeb<$hfin)){
		//verifier que les plages horaires sont libres
		$res=Reservation::where('idItem','=',$id)->get();
		if(isset($res)){
			//$res=$res->toArray();
			$g=0;
			foreach($res as $key=>$value){
				$g=$g+$this->testerValidite($value['jourDeb'],$value['jourFin'],$value['heureDeb'],$value['heureFin'],$jdeb,$jfin,$hdeb,$hfin);
				$g=$g+$this->testerValidite($jdeb,$jfin,$hdeb,$hfin,$value['jourDeb'],$value['jourFin'],$value['heureDeb'],$value['heureFin']);
			}
			if($g==0){
				Reservation::insert($_SESSION['userid'],$id,$hdeb,$jdeb,$hfin,$jfin);
			}
		}else{
			Reservation::insert($_SESSION['userid'],$id,$hdeb,$jdeb,$hfin,$jfin);
		}

	}
	}
	$vue=new VueClient([]);
	$vue->render(1);
}

public function testerValidite($jdebB,$jfinB,$hdebB,$hfinB, $jdebA,$jfinA,$hdebA,$hfinA){
	if($jdebA<$jdebB){
		if($jfinA<$jdebB){
			return 0;
		}else{
			if($jfinA==$jdebB){
				if($hfinA<=$hdebB){
					return 0;
				}else{
					return 1;
				}
			}else{
				return 1;
			}
		}
	}else{
		if($jdebA==$jdebB){
			if($jfinA==$jdebB){
				if($hfinA<=$hdebB){
					return 0;
				}else{
					return 1;
				}
			}else{
				if($jfinB==$jdebA){
					if($hfinB<=$hdebA){
						return 0;
					}else{
						return 1;
					}
				}else{
					return 1;
				}
			}
		}else{
			if($jfinB<$jdebA){
				return 0;
			}else if($jfinB==$jdebA){
				if($hfinB<=$hdebA){
					return 0;
				}else{
					return 1;
				}
			}else{
				return 1;
			}
		}
		return 1;
	}

}


}
