<?php

namespace garagesolidaire\controleur;

/**
 * Controleur qui gère les fonctionnalités présente sur
 * la page d'accueil commun à chaque personne
 */
class GestionAccueil {

  public function afficheAccueil(){
    $vue = new \garagesolidaire\vue\VueAccueil();
    $vue -> render();
  }

  public function afficheAbout(){
    $vue = new \garagesolidaire\vue\VueAccueil(\garagesolidaire\vue\VueAccueil::AFF_ABOUT);
    $vue->render();
  }

  public function afficheContact(){
    $vue = new \garagesolidaire\vue\VueAccueil(\garagesolidaire\vue\VueAccueil::AFF_CONTACT);
    $vue->render();
  }

  public function afficheHelp(){
    $vue = new \garagesolidaire\vue\VueAccueil(\garagesolidaire\vue\VueAccueil::AFF_HELP);
    $vue->render();
  }

  public function error404(){
    $vue = new \garagesolidaire\vue\VueAccueil(\garagesolidaire\vue\VueAccueil::AFF_ERROR_404);
    $vue->render();
  }
}
