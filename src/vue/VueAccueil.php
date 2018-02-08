<?php

namespace garagesolidaire\vue;

/**
 * Vue qui va permettre l'affichage de la page d'accueil commune
 * à tout les utilisateurs
 */
class VueAccueil{

  private $select; //Attribut qui permet de choisir la méthode d'affichage

  const AFF_ABOUT = 1;
  const AFF_CONTACT = 2;
  const AFF_HELP = 3;
  const AFF_ERROR_404 = 4;


  public function __construct($select = -1){
    $this->select = $select;
  }

/**
 * génère une page HTML selon le selecteur en attribut
 */
public function render(){
  $app = \Slim\Slim::getInstance();

  switch($this->select){
    case -1 :
      $html = \garagesolidaire\vue\VueGeneral::genererHeader("demarrage");
      $html .=
      <<<END
  <h1>Bienvenue sur le site du <strong>garage solidaire</strong></h1>
  <h3> garagesolidaire est un site qui permet de réserver un garage afin de réparer sa voiture </h3>
  <p> Groupe : Briand Lucas | Claisse Julien | Combe-Deschaumes Sarah | Domps Clément | Millotte Baptiste </p>
END;
    break;

    case VueAccueil::AFF_ABOUT:
    $html = \garagesolidaire\vue\VueGeneral::genererHeader("about");
    $html .= <<<END
    <h1>A propos de nous</h1>
    <h3>Qui sommes-nous ?</h3>
    <p>Nous sommes 5 étudiants en deuxième année de DUT Informatique à Nancy.<BR>
      Groupe B : Claisse Julien | Grebot Lucas | Savouroux Florian | Mercier Clément | Parisel Guillaume</p>
    <h3>Pourquoi ?</h3>
    <p>Ce site internet est un des projets que nous avons à faire au cours de
      notre année d'étude. <BR>Il nous a été demandé de faire un site permettant la
      création de listes de cadeaux pouvant être partagées.</p>
END;
    break;

    case VueAccueil::AFF_HELP:
    $html = \garagesolidaire\vue\VueGeneral::genererHeader("help");
    $html .= <<<END
    <h1>Besoin d'aide ?</h1>
    <h3>Comment ca marche ?</h3>
    <p>garagesolidaire est un outil vous permettant de créer une ou plusieurs listes de cadeaux que vous souhaitez partagées.<BR>
      Vous pouvez également consulter les listes qui vous ont été partagé.<BR>
      Avant toute chose, il faut vous créer un compte ou vous connectez si cela est déjà fait.<BR>
      Pour ce faire, il suffit de cliquer sur les onglets en haut à droite de la page.<BR></p>
END;
    break;

    case VueAccueil::AFF_ERROR_404:
    $html = \garagesolidaire\vue\VueGeneral::genererHeader("erreur404");
$html .= <<<END
<h1>Oupss on a pas trouv&eacute; votre page</h1>
<section class="error-container">
  <span>4</span>
  <span><span class="screen-reader-text">0</span></span>
  <span>4</span>
</section>
END;
    break;
    }


    $html .= \garagesolidaire\vue\VueGeneral::genererFooter();
    echo $html;
  }
}
