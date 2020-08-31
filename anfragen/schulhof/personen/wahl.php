<?php
Anfrage::post("id", "pool", "gewaehlt", "vorname", "nachname", "schueler", "lehrer", "erziehungsberechtigte", "verwaltungsangestellte", "externe", "recht", "nutzerkonto");

if(!Kern\Check::angemeldet()) {
  Anfrage::addFehler(-2, true);
}

if (strlen($recht) > 0) {
  if (!$DSH_BENUTZER->hatRecht($recht)) {
    Anfrage::addFehler(-4, true);
  }
}

if (!UI\Check::istToggle($schueler) || !UI\Check::istToggle($lehrer) || !UI\Check::istToggle($erziehungsberechtigte) || !UI\Check::istToggle($verwaltungsangestellte) || !UI\Check::istToggle($externe) || !UI\Check::istToggle($nutzerkonto) || !UI\Check::istIDListe($gewaehlt) || !UI\Check::istLatein($id)) {
  Anfrage::addFehler(-3, true);
}

if (strlen($pool > 0) && (!isset($_SESSION[$pool]) || !UI\Check::istIDListe(join(",", $_SESSION[$pool])))) {
  Anfrage::addFehler(-3, true);
} else {
  $pool = join(",", $_SESSION[$pool]);
}

if(!UI\Check::istName($vorname,0)) {
  Anfrage::addFehler(103);
}
if(!UI\Check::istName($nachname,0)) {
  Anfrage::addFehler(104);
}

// Personen suchen und ausgeben
// Prüfen, ob ein Nutzerkonto ausschlaggebend ist
$tabelle = "kern_personen";
if ($nutzerkonto === "1") {
  $tabelle .= " JOIN kern_nutzerkonten ON kern_personen.id = kern_nutzerkonten.person";
}

$sql = "SELECT id, {titel} AS titel, {vorname} AS vorname, {nachname} AS nachname, {art} AS art FROM $tabelle";

$parameter = [];
$parameterarten = "";

$arten = [];
if ($schueler === "1") {
  $arten[] = "art = [?]";
  $parameter[] = "s";
  $parameterarten .= "s";
}
if ($lehrer === "1") {
  $arten[] = "art = [?]";
  $parameter[] = "l";
  $parameterarten .= "s";
}
if ($erziehungsberechtigte === "1") {
  $arten[] = "art = [?]";
  $parameter[] = "e";
  $parameterarten .= "s";
}
if ($verwaltungsangestellte === "1") {
  $arten[] = "art = [?]";
  $parameter[] = "v";
  $parameterarten .= "s";
}
if ($externe === "1") {
  $arten[] = "art = [?]";
  $parameter[] = "x";
  $parameterarten .= "s";
}

$praefilter = [];
if (count($arten) > 0) {
  $praefilter[] = "(".join(" OR ", $arten).")";
}
if (strlen($gewaehlt)) {
  $praefilter[] = "id NOT IN ($gewaehlt)";
}
if (strlen($pool) > 0) {
  $praefilter[] = "id IN ($pool)";
}
if (count($praefilter) > 0) {
  $sql .= " WHERE ".join(" AND ", $praefilter);
}

$sql = "SELECT * FROM ($sql) AS x";

$postfilter = [];
if (strlen($vorname) > 0) {
  $postfilter[] = "UPPER(CONVERT(vorname USING utf8)) LIKE UPPER(?)";
  $parameter[] = "%$vorname%";
  $parameterarten .= "s";
}
if (strlen($nachname) > 0) {
  $postfilter[] = "UPPER(CONVERT(nachname USING utf8)) LIKE UPPER(?)";
  $parameter[] = "%$nachname%";
  $parameterarten .= "s";
}

if (count($postfilter) > 0) {
  $sql .= " WHERE ".join(" AND ", $postfilter);
}

$sql .= " ORDER BY art, titel, nachname, vorname";

$knoepfe = [];
$anfrage = $DBS->anfrage($sql, $parameterarten, $parameter);
while ($anfrage->werte($pid, $titel, $vorname, $nachname, $art)) {
  $anzeigename = (new Kern\Person($pid, $titel, $vorname, $nachname))->getName();
  $knopf = new UI\IconKnopfPerson($anzeigename, $art);
  $knopf->addFunktion("onclick", "kern.schulhof.verwaltung.personen.wahl.einzeln.dazu('$id', '$pid', '$anzeigename', '$art')");
  $knoepfe[] = $knopf;
}

Anfrage::setRueck("ergebnisse", join(" ", $knoepfe));
?>