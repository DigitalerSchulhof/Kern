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

    $rechteAus = function($rechte, $pfad = "") use (&$rechteAus) {
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
          $knoten = "*";
        }

        $knopf = new UI\Knopf("$anzeige");
        $knopf ->addFunktion("onclick", "kern.schulhof.verwaltung.personen.rechtgeben('$pfad.$knoten')");

        $anzeige = new UI\InhaltElement($knopf);
        $anzeige ->setTag("div");

        $box = new UI\InhaltElement();
        $box ->setTag("div");
        $box ->addKlasse("dshRechtebaumBox");
        $unterstes && $box->addKlasse("dshRechtebaumUnterstes");
        $hatKinder && $anzeige->addKlasse("dshRechtebaumHatKinder");
        if($hatKinder) {
          $box ->setInhalt($anzeige.$rechteAus($w, "$pfad.$knoten"));
        } else {
          $box ->setInhalt($anzeige);
        }

        $code .= $box;
      }
      return $code;
    };
    $code .= $rechteAus($allerechte);

    $code .= $this->codeZu();
    return $code;
  }
}

?>