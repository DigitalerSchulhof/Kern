<?php
Anfrage::post("modulname");

if(!Kern\Check::angemeldet()) {
  Anfrage::addFehler(-2, true);
}

if (!isset($DSH_ALLEMODULE[$modulname])) {
  Anfrage::addFehler(-3, true);
}

if (!$DSH_BENUTZER->hatRecht("module.sehen")) {
  Anfrage::addFehler(-4, true);
}

// Modulbeschreibung laden
include_once("$ROOT/yaml.php");
use Async\YAML;

$letzteVersion = (string) new UI\Ueberschrift(3, "Unbekannte Version");
$letzteVersion .= new UI\Absatz("Für die aktuelle Version wurde keine Beschreibung angegeben.");

$fensterid = "dshVerwaltungModuleModulversion{$modulname}";

if (is_file("{$DSH_ALLEMODULE[$modulname]}/version/versionen.yml")) {
  $versionsinfo = YAML::loader(file_get_contents("{$DSH_ALLEMODULE[$modulname]}/version/versionen.yml"));
  $code = "";
  // Initial
  $v = array_shift($versionsinfo);
  $letzteVersion = (string) new UI\Ueberschrift(3, "Version {$v["version"]}");
  $letzteVersion .= new UI\Notiz($v["datum"]);
  if (is_array($v["neuerungen"]) && count($v["neuerungen"]) > 0) {
    $neuerungen = new UI\Liste();
    foreach ($v["neuerungen"] as $n) {
      $neuerungen->add(new UI\InhaltElement($n));
    }
    $letzteVersion .= $neuerungen;
  }

  if (count($versionsinfo) > 0) {
    $toggle = new UI\Toggle("{$fensterid}Alt", "Ältere Versionen anzeigen");
    $toggle->addFunktion("onclick", "kern.schulhof.verwaltung.module.alteEinblenden('{$fensterid}Alt')");
    $alteversionen = (string) new UI\Absatz($toggle);
    $alteversionen .= "<div id=\"{$fensterid}AltFeld\" class=\"dshAlteVersionen\">";
    foreach ($versionsinfo["version"] as $v) {
      $alteversionen .= new UI\Ueberschrift(3, "Version {$v["version"]}");
      $alteversionen .= new UI\Notiz($v["datum"]);
      if (is_array($v["neuerungen"]) && count($v["neuerungen"]) > 0) {
        $neuerungen = new UI\Liste();
        foreach ($v["neuerungen"] as $n) {
          $neuerungen->add(new UI\InhaltElement($n));
        }
        $alteversionen .= $neuerungen;
      }
    }
    $alteversionen .= "</div>";
    $letzteVersion .= $alteversionen;
  }
}

$fenstertitel = (new UI\Icon("fas fa-puzzle-piece"))." Modulversionen";

$code = new UI\Fenster($fensterid, $fenstertitel, UI\Zeile::standard($letzteVersion));

Anfrage::setRueck("Code", (string) $code);
?>
