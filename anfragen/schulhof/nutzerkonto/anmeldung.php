<?php
Anfrage::post("benutzer", "passwort");

if(!UI\Check::istText($benutzer)) {
  Anfrage::addFehler(1);
}
if(strlen($passwort) < 6) {
  Anfrage::addFehler(2);
}
Anfrage::checkFehler();


$angemeldet = false;
$ldap = Kern\Einstellungen::ladenAlle("Kern");

// @TODO: LDAP - falls aktiv
// Ggf. Aktualisierung vom LDAP-Server durchführen
if ($ldap["LDAP"] == "1") {
  echo "LDAP";

  // Annahme die LDAP-Anmeldung hat eine LDAP-Benutzerid ergeben
  $ldapbenuzterid = 0;


  // LDAP Benutzerid
  $sql = "SELECT kern_personen.id AS id, {salt}, {art}, {titel}, {vorname}, {nachname}, {geschlecht}, schuljahr, {uebersichtsanzahl}, {inaktivitaetszeit}, passworttimeout FROM kern_personen JOIN kern_nutzerkonten ON kern_personen.id = kern_nutzerkonten.person JOIN kern_nutzereinstellungen ON kern_nutzerkonten.person = kern_nutzereinstellungen.person WHERE ldapid = ?";
  $anfrage = $DBS->anfrage($sql, "si", $ldapbenuzterid);

}
// FALLBACK - Anmeldung über den Schulhof
else {
  $jetzt = time();
  // Benutzer suchen
  $sql = "SELECT kern_personen.id AS id, {salt}, {art}, {titel}, {vorname}, {nachname}, {geschlecht}, schuljahr, {uebersichtsanzahl}, {inaktivitaetszeit}, passworttimeout FROM kern_personen JOIN kern_nutzerkonten ON kern_personen.id = kern_nutzerkonten.person JOIN kern_nutzereinstellungen ON kern_nutzerkonten.person = kern_nutzereinstellungen.person WHERE benutzername = [?] AND (passworttimeout IS null OR passworttimeout > ?)";
  $anfrage = $DBS->anfrage($sql, "si", $benutzer, $jetzt);
}

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
?>
