<?php
include "$MODUL/klassen/db/Anfrage.php";
include "$MODUL/klassen/db/DB.php";
include "$MODUL/klassen/aktionszeile.php";
include "$MODUL/klassen/einstellungen.php";
include "$MODUL/klassen/dateisystem.php";
include "$MODUL/klassen/mail.php";
include "$MODUL/klassen/rechtehelfer.php";
include "$MODUL/klassen/person.php";

use \DB\DB;

foreach($DSH_DATENBANKEN as $d) {
	if($d == "schulhof") {
    global $DBS;
    $e = $EINSTELLUNGEN["Datenbanken"]["Schulhof"];
		$DBS = new DB($e["Host"], $e["Port"], $e["Benutzer"], $e["Passwort"], $e["DB"], $e["Schluessel"]);
	}
	if($d == "personen") {
    global $DBP;
    $e = $EINSTELLUNGEN["Datenbanken"]["Personen"];
		$DBP = new DB($e["Host"], $e["Port"], $e["Benutzer"], $e["Passwort"], $e["DB"], $e["Schluessel"]);
	}
}
?>
