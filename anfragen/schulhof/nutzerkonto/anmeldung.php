<?php
Anfrage::post("benutzer", "passwort");

if(!Check::istText($benutzer)) {
  Anfrage::addFehler(1);
}
if(strlen($passwort) <= 0) {
  Anfrage::addFehler(2);
}
Anfrage::checkFehler();


$angemeldet = false;
$ldap = Kern\Einstellungen::ladenAlle("Kern");

// LDAP - falls aktiv
// Ggf. Aktualisierung vom LDAP-Server durchführen
if ($ldap["LDAP"] == "1") {
  echo "LDAP";

  // Annahme die LDAP-Anmeldung hat eine LDAP-Benutzerid ergeben
  $ldapbenuzterid = 0;


  // LDAP Benutzerid
  $sql = "SELECT kern_personen.id AS id, {salt}, {art}, {titel}, {vorname}, {nachname}, {geschlecht}, schuljahr, {uebersichtsanzahl}, {inaktivitaetszeit}, passworttimeout FROM kern_personen JOIN kern_nutzerkonten ON kern_personen.id = kern_nutzerkonten.id JOIN kern_nutzereinstellungen ON kern_nutzerkonten.id = kern_nutzereinstellungen.person WHERE ldapid = ?";
  $anfrage = $DBS->anfrage($sql, "si", $ldapbenuzterid);

}
// FALLBACK - Anmeldung über den Schulhof
else {
  $jetzt = time();
  // Benutzer suchen
  $sql = "SELECT kern_personen.id AS id, {salt}, {art}, {titel}, {vorname}, {nachname}, {geschlecht}, schuljahr, {uebersichtsanzahl}, {inaktivitaetszeit}, passworttimeout FROM kern_personen JOIN kern_nutzerkonten ON kern_personen.id = kern_nutzerkonten.id JOIN kern_nutzereinstellungen ON kern_nutzerkonten.id = kern_nutzereinstellungen.person WHERE benutzername = [?] AND (passworttimeout IS null OR passworttimeout > ?)";
  $anfrage = $DBS->anfrage($sql, "si", $benutzer, $jetzt);
}

// Falls es keine passenden Benutzer gibt, abbrechen
if ($anfrage->getAnzahl() == 0) {
  Anfrage::addFehler(5, true);
}
$anfrage->werte($id, $salt, $art, $titel, $vorname, $nachname, $geschlecht, $schuljahr, $uebersichtszahl, $inaktivitaetszeit, $passworttimeout);


// Passwortprüfung nur im FALLBACK-Fall notwendig
if ($ldap["LDAP"] != "1") {
  $passwortgesalzen = $passwort.$salt;
  $sql = "SELECT COUNT(*) AS anzahl FROM kern_nutzerkonten WHERE passwort = SHA1(?) AND id = ?";
  $anfrage = $DBS->anfrage($sql, "si", $passwortgesalzen, $id);
  if ($anfrage->getAnzahl() != 1) {
    Anfrage::addFehler(4, true);
  }
  $anfrage->werte($anz);
  if($anz != 1) {
    Anfrage::addFehler(4, true);
  }
}

// Benutzer anmelden
session_start();
$sessionid = session_id();
$jetzt = time();
$sessiontimeout = $jetzt + $inaktivitaetszeit*60;

// Alte Sessions mit dieser SessionID bearbeiten löschen
$sql = "UPDATE kern_nutzersessions SET sessionid = null WHERE sessionid = [?]";
$anfrage = $DBS->anfrage($sql, "s", $sessionid);

$sql = "SELECT id FROM kern_nutzersessions WHERE nutzer = ? ORDER BY sessiontimeout LIMIT 2";
$anfrage = $DBS->anfrage($sql, "i", $id);
$sicheresessions = [];
while ($anfrage->werte($sid)) {
  $sicheresessions[] = $sid;
}
if (count($sicheresessions) > 0) {
  $sicheresessionssql = implode(",", $sicheresessions);
  $sql = "DELETE FROM kern_nutzersessions WHERE id NOT IN ($sicheresessionssql) AND nutzer = ? AND sessiontimeout < ?";
  $timeoutlimit = $jetzt - 60*60*24*2;
  $anfrage = $DBS->anfrage($sql, "ii", $id, $timeoutlimit);
}

// Sessionvariablen setzen
$anmelden = new Kern\Nutzerkonto($titel, $vorname, $nachname);
$anmelden->setId($id);
$anmelden->setArt($art);
$anmelden->setBenutzer($benutzer);
$anmelden->setSession($sessionid, $sessiontimeout, $inaktivitaetszeit);
$anmelden->setSchuljahr($schuljahr);
$anmelden->setPassworttimeout($passworttimeout);
$anmelden->setUebersichtszahl($uebersichtszahl);
$anmelden->setGeschlecht($geschlecht);

$_SESSION['Benutzer'] = $anmelden;
$_SESSION['DSGVO_FENSTERWEG'] = true;
$_SESSION['DSGVO_EINWILLIGUNG_A'] = true;

// Neue Session eintragen
$sessiondbid = $DBS->neuerDatensatz("kern_nutzersessions");
$sql = "UPDATE kern_nutzersessions SET sessionid = [?], nutzer = ?, sessiontimeout = ? WHERE id = ?";
$anfrage = $DBS->anfrage($sql, "siis", $sessionid, $id, $sessiontimeout, $sessiondbid);

// Postfachordner verwalten
$anmelden->postfachOrdnerAufraeumen();

Anfrage::setTyp("Weiterleitung");
Anfrage::setRueck("Ziel", "Schulhof/Nutzerkonto");
?>
