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

  /** @var string[] URL für die Brotkrumen */
  protected $brotkrumen_url;

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
    $this->brotkrumen_url = $DSH_URL;
    $this->setID("dshAktionszeile");
  }

  /**
   * Setzt die Basis für die Brotkrumen
   * @param string|string[] $url Wenn string[], dann als Array gewertet, ansonsten als ... gewertet
   * @return self
   */
  public function setBrotkrumenURL(...$url) : self {
    if(count($url) === 1 && is_array($url[0])) {
      $url = $url[0];
    }
    $this->brotkrumen_url = $url;
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
    $brotkrumen = "<span id=\"dshBrotkrumen\">";
    if($this->brotkrumen) {
      $pfad = "";
      foreach($this->brotkrumen_url as $i => $segment) {
        if($i > 0) {
          $brotkrumen .= " / ";
        }
        $segmentanzeige = str_replace("_", " ", $segment);
        $el = new UI\InhaltElement("$segmentanzeige");
        $el->getAktionen()->addFunktion("href", "$pfad$segment");
        $el->setTag("a");
        $el->setAttribut("tabindex", "0");
        $brotkrumen .= $el;
        $pfad .= "$segment/";
      }
      $brotkrumen .= "</span>";
    }

    $aktionsicons = "";
    if($this->aktionsicons || true) {
      $angebote = Core\Angebote::angeboteFinden("Kern/Aktionsicons");
      foreach($angebote as $angebot){
        $aktionsicons .= $angebot;
      }
    }

    return "{$this->codeAuf()}$brotkrumen$aktionsicons{$this->codeZu()}";
  }
}

?>
