<?php
namespace Kern;

class Rechtehelfer {
  private const CHECK = true;

  // Instanziierung unterbinden
  private function __construct() { }

  /**
   * Prüft, ob das Recht in der übergebenen Rechteliste vorhanden ist.
   * Die Definition eines Rechts findet sich hier: <a href="https://gist.github.com/jeengbe/b78d01fb68972e51335ba9696206aa50">https://gist.github.com</a>
   * @link https://gist.github.com/jeengbe/b78d01fb68972e51335ba9696206aa50
   * @param mixed   $rechte Rechtebaum der Rechte, die vergeben sind
   * @param  string $recht  Das Recht
   * @return bool   true, wenn das Recht vorhanden ist, false sonst
   */
  public static function hatRecht($rechte, $recht) : bool {
    if(self::CHECK) {
      if(preg_match("/^(?:(?:[a-zäöüß]+|\\[[\\|&](?:[a-zäöüß]+,)+[a-zäöüß]+\\])\\.)*(?:[a-zäöüß]+|\\[[\|&](?:[a-zäöüß]+,)+[a-zäöüß]+\\])$/i", $recht) !== 1) {
        trigger_error("Das zu testende Recht »{$recht}« ist ungültig!", E_USER_WARNING);
      }
    }

    if($rechte === true) {
      return true;
    }
    if(!count($rechte)) {
      return false;
    }

    $recht = explode(".", $recht);

    $checkRecht = function($rechte, $recht) use (&$checkRecht) {
      foreach($recht as $i => $ebene) {
        if(preg_match("/^[a-zäöüß]+$/i", $ebene) === 1) {
          // Rechtebezeichnung
          if(in_array($ebene, array_keys($rechte))) {
            if($rechte[$ebene] === true) {
              return true;
            }
          } else {
            // Recht definitiv nicht gesetzt
            return false;
          }
          $rechte = $rechte[$ebene];
          continue;
        }
        if(preg_match("/^\\[[\\|&](?:[a-zäöüß]+,)*[a-zäöüß]+\\]$/i", $ebene) === 1) {
          // Rechteoder / -und
          $checks = explode(",", substr(substr($ebene, 0, -1), 2));
          // Restliche Ebenenen zu prüfen
          $rest = $recht;
          for($x = 0; $x <= $i; $x++) {
            \array_shift($rest);
          }
          if($ebene[1] === "|") {
            // Rechteoder
            foreach($checks as $c) {
              if($checkRecht($rechte, array_merge([$c], $rest))) {
                return true;
              }
            }
            return false;
          } else if($ebene[1] === "&") {
            // Rechteund
            foreach($checks as $c) {
              if(!$checkRecht($rechte, array_merge([$c], $rest))) {
                return false;
              }
            }
            return true;
          }
        }
      }
      throw new Exception("Rechtecheck weiter fortgeschritten als eigentlich mögich");
    };

    return $checkRecht($rechte, $recht);
  }
}


?>