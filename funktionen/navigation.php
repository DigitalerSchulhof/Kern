<?php

$r = [];
if(Kern\Check::angemeldet(false)) {
  $kopf     = new UI\Reiterkopf("Nutzerkonto", new UI\Icon("fas fa-user"));
  $kopf->addFunktion("href", "Schulhof/Nutzerkonto");
  $kopf->setTag("a");
  $koerper  = new UI\Reiterkoerper();
  $spalteMeinKonto = new UI\Spalte(null,
    new UI\Ueberschrift("4", "Mein Konto"),
    (new Kern\Profil($DSH_BENUTZER))->getAktivitaetsanzeige("dshHauptnavigationAktivitaetNutzerkonto")
  );
  global $DSH_ALLEMODULE;
  $module = array_keys($DSH_ALLEMODULE);
  $spalteGruppen = null;
  if(in_array("Gruppen", $module) || true) {
    $spalteGruppen   = new UI\Spalte("B34", new UI\Ueberschrift("4", "Gruppen"));
    $spalteMeinKonto ->setTyp("A4");
  }
  $koerper  ->addSpalte($spalteMeinKonto);
  if($spalteGruppen !== null) {
    $koerper  ->addSpalte($spalteGruppen);
  }
  $r[]      = new UI\Reitersegment($kopf, $koerper);
}

return $r;

?>