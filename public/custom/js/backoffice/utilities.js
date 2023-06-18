let getRandomToken=function(keyLength) {
    let i, key = "", characters = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    let charactersLength = characters.length;
    for (i = 0; i < keyLength; i++) {
        key += characters.substr(Math.floor((Math.random() * charactersLength) + 1), 1);
    }
    return key;
}

let  getIdsStartingWith =function(startString) {
    let ids = [];
    let allElements = document.getElementsByTagName("*");
    for (let i = 0; i < allElements.length; i++) {
        let elementId = allElements[i].id;
        if (elementId.startsWith(startString)) {
            ids.push(elementId);
        }
    }
    return ids;
}
