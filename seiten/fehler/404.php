<?php
$DSH_TITEL = "Fehler 404";
$CODE .= new Kern\Aktionszeile(true, false);

$CODE .= new UI\Zeile(new UI\Spalte(null, new UI\Meldung("Fehler 404", "Die Seite konnte nicht gefunden werden!", "Fehler")));

$CODE = "";

echo "<b style='font-weight: bold'>Mit Funktion (Es passiert etwas beim Draufdrücken):</b><br><br><br>";

foreach(UI\Knopf::ARTEN as $art) {
  $knopf = new UI\Knopf("$art", $art);
  $knopf->getAktionen()->addFunktion("onclick", "alert('$art')");
  echo "$knopf ";
}
echo "<br><br><br>";

$knopf = new UI\IconKnopf(new UI\Icon(UI\Konstanten::STANDARD), "Standard", "Standard");
$knopf->getAktionen()->addFunktion("onclick", "alert('Standard')");
echo "$knopf ";
$knopf = new UI\IconKnopf(new UI\Icon(UI\Konstanten::ERFOLG), "Erfolg", "Erfolg");
$knopf->getAktionen()->addFunktion("onclick", "alert('Erfolg')");
echo "$knopf ";
$knopf = new UI\IconKnopf(new UI\Icon(UI\Konstanten::WARNUNG), "Warnung", "Warnung");
$knopf->getAktionen()->addFunktion("onclick", "alert('Warnung')");
echo "$knopf ";
$knopf = new UI\IconKnopf(new UI\Icon(UI\Konstanten::FEHLER), "Fehler", "Fehler");
$knopf->getAktionen()->addFunktion("onclick", "alert('Fehler')");
echo "$knopf ";
$knopf = new UI\IconKnopf(new UI\Icon(UI\Konstanten::INFORMATION), "Information", "Information");
$knopf->getAktionen()->addFunktion("onclick", "alert('Information')");
echo "$knopf ";

echo "<br><br><br>";


$knopf = new UI\GrossIconKnopf(new UI\Icon(UI\Konstanten::STANDARD), "Standard", "Standard");
$knopf->getAktionen()->addFunktion("onclick", "alert('Standard')");
echo "$knopf ";
$knopf = new UI\GrossIconKnopf(new UI\Icon(UI\Konstanten::ERFOLG), "Erfolg", "Erfolg");
$knopf->getAktionen()->addFunktion("onclick", "alert('Erfolg')");
echo "$knopf ";
$knopf = new UI\GrossIconKnopf(new UI\Icon(UI\Konstanten::WARNUNG), "Warnung", "Warnung");
$knopf->getAktionen()->addFunktion("onclick", "alert('Warnung')");
echo "$knopf ";
$knopf = new UI\GrossIconKnopf(new UI\Icon(UI\Konstanten::FEHLER), "Fehler", "Fehler");
$knopf->getAktionen()->addFunktion("onclick", "alert('Fehler')");
echo "$knopf ";
$knopf = new UI\GrossIconKnopf(new UI\Icon(UI\Konstanten::INFORMATION), "Information", "Information");
$knopf->getAktionen()->addFunktion("onclick", "alert('Information')");
echo "$knopf ";

echo "<br><br><br>";


$knopf = new UI\MiniIconKnopf(new UI\Icon(UI\Konstanten::STANDARD), "Standard", "Standard");
$knopf->getAktionen()->addFunktion("onclick", "alert('Standard')");
echo "$knopf ";
$knopf = new UI\MiniIconKnopf(new UI\Icon(UI\Konstanten::ERFOLG), "Erfolg", "Erfolg");
$knopf->getAktionen()->addFunktion("onclick", "alert('Erfolg')");
echo "$knopf ";
$knopf = new UI\MiniIconKnopf(new UI\Icon(UI\Konstanten::WARNUNG), "Warnung", "Warnung");
$knopf->getAktionen()->addFunktion("onclick", "alert('Warnung')");
echo "$knopf ";
$knopf = new UI\MiniIconKnopf(new UI\Icon(UI\Konstanten::FEHLER), "Fehler", "Fehler");
$knopf->getAktionen()->addFunktion("onclick", "alert('Fehler')");
echo "$knopf ";
$knopf = new UI\MiniIconKnopf(new UI\Icon(UI\Konstanten::INFORMATION), "Information", "Information");
$knopf->getAktionen()->addFunktion("onclick", "alert('Information')");
echo "$knopf ";

echo "<br><br><br>";

echo "<b style='font-weight: bold'>Ohne Funktion (Passiert nichts beim Draufdrücken):</b><br><br><br>";

foreach(UI\Knopf::ARTEN as $art) {
  $knopf = new UI\Knopf("$art", $art);
  echo "$knopf ";
}
echo " - Knöpfe<br><br><br>";

$knopf = new UI\IconKnopf(new UI\Icon(UI\Konstanten::STANDARD), "Standard", "Standard");
echo "$knopf ";
$knopf = new UI\IconKnopf(new UI\Icon(UI\Konstanten::ERFOLG), "Erfolg", "Erfolg");
echo "$knopf ";
$knopf = new UI\IconKnopf(new UI\Icon(UI\Konstanten::WARNUNG), "Warnung", "Warnung");
echo "$knopf ";
$knopf = new UI\IconKnopf(new UI\Icon(UI\Konstanten::FEHLER), "Fehler", "Fehler");
echo "$knopf ";
$knopf = new UI\IconKnopf(new UI\Icon(UI\Konstanten::INFORMATION), "Information", "Information");
echo "$knopf ";

echo "<br><br><br>";


$knopf = new UI\GrossIconKnopf(new UI\Icon(UI\Konstanten::STANDARD), "Standard", "Standard");
echo "$knopf ";
$knopf = new UI\GrossIconKnopf(new UI\Icon(UI\Konstanten::ERFOLG), "Erfolg", "Erfolg");
echo "$knopf ";
$knopf = new UI\GrossIconKnopf(new UI\Icon(UI\Konstanten::WARNUNG), "Warnung", "Warnung");
echo "$knopf ";
$knopf = new UI\GrossIconKnopf(new UI\Icon(UI\Konstanten::FEHLER), "Fehler", "Fehler");
echo "$knopf ";
$knopf = new UI\GrossIconKnopf(new UI\Icon(UI\Konstanten::INFORMATION), "Information", "Information");
echo "$knopf ";

echo "<br><br><br>";


$knopf = new UI\MiniIconKnopf(new UI\Icon(UI\Konstanten::STANDARD), "Standard", "Standard");
echo "$knopf ";
$knopf = new UI\MiniIconKnopf(new UI\Icon(UI\Konstanten::ERFOLG), "Erfolg", "Erfolg");
echo "$knopf ";
$knopf = new UI\MiniIconKnopf(new UI\Icon(UI\Konstanten::WARNUNG), "Warnung", "Warnung");
echo "$knopf ";
$knopf = new UI\MiniIconKnopf(new UI\Icon(UI\Konstanten::FEHLER), "Fehler", "Fehler");
echo "$knopf ";
$knopf = new UI\MiniIconKnopf(new UI\Icon(UI\Konstanten::INFORMATION), "Information", "Information");
echo "$knopf ";

echo "<br><br><br>";

?>