<?php
Anfrage::post("id");

if(!Kern\Check::angemeldet()) {
  Anfrage::addFehler(-2, true);
}

if ($id != $DSH_BENUTZER->getId() && !$DSH_BENUTZER->hatRecht("kern.rechte.vergeben || kern.rechte.rollen.zuordnen")) {
  Anfrage::addFehler(-4, true);
}

if (!UI\Check::istZahl($id)) {
  Anfrage::addFehler(-3, true);
}

$fensterid = "dshVerwaltungRechteUndRollen{$id}";

$sql = "SELECT rolle FROM kern_rollenzuordnung WHERE nutzer = ?";
$anfrage = $DBS->anfrage($sql, "i", $id);
$anfrage->werte($rolle);

$person = Kern\Nutzerkonto::vonID($id);
$fenstertitel = (new UI\Icon("fas fa-user-lock"))." Rechte und Rollen von $person";

$zeile          = new UI\Zeile();
$spalteRollen   = new UI\Spalte();
$spalteRechte   = new UI\Spalte();

$spalteRollen[] = new UI\Ueberschrift("3", "Rollen");
$spalteRechte[] = new UI\Ueberschrift("3", "Rechte");


$zeile[]        = $spalteRollen;
$zeile[]        = $spalteRechte;
$fensterinhalt  = $zeile;

$code = new UI\Fenster($fensterid, $fenstertitel, $fensterinhalt, true, true);
$code->addFensteraktion(UI\Knopf::schliessen($fensterid));

Anfrage::setRueck("Code", (string) $code);
Anfrage::setRueck("Fensterid", $fensterid);
?>
