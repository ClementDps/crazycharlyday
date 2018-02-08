<?php

namespace garagesolidaire\vue;

/**
 * Vue qui permet de générer les affichages généraux de l'application
 * comme le header et le footer pour éviter la répétition de code
 */
 class VueGeneral {

  /**
   * Methode qui permet de générer le haut de
   * page HTML présent dans toutes les pages
   */
   public static function genererHeader($fichierCss = ""){
     $app = \Slim\Slim::getInstance();
     // $routeListe = $app->urlFor('liste');
     // $routeInsc = $app->urlFor('inscription');
     // $routeConnexion = $app->urlFor('connexion');
     $routeAcc = $app->urlFor('accueil');
     // $routeUser = $app->urlFor('aff-user');
     $root = $app->request->getRootUri();

     $profileHTML = "<li><a href=\""."\">Connexion</a></li><li><a href=\""."\">Inscription</a></li>";
     // if(isset($_SESSION['profile'])){
     //   $routeDeconnexion = $app->urlFor('deconnexion');
     //
     //   $profileHTML = "<li><a href=\"$routeUser\">".$_SESSION["profile"]["prenom"]."</a></li><li><a href=\"$routeDeconnexion\">Déconnexion</a></li>";
     // }

      $html =
      <<<END
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <!-- <link rel="icon" href="$root/img/icone.ico" /> -->
  <link rel="stylesheet" href="$root/css/nav.css"/>
  <link rel="stylesheet" href="$root/css/$fichierCss.css"/>
  <link rel="stylesheet" href="$root/css/footer.css"/>
  <title>Garage Solidaire !</title>
</head>
<body>
  <div id="top-zone">
    <div id="left">
      <ul>
      <li><a href="#">Accueil</a></li><li><a href="#">Garage Solidaire</a></li>
      </ul>
    </div>
    <div id="right">
      <ul>
        $profileHTML
      </ul>
    </div>
  </div>

END;
        return $html;
      }

    /**
    * Methode qui permet de générer le pied de pages
    * HTML présent sur toutes les pages
    */
    public static function genererFooter(){
      $app = \Slim\Slim::getInstance();
      $routeContact = $app->urlFor('contact');
      $routeHelp= $app->urlFor('help');
      $routeAbout = $app->urlFor('about');
      $html =
       <<<END

  <footer>
    <ul>
      <li><a href="$routeAbout">About</a></li>
      <li><a href="$routeHelp">Besoin d'aide ?</a></li>
    </ul>
  </footer>
</body>
</html>
END;
      return $html;
    }
}
