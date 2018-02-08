<?php

namespace garagesolidaire\models;

class Item extends \Illuminate\Database\Eloquent\Model{

  protected $table='item';
	protected $primaryKey='id';
	public $timestamps=false;

  public function insert($nom,$desc,$idCateg){
    $i=new Item();
    $i->nom=$nom;
    $i->description=$desc;
    $i->id_categ=$idCateg;
    $i->save();
  }

  public function mettreAjour($id,$nom,$desc,$idCateg){
    $i=Item::find($id);
    $i->nom=$nom;
    $i->description=$desc;
    $i->id_categ=$idCateg;
    $i->save();
  }
}
