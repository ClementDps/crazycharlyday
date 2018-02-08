<?php

namespace garagesolidaire\vue;

class VueAdministrateur{

  public function render($int){
  switch($int){
    case 1:{
      $content=$this->afficherAccueil();
      break;
    }
  case 2:{

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

      </body>
  </html>

END;
  echo $code;
}

}
