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
		$code=$code.'<img src="../../../img/'.$img.'" width = "150" height="150"></img><br>';
		
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

  public function render($int){
  switch($int){
    case 1:{
      $content=$this->afficherAccueil();
      break;
    }
  case 2:{
		$content=$this->afficherItem();
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
          <link rel="stylesheet" href="../style.css" />
      </head>
      <body>
		$content
      </body>
  </html>

END;
  echo $code;
}
}
