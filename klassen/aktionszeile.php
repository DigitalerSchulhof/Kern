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

  /** @var bool $aktionen Ob Aktionsicons auszugeben sind */
  protected $aktionsicons;

  /**
   * Erzeugt eine neue Aktionszeile
   * @param boolean $brotkrumen Ob Brotkrumen auszugeben sind
   * @param boolean $aktionen Ob Aktionsicons auszugeben sind
   */
  public function __construct($brotkrumen = true, $aktionsicons = true) {
    parent::__construct();
    $this->brotkrumen = $brotkrumen;
    $this->aktionsicons   = $aktionsicons;
    $this->setID("dshAktionszeile");
  }

  /**
   * Setzt, ob Brotkrumen auszugeben sind
   * @param bool $brotkrumen :)
   * @return self
   */
  public function setBrotkrumen($brotkrumen) : self {
    $this->brotkrumen = $brotkrumen;
  }

  public function __toString() : string {
    global $DSH_URL;
    $brotkrumen = "";
    if($this->brotkrumen) {
      $pfad = "";
      foreach($DSH_URL as $i => $segment) {
        if($i > 0) {
          $brotkrumen .= " / ";
        }
        $el = new UI\InhaltElement("$segment");
        $el->getAktionen()->addFunktion("href", "$pfad$segment");
        $el->setTag("a");
        $brotkrumen .= $el;
        $pfad .= "$segment/";
      }
    }

    $aktionsicons = "";
    if($this->aktionsicons) {
      $angebote = Core\Angebote::angeboteFinden("Kern/Aktionsicons");
      foreach($angebote as $angebot){
        $aktionsicons .= $angebot;
      }
    }

    return "{$this->codeAuf()}$brotkrumen$aktionsicons{$this->codeZu()}";
  }
}

?>