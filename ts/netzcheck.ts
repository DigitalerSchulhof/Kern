const netzcheck = () => {
  new Promise((fertig, fehler) => {
    const start = new Date().getTime();

    const http = new XMLHttpRequest();
    http.onreadystatechange = () => {
      if (http.readyState === 4) {
        const ms = new Date().getTime() - start;
        if (http.status !== 0) {
          fertig(ms);
        } else {
          fehler(ms);
        }
      }
    };
    http.onerror = () => {
      const ms = new Date().getTime() - start;
      fehler(ms);
    };
    http.open("HEAD", "ping.php", true);
    http.send(null);
  }).then(
    (): void => {
      setTimeout(netzcheck, 60000);
    },
    (): void => {
      setTimeout(netzcheck, 60000);
    }
  );
};

netzcheck();