<?php
namespace Kern;

class Check {

  public static function istModul($modul) {
    return preg_match("/^[A-Za-z0-9]{1,16}$/", $modul) === 1;
  }

  // Liefert das mögliche System
  public static function systeminfo() {
    $info = strtolower($_SERVER['HTTP_USER_AGENT']);

    // Browser finden
    if (preg_match("/opera/", $info)) {
      $browser = "Opera";
    } else if (preg_match("/opr/", $info)) {
      $browser = "Opera";
    } else if (preg_match("/chromium/", $info)) {
      $browser = "Chromium";
    } elseif (preg_match("/chrome/", $info)) {
      $browser = "Chrome";
    } elseif (preg_match("/webkit/", $info)) {
      $browser = "Safari";
    } elseif (preg_match("/msie/", $info)) {
      $browser = "Internet Explorer / Edge";
    } elseif (preg_match("/mozilla/", $info) && !preg_match("/compatible/", $info)) {
      $browser = "Firefox";
    } else {
      $browser = "Unbekannter Browser";
    }

    // Browser-Version
    $version = "";
    if ($browser != "Unbekannter Browser") {
      if (preg_match("/.+(?:rv|it|ra|ie)[\/: ]([\d.]+)/", $info, $matches)) {
        $version = $matches[1];
      }
    }

    // Betriebssystem
    if (preg_match("/linux/", $info)) {
      $os = "Linux";
    } elseif (preg_match("/macintosh|mac os x/", $info)) {
      $os = "Mac";
    } elseif (preg_match("/windows|win32/", $info)) {
      $os = "Windows";
    } else {
      $os = "Unbekanntes OS";
    }

    return "<span title=\"$info\">$browser $version ($os)</span>";
  }

  public static function strToCode($string) {
    $suche    = ["/ /", "/Ä/", "/Ö/", "/Ü/", "/ß/", "/ä/", "/ö/", "/ü/"];
    $ersetzen = ["",    "Ae",  "Oe",  "Ue",  "ss",  "ae",  "oe",  "ue"];
    return preg_replace($suche, $ersetzen, $string);
  }

  public static function strToLink($string) {
    return str_replace(" ", "_", $string);
  }

  public static function verboten($recht) {
    global $DSH_BENUTZER, $DSH_TITEL, $CODE;
    if (!$DSH_BENUTZER->hatRecht($recht)) {
      einbinden("Fehler/403");
      \Anfrage::setTyp("Seite");
      \Anfrage::setRueck("Titel",  $DSH_TITEL);
      \Anfrage::setRueck("Code",   $CODE);
      \Anfrage::ausgeben();
      die();
    }
  }

  public static function boese($string) {
  	// onevent
  	if (preg_match("/ [oO][nN][a-zA-Z]* *=[^\\\\]*/", $string)) {return true;}
  	// <script>
  	if (preg_match("/<[sS][cC][rR][iI][pP][tT].*>/", $string)) {return true;}
  	// data:
  	if (preg_match("/=['\"]?data:(application\\/(javascript|octet-stream|zip|x-shockwave-flash)|image\\/(svg\+xml)|text\\/(javascript|x-scriptlet|html)|data\\/(javascript))[;,]/", $string)) {
  		return true;
  	}
  	preg_match_all("/(.[^ ])*[jJ](&.*;)*[aA](&.*;)*[vV](&.*;)*[aA](&.*;)*[sS](&.*;)*[cC](&.*;)*[rR](&.*;)*[iI](&.*;)*[pP](&.*;)*[tT](&.*;)*(:|;[cC][oO][lL][oO][nN])/", $string, $matchjs);
  	preg_match_all("/javascript:cms_download\('([-a-zA-Z0-9]+\/)*[\-\_a-zA-Z0-9]{1,244}\.((tar\.gz)|([a-zA-Z0-9]{2,10}))'\)/", $string, $matchdown);

  	if (count($matchjs[0]) != count($matchdown[0])) {
  		return true;
  	}
  	return false;
  }

  /**
   * Prüft ob die Person im Session-Cookie angemeldet ist
   * @return bool true wenn angemeldet, false sonst
   */
  public static function angemeldet() : bool {
    global $DSH_BENUTZER;
    if(session_status() === PHP_SESSION_NONE) {
      session_start();
    }
    $angemeldet = false;
    if (isset($_SESSION["Benutzer"])) {
      $DSH_BENUTZER = $_SESSION["Benutzer"];
      $angemeldet = $DSH_BENUTZER->angemeldet();
    }
    return $angemeldet;
  }

  /**
   * Prüft den Datenschutzcookie
   * @param  string $typ Typ des Datenschutzcookies
   * @return bool        true, wenn Datenschutz zugestimmt, sonst false
   */
  public static function einwilligung($typ = null) : bool {
    // Datenschutzcookies verwalten
    if (!isset($_COOKIE["EinwilligungDSH"])) {
      setcookie("EinwilligungDSH", "nein", time()+30*24*60*60, "/");
      $_COOKIE["EinwilligungDSH"] = "nein";
    } else {
      if ($_COOKIE["EinwilligungDSH"] == "ja") {
        if(session_status() === PHP_SESSION_NONE) {
          session_start();
        }
      }
    }
    if (!isset($_COOKIE["EinwilligungEXT"])) {
      setcookie("EinwilligungEXT", "nein", time()+30*24*60*60, "/");
      $_COOKIE["EinwilligungEXT"] = "nein";
    }

    $typen = ["DSH", "EXT"];
    if (!in_array($typ, $typen)) {return false;}

    if ($_COOKIE["Einwilligung{$typ}"] == "ja") {
      return true;
    } else {
      return false;
    }
  }
}

?>
