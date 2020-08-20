<?php
namespace Kern;

class Wurmloch {
  /**
   * Öffnet ein intermodulares Portal und bindet eine Datei ein, sofern es diese findet
   * @param  string $datei     Die einzubindende Datei (<code>$DSH_MODUL/$datei</code>)
   * @param  array  $parameter Zusätzliche Parameter für die Datei
   *  [Name => Wert, Name2 => Wert]
   *  Die Variablen würden mit <code>$Name</code> und <code>$Name2</code> benutzt werden
   * @param  callback $callback Eine Funktion, die als ersten Parameter die Rückgabe der Einbindung und als zweiten Parameter das Modul erhält
   * @return
   */
  public function __construct($datei, $parameter = array(), $callback = null) {
    global $DSH_ALLEMODULE, $DSH_BENUTZER, $ROOT, $DBS;
    $dateien = [];
    foreach($DSH_ALLEMODULE as $modul => $pfad) {
      if(is_file("$pfad/$datei")) {
        Core\Einbinden::modulLaden($modul, true, false);
        Kern\DB::datenbankenLaden();
        $dateien[$modul] = "$pfad/$datei";
      }
    }
    foreach($dateien as $modul => $d) {
      foreach($parameter as $name => $wert) {
        $$name = $wert;
      }
      if($callback !== null) {
        $callback(((include $d)), $modul);
      } else {
        include $d;
      }
    }
  }
}

?>