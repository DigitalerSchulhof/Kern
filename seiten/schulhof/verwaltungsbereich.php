<?php
if(!Check::angemeldet()) {
  einbinden("Schulhof/Anmeldung");
  return;
}

$DSH_TITEL  = "Nutzerkonto";
$CODE[]     = new Kern\Aktionszeile();
$CODE[]     = UI\Zeile::standard(new UI\SeitenUeberschrift("Willkommen $DSH_BENUTZER!"));

?>
