<?php

namespace garagesolidaire\models;

class Categorie extends \Illuminate\Database\Eloquent\Model{

  protected $table='categorie';
	protected $primaryKey='id';
	public $timestamps=false;

  public insert($nom,$desc){
    $c=new Item();
    $c->nom=$nom;
    $c->description=$desc;
    $c->save();
  }

  public update($id,$nom,$desc){
    $c=Item::find($id);
    $c->nom=$nom;
    $c->description=$desc;
    $c->save();
  }
}
