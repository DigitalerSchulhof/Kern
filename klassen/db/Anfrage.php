<?php
namespace DB;

/**
* Eine Datenbankanfrage
*/
class Anfrage implements \Iterator {
  /** @var int Enthält die Anzahl an beeinflussten Zeilen */
  private $anzahl;
  /** @var array Enthält das Ergebnis */
  private $ergebnis;
  /** @var int Position des Iterators */
  private $position;
  /** @var int Position der Werterückgabe */
  private $positionWerte;

	/**
	* @param int $anzahl Anzahl an Ergebnissen (affected_rows)
	* @param array $ergebnis Die Ergebnisse
	*/
  public function __construct($anzahl = 0, $ergebnis = array()) {
  	$this->anzahl = $anzahl;
    $this->ergebnis = $ergebnis;
    $this->position = 0;
    $this->positionWerte = 0;
  }

  /**
	* @return int gibt zurück, wie viele Datenreihen verändert wurden
	*/
  public function getAnzahl() : int {
    return $this->anzahl;
  }

  /**
	* @return array gibt die Ergebnisse der Anfrage zurück
	*/
  public function getErgebnis() : array {
    return $this->ergebnis;
  }

  /**
  * Nimmt Variablen, an die die Anfragwerte gebunden werden
  * @param mixed... $werte Die Variablen
  */
  public function werte(&...$werte) {
    if(!count($this->ergebnis) || $this->positionWerte == count($this->ergebnis)) {
      return false;
    }
    if(count($werte) == count($this->ergebnis[0])) {
      foreach($werte as $i => $w) {
        $werte[$i] = $this->ergebnis[$this->positionWerte][$i];
      }
    } else {
      throw new \Exception("Werteangabe stimmt nicht überein");
    }
    $this->positionWerte++;
    return true;
  }

  /**
  * Iterable-Interface
  */
  public function rewind() {
    $this->position = 0;
  }

  public function current() {
    return $this->ergebnis[$this->position];
  }

  public function key() : int {
    return $this->position;
  }

  public function next() {
    $this->position++;
  }

  public function valid() : bool {
    return isset($this->ergebnis[$this->position]);
  }

}
?>
