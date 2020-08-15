<?php
namespace Kern;

class Dateisystem {
  /**
   * Löscht den unter $pfad angegebenen Ordner
   * @param  string $pfad :)
   * @return bool   true im Erfolgsfall, sonst false
   */
  public static function ordnerLoeschen($pfad) : bool {
    $dateien = "";
    $ordner = "";
    $groesse = 0;
    if (is_dir ($pfad)) {
      $verzeichnis = scandir($pfad);
      // einlesen der Verzeichnisses
      foreach ($verzeichnis as $v) {
        if (($v != "..") && ($v != ".")) {
          if (is_file($pfad."/".$v)) {
            unlink($pfad."/".$v);
          }
          if (is_dir($pfad."/".$v)) {
            Dateisystem::ordnerLoeschen($pfad."/".$v);
          }
        }
      }
      rmdir($pfad);
      return true;
    }
    return false;
  }
}

?>
