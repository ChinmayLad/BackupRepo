<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="google" value="notranslate">
	<meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="_token" content="{!! csrf_token() !!}"/>
	<title>Fireblocks</title>

	<script type="text/javascript" src="{{ URL::asset('/js/storage.js') }}"></script>
	<script type="text/javascript" src="{{ URL::asset('/js/blockly_compressed.js') }}"></script>
  <script type="text/javascript" src="{{ URL::asset('/js/blocks_compressed.js') }}"></script>
  <script type="text/javascript" src="{{ URL::asset('http://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js') }}"></script>
  <script type="text/javascript" src="{{ URL::asset('/js/firebird_compressed.js') }}"></script>
  <script type="text/javascript" src="{{ URL::asset('/js/generators/firebird.js') }}"></script>
  <script type="text/javascript" src="{{ URL::asset('/js/generators/firebird/display.js') }}"></script>
  <script type="text/javascript" src="{{ URL::asset('/js/generators/firebird/input.js') }}"></script>
  <script type="text/javascript" src="{{ URL::asset('/js/generators/firebird/interrupt.js') }}"></script>
  <script type="text/javascript" src="{{ URL::asset('/js/generators/firebird/logic.js') }}"></script>
  <script type="text/javascript" src="{{ URL::asset('/js/generators/firebird/loops.js') }}"></script>
  <script type="text/javascript" src="{{ URL::asset('/js/generators/firebird/math.js') }}"></script>
  <script type="text/javascript" src="{{ URL::asset('/js/generators/firebird/motion.js') }}"></script>
  <script type="text/javascript" src="{{ URL::asset('/js/generators/firebird/position.js') }}"></script>
  <script type="text/javascript" src="{{ URL::asset('/js/generators/firebird/motor.js') }}"></script>
  <script type="text/javascript" src="{{ URL::asset('/js/generators/firebird/procedures.js') }}"></script>
  <script type="text/javascript" src="{{ URL::asset('/js/generators/firebird/sensor.js') }}"></script>
  <script type="text/javascript" src="{{ URL::asset('/js/generators/firebird/text.js') }}"></script>
  <script type="text/javascript" src="{{ URL::asset('/js/generators/firebird/variables.js') }}"></script>
  <script type="text/javascript" src="{{ URL::asset('/js/generators/firebird/function.js') }}"></script>
  <script type="text/javascript" src="{{ URL::asset('/js/blocks/function.js') }}"></script>
  <script type="text/javascript" src="{{ URL::asset('/js/blocks/display.js') }}"></script>
  <script type="text/javascript" src="{{ URL::asset('/js/blocks/io.js') }}"></script>
  <script type="text/javascript" src="{{ URL::asset('/js/blocks/logic.js') }}"></script>
  <script type="text/javascript" src="{{ URL::asset('/js/blocks/loops.js') }}"></script>
  <script type="text/javascript" src="{{ URL::asset('/js/blocks/math.js') }}"></script>
  <script type="text/javascript" src="{{ URL::asset('/js/blocks/motion.js') }}"></script>
  <script type="text/javascript" src="{{ URL::asset('/js/blocks/position.js') }}"></script>
  <script type="text/javascript" src="{{ URL::asset('/js/blocks/procedures.js') }}"></script>
  <script type="text/javascript" src="{{ URL::asset('/js/blocks/sensor.js') }}"></script>
  <script type="text/javascript" src="{{ URL::asset('/js/blocks/interrupt.js') }}"></script>
  <script type="text/javascript" src="{{ URL::asset('/js/blocks/text.js') }}"></script>
  <script type="text/javascript" src="{{ URL::asset('/js/blocks/variables.js') }}"></script>
  <script type="text/javascript" src="{{ URL::asset('/js/code.js') }}"></script>
  <script type="text/javascript" src="{{ URL::asset('/js/script.js') }}"></script>
  <script type="text/javascript" src="{{ URL::asset('/js/parse.js') }}"></script>
  <script type="text/javascript" src="{{ URL::asset('/js/save.js') }}"></script>

  	<!--<script type="text/javascript" src="{{ URL::asset('js/send.js') }}"></script>-->
	<link href="{{ asset('/css/app.css') }}" rel="stylesheet">
	<link href="{{ asset('/css/style.css') }}" rel="stylesheet">


	<!-- Fonts -->
	<link href='//fonts.googleapis.com/css?family=Roboto:400,300' rel='stylesheet' type='text/css'>

	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
  <script>
  function readBlob(opt_startByte, opt_stopByte) {

    var files = document.getElementById('files').files;
    if (!files.length) {
      alert('Please select a file!');
      return;
    }

    var file = files[0];
    var start = parseInt(opt_startByte) || 0;
    var stop = parseInt(opt_stopByte) || file.size - 1;

    var reader = new FileReader();

    // If we use onloadend, we need to check the readyState.
    reader.onloadend = function(evt) {
      if (evt.target.readyState == FileReader.DONE) { // DONE == 2
        var xmlDom = evt.target.result;
        document.getElementById('content_xml').textContent = xmlDom;
        
        xmlDom = Blockly.Xml.textToDom(xmlDom);
        Code.workspace.clear();
        Blockly.Xml.domToWorkspace(Code.workspace, xmlDom);
        
      }
    };

    var blob = file.slice(start, stop + 1);
    reader.readAsBinaryString(blob);
  };
  
  $(document).ready(function(){
    $('#openButton').click(function(){
      $('#files').click();
      $('#files').change(function(){
          readBlob(null,null);
      });
      
    });
  });

 /* document.querySelector('.readBytesButtons').addEventListener('click', function(evt) {
    if (evt.target.tagName.toLowerCase() == 'button') {
      var startByte = evt.target.getAttribute('data-startbyte');
      var endByte = evt.target.getAttribute('data-endbyte');
      readBlob(startByte, endByte);
    }
  }, false);
*/

</script>
<style>
#files{
  display:none;
}
</style>
</head>
<body>
  @yield('head')

 


	<!-- Scripts -->
	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.1/js/bootstrap.min.js"></script>
  <script type="text/javascript">
    $.ajaxSetup({
       headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
    });
  </script>
</body>
</html>
