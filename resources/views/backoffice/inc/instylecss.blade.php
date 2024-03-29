<style>
    /* Hide the spinner by default */
/*
#spinner {
  display: none;
}
/* Show the spinner when the body has the 'busy' class 
body.busy #spinner {
  display: block;
}
*/


#spinner:not([hidden]) {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    display: flex;
    justify-content: center;
    align-items: center;
}
#spinner::after {
    content: "";
    width: 80px;
    height: 80px;
    border: 2px solid #f3f3f3;
    border-top: 3px solid #f25a41;
    border-radius: 100%;
    -JS-animation: spin 1s linear infinite; 
	animation: spin 1s linear infinite;
    /*
    will-change: transform;
    animation: spin 1s infinite linear;
    */    
}


#spinner_modal:not([hidden]) {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    display: flex;
    justify-content: center;
    align-items: center;
}
#spinner_modal::after {
    content: "";
    width: 80px;
    height: 80px;
    border: 2px solid #f3f3f3;
    border-top: 3px solid #f25a41;
    border-radius: 100%;
    -JS-animation: spin 1s linear infinite; 
	animation: spin 1s linear infinite;
    /*
    will-change: transform;
    animation: spin 1s infinite linear;
    */
}
/*
@keyframes spin {
    from {
        transform: rotate(0deg);
   }
    to {
        transform: rotate(360deg);
   }
}
*/
@-JS-keyframes spin {
    0% { -JS-transform: rotate(0deg); }
    100% { -JS-transform: rotate(360deg); }
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.form-check-input {
    clear: left;
  }
  
  .form-switch.form-switch-sm {
    margin-bottom: 0.5rem; /* JUST FOR STYLING PURPOSE */
  }
  
  .form-switch.form-switch-sm .form-check-input {
    height: 1rem;
    width: calc(1rem + 0.75rem);
    border-radius: 2rem;
  }
  
  .form-switch.form-switch-md {
    margin-bottom: 1rem; /* JUST FOR STYLING PURPOSE */
    margin-right: .5rem;
  }
  
  .form-switch.form-switch-md .form-check-input {
    height: 1.5rem;
    width: calc(2rem + 0.75rem);
    border-radius: 3rem;
  }
  
  .form-switch.form-switch-lg {
    margin-bottom: 1.5rem; /* JUST FOR STYLING PURPOSE */
  }
  
  .form-switch.form-switch-lg .form-check-input {
    height: 2rem;
    width: calc(3rem + 0.75rem);
    border-radius: 4rem;
  }
  
  .form-switch.form-switch-xl {
    margin-bottom: 2rem; /* JUST FOR STYLING PURPOSE */
  }
  
  .form-switch.form-switch-xl .form-check-input {
    height: 2.5rem;
    width: calc(4rem + 0.75rem);
    border-radius: 5rem;
  }

 .accordion-button{
      font-size: inherit;
  }
  
  .accordion {
    --bs-accordion-active-bg: purple;
  }
  
  
  .accordion-item{
     border:1px solid rgba(35,65,100, .15);
  }

  .accordion-button:not(.collapsed) {
      color:inherit;
      background: rgb(35,65,100);
      color:#e8e8e8;
  }
  .accordion-button::focus{
      box-shadow: inherit;
  }

  /* Partial color of Review Star */
 :root {
  --star-size: 35px;
  --star-color: #fff;
  --star-background: #fc0;
}

.stars-landing {
  --percent: calc(var(--rating) / 5 * 100%);
  display: inline-block;
  font-size: var(--star-size);
  /* font-family: Times; */
  line-height: 1; 
  text-align:left;
  letter-spacing:-8px;
  padding-left:0px;
  padding-right:0px;
  margin-top: -2em; 
  padding-top: 0px; /* Set top padding to 0 */
}

.stars-landing::before {
  content: '★★★★★';
  letter-spacing:-8px;
  background:
    linear-gradient(90deg, var(--star-background) 0%, var(--star-background) var(--percent), rgba(0,0,0,1) var(--percent), rgba(0,0,0,1) 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  transform: translatey(35px);
  display: block;
  position: relative;
  /* font-family: Times; */
}

</style>
