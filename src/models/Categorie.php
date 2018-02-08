<?php

namespace garagesolidaire\models;

class Categorie extends \Illuminate\Database\Eloquent\Model{

  protected $table='categorie';
	protected $primaryKey='id';
	public $timestamps=false;

  public function insert($nom,$desc){
    $c=new Item();
    $c->nom=$nom;
    $c->description=$desc;
    $c->save();
  }

  public function mettreAjour($id,$nom,$desc){
    $c=Item::find($id);
    $c->nom=$nom;
    $c->description=$desc;
    $c->save();
  }
}
