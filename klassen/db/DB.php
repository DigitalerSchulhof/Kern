<?php
namespace DB;
use DB\Anfrage\Anfrage;

/**
* Eine Datenbankverbindung
*/
class DB {
  /** @var mysqli Enthält die Datenbankverbindung */
  private $db;
  /** @var string Enthält den Schlüssel der Datenbank */
  private $schluessel;

	/**
	* @param string $host Der Host
	* @param string $benutzer Der Benutzer
	* @param string $passwort Dass Passwort
	* @param string $datenbank Die Datenbank
	* @param string $schluessel Der Datenbankschlüssel
	*/
  public function __construct($host, $benutzer, $passwort, $datenbank, $schluessel) {
    $this->db = new \mysqli($host, $benutzer, $passwort, $datenbank);
  	$this->db->set_charset("utf8");
    $this->schluessel = $schluessel;
  }

  /**
	* Schließt die Verbindung zur Datenbank
	* @return bool true wenn geschlossen, sonst false
	*/
  public function trennen() : bool {
    return $this->db->close();
  }

  /**
  * Stellt eine Anfrage an die Datenbank
  * @param string $anfrage SQL-Anfrage {x} wird entschlüsselt, [y] wird verschlüsselt
	* @param string $parameterarten Datentypen der übergebenen Werte für den Prepared-Request
	* @param array $werte Array mit den Werten, die übergeben werden
  * @return array Ergebnis der Anfrage als indexiertes Array oder Anzahl betroffener Zeilen
  */
  public function anfrage($anfrage, $parameterarten = "", ...$werte) : Anfrage {
    $ergebnis = array();

    if(count($werte) == 1 && is_array($werte[0]) && count($werte[0][0] ?? array()) == 1) {
      $werte = $werte[0];
    }

    $paramfehler = false;
    if (strlen($parameterarten) > 0) {
      if (is_array($werte)) {
        if (is_array($werte[0])) {
          foreach($werte as $w) {
            if (count($w) != strlen($parameterarten)) {
              $paramfehler = true;
            }
          }
        }
        else {
          if (count($werte) != strlen($parameterarten)) {
            $paramfehler = true;
          }
          else {
            $werte = array($werte);
          }
        }
      }
      else {$paramfehler = false;}
    }
    // Fehlerhafte Anfrage
    if ($paramfehler) {throw new \Exception("Ungültige Parameter(-arten)");}

    // Referenzen für bind auf die Werte erstellen
    $ref = array();
    if (!isset($werte[0]) || !is_array($werte[0])) {
      $refneu = array();
      for ($i = 0; $i<count($werte); $i++) {
        $refneu[] = &$werte[$i];
      }
      $ref[] = $refneu;
    }
    else {
      for ($i = 0; $i<count($werte); $i++) {
        $refneu = array();
        for ($j = 0; $j<count($werte[$i]); $j++) {
          $refneu[] = &$werte[$i][$j];
        }
        $ref[] = $refneu;
      }
    }

    // Verschlüsselungsersetungen vornehmen
    $anfrage = str_replace("{", "AES_DECRYPT(", $anfrage);
    $anfrage = str_replace("}", ", '$this->schluessel')", $anfrage);
    $anfrage = str_replace("[", "AES_ENCRYPT(", $anfrage);
    $anfrage = str_replace("]", ", '$this->schluessel')", $anfrage);

    // Stelle Anfrage
    $sql = $this->db->prepare($anfrage);
    if ($sql) {
      foreach ($ref as $r) {
        if (strlen($parameterarten) > 0) {
          $sql->bind_param($parameterarten, ...$r);
        }
        if ($sql->execute()) {
          $dbergebnis = $sql->get_result();
          // Anzahl beeinflusster Zeilen ausgeben, falls keine Rückgabe vorhanden
          $anzahl = $this->db->affected_rows;
          // Rückgabeart bestimmen, falls es eine Rückgabe gibt
          if ($dbergebnis) {
            while ($e = $dbergebnis->fetch_row()) {
              array_push($ergebnis, $e);
            }
          }
        }
      }
      $sql->close();
    }
    else {
      throw new \Exception("Ungültige Anfrage\nFehler: ".mysqli_error($this->db));
    }

    return new Anfrage($anzahl, $ergebnis);
  }
}
?>