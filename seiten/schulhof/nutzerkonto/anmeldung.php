<?php
if (Kern\Check::angemeldet()) {
  Anfrage::setRueck("Weiterleitung", true);
  Anfrage::setRueck("Ziel", "Schulhof/Nutzerkonto");
  Anfrage::ausgeben();
  die();
}

return new Kern\Seite(
  "Anmeldung",
  UI\Zeile::standard(new UI\SeitenUeberschrift("Schulhof")),
  new UI\Spalte(
    "A2",
    new UI\Ueberschrift(2, "Anmeldung"),
    new UI\Meldung(
      "Kompatibilität prüfen",
      "Es wird geprüft, ob Ihr Browser unterstützt wird...",
      "Arbeit",
      [
        "id" => "dshBrowsercheckLaden"
      ]
    ),
    new UI\Meldung(
      "Kompatibilität prüfen",
      "Dieser Browser unterstützt alle Funktionen des Digitalen Schulhofs.",
      "Erfolg",
      [
        "icon" => new UI\Icon(""),
        "id" => "dshBrowsercheckErfolg",
        "style" => [
          "display" => "none"
        ]
      ]
    ),
    new UI\Meldung(
      "Kompatibilität prüfen",
      "<b>Dieser Browser unterstützt möglicherweise nicht alle Funktionen des Digitalen Schulhofs!</b>",
      "Fehler",
      [
        "icon" => new UI\Icon(""),
        "id" => "dshBrowsercheckFehler",
        "style" => [
          "display" => "none"
        ]
      ]
    ),
    new UI\Meldung(
      "Kompatibilität prüfen",
      "Dieser Browser konnte nicht erkannt werden! Um sicherzustellen, dass alle Funktionen des Digitalen Schulhofs verwendet werden können, muss ein aktueller Browser verwendet werden. <a href=\"https://digitaler-schulhof.de/Wiki/Browser\" rel=\"noopener\" target=\"_blank\" class=\"dshExtern\">Hier</a> finden Sie eine Liste an Browsern, die offiziell unterstützt werden.",
      "Warnung",
      [
        "id" => "dshBrowsercheckUnsicher",
        "title" => "Fehlt Ihr Browser? Lassen Sie es uns über GitHub wissen! :)",
        "style" => [
          "display" => "none"
        ]
      ]
    ), // TODO: Browserliste
    new UI\Meldung(
      "Langsame Internetverbindung",
      "Es wurde eine langsame Internetverbindung festgestellt. Für ein bestmögliches Erlebnis ist eine schnelle Internetverbindung notwendig.",
      "Warnung",
      [
        "icon" => new UI\Icon("fas fa-wifi"),
        "id" => "dshBrowsercheckInternetM",
        "style" => [
          "display" => "none"
        ]
      ]
    ),
    new UI\Meldung(
      "Sehr langsame Internetverbindung",
      "Es wurde eine sehr langsame Internetverbindung festgestellt. Gewisse Bereiche des Digitalen Schulhofs sind nur eingeschränkt nutzbar!",
      "Fehler",
      [
        "icon" => new UI\Icon("fas fa-wifi"),
        "id" => "dshBrowsercheckInternetL",
        "style" => [
          "display" => "none"
        ]
      ]
    ),
    new UI\FormularTabelle(
      "kern.schulhof.nutzerkonto.anmelden()",
      new UI\FormularFeld(
        new UI\InhaltElement("Benutzer:"),
        new UI\Textfeld("dshAnmeldungBenutzer"),
        [
          "autocomplete" => "username"
        ]
      ), // @TODO: Platzhalter für den Benutzernamen
      new UI\FormularFeld(
        new UI\InhaltElement("Passwort:"),
        new UI\Passwortfeld("dshAnmeldungPasswort"),
        [
          "autocomplete" => "current-password"
        ]
      ),
      new UI\Knopf(
        "Anmelden",
        [
          "art" => "Erfolg",
          "submit" => true,
          "klassen" => ["autofocus"]
        ]
      ),
      new UI\Knopf(
        "Zugangsdaten vergessen",
        [
          "href" => "Schulhof/Zugangsdaten_vergessen"
        ]
      ),
      new UI\Knopf(
        "Registrieren",
        [
          "href" => "Schulhof/Registrierung"
        ]
      )
    )
  ),
  new UI\Spalte(
    "A2",
    new UI\Ueberschrift(2, "Digitaler Schulhof"),
    new UI\Absatz(
      new UI\IconKnopf(
        new UI\Icon(UI\Konstanten::ANDROID),
        "Andorid",
        [
          "href" => "https://play.google.com/store/apps/details?id=de.dsh",
          "rel" => "noopener",
          "klassen" => [
            "dshExtern"
          ]
        ]
      ),
      new UI\IconKnopf(
        new UI\Icon(UI\Konstanten::APPLE),
        "iOS",
        [
          "href" => "https://apps.apple.com/de/app/digitaler-schulhof/id1500912100",
          "rel" => "noopener",
          "klassen" => [
            "dshExtern"
          ]
        ]
      ),
      new UI\IconKnopf(
        new UI\Icon("fab fa-github"),
        "GitHub",
        [
          "href" => "https://github.com/DigitalerSchulhof",
          "rel" => "noopener",
          "klassen" => [
            "dshExtern"
          ]
        ]
      ),
    ),
    new UI\Absatz(
      [
        "display" => "none",
        "id" => "dshPWAInstallation"
      ],
      new UI\GrossIconKnopf(
        new UI\Icon("fas fa-cloud-download-alt"),
        "Direkt installieren",
        [
          "art" => "Information",
          "onclick" => "core.a2hs.install()"
        ]
      )
    )
  ),
  new UI\Ueberschrift(2, "Links"),
  new UI\Absatz("Links folgen"),
  new UI\Script(
    "kern.schulhof.oeffentlich.browsercheck();"
  )
);
