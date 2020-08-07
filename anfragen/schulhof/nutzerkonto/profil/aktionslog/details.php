<?php
Anfrage::post("logid");

if(!Kern\Check::angemeldet()) {
  Anfrage::addFehler(-2, true);
}

if (!UI\Check::istZahl($logid,0) && $logid != 'alle') {
  Anfrage::addFehler(-3, true);
}

// Logeintrag laden
$sql = "SELECT nutzer, {titel}, {vorname}, {nachname}, {benutzername}, {kern_nutzeraktionslog.art}, {tabellepfad}, {datensatzdatei}, {aktion}, zeitpunkt FROM kern_nutzeraktionslog JOIN kern_personen ON kern_personen.id = kern_nutzeraktionslog.nutzer LEFT JOIN kern_nutzerkonten ON kern_personen.id = kern_nutzerkonten.id WHERE kern_nutzeraktionslog.id = ?";
$anfrage = $DBS->anfrage($sql, "i", $logid);
$anfrage->werte($nutzerid, $titel, $vorname, $nachname, $benutzername, $logart, $tabellepfad, $datensatzdatei, $aktion, $zeitpunkt);

$person = new Kern\Nutzerkonto($nutzerid, $titel, $vorname, $nachname);

// Rechtelage klären
$profil = new Kern\Profil($person);
$recht = $profil->istFremdzugriff();
if (!$DSH_BENUTZER->hatRecht("$recht.aktionslog.details")) {
  Anfrage::addFehler(-4, true);
}

$meldetitel = "";
$meldeinhalt = "";
if ($benutzername === null) {
  $meldeinhalt .= new UI\Absatz("Zugriff von <b>{$person->getName()}</b> (<i>inaktiv</i>) auf <b>$tabellepfad</b>");
} else {
  $meldeinhalt .= new UI\Absatz("Zugriff von <b>{$person->getName()}</b> ($benutzername) auf <b>$tabellepfad</b>");
}

if ($logart == "DB") {
  $meldetitel .= new UI\Icon("fas fa-database");
} else if ($logart == "Datei") {
  $meldetitel .= new UI\Icon("fas fa-archive");
} else {
  $meldetitel .= new UI\Icon("fas fa-shoe-prints");
}
$meldetitel .= " $aktion vom ".((new UI\Datum($zeitpunkt))->kurz("MUs"));
$meldeinhalt .= new UI\Code($datensatzdatei);

Anfrage::setTyp("Meldung");
Anfrage::setRueck("Meldung", new UI\Meldung($meldetitel, $meldeinhalt, "Information"));
Anfrage::setRueck("Knöpfe", [UI\Knopf::ok()]);
?>
