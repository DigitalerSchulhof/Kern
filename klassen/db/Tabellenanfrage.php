<?php
namespace Kern;

/**
* Eine Datenbankanfrage
*/
class Tabellenanfrage {
  /** @var string Anfrage in SQL ?? steht für die Spalten */
  protected $anfrage;
  protected $spalten;
  protected $seite;
  protected $datenproseite;
  protected $sortierennach;
  protected $sortierrichtung;

  /**
   * Erstellt eine Tabellenanfrage
   * @param string  $anfrage         SQL-Code mit ?? an Stelle der Spalten
   * @param string  $spalten         Spalten in bestimmter Form (siehe oben)
   * @param integer $seite           Seite der Tabellendatensätze
   * @param integer $datenproseite   :)
   * @param integer $sortierennach   Spaltennummer beginnend bei 0
   * @param string  $sortierrichtung "ASC" / "DESC"
   */
  public function __construct($anfrage, $spalten, $seite = 1, $datenproseite = 50, $sortierennach = 1, $sortierrichtung = "ASC") {
    $this->anfrage         = $anfrage;
    $this->spalten         = $spalten;
    $this->seite           = $seite;
    $this->datenproseite   = $datenproseite;
    $this->sortierennach   = $sortierennach;
    $this->sortierrichtung = $sortierrichtung;
  }

  public function anfrage($DB, $parameterarten, ...$werte) {
    $spaltencode = [];
    foreach ($this->spalten AS $s) {
      $spaltencode[] = join(", ", $s);
    }
    $spaltensql = join(", ",$spaltencode);

    // SQL-Code mit den Spalten
    $sql = str_replace("??", $spaltensql, $this->anfrage);

    // SQL-Code sortieren
    $sql = "SELECT * FROM ($sql) AS sortiertabelle ORDER BY ";

    $sortierspalten = [];
    foreach ($this->spalten[$this->sortierennach] as $s) {
      $sortierspalten[] = $this->sortiervorbereitung($s)." {$this->sortierrichtung}";
    }

    $sql .= join(", ", $sortierspalten);

    // SQL-Code Seite
    $beginn = $this->datenproseite*($this->seite-1);
    $sql .= " LIMIT $beginn, {$this->datenproseite}";

    return $DB->anfrage($sql, $parameterarten, ...$werte);
  }

  private function sortiervorbereitung($s) {
    $s = str_replace(" as ", " AS ", $s);
    $s = explode(" AS ", $s);
    if (count($s) == 1) {
      $s[0] = str_replace("{", "", $s[0]);
      $s[0] = str_replace("}", "", $s[0]);
      $s[0] = str_replace("[", "", $s[0]);
      $s[0] = str_replace("]", "", $s[0]);
      return $s[0];
    } else {
      return $s[1];
    }
  }

}
?>
