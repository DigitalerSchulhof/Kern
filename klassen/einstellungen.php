<?php
namespace Kern;
use UI;

class Einstellungen {
  /**
   * Gibt den Einstellungswert zurück
   * @param  string $modul das gesuchte Modul
   * @param  string $wert der gesuchte Wertkey
   * @return string Wert der gesuchten Einstellung
   */
  public static function laden($modul, $wert) : string {
    // Falsches Modul
    $modul = strtolower($modul);
    if (!UI\Check::istLatein($modul)) {
      throw new \Exception("Ungültiges Modul");
    }

    global $DBS;
    $anfrage = $DBS->anfrage("SELECT {wert} AS wert FROM {$modul}_einstellungen WHERE inhalt = [?]", "s", $wert);
    $anfrage->werte($r);
    return $r ?? "";
  }

  /**
   * Gibt alle Einstellungswerte eines Moduls zurück
   * @param  string $modul das gesuchte Modul
   * @return array Alle Einstellungen dieses Moduls array[key] = wert
   */
  public static function ladenAlle($modul) : array {
    // Falsches Modul
    $modul = strtolower($modul);
    if (!UI\Check::istLatein($modul)) {
      throw new \Exception("Ungültiges Modul");
    }

    global $DBS;
    $anfrage = $DBS->anfrage("SELECT {inhalt} AS inhalt, {wert} AS wert FROM {$modul}_einstellungen");
    $arr = [];
    while ($anfrage->werte($i, $w)) {
      $arr[$i] = $w;
    }
    return $arr;
  }


}

?>
