pageLoader =function(url, scripts) {
    fetch(url)
    .then(response => response.text())
    .then(html => {
        document.open();
        document.write(html);
        document.close();
        //loadScripts(scripts);
         // Dynamically load the scripts
        //for (let script of scripts) {
             //loadScript(script);
        //}
    })
    .catch(error => console.error('Error loading page:', error));
}

function loadScript(src) {
    return new Promise((resolve, reject) => {
        const script = document.createElement('script');
        script.src = src;
        script.onload = () => {
            console.log(`${src} loaded successfully.`);
            resolve();
        };
        script.onerror = () => {
            console.error(`Error loading ${src}.`);
            reject();
        };
        document.head.appendChild(script);
    });
}


/*
function loadScripts(scripts) {
    const scriptPromises = scripts.map(src => {
        return new Promise((resolve, reject) => {
            const script = document.createElement('script');
            script.src = publicPath + scripts;
            alert(script.src)
            script.onload = resolve;
            script.onerror = reject;
            document.body.appendChild(script);
        });
    });

    Promise.all(scriptPromises)
        .then(() => console.log('All scripts loaded'))
        .catch(error => console.error('Error loading scripts:', error));
}    
*/

