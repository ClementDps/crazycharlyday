<?php

namespace garagesolidaire\models;

class Categorie extends \Illuminate\Database\Eloquent\Model{

  protected $table='categorie';
	protected $primaryKey='id';
	public $timestamps=false;

  public static function insert($nom,$desc){
    $c=new Categorie();
    $c->nom=$nom;
    $c->description=$desc;
    $c->save();
  }

  public static function mettreAjour($id,$nom,$desc){
    $c=Categorie::find($id);
    $c->nom=$nom;
    $c->description=$desc;
    $c->save();
  }
}
