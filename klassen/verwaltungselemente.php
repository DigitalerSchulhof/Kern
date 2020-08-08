<?php
namespace Kern\Verwaltung;
use UI;

class Liste {
  /** @var Kategorie[] [Interner Name] => Kategorie */
  private static $kategorien = array();

  private function __construct() {}

  /**
   * Fügt eine oder mehrere Verwaltungskategorien hinzu, sofern keine Kategorie mit dem jeweiligen internen Namen schon existiert.
   * @param Kategorie ...$kategorien :)
   * @return Kategorie Die erste übergebene Kategorie
   */
  public static function addKategorie(...$kategorien) : Kategorie {
    foreach($kategorien as $kategorie) {
      if(!isset(self::$kategorien[$kategorie->getName()])) {
        self::$kategorien[$kategorie->getName()] = $kategorie;
      }
    }
    return $kategorien[0];
  }

  /**
   * Gibt die Kategorie zu einem internen Namen zurück.
   * @param  string                     $name :)
   * @return Kern\Verwaltung\Kategorie
   */
  public static function getKategorie($name) : Kategorie {
    return self::$kategorien[$name];
  }

  /**
   * Gibt alle registrierten Kategorien zurück
   * @return Kategorie[]
   */
  public static function getKategorien() : array {
    return self::$kategorien;
  }
}

class Kategorie implements \ArrayAccess {
  /** @var Element[] Zugeordngete Verwaltungselemente */
  private $elemente;

  /** @var string Der Interne Name, mithilfe dessen die Kategorie erreicht werden kann */
  private $name;
  /** @var string Der angezeigte Titel */
  private $titel;


  /**
   * Erstellt eine neue Verwaltungskategorie
   * @param string $name  Der Interne Name, mithilfe dessen die Kategorie erreicht werden kann
   * @param string $titel Der angezeigte Titel
   */
  public function __construct($name, $titel) {
    $this->name     = $name;
    $this->titel    = $titel;
    $this->elemente = [];
  }

  /**
   * Gibt den Namen zurück
   * @return string
   */
  public function getName() : string {
    return $this->name;
  }

  /**
   * Gibt den Titel zurück
   * @return string
   */
  public function getTitel() : string {
    return $this->titel;
  }

  /**
   * Gibt alle registrierten Verwaltungselemente zurück
   * @return Element[]
   */
  public function getElemente() : array {
    return $this->elemente;
  }

  /*
   * ArrayAccess Methoden
   */

  public function offsetSet($o, $v) {
    if(!($v instanceof Element)) {
      throw new \TypeError("Das übergebene Element ist nicht vom Typ \\Kern\\Verwaltung\\Element");
    }
    if(!is_null($o)) {
      throw new \Exception("Nicht implementiert!");
    }
    $this->elemente[]   = $v;
  }

  public function offsetExists($o) {
    throw new \Exception("Nicht implementiert!");
  }

  public function offsetUnset($o) {
    throw new \Exception("Nicht implementiert!");
  }

  public function offsetGet($o) {
    throw new \Exception("Nicht implementiert!");
  }
}

class Element extends UI\Link {
  protected $tag = "a";

  /** @var \UI\Icon*/
  private $icon;
  /** @var string */
  private $name;
  /** @var string */
  private $beschreibung;

  /**
   * Legt ein neues Verwaltungselement an
   * @param string $name         :)
   * @param string $beschreibung :)
   * @param \UI\Icon $icon         :)
   * @param string $ziel Ziel des Links
   * @param bool $fortgeschritten Setzt das Verwaltungselement Gymnasialniveau voraus?
   */
  public function __construct($name, $beschreibung, $icon, $ziel, $fortgeschritten = false) {
    parent::__construct(null, $ziel, false);
    $this->name             = $name;
    $this->beschreibung     = $beschreibung;
    $this->icon             = $icon;
    $this->addKlasse("dshVerwaltungsElement");
    if($fortgeschritten) {
      $this->addKlasse("dshVerwaltungsElementFortgeschritten");
    }
  }

  public function __toString() : string {
    $name = new UI\Ueberschrift("4", $this->name);
    return "{$this->codeAuf()}{$this->icon}$name{$this->codeZu()}";
  }
}

?>