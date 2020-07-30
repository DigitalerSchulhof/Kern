<?php
$angemeldet = false;  // @TODO: Angemeldet
if(!$angemeldet) {
  $seite = "Schulhof/Anmeldung";
  einbinden($seite);
  return;
}

$DSH_TITEL = "Nutzerkonto";
$CODE .= new Kern\Aktionszeile();


?>