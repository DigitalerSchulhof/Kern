<?php

use Kern\Verwaltung\Liste;
use Kern\Verwaltung\Element;
use UI\Icon;

$personen = Liste::addKategorie(new \Kern\Verwaltung\Kategorie("personen", "Personen"));
$technik  = Liste::addKategorie(new \Kern\Verwaltung\Kategorie("technik", "Technik"));

if($DSH_BENUTZER->hatRecht("verwaltung.personen.sehen"))        $personen[] = new Element("Personen",         "Benutzerdaten",      new Icon("fas fa-users"),           "Schulhof/Verwaltung/Personen");
if($DSH_BENUTZER->hatRecht("verwaltung.rechte.rollen.sehen"))   $personen[] = new Element("Rollen",           "Rechte",             new Icon(UI\Konstanten::ROLLE),     "Schulhof/Verwaltung/Rollen");

if($DSH_BENUTZER->hatRecht("technik.konfiguration"))          $technik[] = new Element("Konfiguration", "Konfiguration",  new Icon("fas fa-cogs"),            "Schulhof/Verwaltung/Konfiguration",        true);
if($DSH_BENUTZER->hatRecht("module.sehen"))                   $technik[] = new Element("Module",        "Module",         new Icon(UI\Konstanten::MODUL),     "Schulhof/Verwaltung/Module",               true);

$gruppen    = Liste::addKategorie(new \Kern\Verwaltung\Kategorie("gruppen", "Gruppen"));
// $gruppen[]  = new Element("Gremien",        "Gremien",       new Icon("fab fa-black-tie"),       "Schulhof/Verwaltung/Gruppen/Gremien");
// $gruppen[]  = new Element("Fachschaften",   "Fachschaften",  new Icon("fas fa-book"),            "Schulhof/Verwaltung/Gruppen/Fachschaften");
// $gruppen[]  = new Element("Klassen",        "Klassen",       new Icon("fas fa-graduation-cap"),  "Schulhof/Verwaltung/Gruppen/Klassen");
// $gruppen[]  = new Element("Kurse",          "Kurse",         new Icon("fas fa-chalkboard"),      "Schulhof/Verwaltung/Gruppen/Kurse");
// $gruppen[]  = new Element("Stufen",         "Stufen",        new Icon("fas fa-level-up-alt"),    "Schulhof/Verwaltung/Gruppen/Stufen");
// $gruppen[]  = new Element("AGs",            "AGs",           new Icon("far fa-circle"),          "Schulhof/Verwaltung/Gruppen/AGs");
// $gruppen[]  = new Element("AKs",            "AKs",           new Icon("fas fa-circle"),          "Schulhof/Verwaltung/Gruppen/AKs");
// $gruppen[]  = new Element("Fahrten",        "Fahrten",       new Icon("fas fa-suitcase"),        "Schulhof/Verwaltung/Gruppen/Fahrten");
// $gruppen[]  = new Element("Wettbewerbe",    "Wettbewerbe",   new Icon("fas fa-trophy"),          "Schulhof/Verwaltung/Gruppen/Wettbewerbe");
// $gruppen[]  = new Element("Sonstige",       "Sonstige",      new Icon("fas fa-pastafarianism"),  "Schulhof/Verwaltung/Gruppen/Sonstige");
// $gruppen[]  = new Element("Ereignisse",     "Ereignisse",    new Icon("fas fa-calendar-check"),  "Schulhof/Verwaltung/Gruppen/Ereignisse");

?>
