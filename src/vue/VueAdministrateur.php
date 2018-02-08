<?php

namespace garagesolidaire\vue;

use \garagesolidaire\models\User;
use \garagesolidaire\models\Item;

class VueAdministrateur{

  private $infos;

  const AFF_CO = 3;
  const AFF_INSC = 4;
  const AFF_USER = 5;
  const AFF_RESERV = 6;

  public function __construct($tab){
    $this->infos=$tab;
  }

  public function afficherModuleAdmin(){
    $app=\Slim\Slim::getInstance();
    $routeitem=$app->urlFor('afficher-ajoutItem');
    $routecateg=$app->urlFor('afficher-ajoutCateg');
    $code=<<<END
<form id="afficherAjoutItem" method= "post" action ="$routeitem">
<button type="submit" name="valider_allerversitem"  value="valid_versitem">Ajouter un item</button>
</form>

<form id="afficherAjoutCateg" method= "post" action ="$routecateg">
<button type="submit" name="valider_allerverscateg"  value="valid_versitem">Ajouter une catégorie</button>
</form>
END;
  return $code;
  }

  public function afficherAjoutItem(){
$code=<<<END



END;
return $code;
  }

  public function afficherAjoutCateg(){
    $code=<<<END



END;
    return $code;
  }

  public function render($int){
    $code = "";
    $app = \Slim\Slim::getInstance();

  switch($int){
    case 1:{
      $content=$this->afficherAccueil();
      break;
    }
  case VueAdministrateur::AFF_CO :{
          $errorLogIn = "";
          $email = "";
          if(isset($this->infos)){ //Gestion du cas d'erreur
            if(isset($this->infos["email"]) && isset($this->infos['error']) && $this->infos['error'] == "auth"){
              $email = "value=\"".$this->infos['email']."\"";
              $errorLogIn = "<p>*** Mauvais email ou mot de passe ***</p>";
            }
          }
          $cheminCo = $app->urlFor("connexion");
          $cheminInsc =  $app->urlFor("inscription");
          $code = \garagesolidaire\vue\VueGeneral::genererHeader("formulaire");
          $code.= <<<END
                <header>CONNEXION</header>
                <form id="form" method="POST" action="${cheminCo}">
                  <label>EMAIL : </label> <input type="email" name="email" placeholder="EMAIL" $email required>
                  <label>MOT DE PASSE : </label><input type="password" name="mdp" placeholder="MOT DE PASSE" required>
                  $errorLogIn
                  <input id="submit" type="submit" name="connection" value="Connexion">
                  <div id="no_count">
                    <a>Pas de compte ? <a href="$cheminInsc" id="link">Inscrivez-vous !</a></a>
                  </div>
                </form>
                </div>
END;
    break;
    }
    case VueAdministrateur::AFF_INSC : {
      $cheminInsc =  $app->urlFor("inscription");

      //---------------------------------------------Gestion-du-cas-d'erreur
        $nom = "";
        $prenom = "";
        $email = "";
        $errorMdp = "";
        $errorEmail = "";
        if(isset($this->infos)){ // Gestion de l'affichage de l'erreur
          if ($this->infos["error"] === "mdpDiff"){
            $errorMdp = "<p>***Mot de passe invalide !***</p>";
          }else if ($this->infos["error"] === "email"){
            $errorEmail = "<p>***Email invalide !***</p>";
          }else if ($this->infos["error"] === "emailExist"){
            $errorEmail = "<p>***Email existe déjà dans la base !***</p> ";
          }
          $nom = "value=\"".$this->infos["nom"]."\""; //Affichage pré-rempli du formulaire en cas d'erreur
          $prenom = "value=\"".$this->infos["prenom"]."\"";
          $email = "value=\"".$this->infos["email"]."\"";
        }
//----------------------------------------------
        $code = \garagesolidaire\vue\VueGeneral::genererHeader("formulaire");
          $code.= <<<END
  <header>INSCRIPTION</header>
  <form id="form" method="POST" action="$cheminInsc">
    <label>Nom* : </label> <input type="text" name="nom" placeholder="Nom" $nom required>
    <label>Prénom* : </label><input type="text" name="prenom" placeholder="Prénom" $prenom required>
    $errorEmail
    <label id="email">Email* : </label><input type="email" name="email" placeholder="Email" $email required>
    $errorMdp
    <label>Mot de Passe* : </label> <input type="password" name="mdp" placeholder="Mot de passe" required>
    <label>Confirmation* : </label><input type="password" name="mdp-conf" placeholder="Mot de passe" required>
    <input id="submit" type="submit" name="valider-insc" value="S'inscrire"  placeholder="Mot de passe">
  </form>
END;
      break;
    }

    case VueAdministrateur::AFF_USER : {
      $cheminDelete = $app->urlFor('supprimer-compte');
      $cheminCompteInfo = $app->urlFor('modifier-compte');
      $cheminModifMdp = $app->urlFor('modifier-mdp');
      $code = \garagesolidaire\vue\VueGeneral::genererHeader("menu");
      $code .= <<<END
<div id="bouton">
  <a href="$cheminCompteInfo">Modifier son compte</a>
  <a href="$cheminModifMdp">Changer de mot de passe</a>
  <a href="#sup-compte">Supprimer son compte</a>
</div>
<div id="sup-compte" class="modal">
<div class="modal-dialog">
  <div class="modal-content">
    <header class="container">
      <a href="#" class="closebtn">×</a>
        <h4>Supprimer son compte</h4>
      </header>
      <div class="container">
        <p> Voulez-vous réellement supprimer votre compte ? </p><br>
        <form class="reservation" method="GET" action="$cheminDelete">
            <button class="suppr" type="submit" name="valid-reserv" value="valid_reserv">Supprimer</button>
            <a href="#">Annuler</a>
        </form>
      </div>
    </div>
  </div>
</div>
END;

      break;
    }
    case VueAdministrateur::AFF_RESERV : {
      $code = \garagesolidaire\vue\VueGeneral::genererHeader("menu");
      if (count($this->infos)>0) { //Affichage des items
        foreach ($this->infos as $value) {
            $rootDecline = $app->urlFor("reservation-decline", ["id" => $value['id']]) ;
            $rootAccept = $app->urlFor("reservation-accept", ["id" => $value['id']]) ;
            $id = $value['id'];
            $idItem = $value['idItem'];
            $idUser = $value['idUser'];
            $nomItem = Item::find($idItem)->nom;
            $nomUser = User::find($idUser)->nom;
            $prenomUser = User::find($idUser)->prenom;
      $code.= <<<END
      <div class="item">
          <div class="description">
            <h4>Utilisateur : $nomUser $prenomUser</h4>
          </div>
          <p class="etat">Nom Item : $nomItem</p>
          <a href="#id$id" class="supprimer" style="color:green">Valider</a>
          <a href="#id2$id" class="supprimer">Annuler</a>
      </div>
      <div id="id$id" class="modal">
      <div class="modal-dialog">
        <div class="modal-content">
          <header class="container">
            <a href="#" class="closebtn">×</a>
              <h4>Validation Réservation</h4>
            </header>
            <div class="container">
              <p>Valider la reservation de l'item $nomItem ? </p><br>
              <form class="reservation" method="POST" action="$rootAccept">
                  <button class="suppr" type="submit"  >Valider</button>
                  <a href="#">Annuler</a>
              </form>
            </div>
          </div>
        </div>
      </div>
      <div id="id2$id" class="modal">
      <div class="modal-dialog">
        <div class="modal-content">
          <header class="container">
            <a href="#" class="closebtn">×</a>
              <h4>Annuler Réservation</h4>
            </header>
            <div class="container">
              <p>Annuler la réservation de l'item $nomItem par $nomUser $prenomUser? </p><br>
              <form class="reservation" method="POST" action="$rootDecline">
                  <button class="suppr" type="submit" >Annuler</button>
                  <a href="#">Retour</a>
              </form>
            </div>
          </div>
        </div>
      </div>
END;
    }
  } else {
    $code.= "<p>Aucune Reservations...</p>";
  }
      break;
    }case 15:{
    $code.=$this->afficherModuleAdmin();
    break;
  }
  case 16:{
  $code.=$this->afficherAjoutItem();
  break;
}
case 17:{
$code.=$this->afficherAjoutCateg();
break;
}
  $code .= \garagesolidaire\vue\VueGeneral::genererFooter();
  echo $code;
}

}
}
