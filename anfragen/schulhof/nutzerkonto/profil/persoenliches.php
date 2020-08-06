<?php
Anfrage::post("id", "art", "geschlecht", "titel", "vorname", "nachname", "kuerzel");

if(!Check::angemeldet()) {
  Anfrage::addFehler(-2, true);
}

if(!Check::istZahl($id,0) || !in_array($art, Kern\Person::getArten()) || !in_array($geschlecht, Kern\Person::getGeschlechter())) {
  Anfrage::addFehler(-3, true);
}

if(!Check::istTitel($titel)) {
  Anfrage::addFehler(26);
}
if(!Check::istName($vorname)) {
  Anfrage::addFehler(27);
}
if(!Check::istName($nachname)) {
  Anfrage::addFehler(28);
}
if($DSH_BENUTZER->getArt() == "l" && !Check::istText($kuerzel)) {
  Anfrage::addFehler(28);
}
Anfrage::checkFehler();

$profil = new Kern\Profil(new Kern\Nutzerkonto($id));
$recht = $profil->istFremdzugriff()
$sql = "SELECT {id}, {vorname}, {nachname},"
$felder = []


// Falls es keine passenden Benutzer gibt, abbrechen
if ($anfrage->getAnzahl() == 0) {
  Anfrage::addFehler(5, true);
}
$anfrage->werte($id, $salt, $art, $titel, $vorname, $nachname, $geschlecht, $schuljahr, $uebersichtszahl, $inaktivitaetszeit, $passworttimeout);


// Benutzer anlegen
$DSH_BENUTZER = new Kern\Nutzerkonto($id, $titel, $vorname, $nachname);
$DSH_BENUTZER->setArt($art);
$DSH_BENUTZER->setBenutzer($benutzer);
$DSH_BENUTZER->setSchuljahr($schuljahr);
$DSH_BENUTZER->setPassworttimeout($passworttimeout);
$DSH_BENUTZER->setUebersichtszahl($uebersichtszahl);
$DSH_BENUTZER->setGeschlecht($geschlecht);

// Passwortprüfung nur im FALLBACK-Fall notwendig
if ($ldap["LDAP"] != "1") {
  if (!$DSH_BENUTZER->passwortPruefen($passwort.$salt)) {
    Anfrage::addFehler(4, true);
  }
}

// Session setzen und Benutzer anmelden
Anfrage::session_start();
$sessionid = session_id();
$sessiontimeout = time() + $inaktivitaetszeit*60;
$DSH_BENUTZER->setSession($sessionid, $sessiontimeout, $inaktivitaetszeit);

$DSH_BENUTZER->anmelden();

Anfrage::setTyp("Weiterleitung");
Anfrage::setRueck("Ziel", "Schulhof/Nutzerkonto");
?>
