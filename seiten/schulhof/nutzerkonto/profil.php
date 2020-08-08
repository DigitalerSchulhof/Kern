<?php
$SEITE = new Kern\Seite("Profil", null);

$spalte    = new UI\Spalte("A1");
$spalte[]  = new UI\SeitenUeberschrift("Profil von ".($DSH_BENUTZER->getName()));
$profil    = (new Kern\Profil($DSH_BENUTZER))->getProfil();
$spalte[]  = $profil;

$SEITE[]   = new UI\Zeile($spalte);
?>
