<?php
namespace Kern;

/**
* Eine Datenbankanfrage für Tabellen
*/
class Tabellenanfrage {
  /** @var string Anfrage in SQL ?? steht für die Spalten */
  protected $anfrage;
  /** @var string[] Spalten müssen so angegeben werden, dass die Spalten in der
   * Datenbank, aus denen sich nachher die Spalte in der Tabelle zusammensetzt in
   * einem extra-Array-Feld  eingetragen werden müssen. Beispiel:
   * [[DBSpalte1, DBSpalte2], [DBSpalte3, DBSpalte4], [DBSpalte5]]
   * liefert die drei Spalten der Tabelle **/
  protected $spalten;
  /** @var int Seite der Tabelle auf der die Anfrage ausgeführt werden soll */
  protected $seite;
  /** @var int Datensätze, die auf dieser Seite stehen sollen */
  protected $datenproseite;
  /** @var string Nummer im Array Spalten, nach der Spalte, nach der Sortiert werden soll */
  protected $sortierennach;
  /** @var string ASC oder DESC */
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
  public function __construct($anfrage, $spalten, $seite = 1, $datenproseite = 25, $sortierennach = 1, $sortierrichtung = "ASC") {
    $this->anfrage         = $anfrage;
    $this->spalten         = $spalten;
    $this->seite           = $seite;
    $this->datenproseite   = $datenproseite;
    $this->sortierennach   = $sortierennach;
    $this->sortierrichtung = $sortierrichtung;
  }

  /**
   * Stellt eine Anfrage an die angegebene DB und sortiert dabei entsprechend
   * @param  DB       $DB             :)
   * @param  string   $parameterarten :)
   * @param  string[] $werte          :)
   * @return array    ["Anfrage"] ["Seite"]
   */
  public function anfrage($DB, $parameterarten = "", ...$werte) {
    $rueck = [];
    $rueck["Richtung"] = $this->sortierrichtung;
    $rueck["Spalte"] = $this->sortierennach;
    $spaltencode = [];

    foreach ($this->spalten AS $s) {
      $spaltencode[] = join(", ", $s);
    }
    $spaltensql = join(", ",$spaltencode);

    // SQL-Code mit den Spalten
    $sql = str_replace("??", $spaltensql, $this->anfrage);

    if ($this->datenproseite != "alle") {
      // ANFRAGE ZUR BERECHNUNG DER SEITEN DER TABELLE
      $sqlanzahl = "SELECT COUNT(*) FROM ($sql) AS sortiertabelle";
      $sanfrage = $DB->anfrage($sqlanzahl, $parameterarten, ...$werte);
      $sanfrage->werte($datensaetze);

      $seitenanzahl = max(1, ceil($datensaetze / $this->datenproseite));
      $rueck["DatenProSeite"] = $this->datenproseite;
      $rueck["Seitenanzahl"] = $seitenanzahl;
      if ($this->seite > $seitenanzahl) {
        $this->seite = 1;
      }
      $rueck["Seite"] = $this->seite;
    } else {
      $this->seite = 1;
      $rueck["DatenProSeite"] = $this->datenproseite;
      $rueck["Seitenanzahl"] = $this->seite;
      $rueck["Seite"] = $this->seite;
    }


    // SQL-Code sortieren
    $sql = "SELECT * FROM ($sql) AS sortiertabelle ORDER BY ";

    $sortierspalten = [];
    foreach ($this->spalten[$this->sortierennach] as $s) {
      $sortierspalten[] = $this->sortiervorbereitung($s)." {$this->sortierrichtung}";
    }

    $sql .= join(", ", $sortierspalten);

    // SQL-Code Seite
    if ($this->datenproseite != "alle") {
      $beginn = $this->datenproseite*($this->seite-1);
      $sqlmit = $sql." LIMIT $beginn, {$this->datenproseite}";
      $anfrage = $DB->anfrage($sqlmit, $parameterarten, ...$werte);
    } else {
      $anfrage = $DB->anfrage($sql, $parameterarten, ...$werte);
    }

    $rueck["Anfrage"] = $anfrage;

    return $rueck;
  }

  /**
   * Ändert Spaltennamen so ab, dass im Falle einer Umbenennung der richtige Spaltentitel angegeben wird
   * @param  string $s spaltenname
   * @return string    spaltenname hinter AS oder ohne [] und {}
   */
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
