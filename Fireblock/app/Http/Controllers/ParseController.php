<?php namespace App\Http\Controllers;



use Input;
use Request;
session_start();
ini_set('display_errors', 1); 
error_reporting(E_ALL);

class ParseController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Welcome Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders the "marketing page" for the application and
	| is configured to only allow guests. Like most of the other sample
	| controllers, you are free to modify or remove it as you desire.
	|
	*/
	public static $definitions = array();
	public static $strings;
	public static $fcpu;
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('guest');
	}

	public function prefixLines($text,$prefix){
		$text = preg_replace('/\n/i', "\n".$prefix, $text);
		return $prefix.$text;
	}

	public function blockToCode($block){
		if($block == NULL){
			return '';
		}
		if($block->nodeName != 'block'){
			return "xml Wrong".$block->nodeName;
		}
		$type = $block->getAttribute('type');
		switch($type){
			case 'controls_if':{return $this->controls_if($block); break;}
			case 'logic_boolean':{return $this->logic_bool($block);break;}
			case 'logic_compare':{return $this->logic_compare($block);break;}
			case 'logic_operation':{return $this->logic_operation($block);break;}
			case 'bitwise_operator':{return $this->bitwise_operator($block);break;}
			case 'logic_negate':{return $this->logic_negate($block);break;}
			case 'logic_ternary':{return $this->logic_ternary($block);break;}
			case 'logic_null':{return $this->logic_null($block);break;}
			case 'controls_whileUntil':{return $this->controls_whileUntil($block);break;}
			case 'controls_flow_statements':{return $this->controls_flow_statements($block);break;}
			case 'io_buzzer':{return $this->buzzer_msec($block);break;}
			case 'text':{return $this->textvalue($block);break;}
			case 'text_print':{return $this->prints($block);break;}
			case 'display_init':{return $this->LCD_init($block);break;}
			case 'display_cursor':{return $this->cursor($block);break;}
			case 'display_channel':{return $this->channel($block);break;}
			case 'math_number':{return $this->math_number($block);break;}
			case 'motion':{return $this->motion($block);break;}
			case 'turn': {return $this->turn($block);break;}
			case 'soft_turn':{return $this->soft_turn($block);break;}
			case 'back_turn':{return $this->back_turn($block);break;}
			case 'velocity':{return $this->velocity($block);break;}
			case 'position_motion':{return $this->position_motion($block);break;}
			case 'position_turn': {return $this->position_turn($block);break;}
			case 'position_turn_soft':{return $this->position_turn_soft($block);break;}
			case 'position_turn_back':{return $this->position_turn_back($block);break;}
			case 'sensor_white':{return $this->sensor_white($block);break;}
			case 'sensor_sharp':{return $this->sensor_sharp($block);break;}
			case 'sensor_ir':{return $this->sensor_ir($block);break;}
			case 'buzzer_on':{return $this->buzzer_on($block);break;}
			case 'buzzer_off':{return $this->buzzer_off($block);break;}
			case 'delay_ms':{return $this->delay_ms($block);break;}
			case 'math_arithmetic':{return $this->math_arithmetic($block);break;}
			case 'math_single':{return $this->math_single($block);break;}
			case 'math_trig':{return $this->math_trig($block);break;}
			case 'math_modulo':{return $this->math_modulo($block);break;}
			case 'type_casting':{return $this->type_casting($block);break;}
			case 'register':{return $this->register($block);break;}
			case 'pin':{return $this->pin($block);break;}
			case 'set_item':{return $this->set_item($block);break;}
			case 'call_function':{return $this->call_function($block);break;}
			case 'call_function_with_return':{return $this->call_function_with_return($block);break;}
			case 'function_defreturn':{return $this->function_defreturn($block);break;}
			case 'function_defnoreturn':{return $this->function_defnoreturn($block);break;}
			case 'procedures_callnoreturn':{return $this->procedures_callnoreturn($block);break;}
			case 'procedures_callreturn':{return $this->procedures_callreturn($block);break;}
			case 'hex':{return $this->hex($block);break;}
			case 'return':{return $this->returnr($block);break;}
			case 'variable_get':{return $this->variable_get($block);break;}
			case 'incl_ude':{return $this->incl_ude($block);break;}
			case 'define':{return $this->def_ine($block);break;}
			case 'variables_set':{return $this->variables_set($block);break;}
			case 'variables_get':{return $this->variables_get($block);break;}
			case 'int_serv_routine':{return $this->isRoutine($block);break;}
			case 'int_signal':{return $this->isSignal($block);break;}
			default : {echo "not defined in blockToCode ".$block->getAttribute('type');}
		}
	}

	//generates the code structure
	public function xmlToCode($xmlDoc){
		$code="";
		$blocks = $xmlDoc->getElementsByTagName('xml')->item(0)->childNodes;
		foreach($blocks as $block){
			$arg = $this->blockToCode($block);
			$code  = $code."\n\n".$arg;
			$blockr = $block->lastChild;
			while($blockr != NULL && $blockr->nodeName == 'next'){
				$blockr= $blockr->firstChild;
				$code = $code."\n".$this->blockToCode($blockr);
				$blockr = $blockr->lastChild;
			}
		}
		//global $string;
		 self::$strings = self::$fcpu."\n".join("\n",self::$definitions).$code;	
		//return $string;
		
		//$string = preg_replace('/[\n]+/mi', '\n', $string);
		//$string = preg_replace('/[ ]+/mi', ' ',$string);
		//$_SESSION['file'] = $string;

		//header('Location:../gcc/compile.php'); 

	}


	public function getFieldValue($block,$name){
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

	public function statementToCode($block,$name){  // Restructure this code still doubtful
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
						$arg =$this->blockToCode($statement);
						if($arg != null)
							$arg = $this->prefixLines($arg,'	');
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

	public function valueToCode($block,$name){
		$i=0;
		$values = $block->getElementsByTagName('value');
		$value = $values->item($i);
		while($value != NULL){
			if(($value->getAttribute('name') == $name) && ($value->parentNode->getAttribute('id') == $block->getAttribute('id'))){
				$value = $value->firstChild;
				$code = $this->blockToCode($value);
				return $code;
			}else{
				$i++;
				$value = $values->item($i);
			}
		}
		return "";
	}

	public function getArgs($block){
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


	public function math_number($block){
		$code = $this->getFieldValue($block,"NUM");
		$code = $code != NULL?$code:"0";
		return $code;
	}

	//LOGIC TABS

	public function controls_if($block){
		$mutation = $block->getElementsByTagName('mutation')->item(0);

		$value = $this->valueToCode($block,"IF0");
		$value = ($value!=NULL)?$value:'0';
		$statements = $this->statementToCode($block,"DO0");
		$code = "if(".$value."){\n".$statements."}";
		if($mutation!=NULL){
			$no = strval($mutation->getAttribute('elseif'));
			for($i=1;$i<=$no;$i++){
				$val =  $this->valueToCode($block,"IF".$i);
				$val = ($val!=NULL)?$val:'0';
				$stat = $this->statementToCode($block,"DO".$i);
				$code = $code."else if(".$val."){\n".$stat."}";
			}
			if($mutation->getAttribute('else')){
				$code = $code."else{\n".$this->statementToCode($block,"ELSE")."}";
			}
		}
		return $code;
	}

	public function logic_compare($block){
		$arg0 = $this->valueToCode($block,"A");
		$arg0 = ($arg0!=NULL)?$arg0:'0';
		$arg1 = $this->getFieldValue($block,"OP");
		$arg2 = $this->valueToCode($block,"B");
		$arg2 = ($arg2!=NULL)?$arg2:'0';
		switch($arg1){
			case 'EQ':{$arg1 = ' == ';break;}
			case 'NEQ':{$arg1 = ' != ';break;}
			case 'LT':{$arg1 = ' < ';break;}
			case 'LTE':{$arg1 = ' <= ';break;}
			case 'GT':{$arg1 = ' > ';break;}
			case 'GTE':{$arg1 = ' >= ';break;}
		}
		$code = $arg0.$arg1.$arg2;
		return $code;
	}

	public function logic_bool($block){
		$code = $this->getFieldValue($block,"BOOL");
		$code = ($code == 'TRUE')?'1':'0';
		return $code;
	}

	public function logic_negate($block){
		$code = $this->valueToCode($block,"BOOL");
		return "!".$code;
	}
	public function logic_operation($block){
		$arg0 = $this->valueToCode($block,"A");
		$arg0 = ($arg0!=NULL)?$arg0:'0';
		$arg1 = $this->getFieldValue($block,"OP");
		$arg2 = $this->valueToCode($block,"B");
		$arg2 = ($arg2!=NULL)?$arg2:'0';
		switch($arg1){
			case 'AND':{$arg1 = ' && ';break;}
			case 'OR':{$arg1 = ' || ';break;}
		}
		$code = $arg0.$arg1.$arg2;
		return $code;
	}

	public function bitwise_operator($block){
		$arg0 = $this->valueToCode($block,"A");
		$arg0 = ($arg0!=NULL)?$arg0:'0';
		$arg1 = $this->getFieldValue($block,"OP");
		$arg2 = $this->valueToCode($block,"B");
		$arg2 = ($arg2!=NULL)?$arg2:'0';
		switch($arg1){
			case 'BITAND':{$arg1 = ' & ';break;}
			case 'BITOR':{$arg1 = ' | ';break;}
		}
		$code = $arg0.$arg1.$arg2;
		return $code;	
	}


	public function logic_ternary($block){
		$condition = $this->valueToCode($block,"IF");
		$then = $this->valueToCode($block,"THEN");
		$else = $this->valueToCode($block,"ELSE");

		return "(".$condition.")?".$then.":".$else;
	}

	public function logic_null($block){
		return "null";
	}

	//LOOPS

	public function controls_flow_statements($block){
		$code = $this->getFieldValue($block,"FLOW");
		switch($code){
			case 'BREAK':{$code = "break;";break;}
			case 'CONTINUE':{$code = "continue;";break;}
		}
		return $code;
	}

	public function controls_whileUntil($block){
		$mode = $this->getFieldValue($block,"MODE");
		$arg = $this->valueToCode($block,"BOOL");
		$arg = ($arg!=NULL)?$arg:'0';
		$statements = $this->statementToCode($block,"DO");
		if($mode == 'UNTIL'){
			$arg = '!'.$arg;
		}
		return "while(".$arg."){".$statements."}";
	}



	//TEXT
	public function prints($block){
		$code = $this->valueToCode($block,'TEXT');

		return "printf(".$code.");";
	}


	public function textvalue($block){
		$code = $this->getFieldValue($block,"TEXT");
		return "\"".$code."\"";
	}

	//IO
	public function buzzer_msec($block){
		
		self::$definitions['includefirebird'] = "#include \"firebird.h\"";
		$time = $this->valueToCode($block,"buzzer");
		$time = ($time!=NULL)?$time:'0';
		return "buzzer_ms(".$time.");";
	}

	//MOTION
	public function motion($block){
	
		self::$definitions['includefirebird'] = "#include \"firebird.h\"";
		$motion = $this->getFieldValue($block,"motion");
		
		return $motion."();";
	}


	public function turn($block){
	
		self::$definitions['includefirebird'] = "#include \"firebird.h\"";
		$motion = $this->getFieldValue($block,"turn");
		
		return $motion."();";
	}


	public function soft_turn($block){
		self::$definitions['includefirebird'] = "#include \"firebird.h\"";
		$motion = $this->getFieldValue($block,"turn");
		
		return $motion."();";
	}

	public function back_turn($block){
	
		self::$definitions['includefirebird'] = "#include \"firebird.h\"";
		$motion = $this->getFieldValue($block,"turn");
		switch($motion){
			case 'back_right':{$motion = "soft_right_2";break;}
			case 'back_left':{$motion = "soft_left_2";break;}
		}

		return $motion."();";
	}

	public function velocity($block){

		self::$definitions['includefirebird'] = "#include \"firebird.h\"";
		$left = $this->valueToCode($block,"left");
		$right = $this->valueToCode($block, "right");

		return "velocity(".$left.",".$right.");";
	}

	//POSITIONS
	public function position_motion($block){
	//	global $definitions;
		self::$definitions['includefirebird'] = "#include \"firebird.h\"";
		$arg = $this->valueToCode($block,"motion");
		$motion = $this->getFieldValue($block,"forward");
		switch($motion){
			case 'fwd':{$motion = 'forward_mm';break;}
			case 'back':{$motion = 'back_mm';break;}
		}
		return $motion."(".$arg.");";
	}

	public function position_turn($block){
	//	global $definitions;
		self::$definitions['includefirebird'] = "#include \"firebird.h\"";
		$arg = $this->valueToCode($block,"motion");
		$arg = $arg%360;
		$motion = $this->getFieldValue($block,"forward");
		switch($motion){
			case 'right':{$motion = 'right_degrees';break;}
			case 'left':{$motion = 'left_degrees';break;}
		}
		return $motion."(".$arg.");";
	}

	public function position_turn_soft($block){
		// global $definitions;
		self::$definitions['includefirebird'] = "#include \"firebird.h\"";
		$arg = $this->valueToCode($block,"soft_turn");
		$arg = $arg%360;
		$motion = $this->getFieldValue($block,"forward");
		switch($motion){
			case 'right':{$motion = 'soft_right_degrees';break;}
			case 'left':{$motion = 'soft_left_degrees';break;}
		}
		return $motion."(".$arg.");";
	}

	public function position_turn_back($block){
		// global $definitions;
		self::$definitions['includefirebird'] = "#include \"firebird.h\"";
		$arg = $this->valueToCode($block,"turn_back");
		$arg = $arg%360;
		$motion = $this->getFieldValue($block,"forward");
		switch($motion){
			case 'right':{$motion = 'soft_right_2_degrees';break;}
			case 'left':{$motion = 'soft_left_2_degrees';break;}
		}
		return $motion."(".$arg.");";
	}

	public function sensor_white($block){
		// global $definitions;
		self::$definitions['includefirebird'] = "#include \"firebird.h\"";
		$arg = $this->getFieldValue($block,"line_sensor");
		$code='';
	   switch ($arg) {
	    case 'center':{$code = 'ADC_Conversion(2)';break;}
	    case 'left':{$code = 'ADC_Conversion(3)';break;}
		case 'right':{$code = 'ADC_Conversion(1)';break;}
	   }

	  return $code;
	}

	public function sensor_sharp($block){
		// global $definitions;
		self::$definitions['includefirebird'] = "#include \"firebird.h\"";
		$arg = $this->getFieldValue($block,"sharp");
		$code = "";
	    switch ($arg) {
	     case 'fr':{$code = 'ADC_Conversion(11)';break;}
	     case 'lf':{$code = 'ADC_Conversion(9)';break;}
		 case 'rf':{$code = 'ADC_Conversion(13)';break;}
		 case 'ld':{$code = 'ADC_Conversion(10)';break;}
		 case 'rd':{$code = 'ADC_Conversion(12)';break;}   
	  	}

	  return $code;
	}

	public function sensor_ir($block){
		
		self::$definitions['includefirebird'] = "#include \"firebird.h\"";
		$arg = $this->getFieldValue($block,"ir");
		return $arg;
	}

	public function buzzer_on($block){
		
		self::$definitions['includefirebird'] = "#include \"firebird.h\"";
		return "buzzer_on();";
	}

	public function buzzer_off($block){
		// global $definitions;
		self::$definitions['includefirebird'] = "#include \"firebird.h\"";
		return "buzzer_off();";
	}

	public function delay_ms($block){
		
		self::$definitions['includedelay'] = "#include <util/delay.h>";
		$value = $this->valueToCode($block,"delay_value");
		$value = $value!= NULL?$value:"0";
		return "_delay_ms(".$value.");";
	}


	//MATHS

	public function math_arithmetic($block){
		// global $definitions;
		$arg0 = $this->valueToCode($block,"A");
		$arg0 = ($arg0!=NULL)?$arg0:'0';
		$arg1 = $this->getFieldValue($block,"OP");
		$arg2 = $this->valueToCode($block,"B");
		$arg2 = ($arg2!=NULL)?$arg2:'0';
		switch($arg1){
			case 'ADD':{$arg1 = ' + ';break;}
			case 'MINUS':{$arg1 = ' - ';break;}
			case 'MULTIPLY':{$arg1 = ' * ';break;}
			case 'DIVIDE':{$arg1 = ' / ';break;}
			case 'POWER':{break;}
		}

		if($arg1 == 'POWER'){
			self::$definitions['includemath'] = "#include <math.h>";
			return "pow(".$arg0.",".$arg1.");";
		}
		$code = $arg0.$arg1.$arg2;
		return $code;
	}

	public function math_single($block){
		$arg = $this->getFieldValue($block,"OP");
		$value = $this->valueToCode($block,"NUM");
		$value = $value!=NULL?$value:'0';
		// global $definitions;
		if($arg != 'NEG'){
			self::$definitions['includemath'] = "#include<math.h>";
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

	public function math_trig($block){
		$arg = $this->getFieldValue($block,"OP");
		$value = $this->valueToCode($block,"NUM");
		$value = $value!=NULL?$value:'0';
		$value = strval((3.1416/180)*floatval($value));
		// global $definitions;
		self::$definitions['includemath'] = "#include<math.h>";
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

	public function math_modulo($block){
		$dividend = $this->valueToCode($block,"DIVIDEND");
		$divisor = $this->valueToCode($block,"DIVISOR");
		$dividend = $dividend!=NULL?$dividend:"0";
		$divisor = $divisor!=NULL?$divisor:"0";

		return $dividend."%".$divisor;
	}


	//BASICS


	public function pin($block){
		$code = $this->getFieldValue($block,"pin");
		return $code;
	}

	public function set_item($block){

		$arg = $this->getFieldValue($block,"specifier");
		$arg1 = $this->getFieldValue($block,"polarity");
		$arg5 = $this->getFieldValue($block,"size");
		if($arg == "none"){
			$arg = '';
		}else{
			$arg = $arg.' ';
		}
		if($arg5 == "none"){
			$arg5 = '';
		}else{
			$arg5 = $arg5.' ';
		}
		if($arg1=="signed"){
			$arg1 = '';
		}else{
			$arg1 = $arg1.' ';
		}
		$arg2 = $this->getFieldValue($block,"type");
		$arg3 = $this->getFieldValue($block,"NAME");
		$arg4 = $this->valueToCode($block,"input");
		$arg4 = $arg4 !=NULL? " = ".$arg4:"";
		$code= $arg.$arg1.$arg5.$arg2." ".$arg3.$arg4.";";
		return $code;

	}

	public function check_hex($arg){
		
		if(substr("$arg",0,2)=="0x"){
			if(strlen($arg) == 4){
			  if(ctype_xdigit(substr($arg,-2))){
				
					return 1;
				}	
			}
		}
		return 0;
		
	}

	public function register($block)
	{

		$arg1 = $this->getFieldValue($block,"register");
		$arg2 = $this->getFieldValue($block,"set/reset");
		$arg3 = $this->valueToCode($block,"regex");

		if($arg2=="set"){
			$arg2="|";
		}else if($arg2=='none'){
			$code = $arg1." = ".$arg3.";";
			return $code;
		}
		else{
			$arg2="&";
		}
		$code=$arg1."=".$arg1." ".$arg2." ".$arg3.";";

		return $code;
	}

	public function call_function($block)
	{
		$args=array();
		$args=$this->getArgs($block);
		$funcName=$this->getFieldValue($block,"NAME");
		 if(count($args)!=NULL){
		 	return $funcName."(".join(", ",$args).");";
		 }
		 else{
		 	return $funcName."();";
		 }


	}
	public function call_function_with_return($block)
	{
		$args=array();
		$args=$this->getArgs($block);
		$funcName=$this->getFieldValue($block,"NAME");
		if(count($args)!=NULL){
			return $funcName."(".join(", ",$args).");";
		}
		else{
			return $funcName."();";
		}


	}
	public function function_defnoreturn($block){

		$funcName = $this->getFieldValue($block,"NAME");
		$statements = $this->statementToCode($block,"STACK");
		$args = array();
		$args = $this->getArgs($block);
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

	public function function_defreturn($block){
		$type=   $this->getFieldValue($block,"types");
		$funcName = $this->getFieldValue($block,"NAME");
		$statements = $this->statementToCode($block,"STACK");
		$args=array();
		$args=$this->getArgs($block);
		$return = $this->valueToCode($block,"RETURN");
		//echo "$return";
		$return = $return !=NULL? $return:'0';

	    if(count($args)!=NULL){
			return "$type ".$funcName."(".join(", ",$args)."){\n".$statements."\n return"." ".$return."; \n};";
	    }else{
			return "$type ".$funcName."() {\n".$statements."\n return ".$return.";\n}";
	    }
	}

	//CALL public functionS

	public function procedures_callnoreturn($block){
		$mutate = $block->firstChild;
		if($mutate->nodeName == 'mutation'){
			$funcName = $mutate->getAttribute('name');
			$args = $this->getArgs($block);
			$i=0;
			$argcode = array();
			if($args != NULL){
				foreach($args as $arg){
					$argcode[$i] = $this->valueToCode($block,"ARG".$i);
					$i++;
				}
				return $funcName."(".join(", ",$argcode).");";
			}else{
				return $funcName."();";
			}
		}
		return null;
	}

	public function procedures_callreturn($block){
		$mutate = $block->firstChild;
		if($mutate->nodeName == 'mutation'){
			$funcName = $mutate->getAttribute('name');		
			$args = $this->getArgs($block);
			$i=0;
			$argcode = array();
			if($args != NULL){
				foreach($args as $arg){
					$argcode[$i] = $this->valueToCode($block,"ARG".$i);
					$i++;
				}
				return $funcName."(".join(", ",$argcode).")";
			}else{
				return $funcName."()";
			}

		}
		return null;
	}

	public function hex($block){
		$value = $this->getFieldValue($block,'HEX');
		return $value;
	}

	public function returnr($block){
		$retval = $this->valueToCode($block,"RETURN");
		return "return ".$retval;
	}


	public function valriable_get($block){
		$field = $this->getFieldValue($block,"VAR");
		return $field;
	}


	public function incl_ude($block){
		$incl = $this->getFieldValue($block,"header file");

		return "#include \"".$incl."\"";
	}


	public function def_ine($block){
		$defval = $this->getFieldValue($block,"name");
		$defreplace = $this->getFieldValue($block,"value");
		if($defval =='F_CPU'){
			
			self::$fcpu = "#define ".$defval." ".$defreplace;
			return '';
		}
		return "#define ".$defval." ".$defreplace."\n";

	}

	public function LCD_init($block){

		self::$definitions['includelcd'] = "#include \"lcd.c\"";
		$code = "lcd_init();\nlcd_set_4_bit();\n";

		return $code;
	}

	public function cursor($block){
		self::$definitions['includelcd'] = "#include \"lcd.c\"";
		$pos = $this->getFieldValue($block,'position');
		$value = $this->valueToCode($block,'Cursor');
		$value = $value!=NULL ? $value : '0';
		$code = '';
		switch ($pos) {
	    case 'row': {$code = 'row_pos= '.$value.';\n';break;}
	    case 'column': {$code = 'column_pos= '.$value.';\n';break;}
  		}
		return $code;
	}

	public function channel($block){
		
		$arg = $this->valueToCode($block,'channel');
		$arg = $arg != NULL?$arg:'0';
		$row = $this->valueToCode($block,'row');
		$col = $this->valueToCode($block,'column');
		$digit = $this->getFieldValue($block,'digit');
		$code = '';
		self::$definitions['includefirebird'] = "#include \"firebird.h\"";
		switch ($arg) {
		  case 'ADC_Conversion(2)':
		      $code = 'lcd_print('.$row.','.$col.','.$arg.','.$digit.');';
		      break;
		  case 'ADC_Conversion(3)':
		      $code = 'lcd_print('.$row.','.$col.','.$arg.','.$digit.');';
		      break;
			case 'ADC_Conversion(1)':
		      $code = 'lcd_print('.$row.','.$col.','.$arg.','.$digit.');';
		      break;
		  case 'ADC_Conversion(4)':
		      $code = 'lcd_print('.$row.','.$col.','.$arg.','.$digit.');';
			  break;
			case 'ADC_Conversion(5)':
		      $code = 'lcd_print('.$row.','.$col.','.$arg.','.$digit.');';
			  break;
			case 'ADC_Conversion(6)':
		      $code = 'lcd_print('.$row.','.$col.','.$arg.','.$digit.');';
			  break;
			case 'ADC_Conversion(7)':
		      $code = 'lcd_print('.$row.','.$col.','.$arg.','.$digit.');';
			  break;
			case 'ADC_Conversion(8)':
		      $code = 'lcd_print('.$row.','.$col.','.$arg.','.$digit.');';
			  break;
			case 'spi_master_tx_and_rx(5)':
		      $code = 'lcd_print('.$row.','.$col.','.$arg.','.$digit.');';
			  break;
			case 'spi_master_tx_and_rx(6)':
		      $code = 'lcd_print('.$row.','.$col.','.$arg.','.$digit.');';
			  break;
			case 'spi_master_tx_and_rx(7)':
		      $code = 'lcd_print('.$row.','.$col.','.$arg.','.$digit.');';
		      break;
		  case 'ADC_Conversion(9)':
		      $code = 'lcd_print('.$row.','.$col.','.$arg.','.$digit.');';
		      break;
		  case 'ADC_Conversion(10)':
		      $code = 'lcd_print('.$row.','.$col.','.$arg.','.$digit.');';
		      break;
			case 'ADC_Conversion(11)':
		      $code = 'lcd_print('.$row.','.$col.','.$arg.','.$digit.');';
		      break;
		  case 'ADC_Conversion(12)':
		      $code = 'lcd_print('.$row.','.$col.','.$arg.','.$digit.');';
			  break;
			case 'ADC_Conversion(13)':
		      $code = 'lcd_print('.$row.','.$col.','.$arg.','.$digit.');';
		      break;
		   default : 
		      $code = 'lcd_print('.$row.','.$col.','.$arg.','.$digit.');';
		      break;
  		}

  		return $code;
	}


	public function variables_set($block){
		$item = $this->getFieldValue($block,"VAR");
		$value = $this->valueToCode($block,"VALUE");

		return $item." = ".$value.";\n";
	}

	public function variables_get($block){
		$item = $this->getFieldValue($block,"VAR");
	
		return $item;
	}


	public function isRoutine($block){
		$vector = $this->getFieldValue($block,"VECTOR");
		$attribute = $this->getFieldValue($block,"ATTR");
		$bloc = $this->statementToCode($block, "BLOC");
		if($attribute != 'attribute'){
			$attribute = ', '.$attribute;
		}else{
			$attribute = '';
		}
		return 'ISR('.$vector.$attribute.'){'.$bloc.'}';
	}

	public function isSignal($block){
		$vector = $this->getFieldValue($block,"VECTOR");
		$bloc = $this->statementToCode($block, "BLOC");
		return 'SIGNAL('.$vector.'){'.$bloc.'}';
	}

	public function type_casting($block){
		$type = $this->getFieldValue($block,"TYPECAST");
		$value = $this->valueToCode($block,"VAL");
		$code = "(".$type.") ".$value;
		return $code;
	}

	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function parse()
	{

	
		if(Request::ajax()){
			$data = Input::all();
			$file = $data['xmlText'];

			$file = preg_replace('/>[ ]*</mi', '><', $file); 
			$xmlDoc = new \DOMDocument();
			$xmlDoc->loadXML($file);
			self::$definitions['includeavrio'] = "#include <avr/io.h>";
			self::$definitions['includeinterrupt'] = "#include <avr/interrupt.h>";
			self::$definitions['includedelay'] = "#include <util/delay.h>";
			$this->xmlToCode($xmlDoc);
			print_r(self::$strings);
			$_SESSION['file'] = self::$strings;
			header('Location: /gcc/compile.php');

		}
		
	    // Escape any html characters
		//Get the xml file
		//removes all the blank spaces
		//$file = preg_replace('/>[ ]*</mi', '><', $file); 

		//Instantiates and Loads the xml File
		//$xmlDoc = new DOMDocument();
		//$xmlDoc->loadXML($file);
		//actual shit to do
		//return echo xmlToCode($xmlDoc);
		}
	}
