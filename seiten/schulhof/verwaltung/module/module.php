<?php
$SEITE = new Kern\Seite("Module", "module.sehen");

$darflo = $DSH_BENUTZER->hatRecht("module.löschen");
$darfei = $DSH_BENUTZER->hatRecht("module.einstellungen");
$darfve = $DSH_BENUTZER->hatRecht("module.versionshistorie");

$spalte   = new UI\Spalte("A1", new UI\SeitenUeberschrift("Module"));
$tabelle  = new UI\Tabelle("dshVerwaltungModuleInstalliert", "kern.schulhof.verwaltung.module.suchen", new UI\Icon(UI\Konstanten::MODUL), "Modul", "Beschreibung", "Autor", "Version");
$tabelle  ->setAutoladen(true);

$spalte[] = new UI\Ueberschrift(2, "Installiert");

$spalte[] = $tabelle;

if ($DSH_BENUTZER->hatRecht("module.installieren")) {
  // @TODO: Module installieren
  $spalte[] = new UI\Ueberschrift(2, "Verfügbar");
  $tabelle  = new UI\Tabelle("dshVerwaltungModuleVerfuegbar", "null", new UI\Icon(UI\Konstanten::MODUL), "Modul", "Beschreibung", "Autor", "Version");
  $tabelle  ->setAutoladen(true);
  $spalte[] = $tabelle;
}

$SEITE[] = new UI\Zeile($spalte);
?>
