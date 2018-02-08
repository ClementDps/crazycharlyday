<?php

namespace garagesolidaire\vue;
use \garagesolidaire\vue\VueGeneral;
use \garagesolidaire\controleur\ControleurClient;
use \garagesolidaire\models\Item;
use \garagesolidaire\models\User;
use \garagesolidaire\models\Commentaire;


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
	  $app=\Slim\Slim::getInstance();
		$route3=$app->urlFor("reservationitem",['id'=>$id]);
		$buttonlisteres=<<<END
<form id="listeres" method="get" action ="$route3">
<button type="submit" name="valider_affichage_liste_res" value="valid_affichage_liste_res">Afficher la liste des réservations</button>
</form>
END;


$route2=$app->urlFor("afficher-palanning-graph",['num'=>$id]);
$buttonplanning=<<<END
<form id="planninggraph" method="get" action ="$route2">
<button type="submit" name="valider_affichage_planning_graph" value="valid_affichage_planning_graph">Planning graphique</button>
</form>
END;

$route=$app->urlFor('creation-reservation',['id'=>$id]);
$buttonformulaireres=<<<END
<form id="formulaireres" method="get" action ="$route">
<button type="submit" name="valider_affichage_formulaire_res" value="valid_affichage_formulaire_res">Réserver</button>
</form>
END;
		$code=$code.$buttonlisteres.$buttonplanning.$buttonformulaireres;


		if(isset($_SESSION['userid'])){
		$route3=$app->urlFor('ajouter-commentaire',['id'=>$id]);
		$code= $code.<<<END
	<form id = "f1" method="post" action = "$route3">
	<label for "f1_message">Commenter:</label>
	<input type="text" id="f1_message" name="message" >
	<button type="submit" name="valider_message" value="valid_f2">Commenter</button>
	</form>
END;
		}
		$messages = Commentaire::where('idItem','=',$id)->get();
		if (isset($messages[0])){
			$code = $code."<br><br>Commentaires :<br><br>";
			foreach($messages as $message){
				$user=User::find($message['idUser']);
				$code = $code.$user->nom." : ".$message->message." ".$message->dateMess."<br><br>";
			}
		}
		return $code;
  }

   public function afficherItemsCateg(){
     $app=\Slim\Slim::getInstance();
    $code="";
    $c=$this->infos['c'];
    $i=$this->infos['i'];
    $c=$c[0];
    $code="Catégorie :".$c['nom']."<br>"."Description :".$c['description']."<br>";
    $root = $app->request->getRootUri();
    foreach($i as $key=>$value){
      $route = $app->urlFor("item", ['id' => $value['id']]);
      $code=$code."<img src=\"$root/img/item/".$value['img']."\" width=\"50\" height=\"50\">";
      $code=$code."Nom de l'item :"."<A HREF=\"$route\">".$value['nom']."</A>"."<br> Description :".$value['description']."<br>";
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
			$code.="<p>Reservé par ".$reservateur." le ".$jour." de ".$heured."h à ".$heuref."h.<br>";

		}
		if($code == ""){
			$code = "<p>Cet item n'a pas été reservé";
		}
		return $code;
	}

  public function afficherFormulaireReservation(){
    $jours=array('Lundi','Mardi','Mercredi','Jeudi','Vendredi');
    $heuresDeb=array(8,10,14,16);
    $heuresFin=array(10,12,16,18);
    $app = \Slim\Slim::getInstance();
    $route=$app->urlFor("valid-reserv", ['id' => $this->infos]);
    $code=<<<END
    <form id="reservation" method="post" action="$route">
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

  public function afficherPlanningGraphique(){
    $code="<table><tr><th>Jour</th><th>8h-10h</th><th>10h-12h</th><th>14h-16h</th><th>16h-18h</th></tr>";
    $tab=array();
    $tab[]='lundi';
    $tab[]='mardi';
    $tab[]='mercredi';
    $tab[]='jeudi';
    $tab[]='vendredi';
	$y=1;
	$i=0;
	while($y<6){

		$code=$code."<tr><td>".$tab[$i]."</td>";
		$estReserve=0;
		foreach($this->infos as $key=>$value){
			$res=ControleurClient::testerValidite($y,$y,8,10,$value['jourDeb'],$value['jourFin'],$value['heureDeb'],$value['heureFin']);
			if($res==1){
				$estReserve=1;
			}
		}
		$resultat="libre";
		if($estReserve==1){
			$resultat="reserve";
		}
		$code.="<td id=\"$resultat\">$resultat</<td>";

		$estReserve=0;
		foreach($this->infos as $key=>$value){
			$res=ControleurClient::testerValidite($y,$y,10,12,$value['jourDeb'],$value['jourFin'],$value['heureDeb'],$value['heureFin']);
			if($res==1){
				$estReserve=1;
			}
		}
		$resultat="libre";
		if($estReserve==1){
			$resultat="reserve";
		}
		$code.="<td id=\"$resultat\">$resultat</<td>";

		$estReserve=0;
		foreach($this->infos as $key=>$value){
			$res=ControleurClient::testerValidite($y,$y,14,16,$value['jourDeb'],$value['jourFin'],$value['heureDeb'],$value['heureFin']);
			if($res==1){
				$estReserve=1;
			}
		}
		$resultat="libre";
		if($estReserve==1){
			$resultat="reserve";
		}
		$code.="<td id=\"$resultat\">$resultat</<td>";

		$estReserve=0;
		foreach($this->infos as $key=>$value){
			$res=ControleurClient::testerValidite($y,$y,16,18,$value['jourDeb'],$value['jourFin'],$value['heureDeb'],$value['heureFin']);
			if($res==1){
				$estReserve=1;
			}
		}
		$resultat="libre";
		if($estReserve==1){
			$resultat="reserve";
		}
		$code.="<td id=\"$resultat\">$resultat</<td>";
		$code.="</tr>";


		$i++;
		$y=$y+1;
	}
   $code=$code."</table>";
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
  *    $code=$code+"<tr><td>".$tab[$i]."</td>"
  *    // ajoute creneaux horaires
  *
  *     $code=$code."</tr>";
  *    $i++;
  *  }
  *  $code=$code+"</table>";
  *}
  */


  public function afficherMesReservations(){
    $code="<p>Mes réservations</p><br>";
    $jours=array();
    $jours[]="lundi";
    $jours[]="mardi";
    $jours[]="mercredi";
    $jours[]="jeudi";
    $jours[]="vendredi";
    foreach($this->infos as $key=>$value){
      $item=Item::find($value['idItem']);
      $code.="<a href=\"reservation/".$value['id']."\">Item réservé ".$item->nom." : du ".$jours[$value['jourDeb']-1]." à ".$value['heureDeb']."h au ".$jours[$value['jourFin']-1]." à ".$value['heureFin']."h</a><br>";
    }
    return $code;
  }



  /**

  public function afficherPlanningUser(){
	  $code="";
	  if(isset($this->infos)){
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
			$code.="<p>Reservé par ".$reservateur." le ".$jour." de ".$heured."h à ".$heuref."h.<br>";

		}
	  }else{
		  $code="pas de réservation";
	  }
	  return $code;
  }
  */


  public function afficherReservation(){
    $nom=Item::find($this->infos->idItem)->nom;
    $jours=array();
    $jours[]="lundi";
    $jours[]="mardi";
    $jours[]="mercredi";
    $jours[]="jeudi";
    $jours[]="vendredi";
    $code="";
    $nomUser=User::find($this->infos->idUser)->nom;
    $prenomUser=User::find($this->infos->idUser)->prenom;
    $code="Item réservé : ".$nom."<br>";
    $code.="La réservation a lieu du ".$jours[$this->infos->jourDeb]." à ".$this->infos->heureDeb."h jusqu'au ".$jours[$this->infos->jourFin]." à ".$this->infos->heureFin."h<br>";
    $code.="Le réserveur est ".$nomUser." ".$prenomUser."<br>";
    $code.="La réservation est actuellement ".$this->infos->etat."<br>";
    $code.="Elle a été crée le ".$this->infos->dateCreation." et a été modifiée le ".$this->infos->dateCreation."<br>";
	
	if($this->infos->etat!="annuler"){
	$app=\Slim\Slim::getInstance();
	$route=$app->urlFor("annuler-reservation",['id'=>$this->infos->id]);
	$button=<<<END
<form id="annulres" method="post" action ="$route">
<button type="submit" name="valider_annuler_res" value="valid_annuler_res">Annuler la réservation</button>
</form>
END;
$code.=$button;
	}
	if($this->infos->etat=="confirmer"){
		$app=\Slim\Slim::getInstance();
	$route=$app->urlFor("payer-reservation",['id'=>$this->infos->id]);
	$button=<<<END
<form id="payer" method="post" action ="$route">
 <label for="f1_payer">Montant : </label>
 <input type = "number" id="f1_payer" name="montant" placeholder="<Montant>" required></br>
<button type="submit" name="valider_payer" value="valid_payer">Payer</button>
</form>
END;
$code.=$button;
	}
    return $code;
  }


  public function render($int){
	  $code="";
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
  		$code=VueGeneral::genererHeader("formulaire");
  		$code.=$this->afficherFormulaireReservation();
  		break;
  	}
  case 5:{
    $code=VueGeneral::genererHeader("demarrage");
    $code.=$this->afficherMesReservations();
    break;
    }
  case 6:{
    $code=VueGeneral::genererHeader("demarrage");
    $code.=$this->afficherReservation();
    break;
  }

	case 9:{
		$code=VueGeneral::genererHeader("client");
		$code.=$this->afficherPlanningGraphique();
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
