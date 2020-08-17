(() => {
  var netzcheck = () => {
    new Promise((fertig, fehler) => {
      let start = new Date().getTime();

      let http = new XMLHttpRequest();
      http.onreadystatechange = () => {
        if(http.readyState === 4) {
          let ms = new Date().getTime() - start;
          if(http.status !== 0) {
            fertig(ms);
          } else {
            fehler(ms);
          }
        }
      };
      http.onerror = () => {
        let ms = new Date().getTime() - start;
        fehler(ms);
      };
      http.open("HEAD", "ping.php", true);
      http.send(null);
    }).then((ms) => {
      $("#dshNetzcheck").removeKlasse("offline");
      setTimeout(netzcheck, 60000);
    },
    (ms) => {
      $("#dshNetzcheck").addKlasse("offline");
      setTimeout(netzcheck, 60000);
    });
  };
  netzcheck();
})();