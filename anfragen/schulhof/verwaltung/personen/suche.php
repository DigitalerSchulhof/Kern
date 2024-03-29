<?php
Anfrage::post("vorname", "nachname", "klasse", "schueler", "lehrer", "erzieher", "verwaltung", "externe");
Anfrage::postSort();

if(!Kern\Check::angemeldet()) {
  Anfrage::addFehler(-2, true);
}

if (!$DSH_BENUTZER->hatRecht("verwaltung.personen.sehen")) {
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

$spalten = [["{titel} AS titel"], ["{vorname} AS vorname"], ["{nachname} AS nachname"], ["kern_nutzerkonten.person AS konto", "anmeldung AS nid"], ["kern_personen.id AS kid"], ["{art} AS art"]];

$sql = "SELECT ?? FROM kern_personen LEFT JOIN kern_nutzerkonten ON kern_personen.id = kern_nutzerkonten.person LEFT JOIN ((SELECT person, MAX(anmeldezeit) AS anmeldung FROM kern_nutzersessions GROUP BY person) AS sessions) ON kern_personen.id = sessions.person";

$parameter = [];
$parameterarten = "";

$arten = [];
if ($schueler === "1") {
  $arten[] = "art = [?]";
  $parameter[] = "s";
  $parameterarten .= "s";
}
if ($lehrer === "1") {
  $arten[] = "art = [?]";
  $parameter[] = "l";
  $parameterarten .= "s";
}
if ($erzieher === "1") {
  $arten[] = "art = [?]";
  $parameter[] = "e";
  $parameterarten .= "s";
}
if ($verwaltung === "1") {
  $arten[] = "art = [?]";
  $parameter[] = "v";
  $parameterarten .= "s";
}
if ($externe === "1") {
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

$ta = new Kern\Tabellenanfrage($sql, $spalten, $sortSeite, $sortDatenproseite, $sortSpalte, $sortRichtung);
$tanfrage = $ta->anfrage($DBS, $parameterarten, $parameter);
$anfrage = $tanfrage["Anfrage"];

$tabelle = new UI\Tabelle("dshVerwaltungPersonen", "kern.schulhof.verwaltung.personen.suche", new UI\Icon(UI\Konstanten::SCHUELER), "Titel", "Vorname", "Nachname", "Status");
$tabelle ->setSeiten($tanfrage);

$darfsession = $DSH_BENUTZER->hatRecht("personen.andere.profil.sessionprotokoll.sehen");
$darfprofil = $DSH_BENUTZER->hatRecht("personen.andere.profil.sehen");
$darfaufenthalt = $DSH_BENUTZER->hatRecht("verwaltung.personen.aufenthalt");
$darfzfa = $DSH_BENUTZER->hatRecht("verwaltung.personen.zweifaktor");
$darfanlegen = $DSH_BENUTZER->hatRecht("verwaltung.personen.anlegen.nutzerkonto");
$darfrechte = $DSH_BENUTZER->hatRecht("verwaltung.rechte.vergeben || kern.rechte.rollen.zuordnen");
$darfloeschen = $DSH_BENUTZER->hatRecht("verwaltung.personen.loeschen.[|person,nutzerkonto]");

while ($anfrage->werte($tit, $vor, $nach, $nutzer, $anmeldung, $id, $art)) {
  $zeile  = new UI\Tabelle\Zeile($id);

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
      $zeile["Status"]    = new UI\IconBadge(new UI\Icon("fas fa-user-slash"), "kein Nutzerkonto", "Fehler");
    } else {
      if ($anmeldung !== null) {
        $datum            = new UI\Datum($anmeldung);
        $sorttoken = new UI\InhaltElement($anmeldung);
        $sorttoken->setTag("span");
        $sorttoken->addKlasse("dshUiUnsichtbar");
        $zeile["Status"]    = new UI\IconBadge(new UI\Icon("fas fa-id-card-alt"), "$sorttoken{$datum->kurz("X")}", "Erfolg");
      } else {
        $zeile["Status"]    = new UI\IconBadge(new UI\Icon("fas fa-id-card-alt"), "noch keine Anmeldung", "Erfolg");
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
  if ($nutzer === null && $darfanlegen) {
    $knopf = new UI\MiniIconKnopf(new UI\Icon("fas fa-arrow-alt-circle-up"), "Nutzerkonto erstellen", "Erfolg");
    $knopf->addFunktion("onclick", "kern.schulhof.verwaltung.personen.neu.nutzerkonto.anzeigen('$id', '1')");
    $zeile->addAktion($knopf);
  }
  if ($nutzer !== null && $darfrechte) {
    $knopf = new UI\MiniIconKnopf(new UI\Icon("fas fa-user-lock"), "Rechte und Rollen vergeben", "Warnung");
    $knopf->addFunktion("onclick", "kern.schulhof.verwaltung.personen.rechteundrollen('$id')");
    $zeile->addAktion($knopf);
  }
  if ($nutzer !== null && $darfzfa) {
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
    $knopf->addFunktion("onclick", "kern.schulhof.verwaltung.personen.loeschen.fragen('$id', '$nk')");
    $zeile->addAktion($knopf);
  }

  $tabelle[] = $zeile;
}

Anfrage::setRueck("Code", (string) $tabelle);
?>
