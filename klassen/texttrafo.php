<?php
namespace Kern;

class Texttrafo {
  private function __construct() {}

  /**
   * Wandelt einen Text bzw. ein Array dessen in gültige URL-Zeichen bzw. ein Array dieser um.
   * <code>&nbsp;</code> -> <code>_</code>
   * <code>ä</code> -> <code>ae</code>
   * <code>ö</code> -> <code>oe</code>
   * <code>ü</code> -> <code>ue</code>
   * <code>ß</code> -> <code>sz</code>
   * @param  string|string[] $text :)
   * @return string
   */
  public static function text2url($text) {
    if(is_array($text)) {
      $r = [];
      foreach($text as $k => $t) {
        $r[$k] = self::text2url($t);
      }
      return $r;
    } else {
      $text = str_replace(" ", "_" , $text);
      $text = str_replace("Ä", "Ae", $text);
      $text = str_replace("Ö", "Oe", $text);
      $text = str_replace("Ü", "Ue", $text);
      $text = str_replace("ä", "ae", $text);
      $text = str_replace("ö", "oe", $text);
      $text = str_replace("ü", "ue", $text);
      $text = str_replace("ß", "sz", $text);
    }
    return $text;
  }

  /**
   * Wandelt gültige URL-Zeichen bzw. ein Array dieser in einen Text bzw. ein Array dessen um.
   * <code>&nbsp;</code> <- <code>_</code>
   * <code>ä</code> <- <code>ae</code>
   * <code>ö</code> <- <code>oe</code>
   * <code>ü</code> <- <code>ue</code>
   * <code>ß</code> <- <code>sz</code>
   * @param  string|string[] $text :)
   * @return string
   */
  public static function url2text($text) {
    if(is_array($text)) {
      $r = [];
      foreach($text as $k => $t) {
        $r[$k] = self::url2text($t);
      }
      return $r;
    } else {
      $text = str_replace("_" , " ", $text);
      $text = str_replace("Ae", "Ä", $text);
      $text = str_replace("Oe", "Ö", $text);
      $text = str_replace("Ue", "Ü", $text);
      $text = str_replace("ae", "ä", $text);
      $text = str_replace("oe", "ö", $text);
      $text = str_replace("ue", "ü", $text);
      $text = str_replace("sz", "ß", $text);
    }
    return $text;
  }
}

?>