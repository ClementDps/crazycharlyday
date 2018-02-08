<?php

namespace garagesolidaire\vue;

class VueClient{

  private $infos;

  public function __construct($tab){
    $this->infos=$tab;
  }
  
  public function afficherItem(){
	  $nom=$this->infos['nom'];
	  $desc=$this->infos['description'];
	  $img=$this->infos['img'];
	  
	  $code="Nom : ".$nom." <br> Description : ".$desc;
		$code=$code.'<img src="../../../img/'.$img.'" width = "150" height="150"></img><br>';
		return $code;	  
  }
  
   public function afficherItemsCateg(){
    $code="";
    $c=$this->infos['c'];
    $i=$this->infos['i'];
    echo $c;
    echo $i;
    return $code;
  }

  public function render($int){
  switch($int){
    case 1:{
      $content=$this->afficherAccueil();
      break;
    }
  case 2:{
		$content=$this->afficherItem();
      break;
    }
  case 3:{
    $content=$this->afficherItemsCateg();
    break;
    }
  }
  $code= <<<END
  <!DOCTYPE html>
  <html>
      <head>
          <title>GarageSolidaire</title>
          <meta charset="UTF-8">
          <meta name="viewport" content="width=device-width, initial-scale=1.0">
          <link rel="stylesheet" href="../style.css" />
      </head>
      <body>
		$content
      </body>
  </html>

END;
  echo $code;
}
}
