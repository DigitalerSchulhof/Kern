<?php
Anfrage::post("id", "laden");

if(!Kern\Check::angemeldet()) {
  Anfrage::addFehler(-2, true);
}

if (!$DSH_BENUTZER->hatRecht("kern.personen.anlegen.nutzerkonto")) {
  Anfrage::addFehler(-4, true);
}

// Prüfen, ob es für diesen Nutzer bereits ein Nutzerkonto gibt
$sql = "SELECT COUNT(*) FROM kern_nutzerkonten WHERE id = ?";
$anfrage = $DBS->anfrage($sql, "i", $id);
$anfrage->werte($nanzahl);

$sql = "SELECT COUNT(*) FROM kern_personen WHERE id = ?";
$anfrage = $DBS->anfrage($sql, "i", $id);
$anfrage->werte($panzahl);

if (!UI\Check::istZahl($id) || !UI\Check::istToggle($laden) || $nanzahl > 0 || $panzahl != 1) {
  Anfrage::addFehler(-3, true);
}

$fensterid = "dshVerwaltungNeuesNutzerkonto{$id}";

$sql = "SELECT {art}, {geschlecht}, {vorname}, {nachname}, {titel} FROM kern_personen WHERE id = ?";
$anfrage = $DBS->anfrage($sql, "i", $id);
$anfrage->werte($art, $geschlecht, $vorname, $nachname, $titel);

$person = new Kern\Nutzerkonto($id, $titel, $vorname, $nachname);
$person->setArt($art);
$person->setGeschlecht($geschlecht);
$fenstertitel = (new UI\Icon("fas fa-arrow-alt-circle-up"))." Nutzerkonto für $person";

$formular         = new UI\FormularTabelle();

$benutzername = new UI\Textfeld("dshNeuesNutzerkonto{$id}Benutzername");
$benutzername->setAutocomplete("username");
$benutzername->setWert($person->generiereBenutzername());

$formular[]       = new UI\FormularFeld(new UI\InhaltElement("Benutzername:"),  $benutzername);
$formular[]       = new UI\FormularFeld(new UI\InhaltElement("eMail-Adresse:"),             (new UI\Mailfeld("dshNeuesNutzerkonto{$id}Mail"))     ->setAutocomplete("email"));

$formular[]       = (new UI\Knopf("Neues Nutzerkonto anlegen", "Erfolg"))  ->setSubmit(true);
$formular         ->addSubmit("kern.schulhof.verwaltung.personen.neu.nutzerkonto.erstellen('$id', '$laden')");

$fensterinhalt = $formular;

$code = new UI\Fenster($fensterid, $fenstertitel, $fensterinhalt);
$code->addFensteraktion(UI\Knopf::schliessen($fensterid));

Anfrage::setRueck("Code", (string) $code);
?>