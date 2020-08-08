<?php
namespace Kern;

class Seite {
  /** @var string Titel der Seite */
  protected $titel;
  /** @var mixed false = keine Anmeldung nötig
  *              null  = Anmeldung aber kein sonstiges Recht nötig
  *              string = Recht das benötigt wird */
  protected $recht;
  /** @var UI\Zeile[] Titel der Seite */
  protected $zeilen;
  /** @var bool Gibt an, ob die Seite eine Aktionszeile besitzt */
  protected $aktionszeile;

  /**
   * Erzeugt eine neue Seite, checkt Zugriffsrecht und reagiert entsprechend
   * @param string  $titel        Titel der Seite
   * @param mixed   $recht        false = keine Anmeldung nötig
   *                              null  = Anmeldung aber kein sonstiges Recht nötig
   *                              string = Recht das benötigt wird
   * @param bool    $aktionszeile true = Aktionszeile ausgeben, false sonst
   */
  public function __construct($titel, $recht = null, $aktionszeile = true) {
    $this->titel = $titel;
    $this->recht = $recht;
    $this->zeilen = [];
    $this->aktionszeile = $aktionszeile;

    if ($recht !== false) {
      if(!Check::angemeldet()) {
        einbinden("Schulhof/Anmeldung");
        \Anfrage::setTyp("Seite");
        \Anfrage::setRueck("Titel",  $DSH_TITEL);
        \Anfrage::setRueck("Code",   $CODE);
        \Anfrage::ausgeben();
        die;
      }

      if ($recht !== null) {
        Check::verboten($recht);
      }
    }
  }

  /**
   * Setzt das Aktionszeilen-Attribut auf den angegebenen Wert
   * @param  bool $aktionszeile :)
   * @return self               :)
   */
  public function setAktionszeile ($aktionszeile) : self {
    $this->aktionszeile = $aktionszeile;
    return $this;
  }

  /**
   * Fügt der Seite eine neue Zeile hinzu
   * @param  UI\Zeile $zeile :)
   * @return self            :)
   */
  public function addZeile($zeile) : self {
    $this->zeilen[] = $zeile;
    return $this;
  }

  /**
   * Gibt die Seite als String aus
   * @return string :)
   */
  public function __toString() : string {
    $code = "";
    if ($this->aktionszeile) {
      $code .= new Aktionszeile();
    }
    foreach ($zeilen as $z) {
      $code .= $z;
    }
    return $code;
  }

  /**
   * Gibt den Titel der Seite zurück
   * @return string :)
   */
  public function getTitel() : string {
    return $this->titel;
  }
}
?>
