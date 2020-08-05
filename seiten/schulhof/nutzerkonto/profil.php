<?php
if(!Check::angemeldet()) {
  einbinden("Schulhof/Anmeldung");
  return;
}

$DSH_TITEL  = "Profil";
$CODE[]     = new Kern\Aktionszeile();
$CODE[]     = UI\Zeile::standard(new UI\SeitenUeberschrift("Profil von ".($DSH_BENUTZER->getName())."!"));

$spalte    = new UI\Spalte("A1");

global $DBS;
$sql = "SELECT {email}, {notifikationsmail}, {postmail}, {postalletage}, {postpapierkorbtage}, {uebersichtsanzahl}, {oeffentlichertermin}, {oeffentlicherblog}, {oeffentlichegalerie}, {inaktivitaetszeit}, {wikiknopf}, {kuerzel} FROM kern_nutzerkonten JOIN kern_nutzereinstellungen ON kern_nutzerkonten.id = kern_nutzereinstellungen.person LEFT JOIN kern_lehrer ON kern_nutzerkonten.id = kern_lehrer.id WHERE kern_nutzerkonten.id = ?";
$anfrage = $DBS->anfrage($sql, "i", $DSH_BENUTZER->getId());
$anfrage->werte($mail, $notifikationsmail, $postmail, $posttage, $papierkorbtage, $uebersicht, $oetermin, $oeblog, $oegalerie, $inaktiv, $wiki, $kuerzel);

$formular         = new UI\FormularTabelle();
$titelF = (new UI\Textfeld("dshProfilTitel"))->setWert($DSH_BENUTZER->getTitel());
if ($DSH_BENUTZER->hatRecht("kern.nutzerkonto.profil.titel")) {
  $titelF->setAutocomplete("honorific-prefix");
} else {
  $titelF->setAttribut("disabled", "disabled");
}

$vornameF = (new UI\Textfeld("dshProfilVorname"))->setWert($DSH_BENUTZER->getVorname());
if ($DSH_BENUTZER->hatRecht("kern.nutzerkonto.profil.vorname")) {
  $vornameF->setAutocomplete("given-name");
} else {
  $vornameF->setAttribut("disabled", "disabled");
}

$nachnameF = (new UI\Textfeld("dshProfilNachname"))->setWert($DSH_BENUTZER->getNachname());
if ($DSH_BENUTZER->hatRecht("kern.nutzerkonto.profil.nachname")) {
  $nachnameF->setAutocomplete("family-name");
} else {
  $nachnameF->setAttribut("disabled", "disabled");
}

if ($DSH_BENUTZER->getArt() == "l") {
  $kuerzelF = (new UI\Textfeld("dshProfilKuerzel"))->setWert($kuerzel);
  if (!$DSH_BENUTZER->hatRecht("kern.nutzerkonto.profil.kuerzel")) {
    $kuerzelF->setAttribut("disabled", "disabled");
  }
}

$formular[]       = (new UI\FormularFeld(new UI\InhaltElement("Titel:"),                     $titelF))->setOptional(true);
$formular[]       = new UI\FormularFeld(new UI\InhaltElement("Vorname:"),                   $vornameF);
$formular[]       = new UI\FormularFeld(new UI\InhaltElement("Nachname:"),                  $nachnameF);
if ($DSH_BENUTZER->getArt() == "l") {
  $formular[]       = (new UI\FormularFeld(new UI\InhaltElement("Kürzel:"),                      $kuerzelF))->setOptional(true);
}

$formular[]       = (new UI\Knopf("Änderungen speichern", "Erfolg"))  ->setSubmit(true);
$formular         ->addSubmit("kern.schulhof.nutzerkonto.aendern.persoenliches()");

$spalte[]   = new UI\Ueberschrift(2, "Persönliches");
$spalte[]   = $formular;


$formular         = new UI\FormularTabelle();
$mailF = (new UI\Mailfeld("dshProfilMail"))->setWert($mail);
if ($DSH_BENUTZER->hatRecht("kern.nutzerkonto.profil.email")) {
  $mailF->setAutocomplete("email");
} else {
  $mailF->setAttribut("disabled", "disabled");
}

$benutzerF = (new UI\Textfeld("dshProfilBenutzer"))->setWert($DSH_BENUTZER->getBenutzer());
if ($DSH_BENUTZER->hatRecht("kern.nutzerkonto.profil.benutzer")) {
  $benutzerF->setAutocomplete("username");
} else {
  $benutzerF->setAttribut("disabled", "disabled");
}

$formular[]       = new UI\FormularFeld(new UI\InhaltElement("Benutzername:"),                   $benutzerF);
$formular[]       = new UI\FormularFeld(new UI\InhaltElement("eMail:"),                          $mailF);

$formular[]       = (new UI\Knopf("Änderungen speichern", "Erfolg"))  ->setSubmit(true);
$formular         ->addSubmit("kern.schulhof.nutzerkonto.aendern.kontodaten()");
$spalte[]   = new UI\Ueberschrift(2, "Nutzerkonto");
$spalte[]   = $formular;

if ($DSH_BENUTZER->hatRecht("kern.nutzerkonto.profil.passwort")) {
  $formular         = new UI\FormularTabelle();
  $passwortaltF = (new UI\Passwortfeld("dshProfilPasswortAlt"));
  $passwortneuF = (new UI\Passwortfeld("dshProfilPasswortNeu"));
  $passwortneu2F = (new UI\Passwortfeld("dshProfilPasswortNeu2", $passwortneuF));

  $passwortaltF->setAutocomplete("password");
  $passwortneuF->setAutocomplete("password");
  $passwortneu2F->setAutocomplete("password");

  $formular[]       = new UI\FormularFeld(new UI\InhaltElement("Altes Passwort:"),                 $passwortaltF);
  $formular[]       = new UI\FormularFeld(new UI\InhaltElement("Neues Passwort:"),                 $passwortneuF);
  $formular[]       = new UI\FormularFeld(new UI\InhaltElement("Neues Passwort bestätigen:"),      $passwortneu2F);

  $formular[]       = (new UI\Knopf("Änderungen speichern", "Erfolg"))  ->setSubmit(true);
  $formular         ->addSubmit("kern.schulhof.nutzerkonto.aendern.passwort()");
  $spalte[]   = new UI\Ueberschrift(2, "Passwort");
  $spalte[]   = $formular;
}


$formular         = new UI\FormularTabelle();
$formular[]       = new UI\FormularFeld(new UI\InhaltElement("Nachrichten:"),          (new UI\IconToggle("dshProfilNachrichtenmails", "Ich möchte eine eMail-Benachrichtugung erhalten, wenn ich eine Nachricht im Postfach erhalte.", new UI\Icon(UI\Konstanten::HAKEN)))->setWert($postmail));
$formular[]       = new UI\FormularFeld(new UI\InhaltElement("Notifikationsmails:"),          (new UI\IconToggle("dshProfilNotifikationsmails", "Ich möchte eine eMail-Benachrichtugung erhalten, wenn ich eine Notifikation erhalte.", (new UI\Icon(UI\Konstanten::HAKEN))))->setWert($notifikationsmail));
$formular[]       = new UI\FormularFeld(new UI\InhaltElement("Öffentliche Blogeinträge:"),          (new UI\IconToggle("dshProfilOeffentlichBlog", "Ich möchte über Änderungen an öffentlichen Blogeinträgen benachrichtigt werden.", new UI\Icon(UI\Konstanten::HAKEN)))->setWert($oeblog));
$formular[]       = new UI\FormularFeld(new UI\InhaltElement("Öffentliche Termine:"),          (new UI\IconToggle("dshProfilOeffentlichTermine", "Ich möchte über Änderungen an öffentlichen Terminen benachrichtigt werden.", new UI\Icon(UI\Konstanten::HAKEN)))->setWert($oetermin));
$formular[]       = new UI\FormularFeld(new UI\InhaltElement("Öffentliche Galerien:"),          (new UI\IconToggle("dshProfilOeffentlichGalerien", "Ich möchte über Änderungen an öffentlichen Galerien benachrichtigt werden.", new UI\Icon(UI\Konstanten::HAKEN)))->setWert($oegalerie));


$formular[]       = new UI\FormularFeld(new UI\InhaltElement("Hilfe-Knopf:"),          (new UI\IconToggle("dshProfilHilfeknopf", "Ich möchte, dass mir auf den Seiten im Schulhof Erklärungen angezeigt werden, wenn welche zur Verfügung stehen.", new UI\Icon(UI\Konstanten::HAKEN)))->setWert($wiki));

$formular[]       = new UI\FormularFeld(new UI\InhaltElement("Inaktivitätszeit (min):"),       (new UI\Zahlenfeld("dshProfilInaktivitätszeit", 5, 300))->setWert($inaktiv));
$formular[]       = new UI\FormularFeld(new UI\InhaltElement("Elemente pro Übersicht:"),       (new UI\Zahlenfeld("dshProfilElementeProUebersicht", 1, 10))->setWert($uebersicht));
$formular[]       = new UI\FormularFeld(new UI\InhaltElement("Löschfrist Postfach:"),          (new UI\Zahlenfeld("dshProfilPostfachLoeschfrist", 1, 1000))->setWert($posttage));
$formular[]       = new UI\FormularFeld(new UI\InhaltElement("Löschfrist Papierkorb:"),        (new UI\Zahlenfeld("dshProfilPapierkorbLoeschfrist", 1, 1000))->setWert($papierkorbtage));

$formular[]       = (new UI\Knopf("Änderungen speichern", "Erfolg"))  ->setSubmit(true);
$formular         ->addSubmit("kern.schulhof.nutzerkonto.aendern.einstellungen()");

$spalte[]   = new UI\Ueberschrift(2, "Einstellungen");
$spalte[]   = $formular;

$CODE[]     = new UI\Zeile($spalte);
?>
