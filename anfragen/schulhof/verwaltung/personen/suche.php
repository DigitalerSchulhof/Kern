<?php
Anfrage::post("vorname", "nachname", "klasse", "schueler", "lehrer", "erzieher", "verwaltung", "externe");

if(!Kern\Check::angemeldet()) {
  Anfrage::addFehler(-2, true);
}

if (!$DSH_BENUTZER->hatRecht("kern.personen.sehen")) {
  Anfrage::addFehler(-4, true);
}

if (!UI\Check::istToggle($schueler) || !UI\Check::istToggle($lehrer) || !UI\Check::istToggle($erzieher) || !UI\Check::istToggle($verwaltung) || !UI\Check::istToggle($externe)) {
  Anfrage::addFehler(-3, true);
}

if (!UI\Check::istName($vorname,0)) {
  Anfrage::addFehler(85);
}

if (!UI\Check::istName($nachname,0)) {
  Anfrage::addFehler(86);
}

if (!UI\Check::istText($klasse,0)) {
  Anfrage::addFehler(87);
}

// @TODO: Klassensuche einbauen

$sql = "SELECT kern_personen.id AS kid, {art}, {titel} AS titel, {vorname} AS vorname, {nachname} AS nachname, kern_nutzerkonten.id, anmeldung AS nid FROM kern_personen LEFT JOIN kern_nutzerkonten ON kern_personen.id = kern_nutzerkonten.id LEFT JOIN ((SELECT nutzer, MAX(anmeldezeit) AS anmeldung FROM kern_nutzersessions GROUP BY nutzer) AS sessions) ON kern_personen.id = sessions.nutzer";

$parameter = [];
$parameterarten = "";

$arten = [];
if ($schueler == "1") {
  $arten[] = "art = [?]";
  $parameter[] = "s";
  $parameterarten .= "s";
}
if ($lehrer == "1") {
  $arten[] = "art = [?]";
  $parameter[] = "l";
  $parameterarten .= "s";
}
if ($erzieher == "1") {
  $arten[] = "art = [?]";
  $parameter[] = "e";
  $parameterarten .= "s";
}
if ($verwaltung == "1") {
  $arten[] = "art = [?]";
  $parameter[] = "v";
  $parameterarten .= "s";
}
if ($externe == "1") {
  $arten[] = "art = [?]";
  $parameter[] = "x";
  $parameterarten .= "s";
}

if (count($arten) > 0) {
  $sql .= " WHERE ".join(" OR ", $arten);
}

$sql = "SELECT * FROM ($sql) AS x";

$postfilter = [];
if (strlen($vorname) > 0) {
  $postfilter[] = "UPPER(CONVERT(vorname USING utf8)) LIKE UPPER(?)";
  $parameter[] = "%$vorname%";
  $parameterarten .= "s";
}
if (strlen($nachname) > 0) {
  $postfilter[] = "UPPER(CONVERT(nachname USING utf8)) LIKE UPPER(?)";
  $parameter[] = "%$nachname%";
  $parameterarten .= "s";
}
if (strlen($klasse) > 0) {
  // @TODO: Klassensuche einbauen
}

if (count($postfilter) > 0) {
  $sql .= " WHERE ".join(" AND ", $postfilter);
}

$sql .= " ORDER BY nachname, vorname, titel";

$anfrage = $DBS->anfrage($sql, $parameterarten, $parameter);

$tabelle = new UI\Tabelle("dshVerwaltungModule", new UI\Icon(UI\Konstanten::SCHUELER), "Titel", "Vorname", "Nachname", "Status");

$darfsession = $DSH_BENUTZER->hatRecht("kern.personen.profil.sessionprotokoll.sehen");
$darfprofil = $DSH_BENUTZER->hatRecht("kern.personen.profil.sehen");
$darfaufenthalt = $DSH_BENUTZER->hatRecht("kern.personen.aufenthalt");
$darfzfa = $DSH_BENUTZER->hatRecht("kern.personen.zweifaktor");
$darfanlegen = $DSH_BENUTZER->hatRecht("kern.personen.anlegen.nutzerkonto");
$darfloeschen = $DSH_BENUTZER->hatRecht("kern.personen.löschen.[|person,nutzerkonto]");

while ($anfrage->werte($id, $art, $tit, $vor, $nach, $nutzer, $anmeldung)) {
  $zeile  = new UI\Tabelle\Zeile();

  if($art == "s") {$zeile->setIcon(new UI\Icon(UI\Konstanten::SCHUELER));}
  else if($art == "l") {$zeile->setIcon(new UI\Icon(UI\Konstanten::LEHRER));}
  else if($art == "e") {$zeile->setIcon(new UI\Icon(UI\Konstanten::ERZIEHER));}
  else if($art == "v") {$zeile->setIcon(new UI\Icon(UI\Konstanten::VERWALTUNG));}
  else if($art == "x") {$zeile->setIcon(new UI\Icon(UI\Konstanten::EXTERN));}

  $zeile["Titel"]    = $tit;
  $zeile["Vorname"]  = $vor;
  $zeile["Nachname"] = $nach;

  if ($darfsession) {
    if ($nutzer === null) {
      $zeile["Status"]    = new UI\IconKnopf(new UI\Icon("fas fa-user-slash"), "kein Nutzerkonto", "Fehler");
    } else {
      if ($anmeldung !== null) {
        $datum            = new UI\Datum($anmeldung);
        $sorttoken = new UI\InhaltElement($anmeldung);
        $sorttoken->setTag("span");
        $sorttoken->addKlasse("dshUiUnsichtbar");
        $zeile["Status"]    = new UI\IconKnopf(new UI\Icon("fas fa-id-card-alt"), "$sorttoken{$datum->kurz("X")}", "Erfolg");
      } else {
        $zeile["Status"]    = new UI\IconKnopf(new UI\Icon("fas fa-id-card-alt"), "noch keine Anmeldung", "Erfolg");
      }
    }
  } else {
    if ($nutzer === null) {
      $zeile["Status"]    = new UI\MiniIconKnopf(new UI\Icon("fas fa-user-slash"), "kein Nutzerkonto", "Fehler");
    } else {
      $zeile["Status"]    = new UI\MiniIconKnopf(new UI\Icon("fas fa-id-card-alt"), "Nutzerkonto besteht", "Erfolg");
    }
  }

  if ($darfprofil) {
    $knopf = new UI\MiniIconKnopf(new UI\Icon("fas fa-address-card"), "Profil öffnen");
    $knopf->addFunktion("onclick", "kern.schulhof.verwaltung.personen.profil('$id')");
    $zeile->addAktion($knopf);
  }
  if ($darfaufenthalt) {
    $knopf = new UI\MiniIconKnopf(new UI\Icon("fas fa-location-arrow"), "Aufenthaltsort bestimmen");
    $zeile->addAktion($knopf);
  }
  if ($darfanlegen && $nutzer === null) {
    $knopf = new UI\MiniIconKnopf(new UI\Icon("fas fa-arrow-alt-circle-up"), "Nutzerkonto erstellen", "Erfolg");
    $knopf->addFunktion("onclick", "kern.schulhof.verwaltung.personen.neu.nutzerkonto.anzeigen('$id', '1')");
    $zeile->addAktion($knopf);
  }
  if ($darfzfa) {
    $knopf = new UI\MiniIconKnopf(new UI\Icon("fas fa-qrcode"), "Zwei-Faktor-Schlüssel", "Warnung");
    $zeile->addAktion($knopf);
  }
  if ($darfloeschen) {
    $knopf = new UI\MiniIconKnopf(new UI\Icon(UI\Konstanten::LOESCHEN), "Nutzerkonto oder Person löschen", "Warnung");
    if ($nutzer !== null) {
      $nk = "1";
    } else {
      $nk = "0";
    }
    $knopf->addFunktion("onclick", "kern.schulhof.verwaltung.personen.loeschen.fragen('$id', '$nk', '1')");
    $zeile->addAktion($knopf);
  }

  $tabelle[] = $zeile;
}

Anfrage::setRueck("Code", (string) $tabelle);
?>
