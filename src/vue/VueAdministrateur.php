<?php

namespace garagesolidaire\vue;

class VueAdministrateur{

  private $infos;

  const AFF_CO = 3;
  const AFF_INSC = 4;

  public function __construct($tab){
    $this->infos=$tab;
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
  }
  $code .= \garagesolidaire\vue\VueGeneral::genererFooter();
  echo $code;
}

}
