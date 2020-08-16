<?php
Anfrage::post("art");

if(!Kern\Check::angemeldet()) {
  Anfrage::addFehler(-2, true);
}

if (!$DSH_BENUTZER->hatRecht("kern.konfiguration")) {
  Anfrage::addFehler(-4, true);
}

if ($art != "Verzeichnisse" && $art != "Schulhof" && $art != "Personen") {
  Anfrage::addFehler(-3, true);
}

if ($art === "Verzeichnisse") {
  Anfrage::post("basis");
  if(!UI\Check::istText($basis)) {
    Anfrage::addFehler(74);
  }
} else if ($art === "Schulhof" || $art === "Personen") {
  Anfrage::post("host", "port", "datenbank", "benutzer", "passwort", "schluessel");
  if ($art === "Schulhof") {
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
  } else if ($art === "Personen") {
    if(!UI\Check::istText($host)) {
      Anfrage::addFehler(80);
    }
    if(!UI\Check::istZahl($port,0,65535)) {
      Anfrage::addFehler(81);
    }
    if(!UI\Check::istText($datenbank)) {
      Anfrage::addFehler(82);
    }
    if(!UI\Check::istText($benutzer)) {
      Anfrage::addFehler(83);
    }
    if(strlen($schluessel)<=0) {
      Anfrage::addFehler(84);
    }
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
} else if ($art === "Personen") {
  $EINSTELLUNGEN["Datenbanken"]["Personen"]["Host"]       = $host;
  $EINSTELLUNGEN["Datenbanken"]["Personen"]["Port"]       = $port;
  $EINSTELLUNGEN["Datenbanken"]["Personen"]["DB"]         = $datenbank;
  $EINSTELLUNGEN["Datenbanken"]["Personen"]["Benutzer"]   = $benutzer;
  $EINSTELLUNGEN["Datenbanken"]["Personen"]["Passwort"]   = $passwort;
  $EINSTELLUNGEN["Datenbanken"]["Personen"]["Schluessel"] = $schluessel;
}

$config = fopen("$ROOT/core/config.php", "w");
$txt = "<?php
\$EINSTELLUNGEN = [
  \"Base\"        => \"{$EINSTELLUNGEN["Base"]}\",
  \"Datenbanken\" => [
    \"Schulhof\"    => [
      \"Host\"        => \"{$EINSTELLUNGEN["Datenbanken"]["Schulhof"]["Host"]}\",
      \"Port\"        => {$EINSTELLUNGEN["Datenbanken"]["Schulhof"]["Port"]},
      \"DB\"          => \"{$EINSTELLUNGEN["Datenbanken"]["Schulhof"]["DB"]}\",
      \"Benutzer\"    => \"{$EINSTELLUNGEN["Datenbanken"]["Schulhof"]["Benutzer"]}\",
      \"Passwort\"    => \"{$EINSTELLUNGEN["Datenbanken"]["Schulhof"]["Passwort"]}\",
      \"Schluessel\"  => \"{$EINSTELLUNGEN["Datenbanken"]["Schulhof"]["Schluessel"]}\"
    ],
    \"Personen\"    => [
      \"Host\"        => \"{$EINSTELLUNGEN["Datenbanken"]["Personen"]["Host"]}\",
      \"Port\"        => {$EINSTELLUNGEN["Datenbanken"]["Personen"]["Port"]},
      \"DB\"          => \"{$EINSTELLUNGEN["Datenbanken"]["Personen"]["DB"]}\",
      \"Benutzer\"    => \"{$EINSTELLUNGEN["Datenbanken"]["Personen"]["Benutzer"]}\",
      \"Passwort\"    => \"{$EINSTELLUNGEN["Datenbanken"]["Personen"]["Passwort"]}\",
      \"Schluessel\"  => \"{$EINSTELLUNGEN["Datenbanken"]["Personen"]["Schluessel"]}\"
    ],
  ]
];
?>";
fwrite($config, $txt);
fclose($config);

$DBS->logZugriff("Datei", "/core/", "config.php", "Überschreiben - $art");
?>
