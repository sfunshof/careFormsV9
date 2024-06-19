     {{-- JS Bootrap  --}} 
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
     {{-- End JS Bootrap --}}
     
     {{--  PWA JS files --}}
     <script src="{{ asset('/sw.js') }}"></script>
     <script>
         if ("serviceWorker" in navigator) {
             // Register a service worker hosted at the root of the
             // site using the default scope.
             navigator.serviceWorker.register("/sw.js").then(
             (registration) => {
                 console.log("Service worker registration succeeded:", registration);
             },
             (error) => {
                 console.error(`Service worker registration failed: ${error}`);
             },
             );
         } else {
             console.error("Service workers are not supported.");
         }
     </script>
     {{--  End PWA JS files --}}
     
     <script>
        document.addEventListener("DOMContentLoaded", function() {
             var googleTranslationBanner = document.querySelector(".goog-te-banner-frame.skiptranslate");
             if (googleTranslationBanner) {
                 googleTranslationBanner.style.display = "none";
             }
        });
     </script>    

     {{-- JS Customs  --}}
     <script src="https://cdn.jsdelivr.net/npm/storejs@2.0.6/dist/store.min.js"></script>
     <script src="{{asset('custom/js/mobilespotcheck/fnon.min.js')}}"></script>
    <!-- Flatpickr JS -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>