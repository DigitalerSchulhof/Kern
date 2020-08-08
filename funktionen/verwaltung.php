<?php

use Kern\Verwaltung\Liste;
use Kern\Verwaltung\Element;
use UI\Icon;

$personen   = Liste::getKategorie("personen");
$technik   = Liste::getKategorie("technik");

$personen[] = new Element("Personen", "Benutzerdaten",  new Icon("fas fa-user"),         "Schulhof/Verwaltung/Personen");
$personen[] = new Element("Rechte",   "Rechte",         new Icon("fas fa-wrench"),       "Schulhof/Verwaltung/Rechte");
$personen[] = new Element("Rechte zuordnen",  "Rechte", new Icon("fas fa-user-cog"),     "Schulhof/Verwaltung/Rechte");

$technik[] = new Element("Module",    "Module",         new Icon("fas fa-puzzle-piece"), "Schulhof/Verwaltung/Module", true);
$technik[] = new Element("Style",     "Style",          new Icon("fas fa-palette"),      "Schulhof/Verwaltung/Style");

$gruppen    = Liste::addKategorie(new \Kern\Verwaltung\Kategorie("gruppen", "Gruppen"));
$gruppen[]  = new Element("Gremien",  "Gremien",        new Icon("fas fa-user-graduate"), "Schulhof/Verwaltung/Gruppen/Gremien");
$gruppen[]  = new Element("Fachschaften",  "Gremien",        new Icon("fas fa-user-graduate"), "Schulhof/Verwaltung/Gruppen/Gremien");
$gruppen[]  = new Element("Klassen",  "Gremien",        new Icon("fas fa-user-graduate"), "Schulhof/Verwaltung/Gruppen/Gremien");
$gruppen[]  = new Element("Kurse",  "Gremien",        new Icon("fas fa-user-graduate"), "Schulhof/Verwaltung/Gruppen/Gremien");
$gruppen[]  = new Element("Stufen",  "Gremien",        new Icon("fas fa-user-graduate"), "Schulhof/Verwaltung/Gruppen/Gremien");
$gruppen[]  = new Element("AGs",  "Gremien",        new Icon("fas fa-user-graduate"), "Schulhof/Verwaltung/Gruppen/Gremien");
$gruppen[]  = new Element("AKs",  "Gremien",        new Icon("fas fa-user-graduate"), "Schulhof/Verwaltung/Gruppen/Gremien");
$gruppen[]  = new Element("Fahrten",  "Gremien",        new Icon("fas fa-user-graduate"), "Schulhof/Verwaltung/Gruppen/Gremien");
$gruppen[]  = new Element("Wettbewerbe",  "Gremien",        new Icon("fas fa-user-graduate"), "Schulhof/Verwaltung/Gruppen/Gremien");
$gruppen[]  = new Element("Ereignisse",  "Gremien",        new Icon("fas fa-user-graduate"), "Schulhof/Verwaltung/Gruppen/Gremien");
$gruppen[]  = new Element("Rest",  "Gremien",        new Icon("fas fa-user-graduate"), "Schulhof/Verwaltung/Gruppen/Gremien");

?>
