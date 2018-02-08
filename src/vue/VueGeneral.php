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
     $routeCategorie = $app->urlFor('aff-categorie');
     $routeInsc = $app->urlFor('inscription');
     $routeConnexion = $app->urlFor('connexion');
     $routeAcc = $app->urlFor('accueil');
     $routeUser = $app->urlFor('aff-user');
     $root = $app->request->getRootUri();
     $mesReservations=$app->urlFor('mes-reservations');
     $reservationAdmin = $app->urlFor('reservation');
     $menuReservation="";
     $profileHTML = "<li><a href=\"".$routeConnexion."\">Connexion</a></li><li><a href=\"".$routeInsc."\">Inscription</a></li>";
     if(isset($_SESSION['userid'])){
       $routeDeconnexion = $app->urlFor('deconnexion');

       $profileHTML = "<li><a href=\"$routeUser\">".$_SESSION["usernickname"]."</a></li><li><a href=\"$routeDeconnexion\">Déconnexion</a></li>";
     }

     if(isset($_SESSION['userid']) && isset($_SESSION['rang']) && $_SESSION['rang']==0){
       $menuReservation="<li><a href=".$mesReservations.">Mes Réservations</a></li>";
     }else{

       if(isset($_SESSION['userid']) && $_SESSION['rang']>0){
         $menuReservation="<li><a href=\"$reservationAdmin\">Les Réservations</a></li>";

       }
     }


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
      <li><a href="$root">Accueil</a></li><li><a href="$routeCategorie">Garage Solidaire</a></li>$menuReservation
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
