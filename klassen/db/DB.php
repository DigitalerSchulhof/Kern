<?php
namespace Kern;

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
    $log = Einstellungen::laden("Kern", "Aktionslog");
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
  public function anfrage($anfrage, $parameterarten = "", ...$zwerte) : Anfrage {
    $ergebnis = [];

    try {
      // Ziel: Bringe das Array Werte auf die Form
      // $werte[Anfragennummer] = array mit den Werten;
      $werte = [];
      // Anfrage ohne Werte
      if (is_array($zwerte) && count($zwerte) == 0) {
        $werte[] = [];
      }
      // Einfache Anfrage - es wurden einzelne Werte übergeben
      else if (is_array($zwerte) && count($zwerte) > 0 && !is_array($zwerte[0])) {
        $werte[] = $zwerte;
      }
      // Mehrfache Anfrage - es wurden mehrere Arrays übergeben
      else if (is_array($zwerte) && is_array($zwerte[0]) && count($zwerte[0]) > 0 && !is_array($zwerte[0][0])) {
        $werte = $zwerte;
      }
      // Mehrfache Anfrage - es wurde ein Array mit Arrays übergeben
      else if (is_array($zwerte) && is_array($zwerte[0]) && is_array($zwerte[0][0]) && !is_array($zwerte[0][0][0]) && count($zwerte) == 1) {
        $werte = $zwerte[0];
      }
      // FEHLER
      else {
        throw new \Exception("Übergebene Paramter in unzulässigem Format");
      }
    } catch (Exception $e) {
      throw new \Exception("Übergebene Paramter in unzulässigem Format: {$e->getMessage()}");
    }


    // Parameter prüfen
    $werteproanfrage = strlen($parameterarten);
    $paramfehler = false;
    foreach ($werte as $w) {
      if (count($w) != $werteproanfrage) {
        $paramfehler = true;
      }
    }

    if ($paramfehler) {
      throw new \Exception("Übergebene Paramter passen nich tzu übergebenen Parametertypen");
    }

    // Referenzen für bind auf die Werte erstellen
    $ref = [];
    // Durchsuche alle Anfragen
    for ($i = 0; $i<count($werte); $i++) {
      // Setze Referenzen für die Werte innerhalb der Anfrage
      $refneu = [];
      for ($j = 0; $j<count($werte[$i]); $j++) {
        $refneu[] = &$werte[$i][$j];
      }
      $ref[] = $refneu;
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

      $aktion = [];
      if (strpos(strtoupper($anfragenteile[0]), "INSERT") !== false) {
        $aktion[] = "Neuer Datensatz";
      }
      if (strpos(strtoupper($anfragenteile[0]), "UPDATE") !== false) {
        $aktion[] = "Änderung";
      }
      if (strpos(strtoupper($anfragenteile[0]), "DELETE") !== false) {
        $aktion[] = "Löschung";
      }

      if (count($aktion) > 0) {
        $aktion = join(", ", $aktion);
        global $DBS;
        $DBS->logZugriff("DB", $tabellepfad, $anfragewertlos, $aktion, $werte);
      }
    }

    return new Anfrage($anzahl, $ergebnis);
  }

  /**
   * Schreibt einen Zugriff ins Aktionslog
   * @param  string $art            DB oder Datei
   * @param  string $tabellepfad    Tabelle oder Pfad auf den zugegriffen wurde
   * @param  string $datensatzdatei Datei die geschrieben wurde
   * @param  string $aktion         Aktion die ausgeführt wurde
   * @param  array  $werte          Werte einer Anfrage
   */
  public function logZugriff($art, $tabellepfad, $datensatzdatei, $aktion, $werte = []) {
    if (!$this->log) {
      return;
    }
    global $DBS, $DSH_BENUTZER;
    if ($art == "DB") {
      $log = "<b>Anfrage:</b><br>$datensatzdatei<br><br>";
      if (count($werte) > 0) {
        $log .= "<b>Werte:</b><br>";
        $logw = [];
        if (!is_array($werte[0])) {
          $nr = 1;
          foreach ($werte as $w) {
            $logw[] = "[$nr] $w";
            $nr++;
          }
        } else {
          foreach ($werte as $wert) {
            $nr = 1;
            $logw = [];
            foreach ($wert as $w) {
              $logw[] = "[$nr] $w";
              $nr++;
            }
            $log .= join(", ", $logw)."<br>";
          }
        }
      }
      $datensatzdatei = $log;
    }

    $zeitpunkt = time();
    $neueid = $DBS->neuerDatensatz("kern_nutzeraktionslog", true, true);
    if ($DSH_BENUTZER !== null) {
      $nutzerid = $DSH_BENUTZER->getId();
      $sql = $this->db->prepare("UPDATE kern_nutzeraktionslog SET nutzer = ?, art = AES_ENCRYPT(?, '{$this->schluessel}'), tabellepfad = AES_ENCRYPT(?, '{$this->schluessel}'),  datensatzdatei = AES_ENCRYPT(?, '{$this->schluessel}'),  aktion = AES_ENCRYPT(?, '{$this->schluessel}'),  zeitpunkt = ? WHERE id = ?");
      $sql->bind_param("issssii", $nutzerid, $art, $tabellepfad, $datensatzdatei, $aktion, $zeitpunkt, $neueid);
    } else {
      $sql = $this->db->prepare("UPDATE kern_nutzeraktionslog SET art = AES_ENCRYPT(?, '{$this->schluessel}'), tabellepfad = AES_ENCRYPT(?, '{$this->schluessel}'),  datensatzdatei = AES_ENCRYPT(?, '{$this->schluessel}'),  aktion = AES_ENCRYPT(?, '{$this->schluessel}'),  zeitpunkt = ? WHERE id = ?");
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
