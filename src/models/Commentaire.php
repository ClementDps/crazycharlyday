<?php

namespace garagesolidaire\models;

class Commentaire extends \Illuminate\Database\Eloquent\Model{

  protected $table='Commentaire';
	protected $primaryKey='id';
	public $timestamps=false;

  public static function insert($user,$item,$message){
    $c=new Commentaire();
	$c->idUser=$user;
	$c->idItem=$item;
	$c->message=$message;
	$c->dateMess=date("Y-m-d H:i:s",time());
	$c->save();
  }
}
