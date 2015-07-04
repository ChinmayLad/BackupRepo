'use strict'
goog.require('Blockly.Firebird.send');

goog.provide('Blockly.Firebird');


function saveXml(){
        


      var xmlDom = Blockly.Xml.workspaceToDom(Code.workspace);
      var xmlText = Blockly.Xml.domToPrettyText(xmlDom);
      var xmlhttp;

    
      var textFileAsBlob = new Blob([xmlText], {type:'text/file'});
      var fileNameToSaveAs = "myNewFile.xml";
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
     downloadLink.onclick = destroyClickedElement;
// make sure the link is hidden.
    downloadLink.style.display = "none";
// add the link to the DOM
    document.body.appendChild(downloadLink);
    
// click the new link
    downloadLink.click();
    

//    alert('The script is working');
}

function destroyClickedElement(event)
{
// remove the link from the DOM
    document.body.removeChild(event.target);
}

// EOF

   /*var filename = <?php echo $filename;?>;
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
*/