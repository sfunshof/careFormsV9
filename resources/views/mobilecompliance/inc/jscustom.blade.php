     {{-- JS Bootrap  --}} 
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous" defer></script>
     {{-- End JS Bootrap --}}
     
      
     <script>
        document.addEventListener("DOMContentLoaded", function() {
             var googleTranslationBanner = document.querySelector(".goog-te-banner-frame.skiptranslate");
             if (googleTranslationBanner) {
                 googleTranslationBanner.style.display = "none";
             }
        });
     </script>    

     {{-- JS Customs  --}}
     <script src="https://cdn.jsdelivr.net/npm/storejs@2.0.6/dist/store.min.js" defer></script>
     <script src="{{asset('custom/js/mobilespotcheck/fnon.min.js')}}" defer></script>
    <!-- Flatpickr JS -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr" defer></script>