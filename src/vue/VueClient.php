<?php

namespace garagesolidaire\vue;
use \garagesolidaire\vue\VueGeneral;
use \garagesolidaire\models\User;

class VueClient{

  private $infos;

  public function __construct($tab){
    $this->infos=$tab;
  }

  public function afficherItem(){
	  $id=$this->infos['id'];
	  $nom=$this->infos['nom'];
	  $desc=$this->infos['description'];
	  $img=$this->infos['img'];
	  
	  $code="<p>Nom : ".$nom." <br> Description : ".$desc."<p>";
	  if($img!==""){
		$code=$code.'<img src="../../img/item/'.$img.'" width = "150" height="150"></img><br>';
	  }
		//liens des boutons bidons
		$buttonlisteres=<<<END
<form id="listeres" method="get" action ="afficherlisteres/$id">
<button type="submit" name="valider_affichage_liste_res" value="valid_affichage_liste_res">Afficher la liste des réservations</button>
</form>
END;
$buttonplanning=<<<END
<form id="planninggraph" method="get" action ="reservationitem/$id">
<button type="submit" name="valider_affichage_planning_graph" value="valid_affichage_planning_graph">Planning graphique</button>
</form>
END;

$buttonformulaireres=<<<END
<form id="formulaireres" method="get" action ="afficherformulaireres/$id">
<button type="submit" name="valider_affichage_formulaire_res" value="valid_affichage_formulaire_res">Réserver</button>
</form>
END;
		$code=$code.$buttonlisteres.$buttonplanning.$buttonformulaireres;



		return $code;
  }

   public function afficherItemsCateg(){
    $app=\Slim\Slim::getInstance();
    $code="";
    $c=$this->infos['c'];
    $i=$this->infos['i'];
    $c=$c[0];
    $code="Catégorie :".$c['nom']."<br>"."Description :".$c['description']."<br>";
    foreach($i as $key=>$value){
      $code=$code."<img src=\"/img/".$value['img']."\" width=\"50\" height=\"50\">";
      $code=$code."Nom de l'item :"."<A HREF=\"../afficheritem/".$value['id']."\">".$value['nom']."</A>"."<br> Description :".$value['description']."<br>";
    }
    return $code;
}

  public function afficherCategories(){
		$code= "<section><ul>";
		foreach($this->infos as $key=>$value){
			$code=$code." <li><a href='afficheritemscategorie/".$value['id']."'>".$value['nom']."</a> </li><br>";
		}
		$code=$code."</ul></section>";

		return $code;

	}
	
	public function afficherListeUtilisateurs(){
		$code= "<section><ul>";
		foreach($this->infos as $key=>$value){
			$code=$code." <li><a href='afficheritemscategorie/".$value['id']."'>".$value['nom']." ".$value["prenom"]."</a> </li>";
			$code = $code."<img src=\"../img/user/".$value['img'].".jpg\" width=\"50\" height=\"50\"><br><br>";
		}
		$code=$code."</ul></section>";

		return $code;
	}
	
	public function afficherPlanningReservationItem(){
		$code ="";
		foreach($this->infos as $key=>$value){
			$reservateur  = User::find($value["idUser"])["nom"];
			$date = "";
			$heured = $value["heureDeb"];
			$heuref = $value["heureFin"];
			$jour="";
			switch($value["jourDeb"]){
				case 1 :{
					$jour = "Lundi";
					break;}
				case 2 :{
					$jour = "Mardi";
					break;}
				case 3 :{
					$jour = "Mercredi";
					break;}
				case 4 :{
					$jour = "Jeudi";
					break;}
				case 5 :{
					$jour = "Vendredi";
				}	
			}
			$code = $code."Reserver par ".$reservateur." le ".$jour." de ".$heured."h à ".$heuref."h.<br>";
		}
	return $code;
	}

	//mettre les bons css
  public function render($int){
  switch($int){
    case 1:{
		$code=VueGeneral::genererHeader("demarrage");
      $code.=$this->afficherCategories();
      break;
    }
	case 2:{
	  $code=VueGeneral::genererHeader("demarrage");
		$code.=$this->afficherItem();
      break;
    }
	case 3:{
		$code=VueGeneral::genererHeader("demarrage");
		$code.=$this->afficherItemsCateg();
		break;
	}
	case 10:{
		$code=VueGeneral::genererHeader("demarrage");
		$code.=$this->afficherListeUtilisateurs();
		break;
	}
	case 11:{
		$code=VueGeneral::genererHeader("demarrage");
		$code.=$this->afficherPlanningReservationItem();
		break;
	}
  }
  $code.=VueGeneral::genererFooter();
  echo $code;
}
}
