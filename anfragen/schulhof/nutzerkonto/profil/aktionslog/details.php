<?php
Anfrage::post("nutzerid", "logid");

if(!Kern\Check::angemeldet()) {
  Anfrage::addFehler(-2, true);
}

if (!UI\Check::istZahl($logid,0) && $logid != 'alle') {
  Anfrage::addFehler(-3, true);
}

// Logeintrag laden
$sql = "SELECT {titel}, {vorname}, {nachname}, {benutzername}, {kern_nutzeraktionslog.art}, {tabellepfad}, {datensatzdatei}, {aktion}, zeitpunkt FROM kern_nutzeraktionslog JOIN kern_personen ON kern_personen.id = kern_nutzeraktionslog.nutzer LEFT JOIN kern_nutzerkonten ON kern_personen.id = kern_nutzerkonten.id WHERE kern_nutzeraktionslog.id = ? AND nutzer = ?";
$anfrage = $DBS->anfrage($sql, "ii", $logid, $nutzerid);
$anfrage->werte($titel, $vorname, $nachname, $benutzername, $logart, $tabellepfad, $datensatzdatei, $aktion, $zeitpunkt);

$person = new Kern\Nutzerkonto($nutzerid, $titel, $vorname, $nachname);

// Rechtelage klären
$profil = new Kern\Profil($person);
$recht = $profil->istFremdzugriff();
if (!$DSH_BENUTZER->hatRecht("$recht.aktionslog.details")) {
  Anfrage::addFehler(-4, true);
}
if ($DSH_BENUTZER !== $nutzerid && !$DSH_BENUTZER->hatRecht("kern.personen.profil.aktionslog.details")) {
  Anfrage::addFehler(-4, true);
}

$inhalt  = new UI\Ueberschrift(3, "$aktion vom ".((new UI\Datum($zeitpunkt))->kurz("MUs")));

if ($logart == "DB") {
  $fenstertitel = (new UI\Icon("fas fa-database"))." Datenbankzugriff";
} else if ($logart == "Datei") {
  $fenstertitel = (new UI\Icon("fas fa-archive"))." Änderung am Dateisystem";
} else {
  $fenstertitel = (new UI\Icon("fas fa-shoe-prints"))." Sonstige Aktion";
}

if ($benutzername === null) {
  $inhalt .= new UI\Absatz("Zugriff von <b>{$person->getName()}</b> (<i>inaktiv</i>) auf <b>$tabellepfad</b>");
} else {
  $inhalt .= new UI\Absatz("Zugriff von <b>{$person->getName()}</b> ($benutzername) auf <b>$tabellepfad</b>");
}
$inhalt .= new UI\Code($datensatzdatei);

$fensterid = "dshProfil{$nutzerid}FensterLoginfo$logid";
$code = new UI\Fenster("dshProfil{$nutzerid}FensterLoginfo$logid", $fenstertitel, $inhalt);
$code->addFensteraktion(UI\Knopf::schliessen("dshProfil{$nutzerid}FensterLoginfo$logid"));

Anfrage::setRueck("Code", (string) $code);
?>
