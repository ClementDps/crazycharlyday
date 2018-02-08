<?php

namespace garagesolidaire\models;

class User extends \Illuminate\Database\Eloquent\Model{

	protected $table='user';
	protected $primaryKey='id';
	public $timestamps=false;

	public static function insert($nom,$prenom,$email,$mdp,$rang){
		$hash=password_hash($mdp,PASSWORD_DEFAULT,['cost'=>12]);
		$u=new User();
		$u->nom=$nom;
		$u->prenom=$prenom;
		$u->email=$email;
		$u->mdp=$hash;
		$u->img=0;
    $u->rang=$rang;
		$u->save();
	}

	public static function mettreAjour($nom,$prenom,$amdp=null,$nmdp=null){
		$u=User::find($_SESSION['userid']);
		if(password_verify($amdp,$u->mdp)){
			$u->nom=$nom;
			$u->prenom=$prenom;
			if($nmdp!=""){
				$u->mdp=password_hash($nmdp,PASSWORD_DEFAULT,['cost'=>12]);
			}
			$u->save();
		}
	}
}
