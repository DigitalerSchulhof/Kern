<?php
namespace DB;
use DB\Anfrage;
use Kern;

/**
* Eine Datenbankverbindung
*/
class DB {
  /** @var mysqli Enthält die Datenbankverbindung */
  private $db;
  /** @var string Enthält den Schlüssel der Datenbank */
  private $schluessel;
  /** @var bool wenn true, wird in Aktionslog gespeichert, wenn die DB verändert wird, false sonst */
  private $log;

	/**
	* @param string $host :)
	* @param string $benutzer :)
  * @param int    $port :)
	* @param string $passwort :)
	* @param string $datenbank :)
	* @param string $schluessel :)
	* @param string $aktionslog 1
	*/
  public function __construct($host, $port, $benutzer, $passwort, $datenbank, $schluessel) {
    $this->db = new \mysqli($host, $benutzer, $passwort, $datenbank, $port);
  	$this->db->set_charset("utf8");
    $this->schluessel = $schluessel;
    $this->log = false;
  }

  /**
	* Schließt die Verbindung zur Datenbank
	* @return bool true wenn geschlossen, sonst false
	*/
  public function trennen() : bool {
    return $this->db->close();
  }

  /**
   * Aktionslog an- / ausschalten
   * @param  bool $log true = an, false = aus
   * @return self      :)
   */
  public function setLog($log) : self {
    $this->log = $log;
    return $this;
  }

  /**
   * Setzt in allen Datenbanken die LOG-Variable
   * @return [type] [description]
   */
  public static function log() {
    global $DBS, $DSH_DBS;
    $log = Kern\Einstellungen::laden("Kern", "Aktionslog");
    if ($log == "1") {
      $log = true;
    } else {
      $log = false;
    }
    foreach($DSH_DBS as $db) {
      $db->setLog($log);
    }
  }

  public function silentanfrage($anfrage, $parameterarten = "", ...$werte) : Anfrage {
    $alterlog = $this->log;
    $this->setLog(false);
    $anfrage = $this->anfrage($anfrage, $parameterarten, ...$werte);
    $this->log = $alterlog;
    return $anfrage;
  }

  /**
  * Stellt eine Anfrage an die Datenbank
  * @param string $anfrage SQL-Anfrage {x} wird entschlüsselt, [y] wird verschlüsselt
	* @param string $parameterarten Datentypen der übergebenen Werte für den Prepared-Request
	* @param array $werte Array mit den Werten, die übergeben werden
  * @return array Ergebnis der Anfrage als indexiertes Array oder Anzahl betroffener Zeilen
  */
  public function anfrage($anfrage, $parameterarten = "", ...$werte) : Anfrage {
    $ergebnis = [];

    if(count($werte) == 1 && is_array($werte[0]) && count($werte[0][0] ?? []) == 1) {
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
    $ref = [];
    if (!isset($werte[0]) || !is_array($werte[0])) {
      $refneu = [];
      for ($i = 0; $i<count($werte); $i++) {
        $refneu[] = &$werte[$i];
      }
      $ref[] = $refneu;
    }
    else {
      for ($i = 0; $i<count($werte); $i++) {
        $refneu = [];
        for ($j = 0; $j<count($werte[$i]); $j++) {
          $refneu[] = &$werte[$i][$j];
        }
        $ref[] = $refneu;
      }
    }

    // Verschlüsselungsersetungen vornehmen
    $anfragewertlos = $anfrage;
    $anfragewertlos = str_replace("{", "", $anfragewertlos);
    $anfragewertlos = str_replace("}", "", $anfragewertlos);
    $anfragewertlos = str_replace("[", "", $anfragewertlos);
    $anfragewertlos = str_replace("]", "", $anfragewertlos);
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

    // Aus Anfrage Logeintrag basteln
    $anfragenteile = explode(" ", $anfragewertlos);
    if (count($anfragenteile) >= 3) {
      if (strtoupper($anfragenteile[1]) == "INTO" || strtoupper($anfragenteile[1]) == "FROM") {
        $tabellepfad = $anfragenteile[2];
      } else {
        $tabellepfad = $anfragenteile[1];
      }

      if (strtoupper($anfragenteile[0]) == "INSERT") {
        $aktion = "Neuer Datensatz";
      } else if (strtoupper($anfragenteile[0]) == "UPDATE") {
        $aktion = "Änderung";
      } else if (strtoupper($anfragenteile[0]) == "DELETE") {
        $aktion = "Löschung";
      } else {
        $aktion = false;
      }

      if ($aktion) {
        global $DBS;
        $DBS->logZugriff("DB", $tabellepfad, $anfragewertlos, $aktion);
      }
    }

    return new Anfrage($anzahl, $ergebnis);
  }

  public function logZugriff($art, $tabellepfad, $datensatzdatei, $aktion) {
    if (!$this->log) {
      return;
    }
    global $DBS, $DSH_BENUTZER;
    $zeitpunkt = time();
    $neueid = $DBS->neuerDatensatz("kern_aktionslog", true, true);
    if ($DSH_BENUTZER !== null) {
      $nutzerid = $DSH_BENUTZER->getId();
      $sql = $this->db->prepare("UPDATE kern_aktionslog SET nutzer = ?, art = AES_ENCRYPT(?, '{$this->schluessel}'), tabellepfad = AES_ENCRYPT(?, '{$this->schluessel}'),  datensatzdatei = AES_ENCRYPT(?, '{$this->schluessel}'),  aktion = AES_ENCRYPT(?, '{$this->schluessel}'),  zeitpunkt = ? WHERE id = ?");
      $sql->bind_param("issssii", $nutzerid, $art, $tabellepfad, $datensatzdatei, $aktion, $zeitpunkt, $neueid);
    } else {
      $sql = $this->db->prepare("UPDATE kern_aktionslog SET art = AES_ENCRYPT(?, '{$this->schluessel}'), tabellepfad = AES_ENCRYPT(?, '{$this->schluessel}'),  datensatzdatei = AES_ENCRYPT(?, '{$this->schluessel}'),  aktion = AES_ENCRYPT(?, '{$this->schluessel}'),  zeitpunkt = ? WHERE id = ?");
      $sql->bind_param("ssssii", $art, $tabellepfad, $datensatzdatei, $aktion, $zeitpunkt, $neueid);
    }
    $sql->execute();
    $sql->close();
  }

  /**
   * Legt einen leeren Datensatz an
   * @param  string $tabelle :)
   * @param  bool   $anonym Wenn true, wird der Datensatz ohne Nutzerverbindung angelegt
   * @param  bool   $silent verzichtet auf den Aktionslog
   * @return int    Wert der Spalte <code>id</code> des neuen Datensatzes
   */
  public function neuerDatensatz($tabelle, $anonym = false, $silent = false) {
    $fehler = false;
    $id = '-';

    if($anonym) {
      $sql = $this->db->prepare("SELECT MAX(id) FROM kern_nutzerkonten");
      if ($sql->execute()) {
        $sql->bind_result($benutzer);
        $sql->fetch();
        $benutzer++;
      }
      $sql->close();
    } else {
      if (isset($_SESSION['Benutzer'])) {
        $benutzer = $_SESSION['Benutzer']->getId();
      } else {
        throw new \Exception("Nicht identifizierter Benutzer versucht ");
      }
    }

    // Neue ID bestimmten und eintragen
    $jetzt = time();
    $sql = $this->db->prepare("SET FOREIGN_KEY_CHECKS = 0;");
    $sql->execute();
    $sql->close();

    $sql = $this->db->prepare("INSERT INTO $tabelle (id, idvon, idzeit) SELECT id, idvon, idzeit FROM (SELECT IFNULL(id*0,0)+? AS idvon, IFNULL(id*0,0)+? AS idzeit, IFNULL(MIN(id)+1,1) AS id FROM $tabelle WHERE id+1 NOT IN (SELECT id FROM $tabelle)) AS vorherigeid");
    $sql->bind_param("ii", $benutzer, $jetzt);
    $sql->execute();
    $sql->close();

  	$sql = $this->db->prepare("SET FOREIGN_KEY_CHECKS = 1;");
    $sql->execute();
    $sql->close();

    // ID zurückgewinnen
    $id = null;
    $sql = $this->db->prepare("SELECT id FROM $tabelle WHERE idvon = ? AND idzeit = ?");
    $sql->bind_param("ii", $benutzer, $jetzt);
    if ($sql->execute()) {
      $sql->bind_result($id);
      $sql->fetch();
    }
    else {$fehler = true;}
    $sql->close();
    // Persönliche Daten löschen
    if ($id !== null) {
      $sql = $this->db->prepare("UPDATE $tabelle SET idvon = NULL, idzeit = NULL WHERE id = ?");
      $sql->bind_param("i", $id);
      $sql->execute();
      $sql->close();
    }

    if (!$silent) {
      global $DBS;
      $DBS->logZugriff("DB", $tabelle, $id, "Neuer Datensatz");
    }

    return $id;
  }
}
?>
