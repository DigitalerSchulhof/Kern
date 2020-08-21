<?php
namespace Kern;

class Rechtehelfer {
  /** @var bool Sollen Rechte geprüft und bei ungültigen Rechten Fehler ausgegeben werden? */
  public const CHECK = true;

  // Instanziierung unterbinden
  private function __construct() { }

  /**
   * Gibt zurück, ob das übergebene Recht ein gültiges Recht ist, welches vergeben werden kann
   * @param  string $recht :)
   * @return bool
   */
  public static function istRecht($recht) : bool {
    return is_string($recht) && preg_match("/^(?:[a-zäöüß]+\\.)*(?:[a-zäöüß]+|\\*)$/i", $recht) === 1;
  }

  /**
   * Prüft, ob das Recht in der übergebenen Rechteliste vorhanden ist.
   * @param array|bool   $rechte Rechtebaum der Rechte, die vergeben sind
   * @param string $recht  Das Recht
   * @return bool   true, wenn das Recht vorhanden ist, false sonst
   */
  public static function hatRecht($rechte, $recht) : bool {
    if(self::CHECK) {
      // if(preg_match("/^(?:(?:[a-zäöüß]+|\\[[\\|&](?:[a-zäöüß]+,)+[a-zäöüß]+\\])\\.)*(?:[a-zäöüß]+|\\[[\\|&](?:[a-zäöüß]+,)+[a-zäöüß]+\\])$/i", $recht) !== 1) {
      //   trigger_error("Das zu testende Recht »{$recht}« ist ungültig!", E_USER_WARNING);
      // }
    }

    /**
     * Prüft, ob ein einzelnes Recht gegeben ist
     */
    $checkRecht = function($rechte, $recht) use (&$checkRecht) {
      $recht = explode(".", $recht);
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
          if($rechte[$ebene] === false) {
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
      throw new \Exception("Rechtecheck weiter fortgeschritten als eigentlich mögich. Recht: »".join(".", $recht)."«");
    };

    $checkLogik = function($allerechte, $rechte) use (&$checkLogik, $checkRecht) {
      $ur = $rechte;
      // 1,50m Abstand
      $rechte = preg_replace("/(?:\\s*(\\(|\\)|\\&\\&|\\|\\|)\\s*)/", ' $1 ', $rechte);
      $rechte = explode(" ", $rechte);
      // Leere Einträge entfernen
      $rechte = array_diff($rechte, [""]);
      foreach($rechte as $i => $r) {
        if(preg_match("/^(?:(?:[a-zäöüß]+|\\[[\\|&](?:[a-zäöüß]+,)+[a-zäöüß]+\\])\\.)*(?:[a-zäöüß]+|\\[[\\|&](?:[a-zäöüß]+,)+[a-zäöüß]+\\])$/i", $r) === 1) {
          $rechte[$i] = $checkRecht($allerechte, $r);
        }
      };

      // Infinite-Loop Check
      $lc = 0;
      while(++$lc < 1000 && count(($rechte = array_values($rechte))) !== 1) {
        for($i = 0; $i < count($rechte); $i++) {
          if(!isset($rechte[$i])) {
            continue;
          }
          $r = $rechte[$i];
          switch($r) {
            case "(":
              $c = true;
              if(isset($rechte[$i+1]) && ($rechte[$i+1] === true || $rechte[$i+1] === false)) {
                if(isset($rechte[$i+2]) && ($rechte[$i+2] === "||" || $rechte[$i+2] === "&&")) {
                  if(isset($rechte[$i+3]) && ($rechte[$i+3] === true || $rechte[$i+3] === false)) {
                    if(isset($rechte[$i+4]) && ($rechte[$i+4] === ")")) {
                      if($rechte[$i+2] === "||") {
                        $rechte[$i+2] = $rechte[$i+1] || $rechte[$i+3];
                      } else if($rechte[$i+2] === "&&") {
                        $rechte[$i+2] = $rechte[$i+1] && $rechte[$i+3];
                      }
                      unset($rechte[$i  ]);
                      unset($rechte[$i+1]);
                      unset($rechte[$i+3]);
                      unset($rechte[$i+4]);
                      $c = false;
                      $i+=4;
                    }
                  }
                } else if(isset($rechte[$i+2]) && $rechte[$i+2] === ")") {
                  $rechte[$i] = $rechte[$i+1];
                  unset($rechte[$i  ]);
                  unset($rechte[$i+2]);
                }
              }
              if($c) {
                continue 2;
              }
              break;
            case "||":
              if(isset($rechte[$i-1]) && ($rechte[$i-1] !== true || $rechte[$i-1] !== false)) {
                continue 2;
              }
              if(isset($rechte[$i+1]) && ($rechte[$i+1] !== true || $rechte[$i+1] !== false)) {
                continue 2;
              }
              $rechte[$i] = $rechte[$i-1] || $rechte[$i+1];
              unset($rechte[$i-1]);
              unset($rechte[$i+1]);
              $i+=1;
              break;
            case "&&":
              if(!isset($rechte[$i-1]) || $rechte[$i-1] !== true || $rechte[$i-1] !== false) {
                continue 2;
              }
              if(!isset($rechte[$i+1]) || $rechte[$i+1] !== true || $rechte[$i+1] !== false) {
                continue 2;
              }
              $rechte[$i] = $rechte[$i-1] && $rechte[$i+1];
              unset($rechte[$i-1]);
              unset($rechte[$i+1]);
              $i+=1;
              break;
          }
        }
      }
      if($lc === 1000) {
        throw new \Exception("Das Prüfen eines Rechts braucht mindestens 1,000 Iterationen der Schleife!\n\tRecht: »{$ur}«\n\t\$rechte (JSON): ".json_encode($rechte));
        return false;
      }
      return $rechte[0];
    };

    if($rechte === true || $rechte === false) {
      return $rechte;
    }
    if(!count($rechte)) {
      return false;
    }

    return $checkLogik($rechte, "($recht)");
  }

  /**
   * Nimmt ein Array an Rechten, gibt den passenden Rechtebaum zurück
   * @param  string[] $array Array an Rechten
   * @return array|bool
   */
  public static function array2Baum($array) {
    $baum = array();
    if(self::CHECK) {
      foreach($array as $recht) {
        if(!self::istRecht($recht)) {
          trigger_error("Das übergebene Recht »{$recht}« ist ungültig", E_USER_WARNING);
        }
      }
    }
    foreach($array as $recht) {
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
      if($baum === true) {
        break;
      }
    }
    if($baum !== true && !count($baum)) {
      $baum = false;
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
    return $baum;
  }
}

?>