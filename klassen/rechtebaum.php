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

    $istVergeben = function($rechte, $recht) {
      if($recht === "*" && $rechte === true) {
        return true;
      }
      if($rechte === false) {
        return false;
      }

    };
    $knopfid = 0;
    $rechteAus = function($rechte, $pfad = "", $vergeben = false) use (&$rechteAus, $istVergeben, &$knopfid) {
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
          $knoten .= ".*";
        }

        if(substr("$pfad.$knoten", 0, 2) === "..") {
          $r = substr("$pfad.$knoten", 2);
        } else {
          $r = "$pfad.$knoten";
        }


        // Prüfen, ob Recht vergeben ist
        if($vergeben === false) {
          if($istVergeben($this->rechteS, $r)) {
            $vergeben = 1;
          } else if($istVergeben($this->rechteP, $r)) {
            $vergeben = 2;
          }
        }
        if($vergeben === 1) {
          $knopf = new UI\Knopf("$anzeige", "Information");
        } else {
          ++$knopfid;
          $knopf = new UI\Toggle("dshRechtebaum{$this->id}$knopfid", "$anzeige");
          $knopf ->setToggled($vergeben !== false);
          $knopf ->addFunktion("onclick", "kern.schulhof.verwaltung.personen.rechtclick(this)");
        }

        $anzeige = new UI\InhaltElement($knopf);
        $anzeige ->setTag("div");
        $anzeige ->addKlasse("dshRechtebaumRecht");
        
        $box = new UI\InhaltElement();
        $box ->setTag("div");
        $box ->addKlasse("dshRechtebaumBox");
        $unterstes && $box->addKlasse("dshRechtebaumUnterstes");
        $hatKinder && $anzeige->addKlasse("dshRechtebaumHatKinder");
        if($hatKinder) {
          $box ->setInhalt($anzeige.$rechteAus($w, "$pfad.$k", $vergeben));
        } else {
          $box ->setInhalt($anzeige);
        }

        $code .= $box;
      }
      return $code;
    };
    $code .= $rechteAus(array("" => array_merge(array("_" => "Alle Rechte"), $allerechte)));

    $code .= $this->codeZu();
    return $code;
  }
}

?>