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
          $("#dshBrowsercheckInternet").style.display = "";
          $("#dshBrowsercheckInternet").title = "Antwortzeit: " + ms + "ms";
        }
        if(unterstuetzt === true) {
          $("#dshBrowsercheckLaden").style.display  = "none";
          $("#dshBrowsercheckErfolg").style.display = "";
        } else if(unterstuetzt === false) {
          $("#dshBrowsercheckLaden").style.display  = "none";
          $("#dshBrowsercheckFehler").style.display = "";
        } else {
          $("#dshBrowsercheckLaden").style.display  = "none";
          $("#dshBrowsercheckUnsicher").style.display = "";
        }
        if(icon !== null) {
          $("#dshBrowsercheckErfolg").querySelector("i.icon.i1").classList.add(...icon.split(" "));
          $("#dshBrowsercheckFehler").querySelector("i.icon.i1").classList.add(...icon.split(" "));
        }
      });
    }, 333);
  },
  brclick: function (ev) {
    let t  = ev.target;
    if(!t.classList.contains("dshUiFormular")) {
      return;
    }
    let ben = t.querySelector("#dshAnmeldungBenutzer");
    if(ev.offsetX < 0) {
      ben.placeholder = "jesper";
    }
    if(ev.offsetX > t.clientWidth) {
      ben.placeholder = "patrick";
    }
  }
};