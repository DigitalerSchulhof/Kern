<?php
if(!Check::angemeldet()) {
  einbinden("Schulhof/Anmeldung");
  return;
}

$DSH_TITEL  = "Profil";
$CODE[]     = new Kern\Aktionszeile();
$CODE[]     = UI\Zeile::standard(new UI\SeitenUeberschrift("Profil von ".($DSH_BENUTZER->getName())));

$spalte    = new UI\Spalte("A1");

$profil = (new Kern\Profil($DSH_BENUTZER))->getProfil();
$spalte[]   = $profil;

$CODE[]     = new UI\Zeile($spalte);
?>
