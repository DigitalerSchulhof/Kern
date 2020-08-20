<?php
include_once("$KLASSEN/check.php");
include_once("$KLASSEN/db/anfrage.php");
include_once("$KLASSEN/db/tabellenanfrage.php");
include_once("$KLASSEN/db/db.php");
include_once("$KLASSEN/aktionszeile.php");
include_once("$KLASSEN/seite.php");
include_once("$KLASSEN/einstellungen.php");
include_once("$KLASSEN/dateisystem.php");
include_once("$KLASSEN/mail.php");
include_once("$KLASSEN/rechtehelfer.php");
include_once("$KLASSEN/person.php");
include_once("$KLASSEN/profil.php");
include_once("$KLASSEN/filter.php");

use \Kern\DB;

global $DSH_DBS;
$DSH_DBS = [];

foreach($DSH_DATENBANKEN as $d) {
	if($d == "schulhof") {
    global $DBS;
    $e = $EINSTELLUNGEN["Datenbanken"]["Schulhof"];
		$DBS = new DB($e["Host"], $e["Port"], $e["Benutzer"], $e["Passwort"], $e["DB"], $e["Schluessel"]);
    $DSH_DBS[] = $DBS;
	}
	if($d == "personen") {
    global $DBP;
    $e = $EINSTELLUNGEN["Datenbanken"]["Personen"];
		$DBP = new DB($e["Host"], $e["Port"], $e["Benutzer"], $e["Passwort"], $e["DB"], $e["Schluessel"]);
    $DSH_DBS[] = $DBP;
	}
}
?>
