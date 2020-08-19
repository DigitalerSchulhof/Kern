<?php
namespace Kern;

class Rechtebaum extends \UI\Element {
  protected $tag = "div";

  /** @var array Vergebene Rechte Primär (Können verändert werden)*/
  private $rechteP;

  /** @var array Vergebene Rechte Sekundär (Sind statisch)*/
  private $rechteS;

  /**
   * Erzeugt einen neuen Rechtebaum
   * @param string $id :)
   * @param array $rechteP Primär vergebene Rechte
   *  Rechte werden als Toggle ausgegeben.
   * @param array $rechteS Sekundär vergebene Rechte
   *  Rechte werden als "Information"-Knopf ausgegeben.
   */
  public function __construct($id, $rechteP, $rechteS) {
    parent::__construct();
    $this->id       = $id;
    $this->rechteP  = $rechteP;
    $this->rechteS  = $rechteS;
  }

  public function __toString() : string {
    $code  = $this->codeAuf();
    $code .= \var_export($this->rechteP, true);
    $code .= \var_export($this->rechteS, true);
    $code .= $this->codeZu();
    return $code;
  }
}

?>