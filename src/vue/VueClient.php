<?php

namespace garagesolidaire\vue;
use \garagesolidaire\vue\VueGeneral;

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
<form id="planninggraph" method="get" action ="afficherplanninggraph/$id">
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
  
  public function afficherCategories(){
		$code= "<section><ul>";
		foreach($this->infos as $key=>$value){
			$code=$code." <li><a href='affichercatergorie/".$value['id']."'>".$value['nom']."</a> </li><br>";
		}
		$code=$code."</ul></section>";
		
		return $code;
		
	}

	//mettre les bons css
  public function render($int){
  switch($int){
    case 1:{
		
      break;
    }
  case 2:{
	  $code=VueGeneral::genererHeader("demarrage");
		$code.=$this->afficherItem();
      break;
    }
	case 3:{
		$code=VueGeneral::genererHeader("demarrage");
		$code.=$this->afficherCategories();
		break;
	}
  }

  $code.=VueGeneral::genererFooter();
  echo $code;
}
}
