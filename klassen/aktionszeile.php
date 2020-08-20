<?php
namespace Kern;
use UI;
use Core;

/**
 * Die Aktionszeile befindet sich auf der Seite ganz oben. Sie enthält die Brotkrumen und Aktionsicons
 */
class Aktionszeile extends UI\Element {
  protected $tag = "p";

  /** @var bool $brotkrumen Ob Brotkrumen auszugeben sind */
  protected $brotkrumen;

  /** @var string[] Assoziatives Array für Brotkrumen [href => Angezeigter Text] */
  protected $brotkrumen_pfad;

  /** @var bool $aktionen Ob Aktionsicons auszugeben sind */
  protected $aktionsicons;

  /**
   * Erzeugt eine neue Aktionszeile
   * @param boolean $brotkrumen Ob Brotkrumen auszugeben sind
   * @param boolean $aktionen Ob Aktionsicons auszugeben sind
   */
  public function __construct($brotkrumen = true, $aktionsicons = true) {
    global $DSH_URL;
    parent::__construct();
    $this->brotkrumen     = $brotkrumen;
    $this->aktionsicons   = $aktionsicons;
    $this->brotkrumen_url = null;
    $this->setID("dshAktionszeile");
  }

  /**
   * Setzt die Basis für die Brotkrumen
   * @param string[] $pfad Nimmt ein assoziatives Array [href => Angezeigter Text]
   * @return self
   */
  public function setBrotkrumenPfad($pfad) : self {
    $this->brotkrumen_pfad = $pfad;
    return $this;
  }

  /**
   * Setzt, ob Brotkrumen auszugeben sind
   * @param bool $brotkrumen :)
   * @return self
   */
  public function setBrotkrumen($brotkrumen) : self {
    $this->brotkrumen = $brotkrumen;
    return $this;
  }

  /**
   * Setzt, ob Aktionsicons auszugeben sind
   * @param bool $aktionsicons :)
   * @return self
   */
  public function setAktionsicons($aktionsicons) : self {
    $this->aktionsicons = $aktionsicons;
    return $this;
  }

  public function __toString() : string {
    $brotkrumen = "";
    if($this->brotkrumen) {
      $brotkrumen .= "<span id=\"dshBrotkrumen\">";
      $pfad = "";
      if($this->brotkrumen_pfad === null) {
        global $DSH_URL;
        foreach($DSH_URL as $i => $segment) {
          if($i > 0) {
            $brotkrumen .= " / ";
          }
          $el = new UI\InhaltElement(str_replace("_", " ", $segment));
          $el->addFunktion("href", "$pfad$segment");
          $el->setTag("a");
          $el->setAttribut("tabindex", "0");
          $brotkrumen .= $el;
          $pfad .= "$segment/";
        }
      } else {
        $i = 0;
        foreach($this->brotkrumen_pfad as $href => $text) {
          if($i++ > 0) {
            $brotkrumen .= " / ";
          }
          $el = new UI\InhaltElement("$text");
          $el->addFunktion("href", "$href");
          $el->setTag("a");
          $el->setAttribut("tabindex", "0");
          $brotkrumen .= $el;
        }
      }
      $brotkrumen .= "</span>";
    }


    $aktionsicons = "";
    if($this->aktionsicons || true) {
      $angebote = Core\Angebote::finden("Kern/Aktionsicons");
      foreach($angebote as $angebot){
        $aktionsicons .= $angebot;
      }
    }

    return "{$this->codeAuf()}$brotkrumen$aktionsicons{$this->codeZu()}";
  }
}

?>
