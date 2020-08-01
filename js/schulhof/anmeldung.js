kern.schulhof.anmeldung = {
  browsercheck: () => {
    if($("#dshBrowsercheckLaden").length === 0) {
      return;
    }
    setTimeout(() => {
      var browser = (() => {
        var ua=navigator.userAgent;
        var tem;
        var M = ua.match(/(opera|chrome|safari|firefox|msie|trident(?=\/))\/?\s*(\d+)/i) || [];
        if(/trident/i.test(M[1])) {
          tem=/\brv[ :]+(\d+)/g.exec(ua) || [];
          return {name:'IE',version:(tem[1]||'')};
        }
        if(M[1] === 'Chrome') {
          tem=ua.match(/\bOPR|Edge\/(\d+)/);
          if(tem != null) {
            return {name:'Opera', version:tem[1]};
          }
        }
        M = M[2] ? [M[1], M[2]] : [navigator.appName, navigator.appVersion, '-?'];
        if((tem = ua.match(/version\/(\d+)/i)) != null) {
          M.splice(1,1,tem[1]);
        }
        return { name: M[0], version: M[1]};
      })();
      var icon = null;
      var unterstuetzt = false;
      switch(browser.name) {
        case "Safari":
          icon          = "fab fa-safari";
          unterstuetzt  = browser.version >= 12;
          break;
        case "Firefox":
          icon          = "fab fa-firefox-browser";
          unterstuetzt  = browser.version >= 70;
          break;
        case "Opera":
          icon          = "fab fa-opera";
          unterstuetzt  = browser.version >= 50;
          break;
        case "Chrome":
          icon          = "fab fa-chrome";
          unterstuetzt  = browser.version >= 63;
          break;
        case "Edge":
          icon          = "fab fa-edge";
          unterstuetzt  = browser.version >= 38;
          break;
        default:
          unterstuetzt  = undefined;
      };
      new Promise((fertig) => {
        let start = new Date().getTime();

        let http = new XMLHttpRequest();
        http.onreadystatechange = () => {
          if(http.readyState === 4) {
            let ms = new Date().getTime() - start;
            if(http.status !== 0) {
              fertig(ms);
            } else {
              fertig(null);
            }
          }
        };
        http.onerror = () => {
          let ms = new Date().getTime() - start;
          fertig(null);
        };
        http.open("HEAD", "ping.php", true);
        http.send(null);
      }).then((ms) => {
        if(ms !== null && ms > 100) {
          $("#dshBrowsercheckInternet").einblenden();
          $("#dshBrowsercheckInternet").setAttr("title", "Antwortzeit: " + ms + "ms");
        }
        $("#dshBrowsercheckLaden").ausblenden();
        if(unterstuetzt === true) {
          $("#dshBrowsercheckErfolg").einblenden();
        } else if(unterstuetzt === false) {
          $("#dshBrowsercheckFehler").einblenden();
        } else {
          $("#dshBrowsercheckUnsicher").einblenden();
        }
        if(icon !== null) {
          $("#dshBrowsercheckErfolg i.dshUiIcon.i1", "#dshBrowsercheckFehler i.dshUiIcon.i1").addKlasse(...icon.split(" "));
        }
      });
    }, 333);
  },
  anmelden: () => {
    var benutzer = $("#dshAnmeldungBenutzer").getWert();
    var passwort = $("#dshAnmeldungPasswort").getWert();

    core.ajax("Kern", 0, ["Anmeldung", "Anmeldedaten werden überprüft"], {benutzer: benutzer, passwort: passwort});
  }
};
