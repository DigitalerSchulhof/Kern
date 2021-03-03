<?php
return new Kern\Seite(
  "Identitätsdiebstahl",
  new UI\Zeile(
    new UI\Spalte("A1"),
    new UI\SeitenUeberschrift("Identitätsdiebstahl melden"),
    new UI\Meldung(
      "Identitätsdiebstahl",
      new UI\Absatz(
        "Ein Identitätsdiebstahl liegt nur vor, wenn dieses Nutzerkonto benutzt wurde, ohne dass die Benutzung vom Besitzer des Kontos ausging. Diese Funktion ist nicht leichtfertig zu benutzen, denn sie löst eine Reihe an Folgetätigkeiten (Sicherheitsprüfungen, Datenschutzvorkehrungen, Informieren der Schulgemeinschaft, ...) aus.",
        "Diese Funktion darf nicht zum leichtfertigen Ändern des Passworts verwendet werden!!",
        "Der Verdacht auf einen Identitätsdiebstahl ist meldepflichtig!",
        "Rückfragen durch die Administration sind sehr wahrscheinlich."
      ),
      [
        "art" =>  "Warnung",
        "icon" => new UI\Icon("fas fa-user-secret")
      ]
    ),
    new UI\FormularTabelle(
      "kern.schulhof.nutzerkonto.identitaetsdiebstahl()",
      new UI\FormularFeld(
        new UI\InhaltElement("Altes Passwort:"),
        new UI\Passwortfeld(
          "dshIdentitaetPasswortAlt",
          ["autocomplete" => "password"]
        )
      ),
      ($passwortFeld = new UI\FormularFeld(
        new UI\InhaltElement("Neues Passwort:"),
        new UI\Passwortfeld(
          "dshIdentitaetPasswortNeu",
          ["autocomplete" => "password"]
        )
      )),
      new UI\FormularFeld(
        new UI\InhaltElement("Neues Passwort bestätigen:"),
        [
          "bezug" => $passwortFeld,
          "autocomplete" => "password"
        ]
      ),
      new UI\FormularFeld(
        new UI\InhaltElement("Hinweise / Bemerkungen:"),
        new UI\Textarea("dshIdentitaetHinweise")
      ),
      new UI\Knopf(
        "Identitätsdiebstahl melden",
        [
          "art" => "Warnung",
          "submit" => true
        ]
      )
    ),
  )
);
