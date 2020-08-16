<?php
namespace Kern;

class Rechtehelfer {
  /** @var bool Sollen Rechte geprüft und bei ungültigen Rechten Fehler ausgegeben werden? */
  public const CHECK = true;

  // Instanziierung unterbinden
  private function __construct() { }

  /**
   * Prüft, ob das Recht in der übergebenen Rechteliste vorhanden ist.
   * Die Definition eines Rechts findet sich hier: <a href="https://gist.github.com/jeengbe/b78d01fb68972e51335ba9696206aa50">https://gist.github.com</a>
   * @link https://gist.github.com/jeengbe/b78d01fb68972e51335ba9696206aa50
   * @param array|bool   $rechte Rechtebaum der Rechte, die vergeben sind
   * @param string $recht  Das Recht
   * @return bool   true, wenn das Recht vorhanden ist, false sonst
   */
  public static function hatRecht($rechte, $recht) : bool {
    if(self::CHECK) {
      if(preg_match("/^(?:(?:[a-zäöüß]+|\\[[\\|&](?:[a-zäöüß]+,)+[a-zäöüß]+\\])\\.)*(?:[a-zäöüß]+|\\[[\\|&](?:[a-zäöüß]+,)+[a-zäöüß]+\\])$/i", $recht) !== 1) {
        trigger_error("Das zu testende Recht »{$recht}« ist ungültig!", E_USER_WARNING);
      }
    }

    if($rechte === true || $rechte === false) {
      return $rechte;
    }
    if(!count($rechte)) {
      return false;
    }

    $recht = explode(".", $recht);

    $checkRecht = function($rechte, $recht) use (&$checkRecht) {
      $rest = $recht;
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
          array_unshift($rest);
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

  /**
   * Nimmt ein Array an Rechten, gibt den passenden Rechtebaum zurück
   * @param  string[] $array Array an Rechten
   * @return array|bool
   */
  public static function array2Baum($array) {
    $baum = array();
    foreach($array as $recht) {
      if(self::CHECK) {
        if(preg_match("/^(?:[a-zäöüß]+\\.)*(?:[a-zäöüß]+|\\*)$/i", $recht) !== 1) {
          trigger_error("Das übergebene Recht »{$recht}« ist ungültig", E_USER_WARNING);
        }
      }
      $recht = explode(".", $recht);
      $baumTeil = &$baum;
      for($i = 0; $i < count($recht); $i++) {
        if($recht[$i] === "*" || $i === count($recht) - 1) {
          if($recht[$i] === "*") {
            $baumTeil = true;
          } else {
            $baumTeil[$recht[$i]] = true;
          }
        } else {
          $baumTeil = &$baumTeil[$recht[$i]];
        }
      }
    }
    return $baum;
  }

  /**
   * Nimmt einen Rechtebaum und sortiert diesen so, dass weniger komplexe Rechte (Pfade, die früh mit .* enden) weiter oben sind
   * @param  array $baum :)
   * @return array|bool Sortierter Rechtebaum
   */
  public static function baumSortieren($baum) {
    if($baum === true || $baum === false) {
      return $baum;
    }

    $sort = function(&$baum) use (&$sort) {
      uasort($baum, function($a, $b) use (&$sort) {
        if($a === true) {
          return -1;
        }
        if($b === true) {
          return 1;
        }
        $sort($a);
        $sort($b);
        return $a <=> $b;
      });
    };

    $sort($baum);

    die();
  }
}


?>