<?php
if(!Check::angemeldet()) {
  einbinden("Schulhof/Anmeldung");
  return;
}

$DSH_TITEL = "Nutzerkonto";
$CODE .= new Kern\Aktionszeile();

$spalte = new UI\Spalte();
$spalte->add(new UI\SeitenUeberschrift("Willkommen $DSH_BENUTZER!"));
$CODE .= new UI\Zeile($spalte);

$spalte1 = new UI\Spalte("A2");
$spalte1 ->add($DSH_BENUTZER->aktivitaetsanzeige("dshAktivitaetNutzerkonto"));

$spalte2 = new UI\Spalte("A2");
$CODE .= new UI\Zeile($spalte1, $spalte2);
?>
