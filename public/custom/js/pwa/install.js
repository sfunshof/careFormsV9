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
        return callCount <= 2;
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
        modalTitle.textContent = "Install Spot Check";
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
        textElement1.classList.add("me-2");
        textElement1.classList.add("fs-5");
        textElement1.textContent = "Just tap";
        const imgElement = document.createElement("img");
        imgElement.src = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAGQAAABkCAYAAABw4pVUAAAACXBIWXMAAAsTAAALEwEAmpwYAAACy0lEQVR4nO2azW7TQBhF3ccru+5o/FUqrHiGeHhVWNAV8AAdhxLkdEAizZ9jx+cqvkeaTWLLx/d+EyWRq8qYsYmU33fLyQoQKT9Gyr/KeqR9Zk00+UPd5JdI7fp15d8Pn9tPtNcsiTdluBTBMlrvFL0yWpeiV0brUi5NnfLH7TJ2lbPrmO7ciwvOidi5M16/Ub3ZFQeOpe/jKogjAW8Xcso55kJl7Ctk87pLGZc4MdB9hbgUoIxjhWze904ZRvQM8FghLmXCMk4tZHOcd0o/zg0sTizEpUxQRt9CNsd7pxxmkfJiyK/r6FnIoV/9nUs1b9Y3dco/h/zVEWcUsq+UaPKPzqmaL+ubLoQh/zvFmYXsKqVO+fvMC6mqevl815XShVE3+b7v+UMK2Vy/yff/rr98vut7vhm5EDMy4UK0CBeiRbgQLcKFaBEuRItwIVqEC9EiXIgW4UJ2BzF01an9sliubqcu5CGt3nXXHvt+qqkZ+wZKKU9DPXqf37TfLnEv1dS4kPa6C6E+shbL1e1VfmRNLlCwh4PQHAxcoGAPB6E5GLhAwR4OQnMwcIGCPRyE5mDgAgV7OAjNwcAFCvZwEJqDgQsU7OEgNAcDFyjYw0FoDgYuULCHg9AcDFygYA8HoTkYuEDBHg5CczBwgYI9HITmYOACBXs4CM3BwAUK9nAQmoOBCxTqpv3616F7aLqCwPPABf5/ev2pW+c8PX81eeACYuB54AJi4HngAmLgeeACYuB54AJi4HngAmLgeeACYuB54AJi4HngAmLgeeACYuB54AJi4HngAmLgeeACYuB54AJi4HngAmLgeeACYuB54AJi4HngAmLgeeACYuB54AJi4HngAmLgeeACYuB54AJi4HngAmLgeeACYuB54AJi4HngAmLgeeACYuB54AJi4HngAmLgeWwLeLUuJIQHwTsk8SW4kMQHP3YhfwBDhGSY83tIYQAAAABJRU5ErkJggg=="
        imgElement.alt = "Add to Home Screen";
        const textElement2 = document.createElement("p");
        textElement2.textContent = "then 'Add to Home Screen'";
        textElement2.classList.add("me-2");
        textElement2.classList.add("fs-5");

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