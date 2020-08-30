<?php
namespace Kern;
use UI;

abstract class Filter extends UI\Eingabe {
  protected $tag = "div";

  /** @var string JS-Filtermethode - ohne () */
  protected $ziel;
  /** @var bool Knopf zum Ein- und Ausblenden des Filters */
  protected $knopf;
  /** @var bool Anfangs angezeigt, wenn true, sonst false */
  protected $anzeigen;

  /**
   * Erstellt einen neuen Filter
   * @param int    $id    :)
   * @param string $ziel  Javascript-Funktion die ausgelöst wird
   * @param bool   $auto  Aktiviert die Aktualisierung des Ergebnisses bei Nutzereingabe wenn true
   */
  public function __construct($id, $ziel, $knopfart) {
    parent::__construct($id);
    $this->ziel = $ziel;
    $this->id = $id;
    if ($knopfart == "Hinzufügen") {
      $this->knopf = new UI\MiniIconToggle("{$this->id}Anzeigen", "Hinzufügen", new UI\Icon(UI\Konstanten::NEU));
    } else {
      $this->knopf = new UI\Toggle("{$this->id}Anzeigen", "Filter");
    }
    $this->knopf->addFunktion("onclick", "kern.filter.anzeigen('{$this->id}')");
    $this->anzeigen = false;
  }

  /**
   * Setzt den Wert Anzeigen
   * @param  bool $anzeigen :)
   * @return self           :)
   */
  public function setAnzeigen($anzeigen) : self {
    $this->anzeigen = $anzeigen;
    return $this;
  }
}

class Personenfilter extends Filter {
  /**
   * Erstellt einen neuen Filter
   * @param int    $id    :)
   * @param string $ziel  Javascript-Funktion die ausgelöst wird
   * @param bool   $auto  Aktiviert die Aktualisierung des Ergebnisses bei Nutzereingabe wenn true
   */
  public function __construct($id, $ziel, $knopfart = "Hinzufügen") {
    parent::__construct($id, $ziel, $knopfart);
  }

  public function __toString() : string {
    global $DSH_ALLEMODULE;

    $arten = new UI\Multitoggle("{$this->id}Arten");
    $schueler = new UI\Toggle("{$this->id}ArtenSchueler", "Schüler");
    $lehrer = new UI\Toggle("{$this->id}ArtenLehrer", "Lehrer");
    $erziehungsberechtigte = new UI\Toggle("{$this->id}ArtenErziehungsberechtigte", "Erziehungsberechtigte");
    $verwaltungsangestellte = new UI\Toggle("{$this->id}ArtenVerwaltungsangestellte", "Verwaltungsangestellte");
    $externe = new UI\Toggle("{$this->id}ArtenExterne", "Externe");

    $vorname          = new UI\Textfeld("{$this->id}Vorname");
    $nachname         = new UI\Textfeld("{$this->id}Nachname");
    $klasse           = new UI\Textfeld("{$this->id}Klasse");
    $vorname->setPlatzhalter("Vorname");
    $nachname->setPlatzhalter("Nachname");
    $klasse->setPlatzhalter("Klasse");

    $vorname->addFunktion("oninput", $this->ziel);
    $nachname->addFunktion("oninput", $this->ziel);
    $klasse->addFunktion("oninput", $this->ziel);
    $schueler->addFunktion("onclick", $this->ziel);
    $lehrer->addFunktion("onclick", $this->ziel);
    $erziehungsberechtigte->addFunktion("onclick", $this->ziel);
    $verwaltungsangestellte->addFunktion("onclick", $this->ziel);
    $externe->addFunktion("onclick", $this->ziel);

    $arten->add($schueler);
    $arten->add($lehrer);
    $arten->add($erziehungsberechtigte);
    $arten->add($verwaltungsangestellte);
    $arten->add($externe);

    $felder  = new UI\Absatz($vorname);
    $felder .= new UI\Absatz($nachname);
    $felder .= new UI\Absatz($vorname);
    $a = new UI\Absatz($klasse);
    if (!in_array("Gruppen", array_keys($DSH_ALLEMODULE))) {
      $a->addKlasse("dshUiUnsichtbar");
    }
    $felder .= $a;
    $felder .= new UI\Absatz($arten);

    $code = new UI\Absatz($this->knopf);
    $felder = new UI\InhaltElement($felder);
    $felder->setTag("div");
    $felder->setID("{$this->id}A");
    $felder->addKlasse("dshUiFilter");
    if (!$this->anzeigen) {
      $felder->addKlasse("dshUiUnsichtbar");
    } else {
      $this->knopf->setWert("1");
    }
    return $code.$felder;
  }

}


class Personenwahl extends Filter {
  /**
   * Erstellt einen neuen Filter
   * @param int    $id    :)
   * @param string $ziel  Javascript-Funktion die ausgelöst wird
   * @param bool   $auto  Aktiviert die Aktualisierung des Ergebnisses bei Nutzereingabe wenn true
   */
  public function __construct($id, $ziel, $knopfart = "Hinzufügen") {
    parent::__construct($id, $ziel, $knopfart);
  }

  public function __toString() : string {
    global $DSH_ALLEMODULE;

    $arten = new UI\Multitoggle("{$this->id}Arten");
    $schueler = new UI\Toggle("{$this->id}ArtenSchueler", "Schüler");
    $lehrer = new UI\Toggle("{$this->id}ArtenLehrer", "Lehrer");
    $erziehungsberechtigte = new UI\Toggle("{$this->id}ArtenErziehungsberechtigte", "Erziehungsberechtigte");
    $verwaltungsangestellte = new UI\Toggle("{$this->id}ArtenVerwaltungsangestellte", "Verwaltungsangestellte");
    $externe = new UI\Toggle("{$this->id}ArtenExterne", "Externe");

    $vorname          = new UI\Textfeld("{$this->id}Vorname");
    $nachname         = new UI\Textfeld("{$this->id}Nachname");
    $vorname->setPlatzhalter("Vorname");
    $nachname->setPlatzhalter("Nachname");

    $vorname->addFunktion("oninput", $this->ziel);
    $nachname->addFunktion("oninput", $this->ziel);
    $schueler->addFunktion("onclick", $this->ziel);
    $lehrer->addFunktion("onclick", $this->ziel);
    $erziehungsberechtigte->addFunktion("onclick", $this->ziel);
    $verwaltungsangestellte->addFunktion("onclick", $this->ziel);
    $externe->addFunktion("onclick", $this->ziel);

    $arten->add($schueler);
    $arten->add($lehrer);
    $arten->add($erziehungsberechtigte);
    $arten->add($verwaltungsangestellte);
    $arten->add($externe);

    $felder  = new UI\Absatz($vorname);
    $felder .= new UI\Absatz($nachname);
    $felder .= new UI\Absatz($arten);

    $code = new UI\Absatz($this->knopf);
    $suchergebnisse = new UI\InhaltElement(new UI\Notiz("Suche ohne Ergebnis"));
    $suchergebnisse->setTag("div");
    $suchergebnisse->setID("{$this->id}Suchergebnisse");
    $suchergebnisse->addKlasse("dshUiSuchergebnisse");

    $felder = new UI\InhaltElement($felder.$suchergebnisse);
    $felder->setTag("div");
    $felder->setID("{$this->id}A");
    $felder->addKlasse("dshUiFilter");
    if (!$this->anzeigen) {
      $felder->addKlasse("dshUiUnsichtbar");
    } else {
      $this->knopf->setWert("1");
    }
    return $code.$felder;
  }
}
?>
