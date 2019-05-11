<?php
$UI = "
<h1>About the PHP, JavaScript, HTML, &amp; CSS</h1>
 <ul class=\"FinePrint\">
  <li>JavaScript is used here to validate html form data elements when the Submit button is clicked, 
  before it's packaged into POST data and submitted to
  the resource, FCForm.php, as specified in the form's action property.  
  
  <br /><br />Use ctrl-u or Mac equivalent 
  to look at the membership form's html.  Click the link to the .js file in the &lt;script tag in the
  head of the document. Find the function ValidateForm() in the file FCValidateForm.js 
	ValidateForm alerts the user of validation errors and blocks submission of an incomplete form. </li>

  <li>Try leaving the form empty, or leaving a field or two blank, 
	 before clicking the submit button so you can see how 
	 the form behaves when the function ValidateForm encounters 
	 validation errors in the form's input elements. </li>
  <li>The server-side script is available at /home/skhatiwada/web/FCForm.php.  </li>
  <li>Because it's easy for a cracker or over-cautious web user to defeat JavaScript form validation, 
  the server-side script that receives the form's POST data must also validate the POST data,
  perhaps using server-side resources to validate the input more carefully than browser-side
  JavaScript can.
  <br /><br />
  For security, the receiving script must examine and addslashes or otherwise sanitize any data that
  originates from the web or intranet.  This is to detect, log and prevent
  sql injections, html injections, and to fend off vandals, thieves, and spys of all
  types who surveil our sites and seek to harm it or harvest its data.
  <br /><br />
  Make sure you have your eyes on the PHP and JavaScript before you do much else.  
  </li>  
  <li>A checkbox has been provided near the form's submit button 
  so you can see the effect of defeating the JavaScript validation.
  Uncheck the box before clicking Submit to skip ValidateForm and see how the server also validates form data.</li>

  <li>From the command line find the function  MakeTheForm() at the beginning or FCForm.php. 
  It is coded as a function because the form needs to be made from more than one place in the script.
  At first appearance the form is shown with empty and selections.
  When \$View is 'Submit' it is shown with form data from \$_POST, and
  if there are error messages they are shown and red asterisks placed in the labels for the offending input element.  </li>


  <li>MakeTheForm() uses the semantic elements provided by HTML5 to describe form
    elements in the most accessible way.  Fieldset and label are used to frame
         and describe each input.  The placeholder property is set to describe what needs to be entered in each 
         text box, behaves in a way familiar to web users, and scores high on measures of accessibility 
                  Investigate other properties of form elements that help lead the user
         to submitting a valid form.   jQuery excels at user interface enhancement, is Google's way...</li>


  <li>Note that care has been taken by the developer to name program variables in PHP &amp; JavaScript, 
  html form elements, and fields in the table the same.
  This reduces confusion and PHP's extract function can work to best advantage to get data from 
  super-globals or database results to the browser. </li>
  <li>Never use or allow spaces in 
    table, column, name or id of form elements, or file names!.  Note how str_replace is used to remove
    space from names of states like North Carolina or South Dakota when making ids</li> 


  <li>Check boxes, radio buttons, and select boxes are constructed from lists in text files, not 'by hand'.  
  Several lines of PHP code
  can substitute for hundreds of lines of html where forms are involved, and they can be
  copied/pasted/modified to use in other circumstances..  <br /><br />
  The properties of Other States, StatesVisited, and StateFavorite 
        are loaded by reading text files off the web direcgtory at /home/tinstructor/OptionsOther States and OptionsStates. 
        It's quicker and more flexible to code a little loop to make the controls rather than repeat
        tedious hand-editing for each control.  <br /><br />
         Also, the code can easlity set 'selected' or 'checked' with reference to \$_POST data so that
         choices made by the user are shown when the form is represented for review or with errors.
         Note that the checkboxes for StatesVisited and radio buttons for StateFavorite are built in the same loop.
         <br /><br />
         Users do not like to repeat several fields of data entry when an error is encountered...</li>

  <li>Checkboxes and radio buttons are embedded in label elements as is recommended for accessibility and
         to make a larger 'target' for clicking on a mobile device -- 
         clicking the label, box, or button checks the box or button.</li>
  <li>Since checkboxes and selects allow selection of none, one, or more options an array is
  used to receive post data in the PHP script.  In the HTML, note that StatesVisisted[] and Other States[]
  use empty square brackets to indicate that an array is involved.  </li>

  <li>The Form's onSubmit event calls the JavaScript function ValidateForm().
         If ValidateForm runs without adding any occurances to the array ValidationErrors[] 
         the form is submitted otherwise the windows alert method is used to show the ValidationErrors[] 
         to the user. 
         <br />
         <br />
         At the end of the ValidateForm() function 'false' is returned if there are ValidationErrors, else
         'true' is returned.  
         <br /><br />
         A false returned from a form's onSubmit event script blocks the form's submission and 
         returns control to the browser so the form's user may make corrections.  
         This preserves the data in the fields as entered by the user
         when control is returned to the browser. Or, JavaScript may make changes to 
         any of the properties of the window's or form's elements.
         <br /><br />  
         If true is returned by ValidateForm, the form's submission continues
         to the script specified in the form's method, in this case FCForm.php.</li>
         
  <li>PLEASE NOTE: Following a rejected form, the POST data in the form is returned to the user so they 
  don't need to rekey or re-check or re-select all the data if a field is missing or not valid.  </li>

  <li>This demo stops short of writing the form to the database.  It's not wise to put
         data entry forms up unless they're behind a rigorous user authentication and validation scheme.  
         Otherwise they'll be spammed and raunched up or database or error logs flooded  by bots, 
         or persons, intending to vandalize a site.  </li>
 </ul>
";
$FormTemplate = file_get_contents('TemplateFCForm.html');
$FormTemplate = str_replace('[[[TheForm]]]', $UI, $FormTemplate);
echo $FormTemplate;
exit;
?>
