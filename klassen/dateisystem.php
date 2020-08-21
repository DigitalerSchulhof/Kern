<?php
namespace Kern;

class Dateisystem {
  /**
   * Löscht den unter $pfad angegebenen Ordner
   * @param  string $pfad :)
   * @return bool   true im Erfolgsfall, sonst false
   */
  public static function ordnerLoeschen($pfad) : bool {
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

  /**
   * Bestimmt wie viele Dateien und Ordner in diesem Pfad liegen und wie viel
   * Speicherplatz der Ordner oder die Datei verbrauchen
   * @param  string $pfad :)
   * @return array        Array mit Indizes dateien, ordner und groesse
   */
  public static function ordnerInfo($pfad) : array {
    $info = [];
    $info["dateien"] = 0;
    $info["ordner"]  = 0;
    $info["groesse"] = 0;

    if (is_dir ($pfad)) {
      $verzeichnis = scandir($pfad);
      // einlesen der Verzeichnisses
      foreach ($verzeichnis as $v) {
        if (($v != "..") && ($v != ".")) {
          if (is_file($pfad."/".$v)) {
            $info["dateien"]++;
            $info["groesse"] += filesize($pfad);
          }
          if (is_dir($pfad."/".$v)) {
            $info["ordner"]++;
            $unterordner = Dateisystem::ordnerInfo($pfad."/".$v);
            $info["dateien"] += $unterordner["dateien"];
            $info["ordner"]  += $unterordner["ordner"];
            $info["groesse"] += $unterordner["groesse"];
          }
        }
      }
    } else if (is_file($pfad)) {
      $info["dateien"]++;
      $info["groesse"] = filesize($pfad);
    }
    return $info;
  }

  /**
   * Ermittelt die Größe der angegebenen Datenbank-Tabellen
   * @param  Kern\DB  $DB       :)
   * @param  string[] $tabellen Namen der Tabellen in einem Array
   * @return int                Größe in Byte
   */
  public static function tabellenGroesse($DB, $tabellen) : int {
  	$groesse = 0;
  	$tabellencode = "";
  	foreach ($tabellen as $t) {
  		$tabellencode .= " OR table_name = '$t'";
  	}
  	if (strlen($tabellencode) > 0) {
  		$tabellencode = '('.substr($tabellencode, 4).')';
      $sql = "SELECT SUM(data_length + index_length) AS groesse FROM information_schema.tables WHERE table_schema = ? AND $tabellencode";
  		$anfrage = $DB->anfrage($sql, "s", $DB->getDatenbankname());
  		$anfrage->werte($groesse);
  	}
  	return $groesse;
  }
}

?>
