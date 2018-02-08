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
    $app = \Slim\Slim::getInstance();
    foreach($this->infos as $key=>$value){
      $route = $app->urlFor("afficher-item", ['num' => $value['id']]);
			$code=$code." <li><a href='$route'>".$value['nom']."</a> </li><br>";
		}
		$code=$code."</ul></section>";

		return $code;
	}

  public function afficherFormulaireReservation(){
    $jours=array('Lundi','Mardi','Mercredi','Jeudi','Vendredi');
    $heuresDeb=array(8,10,14,16);
    $heuresFin=array(10,12,16,18);
    $code=<<<END
    <form id="reservation" method="post" action="validerReservation">
    <label for="f1_jourdeb">Jour de départ</label>
    <select id="f1_jourdeb" name="jourdeb" required>
END;
  $i=0;
  while($i<5){
    $y=$i+1;
    $code.="<option value=\"".$y."\">".$jours[$i]."</option>";
    $i=$i+1;
  }
  $code=$code."</select><br>";
  $code.=<<<END
  <label for="f1_heuredeb">Heure de départ</label>
  <select id="f1_heuredeb" name="heuredeb" required>
END;
  $i=0;
  while($i<4){
    $code.="<option value=\"".$heuresDeb[$i]."\">".$heuresDeb[$i]."</option>";
    $i=$i+1;
  }
  $code=$code."</select><br>";
  $code.=<<<END
  <label for="f1_jourfin">Jour de Fin</label>
  <select id="f1_jourfin" name="jourfin" required>
END;
$i=0;
while($i<5){
  $y=$i+1;
  $code.="<option value=\"".$y."\">".$jours[$i]."</option>";
  $i=$i+1;
}
$code=$code."</select><br>";
  $code.=<<<END
  <label for="f1_heurefin">Heure de fin</label>
  <select id="f1_heurefin" name="heurefin" required>
END;
  $i=0;
  while($i<4){
  $code.="<option value=\"".$heuresFin[$i]."\">".$heuresFin[$i]."</option>";
  $i=$i+1;
  }
  $code=$code."</select><br>";
  $code.="<button type=\"submit\" name=\"valider_reservation\" value=\"valid_reservation\">Valider</button></form>";
  return $code;
  }
  /**
  *public function afficherPlanningGraphique(){
  *  $code="<table><tr><th>Jour</th><th>8h-10h</th><th>10h-12h</th><th>12h-14h</th><th>14h-16h</th><th>16h-18h</th></tr>";
  *  $tab=array();
  *  tab[]='lundi';
  *  tab[]='mardi';
  *  tab[]='mercredi';
  *  tab[]='jeudi';
  *  tab[]='vendredi';
  *  $i=0;
  *  foreach($this->infos as $key=>$value){
  *    $code=$code+"<tr><td>".$tab[i]."</td>";
  *    $i++;
  *  }
  *  $code=$code+"</table>";
  *}
  */

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
  case 4:{
  		$code=VueGeneral::genererHeader("demarrage");
  		$code.=$this->afficherFormulaireReservation();
  		break;
  	}
  }

  $code.=VueGeneral::genererFooter();
  echo $code;
}
}
