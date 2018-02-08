<?php

namespace garagesolidaire\vue;

class VueAdministrateur{

  private $infos;

  const AFF_CO = 3;
  const AFF_INSC = 4;
  const AFF_USER = 5;

  public function __construct($tab){
    $this->infos=$tab;
  }

  public function afficherItems(){
	  $code = "";
	  $app = \Slim\Slim::getInstance();
	  $route = $app->urlFor("afficher-item", ['num' => $value['id']]);
	  
	  foreach($this->infos as $key=>$value){
		$code = $code."<li><a href='$route'>".$value['nom']."</a> </li><br>";
	  }
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
	case 10 : {
		$code = \garagesolidaire\vue\VueGeneral::genererHeader("menu");
		$code.=afficherItems();

	}
	
  }
  $code .= \garagesolidaire\vue\VueGeneral::genererFooter();
  echo $code;
}

}
