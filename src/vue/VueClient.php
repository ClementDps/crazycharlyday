<?php

namespace garagesolidaire\vue;

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

	  $code="Nom : ".$nom." <br> Description : ".$desc;
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
		foreach($this->infos as $key=>$value){
			$code=$code." <li><a href='affichercatergorie/".$value['id']."'>".$value['nom']."</a> </li><br>";
		}
		$code=$code."</ul></section>";

		return $code;
	}

  public function afficherPlanningGraphique(){
    $code="<table><tr><th>Jour</th><th>8h-10h</th><th>10h-12h</th><th>12h-14h</th><th>14h-16h</th><th>16h-18h</th></tr>";
    $tab=array();
    tab[]='lundi';
    tab[]='mardi';
    tab[]='mercredi';
    tab[]='jeudi';
    tab[]='vendredi';
    $i=0;
    foreach($this->infos as $key=>$value){
      $code=$code+"<tr><td>".$tab[i]."</td>";
      if(){
        
      }
    }
    $code=$code+"</table>"

  }

  public function render($int){
  switch($int){
    case 1:{
      $content=$this->afficherCategories();
      break;
    }
  case 2:{
		$content=$this->afficherItem();
      break;
    }
  case 3:{
    $content=$this->afficherItemsCateg();
    break;
    }
    case 4:{
      $content=$this->afficherPlanningGraphique();
      break;
      }
  }
  $code= <<<END
  <!DOCTYPE html>
  <html>
      <head>
          <title>GarageSolidaire</title>
          <meta charset="UTF-8">
          <meta name="viewport" content="width=device-width, initial-scale=1.0">
          <link rel="stylesheet" href="../../client.css" />
      </head>
      <body>
		$content
      </body>
  </html>

END;
  echo $code;
}
}
