<?php

namespace garagesolidaire\models;

class Reservation extends \Illuminate\Database\Eloquent\Model{

  protected $table='Reservation';
	protected $primaryKey='id';
	public $timestamps=false;

	public function insert($user,$item,$heureDeb,$jourDeb,$heureFin,$jourFin){
		$r = new Reservation();
		$r->idUser=$user;
		$r->idItem=$item;
		$r->heureDeb=$heureDeb;
		$r->jourDeb=$jourDeb;
		$r->heureFin=$heureFin;
		$r->jourFin=$jourFin;
		$r->etat="reserve";
		$r->dateCreation=date("Y-m-d H:i:s",time());
		$r->dateDerniereModif=date("Y-m-d H:i:s",time());
		$r->save();
	}
	public function mettreAjour($id,$etat,$note,$prix){
		$r = Reservation::find($id);
		$r->etat=$etat;
		$r->note=$note;
		$r->prix=$prix;
		$r->dateDerniereModif=date("Y-m-d H:i:s",time());
		$r->save();
	}
}
