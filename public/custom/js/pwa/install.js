"use strict";
//alert(PWA_name)
let  showIosInstallModal=function() {
    // Detect if the device is on iOS
    const isIos = () => {
        const userAgent = window.navigator.userAgent.toLowerCase();
        return /iphone|ipad|ipod/.test(userAgent);
    };

    // Check if the device is in standalone mode
    const isInStandaloneMode = () => {
        return (
            "standalone" in window.navigator &&
            window.navigator.standalone
        );
    };

    // Show the modal only once
    //const localStorageKeyValue = localStorage.getItem(localStorageKey);
    //const iosInstallModalShown = localStorageKeyValue
    //    ? JSON.parse(localStorageKeyValue)
    //    : false;
    const shouldShowModalResponse =
        isIos() && !isInStandaloneMode()  //&& !iosInstallModalShown;
    //if (shouldShowModalResponse) {
    //    localStorage.setItem(localStorageKey, "true");
    //}
    return shouldShowModalResponse;
}

alert(showIosInstallModal())