'use strict'

goog.require('Blockly.Firebird.send');

goog.provide('Blockly.Firebird');

function sendTextAsFile()
{      
// grab the content of the form field and place it into a variable
    var textToWrite = Blockly.Firebird.workspaceToCode(Code.workspace);
//    textToWrite.replace(/\n/gi,";\r\n");
    //alert(textToWrite);
//  create a new Blob (html5 magic) that conatins the data from your form feild
  var textFileAsBlob = new Blob([textToWrite], {type:'text/file'});
// Specify the name of the file to be saved
    var fileNameToSaveAs = "myNewFile.c";
    
// Optionally allow the user to choose a file name by providing 
// an imput field in the HTML and using the collected data here
// var fileNameToSaveAs = txtFileName.text;

// create a link for our script to 'click'
    var downloadLink = document.createElement("a");
//  supply the name of the file (from the var above).
// you could create the name here but using a var
// allows more flexability later.
    downloadLink.download = fileNameToSaveAs;
// provide text for the link. This will be hidden so you
// can actually use anything you want.
    downloadLink.innerHTML = "My Hidden Link";
    
// allow our code to work in webkit & Gecko based browsers
// without the need for a if / else block.
    window.URL = window.URL || window.webkitURL;
          
// Create the link Object.
    downloadLink.href = window.URL.createObjectURL(textFileAsBlob);

    console.log(downloadLink);

      var filename = <?php echo $filename;?>;
      var data = new FormData();
      data.append('file', blob);

      $.ajax({
        url :  "sent.php",
        type: 'POST',
        data: data,
        contentType: false,
        processData: false,
        success: function(data) {
          alert("boa!");
        },    
        error: function() {
          alert("not so boa!");
        }
      });
});

}