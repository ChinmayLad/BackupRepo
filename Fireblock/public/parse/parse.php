<?php

session_start();
require "/../../vendor/autoload.php";
use Nathanmac\Utilities\Parser\Parser;




//$parser = new Parser();



//$parsed = $parser->xml($file);

define("INDENT", '   ');

$definitions = array();
$string ="";

function prefixLines($text,$prefix){
	$text = preg_replace('/\n/i', "\n".$prefix, $text);
	return $prefix.$text;
}

function getTargetBlock($block,$name){
	$childs = $block->childNodes;
	foreach($childs as $child){
		if($child->getAttribute('name') == $name){
			$targetblock = $child;
			break;
		}
	}
	return $targetblock;
}

function blockToCode($block){
	if($block == NULL){
		return '';
	}
	if($block->nodeName != 'block'){
		echo "xml Wrong";
		echo $block->nodeName;
		return '';
	}
	$type = $block->getAttribute('type');
	switch($type){
		case 'controls_if':{return controls_if($block); break;}
		case 'logic_boolean':{return logic_bool($block);break;}
		case 'logic_compare':{return logic_compare($block);break;}
		case 'logic_operation':{return logic_operation($block);break;}
		case 'logic_negate':{return logic_negate($block);break;}
		case 'logic_ternary':{return logic_ternary($block);break;}
		case 'logic_null':{return logic_null($block);break;}
		case 'controls_whileUntil':{return controls_whileUntil($block);break;}
		case 'controls_flow_statements':{return controls_flow_statements($block);break;}
		case 'io_buzzer':{return buzzer_msec($block);break;}
		case 'text':{return textvalue($block);break;}
		case 'text_print':{return prints($block);break;}
		case 'math_number':{return math_number($block);break;}
		case 'motion':{return motion($block);break;}
		case 'turn': {return turn($block);break;}
		case 'soft_turn':{return soft_turn($block);break;}
		case 'back_turn':{return back_turn($block);break;}
		case 'position_motion':{return position_motion($block);break;}
		case 'position_turn': {return position_turn($block);break;}
		case 'position_turn_soft':{return position_turn_soft($block);break;}
		case 'position_turn_back':{return position_turn_back($block);break;}
		case 'sensor_white':{return sensor_white($block);break;}
		case 'sensor_sharp':{return sensor_sharp($block);break;}
		case 'sensor_ir':{return sensor_ir($block);break;}
		case 'buzzer_on':{return buzzer_on($block);break;}
		case 'buzzer_off':{return buzzer_off($block);break;}
		case 'delay_ms':{return delay_ms($block);break;}
		case 'math_arithmetic':{return math_arithmetic($block);break;}
		case 'math_single':{return math_single($block);break;}
		case 'math_trig':{return math_trig($block);break;}
		case 'math_modulo':{return math_modulo($block);break;}
		case 'register':{return register($block);break;}
		case 'pin':{return pin($block);break;}
		case 'set_item':{return set_item($block);break;}
		case 'call_function':{return call_function($block);break;}
		case 'call_function_with_return':{return call_function_with_return($block);break;}
		case 'function_defreturn':{return function_defreturn($block);break;}
		case 'function_defnoreturn':{return function_defnoreturn($block);break;}
		case 'procedures_callnoreturn':{return procedures_callnoreturn($block);break;}
		case 'procedures_callreturn':{return procedures_callreturn($block);break;}
		case 'hex':{return hex($block);break;}
		case 'return':{return returnr($block);break;}
		case 'variable_get':{return variable_get($block);break;}
		case 'incl_ude':{return incl_ude($block);break;}
		case 'define':{return def_ine($block);break;}
		default : {echo "not defined in blockToCode ".$block->getAttribute('type');}
	}
}

//generates the code structure
function xmlToCode($xmlDoc){
	$code="";
	global $definitions;
	$blocks = $xmlDoc->getElementsByTagName('xml')->item(0)->childNodes;
	foreach($blocks as $block){
		$arg = blockToCode($block);
		$code  = $code."\n\n".$arg;
		$blockr = $block->lastChild;
		while($blockr != NULL && $blockr->nodeName == 'next'){
			$blockr= $blockr->firstChild;
			$code = $code."\n".blockToCode($blockr);
			$blockr = $blockr->lastChild;
		}
	}
	global $string;
	$string = join("\n",$definitions).$code;	
	return $string;
	
	//$string = preg_replace('/[\n]+/mi', '\n', $string);
	//$string = preg_replace('/[ ]+/mi', ' ',$string);
	//$_SESSION['file'] = $string;

	//header('Location:../gcc/compile.php'); 

}


function getFieldValue($block,$name){
	$i=0;
	$field = $block->getElementsByTagName('field')->item($i);
	while($field != NULL){
		if($field->getAttribute('name') == $name){
			//echo $field->getAttribute('name');
			$code = $field->nodeValue;
			return $code;
		}
		else{
			$i++;
			$field = $block->getElementsByTagName('field')->item($i);
		}
	}
	return "";
}

function statementToCode($block,$name){  // Restructure this code still doubtful
	$code = "";
	$test = $block->firstChild;
	if($test != NULL){
		if($test->nodeName != 'next'){
		$statement = $block->getElementsByTagName('statement')->item(0);
		if($statement !=NULL){
			$statement = $statement->firstChild;
			while($statement != NULL){
				if($statement->nodeName == 'next'){
					$statement = $statement->firstChild;	
				}
				else if($statement->nodeName =='block'){
					$arg =blockToCode($statement);
					if($arg != null)
						$arg = prefixLines($arg,INDENT);
					$code = $code."\n".$arg;
					$statement = $statement->lastChild;
				}
				else{
					$statement = NULL;
				}
			}
		}
		return $code."\n";
		}
	}
	return "";
}

function valueToCode($block,$name){
	$i=0;
	$values = $block->getElementsByTagName('value');
	$value = $values->item($i);
	while($value != NULL){
		if($value->getAttribute('name') == $name){
			//echo $value->getAttribute('name');
			$value = $value->firstChild;
			$code = blockToCode($value);
			return $code;
		}else{
			$i++;
			$value = $values->item($i);
		}
	}
	return "";
}

function getArgs($block){
	$mutator = $block->getElementsByTagName('mutation')->item(0);
	$args = array();
	if($mutator->hasChildNodes()){
		$mutate = $mutator->getElementsByTagName('arg');
		$i=0;
		foreach($mutate as $mutate){
			$args[$i] = $mutate->getAttribute('name');
			$i++;
		}
		return $args;
	}
}


function math_number($block){
	$code = getFieldValue($block,"NUM");
	$code = $code != NULL?$code:"0";
	return $code;
}

//LOGIC TABS

function controls_if($block){
	$mutation = $block->getElementsByTagName('mutation')->item(0);

	$value = valueToCode($block,"IF0");
	$value = ($value!=NULL)?$value:'0';
	$statements = statementToCode($block,"DO0");
	$code = "if(".$value."){\n".$statements."}";
	if($mutation!=NULL){
		$no = strval($mutation->getAttribute('elseif'));
		for($i=1;$i<=$no;$i++){
			$val =  valueToCode($block,"IF".$i);
			$val = ($val!=NULL)?$val:'0';
			$stat = statementToCode($block,"DO".$i);
			$code = $code."else if(".$val."){\n".$stat."}";
		}
		if($mutation->getAttribute('else')){
			$code = $code."else{\n".statementToCode($block,"ELSE")."}";
		}
	}
	return $code;
}


function logic_compare($block){
	$arg0 = valueToCode($block,"A");
	$arg0 = ($arg0!=NULL)?$arg0:'0';
	$arg1 = getFieldValue($block,"OP");
	$arg2 = valueToCode($block,"B");
	$arg2 = ($arg2!=NULL)?$arg2:'0';
	switch($arg1){
		case 'EQ':{$arg1 = '==';break;}
		case 'NEQ':{$arg1 = '!=';break;}
		case 'LT':{$arg1 = '<';break;}
		case 'LTE':{$arg1 = '<=';break;}
		case 'GT':{$arg1 = '>';break;}
		case 'GTE':{$arg1 = '>=';break;}
	}
	$code = $arg0.$arg1.$arg2;
	return $code;
}

function logic_bool($block){
	$code = getFieldValue($block,"BOOL");
	$code = ($code == 'TRUE')?'1':'0';
	return $code;
}

function logic_negate($block){
	$code = valueToCode($block,"BOOL");
	return "!".$code;
}
function logic_operation($block){
	$arg0 = valueToCode($block,"A");
	$arg0 = ($arg0!=NULL)?$arg0:'0';
	$arg1 = getFieldValue($block,"OP");
	$arg2 = valueToCode($block,"B");
	$arg2 = ($arg2!=NULL)?$arg2:'0';
	switch($arg1){
		case 'AND':{$arg1 = '&&';break;}
		case 'OR':{$arg1 = '||';break;}
	}
	$code = $arg0.$arg1.$arg2;
	return $code;
}

function logic_ternary($block){
	$condition = valueToCode($block,"IF");
	$then = valueToCode($block,"THEN");
	$else = valueToCode($block,"ELSE");

	return "(".$condition.")?".$then.":".$else;
}

function logic_null($block){
	return "null";
}

//LOOPS

function controls_flow_statements($block){
	$code = getFieldValue($block,"FLOW");
	switch($code){
		case 'BREAK':{$code = "break;";break;}
		case 'CONTINUE':{$code = "continue;";break;}
	}
	return $code;
}

function controls_whileUntil($block){
	$mode = getFieldValue($block,"MODE");
	$arg = valueToCode($block,"BOOL");
	$arg = ($arg!=NULL)?$arg:'0';
	$statements = statementToCode($block,"DO");
	if($mode == 'UNTIL'){
		$arg = '!'.$arg;
	}
	return "while(".$arg."){\n".$statements."}";
}



//TEXT
function prints($block){
	$code = valueToCode($block,'TEXT');

	return "printf(".$code.");";
}


function textvalue($block){
	$code = getFieldValue($block,"TEXT");
	return "\"".$code."\";";
}

//IO
function buzzer_msec($block){
	global $definitions;
	$definitions['includefirebird'] = "#include \"firebird.h\"";
	$time = valueToCode($block,"buzzer");
	$time = ($time!=NULL)?$time:'0';
	return "buzzer_ms(".$time.");";
}

//MOTION
function motion($block){
	global $definitions;
	$definitions['includefirebird'] = "#include \"firebird.h\"";
	$motion = getFieldValue($block,"motion");
	
	return $motion."()";
}


function turn($block){
	global $definitions;
	$definitions['includefirebird'] = "#include \"firebird.h\"";
	$motion = getFieldValue($block,"turn");
	
	return $motion."()";
}


function soft_turn($block){
	global $definitions;
	$definitions['includefirebird'] = "#include \"firebird.h\"";
	$motion = getFieldValue($block,"turn");
	
	return $motion."()";
}

function back_turn($block){
	global $definitions;
	$definitions['includefirebird'] = "#include \"firebird.h\"";
	$motion = getFieldValue($block,"turn");
	switch($motion){
		case 'back_right':{$motion = "soft_right_2";break;}
		case 'back_left':{$motion = "soft_left_2";break;}
	}

	return $motion."()";
}

//POSITIONS
function position_motion($block){
	global $definitions;
	$definitions['includefirebird'] = "#include \"firebird.h\"";
	$arg = valueToCode($block,"motion");
	$motion = getFieldValue($block,"forward");
	switch($motion){
		case 'fwd':{$motion = 'forward_mm';break;}
		case 'back':{$motion = 'back_mm';break;}
	}
	return $motion."(".$arg.");";
}

function position_turn($block){
	global $definitions;
	$definitions['includefirebird'] = "#include \"firebird.h\"";
	$arg = valueToCode($block,"motion");
	$arg = $arg%360;
	$motion = getFieldValue($block,"forward");
	switch($motion){
		case 'right':{$motion = 'right_degrees';break;}
		case 'left':{$motion = 'left_degrees';break;}
	}
	return $motion."(".$arg.");";
}

function position_turn_soft($block){
	global $definitions;
	$definitions['includefirebird'] = "#include \"firebird.h\"";
	$arg = valueToCode($block,"soft_turn");
	$arg = $arg%360;
	$motion = getFieldValue($block,"forward");
	switch($motion){
		case 'right':{$motion = 'soft_right_degrees';break;}
		case 'left':{$motion = 'soft_left_degrees';break;}
	}
	return $motion."(".$arg.");";
}

function position_turn_back($block){
	global $definitions;
	$definitions['includefirebird'] = "#include \"firebird.h\"";
	$arg = valueToCode($block,"turn_back");
	$arg = $arg%360;
	$motion = getFieldValue($block,"forward");
	switch($motion){
		case 'right':{$motion = 'soft_right_2_degrees';break;}
		case 'left':{$motion = 'soft_left_2_degrees';break;}
	}
	return $motion."(".$arg.");";
}

function sensor_white($block){
	global $definitions;
	$definitions['includefirebird'] = "#include \"firebird.h\"";
	$arg = getFieldValue($block,"line_sensor");
	$code='';
   switch ($arg) {
    case 'center':{$code = 'ADC_Conversion(2)';break;}
    case 'left':{$code = 'ADC_Conversion(3)';break;}
	case 'right':{$code = 'ADC_Conversion(1)';break;}
   }

  return $code;
}

function sensor_sharp($block){
	global $definitions;
	$definitions['includefirebird'] = "#include \"firebird.h\"";
	$arg = getFieldValue($block,"sharp");
	$code = "";
    switch ($arg) {
     case 'fr':{$code = 'sharp_fr(11)';break;}
     case 'lf':{$code = 'sharp_fr(9)';break;}
	 case 'rf':{$code = 'sharp_fr(13)';break;}
	 case 'ld':{$code = 'sharp_fr(10)';break;}
	 case 'rd':{$code = 'sharp_fr(12)';break;}   
  	}

  return $code;
}

function sensor_ir($block){
	global $definitions;
	$definitions['includefirebird'] = "#include \"firebird.h\"";
	$arg = getFieldValue($block,"ir");
	return $arg;
}

function buzzer_on($block){
	global $definitions;
	$definitions['includefirebird'] = "#include \"firebird.h\"";
	return "buzzer_on();";
}

function buzzer_off($block){
	global $definitions;
	$definitions['includefirebird'] = "#include \"firebird.h\"";
	return "buzzer_off();";
}

function delay_ms($block){
	global $definitions;
	$definitions['includedelay'] = "#include <util/delay.h>";
	$value = valueToCode($block,"delay_value");
	$value = $value!= NULL?$value:"0";
	return "_delay_ms(".$value.");";
}


//MATHS

function math_arithmetic($block){
	global $definitions;
	$arg0 = valueToCode($block,"A");
	$arg0 = ($arg0!=NULL)?$arg0:'0';
	$arg1 = getFieldValue($block,"OP");
	$arg2 = valueToCode($block,"B");
	$arg2 = ($arg2!=NULL)?$arg2:'0';
	switch($arg1){
		case 'ADD':{$arg1 = ' + ';break;}
		case 'MINUS':{$arg1 = ' - ';break;}
		case 'MULTIPLY':{$arg1 = ' * ';break;}
		case 'DIVIDE':{$arg1 = ' / ';break;}
		case 'POWER':{break;}
	}

	if($arg1 == 'POWER'){
		$definitions['includemath'] = "#include <math.h>";
		return "pow(".$arg0.",".$arg1.");";
	}
	$code = $arg0.$arg1.$arg2;
	return $code;
}

function math_single($block){
	$arg = getFieldValue($block,"OP");
	$value = valueToCode($block,"NUM");
	$value = $value!=NULL?$value:'0';
	global $definitions;
	if($arg != 'NEG'){
		$definitions['includemath'] = "#include<math.h>";
	}
	$code = "";
	switch($arg){
		case 'ROOT':{$code = "sqrt(".$value.")";break;}
		case 'ABS':{$code = "sqrt(".$value.")";break;}
		case 'NEG':{;break;}
		case 'LN':{$code = "sqrt(".$value.")";break;}
		case 'LOG10':{$code = "sqrt(".$value.")";break;}
		case 'EXP':{$code = "sqrt(".$value.")";break;}
		case 'POW10':{$code = "pow(10,".$value.")";break;}
	}

	return $code;
}

function math_trig($block){
	$arg = getFieldValue($block,"OP");
	$value = valueToCode($block,"NUM");
	$value = $value!=NULL?$value:'0';
	$value = strval((3.1416/180)*floatval($value));
	global $definitions;
	$definitions['includemath'] = "#include<math.h>";
	$code = "";
	switch($arg){
		case 'SIN':{$code = "sin(".$value.")";break;}
		case 'COS':{$code = "cos(".$value.")";break;}
		case 'TAN':{$code = "tan".$value.")";break;}
		case 'ASIN':{$code = "asin(".$value.")";break;}
		case 'ACOS':{$code = "acos(".$value.")";break;}
		case 'ATAN':{$code = "atan(".$value.")";break;}
	}

	return $code;
}

function math_modulo($block){
	$dividend = valueToCode($block,"DIVIDEND");
	$divisor = valueToCode($block,"DIVISOR");
	$dividend = $dividend!=NULL?$dividend:"0";
	$divisor = $divisor!=NULL?$divisor:"0";

	return $dividend."%".$divisor;
}


//BASICS


function pin($block){
	$code = getFieldValue($block,"pin");
	return $code;
}

function set_item($block)
{

$arg1 = getFieldValue($block,"polarity");
if($arg1=="signed"){
	$arg1="";
}
$arg2 = getFieldValue($block,"type");
$arg3 = getFieldValue($block,"NAME");
$arg4 = valueTOCode($block,"input");
$code= $arg1." ".$arg2." ".$arg3."=".$arg4;
return $code;

}

function check_hex($arg){
	
	if(substr("$arg",0,2)=="0x"){
		if(strlen($arg) == 4){
		  if(ctype_xdigit(substr($arg,-2))){
			
				return 1;
			}	
		}
	}
	return 0;
	
}

function register($block)
{

$arg1 = getFieldValue($block,"register");
$arg2 = getFieldValue($block,"set/reset");
$arg3 = getFieldValue($block,"hex_value");

if(check_hex($arg3)){
if($arg2=="set"){
	$arg2="&";
}
else{
	$arg2="|";
}
$code=$arg1."=".$arg1." ".$arg2." ".$arg3;

return $code;
}
else{
	echo"<script>alert(\"enter hexa_decimal value\")</script>";
}

}

function call_function($block)
{
	$args=array();
	$args=getArgs($block);
	$funcName=getFieldValue($block,"NAME");
	 if(count($args)!=NULL){
	 	return $funcName."(".join(", ",$args).");";
	 }
	 else{
	 	return $funcName."();";
	 }


}
function call_function_with_return($block)
{
	$args=array();
	$args=getArgs($block);
	$funcName=getFieldValue($block,"NAME");
	if(count($args)!=NULL){
		return $funcName."(".join(", ",$args).");";
	}
	else{
		return $funcName."();";
	}


}
function function_defnoreturn($block){

	$funcName = getFieldValue($block,"NAME");
	$statements = statementToCode($block,"STACK");
	$args = array();
	$args = getArgs($block);
	/*foreach(array_values($args) as $arg){
		$code = $code.','.$arg;
	}*/
	if(count($args)!=NULL){
	return "void ".$funcName."(".join(", ",$args)."){\n".$statements."}";
    }
	else{
		return "void ".$funcName."(){"."\n".$statements."}";
    }
}

function function_defreturn($block){
	$type=   getFieldValue($block,"types");
	$funcName = getFieldValue($block,"NAME");
	$statements = statementToCode($block,"STACK");
	$args=array();
	$args=getArgs($block);
	$return = valueToCode($block,"RETURN");
	//echo "$return";
	$return = $return !=NULL? $return:'0';

    if(count($args)!=NULL){
		return "$type ".$funcName."(".join(", ",$args)."){\n".$statements."\n return"." ".$return."; \n};";
    }else{
		return "$type ".$funcName."() {\n".$statements."\n return ".$return.";\n}";
    }
}

//CALL FUNCTIONS

function procedures_callnoreturn($block){
	$mutate = $block->firstChild;
	if($mutate->nodeName == 'mutation'){
		$funcName = $mutate->getAttribute('name');
		$args = getArgs($block);
		$i=0;
		$argcode = array();
		if($args != NULL){
			foreach($args as $arg){
				$argcode[$i] = valueToCode($block,"ARG".$i);
				$i++;
			}
			return $funcName."(".join(", ",$argcode).")";
		}else{
			return $funcName."()";
		}
	}
	return null;
}

function procedures_callreturn($block){
	$mutate = $block->firstChild;
	if($mutate->nodeName == 'mutation'){
		$funcName = $mutate->getAttribute('name');		
		$args = getArgs($block);
		$i=0;
		$argcode = array();
		if($args != NULL){
			foreach($args as $arg){
				$argcode[$i] = valueToCode($block,"ARG".$i);
				$i++;
			}
			return $funcName."(".join(", ",$argcode).")";
		}else{
			return $funcName."()";
		}

	}
	return null;
}

function hex($block){
	$value = getFieldValue($block,'HEX');
	return $value;
}

function returnr($block){
	$retval = valueToCode($block,"RETURN");
	return "return ".$retval;
}


function valriable_get($block){
	$field = getFieldValue($block,"VAR");
	return $field;
}


function incl_ude($block){
	$incl = getFieldValue($block,"header file");

	return "#include \"".$incl."\"";
}


function def_ine($block){
	$defval = getFieldValue($block,"name");
	$defreplace = getFieldValue($block,"value");
	if($defval =='F_CPU'){
		global $definitions;
		$definitions['def_fcpu'] = "#define ".$defval." ".$defreplace;
		return null;
	}
	return "#define ".$defval." ".$defreplace;

}

// DISPLAYING THE VALUES MOSTLY LCD
/*function lcd_init($block){
	return "lcd_init();"
}

function display_channel($block){

	$value = valueToCode($block);
	return lcd_print
}
*/
function main(){
	//first check if the variable is properly transferred
	if (isset($_POST['xmlText'])) {
    // Escape any html characters
	//Get the xml file
	$file = $_POST['xmlText'];
	//removes all the blank spaces
	$file = preg_replace('/>[ ]*</mi', '><', $file); 
	//print_r($file);
	//Instantiates and Loads the xml File
	$xmlDoc = new DOMDocument();
	$xmlDoc->loadXML($file);
	//actual shit to do
	
	echo xmlToCode($xmlDoc);

	
	global $string;

	$_SESSION['file'] = $string;

	header('Location:../gcc/compile.php'); 
}else{
	echo "the fuck";
}
}
main();
