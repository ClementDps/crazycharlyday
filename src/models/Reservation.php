<?php

namespace garagesolidaire\models;

class Reservation extends \Illuminate\Database\Eloquent\Model{

  protected $table='Reservation';
	protected $primaryKey='id';
	public $timestamps=false;

	public static function insert($user,$item,$heureDeb,$jourDeb,$heureFin,$jourFin){
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
	public static function mettreAjour($id,$etat,$note,$prix){
		$r = Reservation::find($id);
		$r->etat=$etat;
		$r->note=$note;
		$r->prix=$prix;
		$r->dateDerniereModif=date("Y-m-d H:i:s",time());
		$r->save();
	}
	
	public static function changerEtat($id,$etat){
		$r = Reservation::find($id);
		$r->etat=$etat;
		$r->dateDerniereModif=date("Y-m-d H:i:s",time());
		$r->save();
	}
	
	public static function payer($id,$montant){
		$r = Reservation::find($id);
		$r->etat="payee";
		$r->prix=$montant;
		$r->dateDerniereModif=date("Y-m-d H:i:s",time());
		$r->save();
	}
	
	public static function noter($id,$note){
		$r = Reservation::find($id);
		$r->note=$note;
		$r->dateDerniereModif=date("Y-m-d H:i:s",time());
		$r->save();
	}
}
