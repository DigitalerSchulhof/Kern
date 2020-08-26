<?php
Anfrage::post("id");

if(!Kern\Check::angemeldet()) {
  Anfrage::addFehler(-2, true);
}

if ($id != $DSH_BENUTZER->getId() && !$DSH_BENUTZER->hatRecht("personen.andere.profil.sehen")) {
  Anfrage::addFehler(-4, true);
}

if (!UI\Check::istZahl($id)) {
  Anfrage::addFehler(-3, true);
}

$fensterid = "dshVerwaltungProfil{$id}";

$person = Kern\Nutzerkonto::vonID($id);

$fenstertitel = (new UI\Icon("fas fa-address-card"))." Profil von $person";
$profil = new Kern\Profil($person);

$fensterinhalt = UI\Zeile::standard($profil->getProfil());

$code = new UI\Fenster($fensterid, $fenstertitel, $fensterinhalt);

Anfrage::setRueck("Code", (string) $code);
?>
