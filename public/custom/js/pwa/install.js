"use strict";

// Check if the device is in standalone mode
var isInStandaloneMode = () => {
    return (
        "standalone" in window.navigator &&
        window.navigator.standalone
    );
};

let  showIosInstallModal=function() {
    // Detect if the device is on iOS
    const isIos = () => {
        const userAgent = window.navigator.userAgent.toLowerCase();
        return /iphone|ipad|ipod/.test(userAgent);
    };

    
    const checkCallFrequency = () => {
        // Get current date and time
        const currentTime = new Date();
        // Retrieve stored last call time from localStorage
        const lastCallTime = localStorage.getItem('lastCallTime');
        // If last call time is not stored, or if it's a different day, reset the count
        if (!lastCallTime || new Date(lastCallTime).getDate() !== currentTime.getDate()) {
            localStorage.setItem('lastCallTime', currentTime);
            localStorage.setItem('callCount', 1);
            return true;
        }
        // If it's the same day, increment the call count
        let callCount = parseInt(localStorage.getItem('callCount')) || 0;
        callCount++;
        localStorage.setItem('callCount', callCount);
        // Return true if called once or twice, false otherwise
        return callCount <= 2000;
    }

    const shouldShowModalResponse = isIos() && !isInStandaloneMode()  && checkCallFrequency() ;
    return shouldShowModalResponse;
}

function resetRoutine() {
    localStorage.removeItem('lastCallTime');
    localStorage.removeItem('callCount');
}

//resetRoutine();

if (showIosInstallModal()){
    function openMyModal() {
       //title
        const modalTitle = document.getElementById('modalTitle');
        modalTitle.textContent = "Install " + PWA_name;
        //boyText
        let  bodyText= " <p class='fs-5'>  Install this application on your home screen for quick and easy access when you are on the go </p>";
        const modalBody = document.getElementById('modalBodyID');
        modalBody.innerHTML = bodyText; 
        //footer
        // Assuming you have an existing modal with the ID "myModal"
        const myModal = document.querySelector("#myModal");
        // Create the footer content
        const footerContent = document.createElement("div");
        footerContent.classList.add("modal-footer", "d-flex", "justify-content-center", "align-items-center");
        const textElement1 = document.createElement("p");
        textElement1.classList.add("me-2", "fs-5");
        textElement1.textContent = "Just tap";
        const imgElement = document.createElement("img");
        imgElement.src = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAACXBIWXMAAAsTAAALEwEAmpwYAAABM0lEQVR4nO2YQWrDMBBF5xSJ6ckEGsg5igS6W7NJV3GhNO1BuvBsVBTkYozlFKq0X8l8GCxsbOvN/7aFia4sDnGTilrWLsTOOnlLlcbUosxj3Fonr+wlprJePkz4fKCWIbhFmF2IHXs55cmP2+n4BB8zM3XCyXvq/jdIiJvxGLQzZgEi7R9BzmN0GFOAmINAw5gViCUQWBjr5WXtIV4Cmb8UrJOe/lvsh2f2ciy9iUogkw9mb91wIHTxCkhTYgUBkzpSU/NFX6msH/aXrlE6ns796X3o2iDshqcmQOgXgrgGI0yCFASsm6SOgHWT1BGwbpI6AtZNUkfAuknqCFg3CcSR8zJ9ZZn/J/NgkB8HrCBZ6khlsUYrS6NVWazRytJoVRZrtLI0WpXFGq1bjRaDFN0DyBepFQErw7dHKQAAAABJRU5ErkJggg=="
        imgElement.alt = "Add to Home Screen";
        const textElement2 = document.createElement("p");
        textElement2.textContent = "then 'Add to Home Screen'";
        textElement2.classList.add("me-2", "fs-5");

        // Append all elements to the footerContent div
        footerContent.appendChild(textElement1);
        footerContent.appendChild(imgElement);
        footerContent.appendChild(textElement2);

        // Append the footerContent to the modal footer
        myModal.querySelector(".modal-footer").appendChild(footerContent);


        // Append the elements to the footer
        footerContent.appendChild(textElement1);
        footerContent.appendChild(imgElement);
        footerContent.appendChild(textElement2);

        // Append the footer to the modal
        myModal.querySelector(".modal-content").appendChild(footerContent);


        //Now Show it
        const modalEl = document.getElementById('myModal');
        const bsModal = new bootstrap.Modal(modalEl);
        bsModal.show();
    }

    // Call the function to open the modal
    openMyModal();
}

let showAndroidInstallModal=function(){
    let deferredPrompt;
    let promptCount = 0;

    // Function to check if prompt can be shown
    function canShowPrompt() {
        // Reset prompt count if new day
        const lastPromptDate = localStorage.getItem('lastPromptDate');
        const today = new Date().toDateString();
        if (lastPromptDate !== today) {
            localStorage.setItem('lastPromptDate', today);
            promptCount = 0;
        }
        // Check if prompt count is less than 3
        return promptCount < 30;
    }

    window.addEventListener('beforeinstallprompt', (e) => {
        e.preventDefault(); // Prevent default Chrome prompt

        if (canShowPrompt()) {
            deferredPrompt = e; // Save the event for later
            let infoBar = document.querySelector('#custom-info-bar');
            if (infoBar) {
                infoBar.style.display = ''; // Show custom install button

                let installBtn = document.querySelector('#custom-install-button');
                installBtn.addEventListener('click', (e) => {
                    deferredPrompt.prompt(); // Show prompt
                    deferredPrompt.userChoice.then((choiceResult) => {
                        if (choiceResult.outcome === 'accepted') {
                            infoBar.style.display = 'none'; // Hide info bar
                            deferredPrompt = null; // Reset deferredPrompt
                            promptCount++; // Increment prompt count
                            localStorage.setItem('promptCount', promptCount);
                        }
                    });
                });
            }
        }
    });

    // Check and set prompt count on page load
    window.addEventListener('load', () => {
        const storedPromptCount = localStorage.getItem('promptCount');
        if (storedPromptCount) {
            promptCount = parseInt(storedPromptCount);
        }
    });
}
showAndroidInstallModal()