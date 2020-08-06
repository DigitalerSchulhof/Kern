<?php
namespace Kern\Verwaltung;

class Kategorie {
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
    global $KATEGORIEN;
    $this->name     = $name;
    $this->titel    = $titel;
    $this->elemente = [];
    $KATEGORIEN[]   = $this;
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
}

class Element {
  /** @var \UI\Icon*/
  private $icon;
  /** @var string */
  private $name;
  /** @var string */
  private $beschreibung;
}

?>