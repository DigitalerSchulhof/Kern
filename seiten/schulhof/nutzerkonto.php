<?php
if(!Check::angemeldet()) {
  einbinden("Schulhof/Anmeldung");
  return;
}

$DSH_TITEL  = "Nutzerkonto";
$CODE[]     = new Kern\Aktionszeile();
$CODE[]     = UI\Zeile::standard(new UI\SeitenUeberschrift("Willkommen $DSH_BENUTZER!"));

$spalte1    = new UI\Spalte("A2");
$spalte1[]  = $DSH_BENUTZER->aktivitaetsanzeige("dshAktivitaetNutzerkonto");

$CODE[]     = new UI\Zeile($spalte1);
?>
