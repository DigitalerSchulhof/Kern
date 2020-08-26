<?php
/**
 * Gibt die Details eine Rolle aus, und lädt ggf. passende Rechte
 * @param  int|null $id Die ID der zu ladenden Rolle
 * @return
 */
function rollenDetails($id = null) : UI\Element {
  $formular         = new UI\FormularTabelle();

  if($id === null) {
    $rid = "dshNeueRolle";
  } else {
    $rid = "dshBearbeitenRolle$id";
  }

  $bezeichung = new UI\Textfeld("{$rid}Bezeichnung");

  if($id !== null) {
    global $DBS;
    $DBS->anfrage("SELECT {bezeichnung} FROM kern_rollen WHERE id = ?", "i", $id)->werte($bez);
    $bezeichung->setWert($bez);
  }

  global $DSH_MODULE;
  include_once("$DSH_MODULE/Kern/klassen/rechtebaum.php");
  include_once("$DSH_MODULE/Kern/klassen/rechtehelfer.php");

  $rechte = array();
  if($id !== null) {
    $anf = $DBS->anfrage("SELECT {recht} FROM kern_rollenrechte WHERE rolle = ?", "i", $id);
    while($anf->werte($recht)) {
      $rechte[] = $recht;
    }
    $rechte = Kern\Rechtehelfer::array2Baum($rechte);
  }

  $formular[]   = new UI\FormularFeld(new UI\InhaltElement("Bezeichnung:"), $bezeichung);
  $rechtebox    = (new UI\Box())->setId("{$rid}Rechtebox");
  $rechtebox[]  = new Kern\Rechtebaum("{$rid}Rechtebaum", $rechte);
  $rechtebox[]  = "<br>";
  $rechtebox[]  = new UI\Notiz("Das Vergeben eines Rechts vergibt zugleich alle untergeordneten Rechte.");
  $rechtebox[]  = (new UI\Knopf("Nicht vergeben",  "Standard"))->addFunktion("onclick", null)." ";
  $rechtebox[]  = (new UI\Knopf("Vergeben",        "Erfolg"))->addFunktion("onclick", null)." ";
  $formular[]   = new UI\FormularFeld(new UI\InhaltElement("Rechte:"),  $rechtebox);

  if($id === null) {
    $formular[] = (new UI\Knopf("Neue Rolle anlegen", "Erfolg"))          ->setSubmit(true);
    $formular   ->addSubmit("kern.schulhof.verwaltung.rollen.neu.speichern()");
  } else {
    $formular[] = (new UI\Knopf("Änderungen speichern", "Erfolg"))        ->setSubmit(true);
    $formular   ->addSubmit("kern.schulhof.verwaltung.rollen.bearbeiten.speichern($id)");
  }
  return $formular;
}

?>