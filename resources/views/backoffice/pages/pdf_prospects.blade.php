<!DOCTYPE html>
    <html>
    <head>
     <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assessment Details</title>
  
    <style>
                
        p {
            margin-top: 0;
            margin-bottom: 1rem;
        }

        .radio-button {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
            color: #212529;
            font-size: 1rem;
            line-height: 1.5;
         }

        .radio-button label {
            display: inline-block;
            padding-left: 2rem;
            position: relative;
            cursor: pointer;
            user-select: none;
        }

        .radio-button input[type="radio"] {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            margin-top: 0.3rem;
            margin-right: 0.3rem;
            width: 1rem;
            height: 1rem;
            position: relative;
            border: none;
            background: none;
        }

        .radio-button input[type="radio"]::before {
            content: "==";
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: #adb5bd;
            font-size: 0.8rem;
        }

        .radio-button input[type="radio"]:checked::before {
            content: "";
            width: 1rem;
            height: 1rem;
            border: 1px solid #adb5bd;
            border-radius: 50%;
            background-color: #fff;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

         .radio-button input[type="radio"]:checked::after {
            content: "";
            width: 0.5rem;
            height: 0.5rem;
            border-radius: 50%;
            background-color: #0d6efd;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
         }

        textarea {
            display: block;
            width: 100%;
            padding: 0.5rem 1rem;
            font-size: 1rem;
            line-height: 1.5;
            color: #212529;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }


        textarea[disabled],
            fieldset[disabled] textarea {
            background-color: #eee;
            opacity: 1;
            cursor: not-allowed;
        }
       
         .text-center {
            text-align: center !important;
        }
        
        h3 {
             text-align: center;
             color:green
        }


        /* Basic table styles */
        table {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            vertical-align: top;
        }
        th {
            background-color: #f2f2f2;
            text-align: left;
        }
        
        /* Table caption styles */
        .table-caption {
            font-weight: bold;
            text-align: center;
            background-color: #f9f9f9;
            font-size: 1.2em;
            padding: 10px;
        }

        /* Float label styles (CSS2 equivalent) */

        .float-label {
            display: block;
            font-size: 0.85em;
            color: #999;
            margin-bottom: 0.5em; /* Space between title and name */
        }

        .name-label {
            display: block;
            font-size: 1em;
            color: #000;
            word-wrap: break-word; /* Allows long words to be able to break and wrap onto the next line */
        }

        /* Adjustments for table data cells */
        td {
            position: relative; /* Establishes a positioning context for absolute positioning of float-label */
            padding-top: 1.5em; /* Adds space at the top for the float-label */
        }

        /* Ensuring the float-label is positioned correctly */
        .float-label {
            position: absolute;
            top: 0;
            left: 0;
        }

        .italic-right {
            font-style: italic;
            text-align: right;
            /* Additional styling (optional) */
            float: right; /* For older browsers with limited right-alignment support */
        }
         
        .horizontal-line {
            border-top: 1px solid black; /* Adjust color and thickness as needed */
            width: 100%; /* Optional: Sets the line to full width */
            margin: 10px 0; /* Optional: Adds spacing above and below the line */
        }
    </style>
 
    </head>
    <body>

       @include('backoffice.inc.pdf_serviceUserFields')

    </body>
</html>