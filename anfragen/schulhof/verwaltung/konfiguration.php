<?php
Anfrage::post("art");

if(!Kern\Check::angemeldet()) {
  Anfrage::addFehler(-2, true);
}

if (!$DSH_BENUTZER->hatRecht("technik.konfiguration")) {
  Anfrage::addFehler(-4, true);
}

if ($art != "Verzeichnisse" && $art != "Schulhof") {
  Anfrage::addFehler(-3, true);
}

if ($art === "Verzeichnisse") {
  Anfrage::post("basis");
  if(!UI\Check::istText($basis)) {
    Anfrage::addFehler(74);
  }
} else if ($art === "Schulhof") {
  Anfrage::post("host", "port", "datenbank", "benutzer", "passwort", "schluessel");
  if(!UI\Check::istText($host)) {
    Anfrage::addFehler(75);
  }
  if(!UI\Check::istZahl($port,0,65535)) {
    Anfrage::addFehler(76);
  }
  if(!UI\Check::istText($datenbank)) {
    Anfrage::addFehler(77);
  }
  if(!UI\Check::istText($benutzer)) {
    Anfrage::addFehler(78);
  }
  if(strlen($schluessel)<=0) {
    Anfrage::addFehler(79);
  }
}

Anfrage::checkFehler();

// @TODO:Datenbanken neu verschlüsseln

if ($art === "Verzeichnisse") {
  $EINSTELLUNGEN["Base"] = $basis;
} else if ($art === "Schulhof") {
  $EINSTELLUNGEN["Datenbanken"]["Schulhof"]["Host"]       = $host;
  $EINSTELLUNGEN["Datenbanken"]["Schulhof"]["Port"]       = $port;
  $EINSTELLUNGEN["Datenbanken"]["Schulhof"]["DB"]         = $datenbank;
  $EINSTELLUNGEN["Datenbanken"]["Schulhof"]["Benutzer"]   = $benutzer;
  $EINSTELLUNGEN["Datenbanken"]["Schulhof"]["Passwort"]   = $passwort;
  $EINSTELLUNGEN["Datenbanken"]["Schulhof"]["Schluessel"] = $schluessel;
}

$config = fopen("$ROOT/core/config.php", "w");
$txt = '<?php $EINSTELLUNGEN='.var_export($EINSTELLUNGEN, true).';?>';
fwrite($config, $txt);
fclose($config);

$DBS->logZugriff("Datei", "/core/", "config.php", "Überschreiben - $art");
?>
