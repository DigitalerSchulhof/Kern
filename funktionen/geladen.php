<?php
include "$MODUL/klassen/db/Anfrage.php";
include "$MODUL/klassen/db/DB.php";
include "$MODUL/klassen/aktionszeile.php";

use \DB\DB;

foreach($DSH_DATENBANKEN as $d) {
	if($d == "schulhof") {
    $e = $EINSTELLUNGEN["Datenbanken"]["Schulhof"];
		$DBS = new DB($e["Host"], $e["Port"], $e["Benutzer"], $e["Passwort"], $e["DB"], $e["Schluessel"]);
	}
	if($d == "personen") {
    $e = $EINSTELLUNGEN["Datenbanken"]["Personen"];
		$DBP = new DB($e["Host"], $e["Port"], $e["Benutzer"], $e["Passwort"], $e["DB"], $e["Schluessel"]);
	}
}
?>
