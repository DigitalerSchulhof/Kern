<?php

include "$MODUL/klassen/db/Anfrage.php";
include "$MODUL/klassen/db/DB.php";

use \DB\DB;

foreach($DSH_DATENBANKEN as $d) {
	if($d == "schulhof") {
		$DBS = new DB("localhost", "root", "", "dsh_schulhof", "MeinPasswortIstSicher");
	}
	if($d == "personen") {
		$DBS = new DB("localhost", "root", "", "dsh_personen", "MeinPasswortIstSicher");
	}
}
?>
