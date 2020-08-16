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

$sql = "SELECT {art}, {geschlecht}, {vorname}, {nachname}, {titel} FROM kern_personen WHERE id = ?";
$anfrage = $DBS->anfrage($sql, "i", $id);
$anfrage->werte($art, $geschlecht, $vorname, $nachname, $titel);

$person = new Kern\Nutzerkonto($id, $titel, $vorname, $nachname);
$person->setArt($art);
$person->setGeschlecht($geschlecht);
$fenstertitel = (new UI\Icon("fas fa-address-card"))." Profil von $person";
$profil = new Kern\Profil($person);

$fensterinhalt = $profil->getProfil();

$code = new UI\Fenster($fensterid, $fenstertitel, $fensterinhalt, true);
$code->addFensteraktion(UI\Knopf::schliessen($fensterid));

Anfrage::setRueck("Code", (string) $code);
Anfrage::setRueck("Fensterid", $fensterid);
?>
