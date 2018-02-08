<?php

  namespace mywishlist\models;
  /**
   * Classe permettant la gestion de l'Authentification
   */
  class Authentication {

    /**
    * Créer un utilisateur et défini ses droits
    */
    public static function createUser($uid , $password){
      // vérifier la conformité de $password avec la police
      if(strlen($password) >= 6){
        // si ok : hacher $password
        $hash = password_hash($password, PASSWORD_DEFAULT, [ 'cost' => 12]);
        // créer et enregistrer l'utilisateur
        $user = UserPass::where('uid','=',$uid)->first();
        if($user == null){
          $user = new UserPass();
          $user->uid = $uid;
        }
        $user->hash = $hash;
        $user->save();

      } else {
        throw new AuthException("Pass too short !");
      }
    }

    /**
     * Fonction permettant de controler l'identifiant et le mot de passe
     */
    public static function authenticate($uid , $password){

      // charger utilisateur $user
      $user = UserPass::where("uid","=",$uid)->first();
      // vérifier $user->hash == hash($password)
      if($user == null || !password_verify($password, $user->hash))
        throw new AuthException("Error login");

      }

    /**
     * Permet de charger le profil de l'utilisateur en session
     */
    public static function loadProfile($uid){
      // charger l'utilisateur et ses droits
      $user = UserInfo::where("uid","=",$uid)->first();
      $lists = Liste::where("user_id","=",$uid)->get();
    // Accès listes
      $listAccess = [];
      foreach ($lists as $list) {
        $listAccess[] = $list->no;
      }
    //Chargement profil
      $profile = [ "uid" => $user->uid , "nom" => $user->nom, "prenom" => $user->prenom, "datenaiss" => $user->datenaiss, "email" => $user->email , "liste" => $listAccess];
      // détruire la variable de session
      session_destroy();
      // créer variable de session = profil chargé
      session_start();
      $_SESSION['profile'] = $profile;
    }

    /**
     * Regarde si l'utlisateur possède le droits ou non en fonction de l'id de la liste entrée
     */
    public static  function checkAccessRights($idList){
      $trouve = false;
      foreach ($_SESSION["profile"]["liste"] as $id) { //Recherche de l'accès de la liste en question
        if($idList == $id)
          $trouve = true;
      }
      if(!$trouve) //Si il n'a pas trouvé génére une erreur
        throw new AuthException("Error Access") ;

    }


  }
