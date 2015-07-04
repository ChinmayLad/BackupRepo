'use strict'

/*
*Function : click butoon event.
*
*
*
*
*/
$(document).ready(function(){
    $('#runButton').click(function(){
      var xmlDom = Blockly.Xml.workspaceToDom(Code.workspace);
      var xmlText1 = Blockly.Xml.domToText(xmlDom);        
      xmlText1 = "xmlText="+xmlText1;
      $.ajax({
      type: "POST",
      url: 'parsing',
      data: xmlText1,
      cache: false,
      contentType: "application/x-www-form-urlencoded",
      processData: false,
      success:  function(data){
        //alert("---"+data);
        //alert(data);
        window.open("gcc/compile.php");
      },
      error:  function(data){
        alert("Something wrong");
      }
});
    });
});
/*
function parseXml(){
        
var xmlDom = Blockly.Xml.workspaceToDom(Code.workspace);
var xmlText = Blockly.Xml.domToText(xmlDom);      

var xmlhttp;

if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
     // alert(xmlhttp.responseText);
     window.open("gcc/download.php");
    }
  }
  xmlhttp.open('POST','parse/parse.php',true);
  xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
  xmlhttp.send('xmlText='+xmlText);
};*/