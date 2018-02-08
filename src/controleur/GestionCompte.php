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

      public function afficherPanel(){
        \garagesolidaire\controleur\GestionCompte::checkConnect();


        $vue = new VueAdministrateur(null);
        $vue->render(VueAdministrateur::AFF_USER);
      }

      public function supprimerCompte(){
        \garagesolidaire\controleur\GestionCompte::checkConnect();

        $c = Model\Commentaire::where("idUser","=",$_SESSION['userid'])->get();
        $r = Model\Reservation::where("idUser","=",$_SESSION['userid'])->get();

        foreach ($c as $value) {
          $value->delete();
        }
        foreach ($r as $value) {
          $value->delete();
        }
        $u = Model\User::where("id","=",$_SESSION['userid'])->first()->delete();
        $this->deconnecter();
        $app = \Slim\Slim::getInstance();
        $app->redirect( $app->urlFor("accueil") ) ; //Redirection à ses listes
      }

      public function afficherChangerMotDePasse(){
        \garagesolidaire\controleur\GestionCompte::checkConnect();

        $vue = new VueAdministrateur(null);
        $vue->render(VueAdministrateur::AFF_CMDP);
      }

      public function afficherModifCompte(){
        \garagesolidaire\controleur\GestionCompte::checkConnect();

        $vue = new VueAdministrateur(null);
        $vue->render(VueAdministrateur::AFF_MODIF_COMPTE);
      }

      public function modifCompte(){
        \garagesolidaire\controleur\GestionCompte::checkConnect();

        $app = \Slim\Slim::getInstance();

        $valueFiltred = $this->filterVar($_POST);
        Model\User::mettreAjour($valueFiltred['nom'],$valueFiltred['prenom']);

        $app->redirect( $app->urlFor("aff-user") ) ; //Redirection à ses listes

      }

      public function changerMotDePasse(){
        \garagesolidaire\controleur\GestionCompte::checkConnect();
        $app = \Slim\Slim::getInstance();

        $user = Model\User::where('id','=',$_SESSION['userid'])->first();

        //Test de l'authentification utilisateur

          $res = Authentification::authenticate($user->email,$_POST['old-pass']); // Authentification
          if($_POST['new-pass'] != $_POST['conf-pass']){
              $res = 2 ;
          }
          if($res == 0){
            //Mettre a jour user
            Model\User::mettreAjour($user->nom,$user->prenom,$_POST['old-pass'],$_POST['new-pass']);
            $app->redirect( $app->urlFor("aff-user") ) ; //Redirection à ses listes
          } else { //Si faux
            $report = 'error';
            $vue = new VueAdministrateur($report); //Charge la page de connexion avec l'erreur correspondant
            $vue->render(VueAdministrateur::AFF_CMDP);
          }
      }
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
        $vue = new VueAdministrateur(null);
        $vue->render(VueAdministrateur::AFF_NO_ACCES);
      }

      /**
       * Affichage avertissement accès interdit
       */
      public function afficheNonConnection(){
        $vue = new VueAdministrateur(null);
        $vue->render(VueAdministrateur::AFF_NO_CO);
      }

      /**
      * Ajoute un utlisateur dans la base de données
      */
      public function ajouterUtilisateur(){
        $app = \Slim\Slim::getInstance();
        if(isset($_POST['nom']) && isset($_POST['prenom']) && isset($_POST['email']) && isset($_POST['mdp']) && isset($_POST['mdp-conf'])){
          if (!filter_var( $_POST['email'] , FILTER_VALIDATE_EMAIL)){
            $valueFiltred = $this->filterVar($_POST);
            $valueFiltred['error'] = "email";
            $vue = new VueAdministrateur( $valueFiltred);
            $vue->render(VueCreateur::AFF_INSC);
          }else{
            $valueFiltred = $this->filterVar($_POST);

            if($valueFiltred['mdp'] != $valueFiltred['mdp-conf']){
              $valueFiltred['error'] = 'mdpDiff';
              $vue = new VueAdministrateur($valueFiltred);
              $vue->render(VueAdministrateur::AFF_INSC);
            }else{
              $res = Authentification::createUser($valueFiltred['nom'], $valueFiltred['prenom'],$valueFiltred['email'], $valueFiltred['mdp'] );
              if($res == 0){
                $valueFiltred['error'] = "emailExist";
                $vue = new VueAdministrateur($valueFiltred);
                $vue->render(VueAdministrateur::AFF_INSC);
              }
              else
                $app->redirect( $app->urlFor("accueil"));
           }
         }
         }else
          $app->redirect( $app->urlFor("accueil"));
      }

      /**
       *  Fonction permettant de filtrer les données venant d'un formulaire
       *  @return tab avec ses valeurs filtrée
       */
      public function filterVar($tab){
          $res = [];
          foreach ($tab as $key => $value) {
            $res[$key] = filter_var( $value , FILTER_SANITIZE_STRING);
          }

          return $res;
      }


    /**
     * Déconnecte l'utilisateur
     */
    public function deconnecter(){
      \garagesolidaire\controleur\GestionCompte::checkConnect();

      $app = \Slim\Slim::getInstance();
      Authentification::deconnexion();
      $app->redirect( $app->request->getRootUri() ) ;
    }

    /**
     * Connecte l'utilisateur
     */
    public function etablirConnection(){
      $app = \Slim\Slim::getInstance();

      //Filtrage du mail
      $email = filter_var($_POST['email'] , FILTER_SANITIZE_EMAIL);
      $mdp = $_POST['mdp'];

      $res = Authentification::authenticate($email,$mdp);
      if( $res == 0 ){
        $app->redirect( $app->urlFor("accueil")  ) ;
      }else{
        $valueFiltred['error'] = "auth";
        $valueFiltred['email'] = $email;
        $vue = new VueAdministrateur($valueFiltred);
        $vue->render(VueAdministrateur::AFF_CO);
      }
    }

    public static function checkConnect(){
      $app = \Slim\Slim::getInstance();
      //Redirection si l'utilisateur n'est pas connecté
       if(!isset($_SESSION["userid"])){
         $app->redirect( $app->urlFor("no-connection")  ) ;
       }
    }
}
