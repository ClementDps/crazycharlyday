<?php

namespace garagesolidaire\models;
use \garagesolidaire\models\User;
class Authentication{


  public static function createUser($n,$p,$e,$mdp){
	 $u=User::where("email","=",$e)->get();
	if(isset($u[0])){
		return 0;
	}
	else{
		User::insert($n,$p,$e,$mdp,0);
		Authentication::authenticate($e,$mdp);
		return 1;
	}
  }


  public static function authenticate($email,$password){
	$u=User::where("email","=",$email)->get();
	if(isset($u[0])){
		$hash= $u[0]->mdp;
		if(password_verify($password,$hash)){
			Authentication::loadProfile($u[0]->id);
			return 0;
		}
	}
	return 1;
  }

  public static function loadProfile($id){
	   $u=User::where("id","=",$id)->get();
	    $_SESSION=[];
      $_SESSION['usernickname'] = $u[0]->prenom;
	     $_SESSION['username'] = $u[0]->nom;
	      $_SESSION['userid'] = $u[0]->id;
        $_SESSION['rang']=$u[0]->rang;
  }

  public static function checkAccessRights($rang){
		if(isset($_SESSION['userid'])){
      $u=User::find($_SESSION['userid']);
      if($u['rang']>=$rang){
        return true;
      }
		}
		return false;
  }

  public static function deconnexion(){
	  $_SESSION=[];
  }


}
