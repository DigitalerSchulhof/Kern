<?php
Anfrage::post("id");

if(!Kern\Check::angemeldet()) {
  Anfrage::addFehler(-2, true);
}

if ($id != $DSH_BENUTZER->getId() && !$DSH_BENUTZER->hatRecht("kern.personen.profil.sehen")) {
  Anfrage::addFehler(-4, true);
}

if (!UI\Check::istZahl($id)) {
  Anfrage::addFehler(-3, true);
}

$fensterid = "dshVerwaltungProfil{$id}";

$person = Kern\Nutzerkonto::vonID($id);

$fenstertitel = (new UI\Icon("fas fa-address-card"))." Profil von $person";
$profil = new Kern\Profil($person);

$fensterinhalt = $profil->getProfil();

$code = new UI\Fenster($fensterid, $fenstertitel, $fensterinhalt, true);
$code->addFensteraktion(UI\Knopf::schliessen($fensterid));

Anfrage::setRueck("Code", (string) $code);
?>
