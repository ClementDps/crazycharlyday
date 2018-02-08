<?php
namespace garagesolidaire\controleur;

  use garagesolidaire\vue\VueAdministrateur as VueAdministrateur;
  use garagesolidaire\models\Authentication as Authentification;
  use garagesolidaire\models\UserInfo as UserInfo;
  use garagesolidaire\models as Model;

  /**
   * Controleur qui va gérer la gestion de compte
   * (formulaire de création de compte)
   */
  class GestionCompte{
      /**
       * Affichage formulaire inscription
       */
      public function afficheInscription(){
        $vue = new VueAdministrateur(null);
        $vue->render(VueAdministrateur::AFF_INSC);
      }

      /**
       * Affichage formulaire connexion
       */
      public function afficheConnexion(){
        $vue = new VueAdministrateur(null);
        $vue->render(VueAdministrateur::AFF_CO);
      }

      /**
       * Affichage avertissement accès interdit
       */
      public function afficheNonAccess(){
        // $vue = new VueAdministrateur(null, VueAdministrateur::AFF_NO_ACCES);
        // $vue->render();
      }

      /**
       *  Affiche le panneaux de configuration de l'utilisateur
       */
      public function afficheUser(){
        $app = \Slim\Slim::getInstance();
          //Redirection si l'utilisateur n'est pas connecté
          if(!isset($_SESSION["profile"])){
            $app->redirect( $app->urlFor("no-connection")  ) ;
          }

          $vue = new VueAdministrateur(null, VueAdministrateur::AFF_USER);
          $vue->render();
      }

      /**
      * Modifie les informations du compte
      */
      public function afficheModifierInfoCompte(){
        $app = \Slim\Slim::getInstance();
        //Redirection si l'utilisateur n'est pas connecté
        if(!isset($_SESSION["profile"])){
          $app->redirect( $app->urlFor("no-connection")  ) ;
        }
        $vue = new VueAdministrateur(null, VueAdministrateur::AFF_MODIF_COMPTE);
        $vue->render();
      }

      /**
       * Fontion permettant de modifier les données principales du créateur
       */
      public function modifierInfoCompte($tab){
        $app = \Slim\Slim::getInstance();
        //Redirection si l'utilisateur n'est pas connecté
        if(!isset($_SESSION["profile"])){
          $app->redirect( $app->urlFor("no-connection")  ) ;
        }

        //Filtration des données
        $tab['nom'] = filter_var($tab['nom'] , FILTER_SANITIZE_STRING);
        $tab['prenom'] = filter_var($tab['prenom'] , FILTER_SANITIZE_STRING);
        $tab['naissance'] = filter_var($tab['naissance'] , FILTER_SANITIZE_STRING);

        //Si email non valide retour sur le formulaire avec l'affichage de l'erreur
        if (!filter_var( $tab['email'] , FILTER_VALIDATE_EMAIL)){
          $vue = new VueAdministrateur("emailError", VueAdministrateur::AFF_MODIF_COMPTE );
          $vue->render();

        }else{
          //Sinon on accepte les modifications des valeurs
          $userInfo = Model\UserInfo::where('uid',"=",$_SESSION['profile']['uid'])->first();
          if($userInfo != null){
            if($tab['nom'] != null)
              $userInfo->nom = $tab['nom'];
            if($tab['prenom'] != null)
              $userInfo->prenom = $tab['prenom'];
            if($tab['naissance'] != null)
              $userInfo->datenaiss = $tab['naissance'];

            //Test de la modification et de la validation de l'email
            $trouve = 0;
            if($tab['email'] != null){
              $emailBase = Model\UserInfo::select( 'email' )->get();

              foreach ($emailBase as $value) {
                if($value->email == $tab['email'] && $value->email != $_SESSION['profile']['email'])
                  $trouve = 1;
              }
              if ($trouve == 0)
                $userInfo->email = $tab['email'];
            }
            //enregistrement des données
            $userInfo->save();
          }

          //Chargement du nouveau profile
          Authentification::loadProfile($_SESSION['profile']['uid']);

          if($trouve == 0)
            $app->redirect( $app->urlFor('aff-user')); //Redirection à la page utilisateur
          else {
            //Affichage de l'erreur
            $vue = new VueAdministrateur("emailExist", VueAdministrateur::AFF_MODIF_COMPTE);
            $vue->render();
          }
        }
      }

      /**
       * Affiche le formulaire permettant de modifier un mot de passe
       */
      public function afficheModifierMdp(){
        $app = \Slim\Slim::getInstance();
        //Redirection si l'utilisateur n'est pas connecté
        if(!isset($_SESSION["profile"])){
          $app->redirect( $app->urlFor("no-connection")  ) ;
        }

        $vue = new VueAdministrateur(null, VueAdministrateur::AFF_MODIF_MDP);
        $vue->render();
      }

      /**
       * Vérifie et modifie le mot de passe du compte créateur
       */
      public function modifierMdp($old, $new, $conf){
        $app = \Slim\Slim::getInstance();
        //Redirection si l'utilisateur n'est pas connecté
        if(!isset($_SESSION["profile"])){
          $app->redirect( $app->urlFor("no-connection")  ) ;
        }

        $user = Model\UserPass::where('uid','=',$_SESSION['profile']['uid']);

        //Test de l'authentification utilisateur
        try {
        if($user == null) //Test si l'utilisateur est présent dans la base ou non
            throw new \garagesolidaire\models\AuthException("User not exist");

          Authentification::authenticate($_SESSION['profile']['uid'],$old); // Authentification
          if($new != $conf)
            throw new \garagesolidaire\models\AuthException("Mdp diff");

          Authentification::createUser($_SESSION['profile']['uid'],$new);

          $app->redirect( $app->urlFor("aff-user") ) ; //Redirection à ses listes

        } catch (  \garagesolidaire\models\AuthException $ae ) { //Si faux
          $report = 'error';
          $vue = new VueAdministrateur($report, VueAdministrateur::AFF_MODIF_MDP); //Charge la page de connexion avec l'erreur correspondant
          $vue->render();
        }


      }

      /**
       * Supprime définitivement un compte utilisateur
       * Et toute ses données entrées dans la base (reservation , items + image , listes)
       */
      public function supprimerCompte(){
          $app = \Slim\Slim::getInstance();
          //Redirection si l'utilisateur n'est pas connecté
          if(!isset($_SESSION["profile"])){
            $app->redirect( $app->urlFor("no-connection")  ) ;
          }

          $listes = Model\Liste::where('user_id',"=",$_SESSION['profile']['uid'])->get();
          foreach ($listes as $list) { //Parcours de toutes les listes une par une
            $items = Model\Item::where('liste_id','=',$list->no)->get();
            foreach ($items as $item) {   //Parcours de chaque item de la liste
                $reserv = Model\Reservation::where('id_item',"=",$item->id)->first(); //Prendre les reservation de chaque item
                if($reserv != null){
                  $reserv->delete();
                }
                if($item->img != null && $item->img != ''){ //retirer l'image de chaque item
                  $nomFichier = "img/".$item->img;
                  unlink($nomFichier);
                }
                $item->delete();
            }
            $list->delete();
          }
          //Suppression password
          $userPass = Model\UserPass::where('uid',"=",$_SESSION['profile']['uid'])->first();
          $userPass->delete();

          //Supression information utilisateurs
          $userInfo = Model\UserInfo::where('uid',"=",$_SESSION['profile']['uid'])->first();
          $userInfo->delete();

          //Déconnection de l'utilisateur
          $this->deconnecter();
      }

      /**
       *  Fonction permettant de filtrer les données venant du formulaire
       *  @return tab avec ses valeurs filtrées | tab est vide si l'email ou le mot de passe est invalide
       */
      public function filtrerInscription($tab){
          $tab['nom'] = filter_var($tab['nom'] , FILTER_SANITIZE_STRING);
          $tab['prenom'] = filter_var($tab['prenom'] , FILTER_SANITIZE_STRING);
        //  $tab['naissance'] = filter_var($tab['naissance'] , FILTER_SANITIZE_STRING) ;

          if (!filter_var( $tab['email'] , FILTER_VALIDATE_EMAIL)){
            $tab["error"] = "email";
            $vue = new VueAdministrateur($tab, VueAdministrateur::AFF_INSC );
            $vue->render();
            return [];
          }

          if($tab['mdp'] !== $tab['mdp-conf']){
            $tab["error"] = "mdpDiff";
            $vue = new VueAdministrateur($tab, VueAdministrateur::AFF_INSC );
            $vue->render();
            return [];
        }


        return $tab;
    }
    /**
     * Déconnecte l'utilisateur
     */
    public function deconnecter(){
      $app = \Slim\Slim::getInstance();
      if(isset($_SESSION['profile'])){
        unset($_SESSION['profile']);
      }
      $app->redirect( $app->request->getRootUri() ) ;
    }

    /**
     * Connecte l'utilisateur
     */
    public function etablirConnection($param){
      $app = \Slim\Slim::getInstance();

      //Filtrage du mail
      $email = filter_var($param['email'] , FILTER_SANITIZE_EMAIL);
      $mdp = $param['mdp'];

      $user = UserInfo::where("email","=",$email)->first(); //Récupération des Info

      try {
        if($user == null) //Test si l'utilisateur est présent dans la base ou non
          throw new \garagesolidaire\models\AuthException("User not exist");

        Authentification::authenticate($user->uid,$mdp); // Authentification
        Authentification::loadProfile($user->uid); //Chargement des données utilisateurs
        $app->redirect( $app->urlFor("liste") ) ; //Redirection à ses listes

      } catch (  \garagesolidaire\models\AuthException $ae ) { //Cas d'erreur
        $tab_report = ['email' => $email, 'error' => 'auth'];
        $vue = new VueAdministrateur($tab_report, VueAdministrateur::AFF_CO ); //Charge la page de connexion avec l'erreur correspondant
        $vue->render();
      }

    }
    /**
     * Ajoute un utlisateur dans la base de données
     */
    public function ajouterUtilisateur($param){

      $user = UserInfo::where("email", "=", $param["email"])->first();
      if( $user == null){

        //Preparation des variables
            $nomUser = $param['nom'];
            $prenomUser = $param['prenom'];
            $dateNaissUser = $param['naissance'];

            $email = $param['email'];
            $mdp = $param['mdp'];

       //Ajout des dernieres valeurs
            $newUser = new UserInfo() ;
            $newUser->nom = $nomUser;
            $newUser->prenom = $prenomUser;
            $newUser->email = $email;
            if($dateNaissUser != '' && $dateNaissUser != null )
              $newUser->datenaiss = $dateNaissUser;
            $newUser->save();
        //Ajout de l'email et du mot de passe sécurisé
        try{
            Authentification::createUser($newUser->uid,$mdp);
            Authentification::loadProfile($newUser->uid); //Chargement des données utilisateurs
        //Redirection Page des listes
            $app = \Slim\Slim::getInstance();
            $app->redirect( $app->urlFor("liste") ) ;
        } catch ( \garagesolidaire\models\AuthException $ae ){ //Si le mot de passe ne respect pas la politique
          $newUser->delete(); //Retrait des données enregistré
          $param["error"] = "mdpShort";
          $vue = new VueAdministrateur($param, VueAdministrateur::AFF_INSC ); //Charge la page d'inscription avec erreur et donnée pré-remplies
          $vue->render();
        }

      } else {
        $param["error"] = "emailExist";
        $vue = new VueAdministrateur($param, VueAdministrateur::AFF_INSC ); //Charge la page d'inscription avec erreur et donnée pré-remplies
        $vue->render();
      }

      }
  }
