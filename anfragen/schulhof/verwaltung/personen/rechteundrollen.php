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

$person = Kern\Nutzerkonto::vonID($id);
$fenstertitel = (new UI\Icon("fas fa-user-lock"))." Rechte und Rollen von $person";

$zeile          = new UI\Zeile();
$spalteRollen   = new UI\Spalte();
$spalteRechte   = new UI\Spalte();

$spalteRollen[] = new UI\Ueberschrift("3", "Rollen");
$spalteRechte[] = new UI\Ueberschrift("3", "Rechte");

$sql = "SELECT r.id, {r.bezeichnung}, IF(EXISTS(SELECT nutzer FROM kern_rollenzuordnung as rz WHERE rz.nutzer = ? AND rz.rolle = r.id), '1', '0') FROM kern_rollen as r";
$anfrage = $DBS->anfrage($sql, "i", $id);

while($anfrage->werte($rolle, $bezeichnung, $hat)) {
  $tog = new UI\Toggle("dshVerwaltungRechteUndRollen{$id}Rolle$rolle", $bezeichnung);
  $tog ->setWert($hat);
  $tog ->addFunktion("onclick", "kern.schulhof.verwaltung.personen.rolle('$id', '$rolle')");
  $spalteRollen[] = $tog." ";
}

$zeile[]        = $spalteRollen;
$zeile[]        = $spalteRechte;
$fensterinhalt  = $zeile;

$code = new UI\Fenster($fensterid, $fenstertitel, $fensterinhalt, true, true);
$code->addFensteraktion(UI\Knopf::schliessen($fensterid));

Anfrage::setRueck("Code", (string) $code);
?>
