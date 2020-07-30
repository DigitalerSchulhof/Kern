<?php
$angemeldet = false;  // @TODO: Angemeldet
if(!$angemeldet) {
  einbinden("Schulhof/Anmeldung");
  return;
}

$DSH_TITEL = "Nutzerkonto";
$CODE .= new Kern\Aktionszeile();


?>