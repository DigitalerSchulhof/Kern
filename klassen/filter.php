<?php
namespace Kern;
use UI;

class Personenfilter extends UI\Eingabe {
  protected $tag = "div";

  protected $ziel;
  protected $autoaktualisierung;
  protected $knopf;
  protected $anzeigen;

  /**
   * Erstellt einen neuen Filter
   * @param int    $id    :)
   * @param string $ziel  Javascript-Funktion die ausgelöst wird
   * @param bool   $auto  Aktiviert die Aktualisierung des Ergebnisses bei Nutzereingabe wenn true
   */
  public function __construct($id, $ziel, $knopfart = "Hinzufügen", $auto = true) {
    parent::__construct($id);
    $this->ziel = $ziel;
    $this->autoaktualisierung = $auto;
    $this->id = $id;
    if ($knopfart == "Filter") {
      $this->knopf = new UI\MiniIconToggle("{$this->id}Anzeigen", "Hinzufügen", new UI\Icon(UI\Konstanten::NEU));
    } else {
      $this->knopf = new UI\Toggle("{$this->id}Anzeigen", "Filter");
      $this->knopf->addFunktion("onclick", "kern.filter.anzeigen('{$this->id}')");
    }
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

  public function __toString() : string {
    if ($this->anzeigen) {
      $this->knopf->setWert("1");
    }
    $code = new UI\Absatz($this->knopf);

    $arten = new UI\Multitoggle("dshPersonenFilterArten");
    $schueler = new UI\Toggle("dshPersonenFilterSchueler", "Schüler");
    $lehrer = new UI\Toggle("dshPersonenFilterLehrer", "Lehrer");
    $erziehungsberechtigte = new UI\Toggle("dshPersonenFilterErziehungsberechtigte", "Erziehungsberechtigte");
    $verwaltungsangestellte = new UI\Toggle("dshPersonenFilterVerwaltungsangestellte", "Verwaltungsangestellte");
    $externe = new UI\Toggle("dshPersonenFilterExterne", "Externe");

    $formular         = new UI\FormularTabelle();
    $vorname          = new UI\Textfeld("dshPersonenFilterVorname");
    $nachname         = new UI\Textfeld("dshPersonenFilterNachname");
    $klasse           = new UI\Textfeld("dshPersonenFilterKlasse");

    if ($this->autoaktualisierung) {
      $vorname->addFunktion("onkeyup", $this->ziel);
      $nachname->addFunktion("onkeyup", $this->ziel);
      $klasse->addFunktion("onkeyup", $this->ziel);
      $schueler->addFunktion("onclick", $this->ziel);
      $lehrer->addFunktion("onclick", $this->ziel);
      $erziehungsberechtigte->addFunktion("onclick", $this->ziel);
      $verwaltungsangestellte->addFunktion("onclick", $this->ziel);
      $externe->addFunktion("onclick", $this->ziel);
    }

    $arten->add($schueler);
    $arten->add($lehrer);
    $arten->add($erziehungsberechtigte);
    $arten->add($verwaltungsangestellte);
    $arten->add($externe);

    $formular[]       = new UI\FormularFeld(new UI\InhaltElement("Vorname:"),              $vorname);
    $formular[]       = new UI\FormularFeld(new UI\InhaltElement("Nachname:"),             $nachname);
    $formular[]       = new UI\FormularFeld(new UI\InhaltElement("Klasse:"),               $klasse);
    $formular[]       = new UI\FormularFeld(new UI\InhaltElement("Art des Nutzerkontos:"), $arten);


    $formular[]       = (new UI\Knopf("Suchen", "Erfolg"))  ->setSubmit(true);
    $formular         -> addSubmit($this->ziel);
    $formular         -> setID("{$this->id}A");
    if (!$this->anzeigen) {
      $formular       -> addKlasse("dshUiUnsichtbar");
    }

    $code            .= $formular;
    return $code;
  }



}
?>
