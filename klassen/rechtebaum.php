<?php
namespace Kern;
use UI;

class Rechtebaum extends \UI\Element {
  protected $tag = "div";

  /** @var array Vergebene Rechte Primär (Können verändert werden)*/
  private $rechteP;

  /** @var array Vergebene Rechte Sekundär (Sind nicht genommen oder vergeben werden)*/
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
    global $ROOT;
    $code  = $this->codeAuf();
    $allerechte = \unserialize(file_get_contents("$ROOT/core/rechte.core"));

    $rechteAus = function($rechte) use (&$rechteAus) {
      $i = 0;
      $code = "";
      foreach($rechte as $k => $w) {
        $hatKinder  = is_array($w);
        $unterstes  = ++$i === count($rechte);
        $knoten     = $k;
        $anzeige    = $w;
        if($hatKinder) {
          if(isset($w["_"])) {
            $anzeige = $w["_"];
            unset($w["_"]);
          } else {
            $anzeige = ucwords($k);
          }
        }

        $anzeige = new UI\Knopf("$anzeige");
        $anzeige ->addKlasse("dshRechtebaumKnoten");
        $hatKinder && $anzeige->setAttribut("data-knoten", "*");

        $inh = $anzeige;
        if($hatKinder) {
          $box = new UI\InhaltElement();
          $box ->setTag("div");
          $box ->setAttribut("a");
          $box ->addKlasse("dshRechtebaumBox");
          $box ->setInhalt($rechteAus($w));
          $inh .= $box;
        }
        $k = new UI\InhaltElement($inh);
        $k ->setTag("div");
        $k ->setAttribut("b");
        $hatKinder && $k->addKlasse("dshRechtebaumHatKinder");
        $unterstes && $k->addKlasse("dshRechtebaumUnterstes");
        $k ->setAttribut("data-knoten", $knoten);
        $code .= $k;
      }
      return $code;
    };
    $r = array();
    foreach($allerechte as $w) {
      foreach($w as $k => $p) {
        $r[$k] = $p;
      }
    }
    $code .= "<div class=\"dshRechtebaumBox dshRechtebaumUnterstes\">";
      $code .= $rechteAus($r, "");
    $code .= "</div>";

    $code .= $this->codeZu();
    return $code;
  }
}

?>