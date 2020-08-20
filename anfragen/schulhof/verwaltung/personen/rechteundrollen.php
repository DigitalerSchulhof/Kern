<?php
Anfrage::post("id");

if(!Kern\Check::angemeldet()) {
  Anfrage::addFehler(-2, true);
}

if (!$DSH_BENUTZER->hatRecht("verwaltung.rechte.vergeben || kern.rechte.rollen.zuordnen")) {
  Anfrage::addFehler(-4, true);
}

if (!UI\Check::istZahl($id)) {
  Anfrage::addFehler(-3, true);
}

$fensterid = "dshVerwaltungRechteUndRollen{$id}";

$person = Kern\Nutzerkonto::vonID($id);
if($person === null) {
  Anfrage::addFehler(-3, true);
}

$fenstertitel = (new UI\Icon("fas fa-user-lock"))." Rechte und Rollen von $person";

$zeile          = new UI\Zeile();
$spalteRechte   = new UI\Spalte();

if($DSH_BENUTZER->hatRecht("verwaltung.rechte.rollen.zuordnen")) {
  $spalteRollen   = new UI\Spalte();
  $spalteRollen[] = new UI\Ueberschrift("3", "Rollen");
  $sql = "SELECT r.id, {r.bezeichnung}, IF(EXISTS(SELECT person FROM kern_rollenzuordnung as rz WHERE rz.person = ? AND rz.rolle = r.id), '1', '0') FROM kern_rollen as r";
  $anfrage = $DBS->anfrage($sql, "i", $id);

  while($anfrage->werte($rolle, $bezeichnung, $hat)) {
    $tog = new UI\Toggle("dshVerwaltungRechteUndRollen{$id}Rolle$rolle", $bezeichnung);
    $tog ->setWert($hat);
    $tog ->addFunktion("onclick", "kern.schulhof.verwaltung.personen.rolle('$id', '$rolle')");
    $spalteRollen[] = $tog." ";
  }

  $zeile[]        = $spalteRollen;
}

if($DSH_BENUTZER->hatRecht("verwaltung.rechte.vergeben")) {
  $spalteRechte[] = new UI\Ueberschrift("3", "Rechte");
  include "$DIR/klassen/rechtebaum.php";

  $anfrage = $DBS->anfrage("SELECT {recht} FROM kern_nutzerrechte as nr WHERE person = ?", "i", $id);
  $nutzerrechte = [];
  while($anfrage->werte($recht)) {
    $nutzerrechte[] = $recht;
  }

  $anfrage = $DBS->anfrage("SELECT {recht} FROM kern_rollenrechte as rr JOIN kern_rollenzuordnung as rz ON rz.rolle = rr.rolle WHERE rz.person = ?", "i", $id);
  $rollenrechte = [];
  while($anfrage->werte($recht)) {
    $rollenrechte[] = $recht;
  }

  $spalteRechte[] = new Kern\Rechtebaum("dshVerwaltungRechte$id", Kern\Rechtehelfer::array2Baum($nutzerrechte), Kern\Rechtehelfer::array2Baum($rollenrechte));

  $zeile[]        = $spalteRechte;
}
$zeile[]        = new UI\Spalte("A1", new UI\Knopf("Rechte aktualisieren", null, "kern.schulhof.verwaltung.personen.rechteneuladen('$id')"));
$fensterinhalt  = $zeile;

$code = new UI\Fenster($fensterid, $fenstertitel, $fensterinhalt, true, true);
$code->addFensteraktion(UI\Knopf::schliessen($fensterid));

Anfrage::setRueck("Code", (string) $code);
?>