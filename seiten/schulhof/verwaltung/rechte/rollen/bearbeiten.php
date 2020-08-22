<?php
$SEITE = new Kern\Seite("Personen", "verwaltung.rechte.rollen.bearbeiten");

$spalte = new UI\Spalte("A1", new UI\SeitenUeberschrift("Rolle bearbeiten"));

$anf = $DBS->anfrage("SELECT id FROM kern_rollen WHERE bezeichnung = [?]", "s", str_replace("_", " ", $DSH_URL[3]));
if(!$anf->werte($id)) {
  Seite::seiteAus("Fehler/404");
}
include_once __DIR__."/details.php";

$spalte[] = rollenDetails($id);

$SEITE[] = new UI\Zeile($spalte);
?>
