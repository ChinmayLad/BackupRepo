'use strict';

goog.provide('Blockly.Firebird.function');

goog.require('Blockly.Firebird');
Blockly.Firebird.addReservedWords('Math');
Blockly.Firebird['pin'] = function(block) {
  // Numeric value.
  var code = block.getFieldValue('pin');
  // -4.abs() returns -4 in Firebird due to strange order of operation choices.
  // -4 is actually an operator and a number.  Reflect this in the order.
  //var order = code < 0 ?
     // Blockly.Firebird.ORDER_UNARY_PREFIX : Blockly.Firebird.ORDER_ATOMIC;
   return [code, Blockly.Firebird.ORDER_ATOMIC];
};
Blockly.Firebird['set_item'] = function(block) {
  // Variable getter.
  var argument = block.getFieldValue('specifier');
 var argument0 = block.getFieldValue('polarity');
  var argument1 = block.getFieldValue('type');
  var argument2= block.getFieldValue('NAME');
  var argument3 = block.getFieldValue('size');
  if(argument == 'none'){
    argument='';
  }else{
    argument += ' ';
  }
  if(argument3 == 'none'){
    argument3='';
  }else{
    argument3 += ' ';
  }
  if(argument0 == 'signed'){
    argument0 = '';
  }else{
    argument0 += ' ';
  }

   var arg =Blockly.Firebird.valueToCode(block, 'input', Blockly.Firebird.ORDER_NONE) || 0;
   if(!arg){
    arg = '';
   }else{
    arg = ' = ' + arg;
   }
   var code= argument+ argument0+ argument3 + argument1 + ' ' + argument2 + arg + ';' +'\n';
   return code;
};

Blockly.Firebird['register'] = function(block) {
  var arg0=block.getFieldValue('register');
  var arg1=block.getFieldValue('set/reset'); 
  var arg2=Blockly.Firebird.valueToCode(block, 'regex', Blockly.Firebird.ORDER_MULTIPLICATIVE);


  if(arg1=='set'){
    arg1='|';
  }
  else if(arg1 == 'none'){
    return arg0+' = '+ arg2+';';
  }
  else{
    arg1='&';
  }
  var code=arg0 + ' = ' + arg0 + ' ' + arg1 + ' ' + arg2 + ';'+'\n';
   return code;
 };

 Blockly.Firebird['call_function'] = function(block) {
  var funcName = block.getFieldValue('NAME');
    
    var args = [];
  for (var x = 0; x < block.arguments_.length; x++) {
    args[x] = Blockly.Firebird.valueToCode(block, 'ARG' + x,
        Blockly.Firebird.ORDER_NONE) || 'null';
    args[x] =block.arguments_[x];
    
  }
  var code = funcName + '(' + args.join(', ') + ')'
      + ';' + '\n';
  //code = Blockly.Firebird.scrub_(block, code);
  
  return code;
   };
 Blockly.Firebird['call_function_with_return'] = function(block) {
  var funcName = block.getFieldValue('NAME');
    
    var args = [];
  for (var x = 0; x < block.arguments_.length; x++) {
    args[x] = Blockly.Firebird.valueToCode(block, 'ARG' + x,
        Blockly.Firebird.ORDER_NONE) || 'null';
    args[x] =block.arguments_[x];
    
  }
  var code = funcName + '(' + args.join(', ') + ')';
  //code = Blockly.Firebird.scrub_(block, code);
  
  return code;
   };
    
    Blockly.Firebird['function_defnoreturn'] = function(block) {
  // Define a procedure with a return value.
  var funcName = Blockly.Firebird.variableDB_.getName(block.getFieldValue('NAME'),
      Blockly.Procedures.NAME_TYPE);
  var branch = Blockly.Firebird.statementToCode(block, 'STACK');
  if (Blockly.Firebird.STATEMENT_PREFIX) {
    branch = Blockly.Firebird.prefixLines(
        Blockly.Firebird.STATEMENT_PREFIX.replace(/%1/g,
        '\'' + block.id + '\''), Blockly.Firebird.INDENT) + branch;
  }
  if (Blockly.Firebird.INFINITE_LOOP_TRAP) {
    branch = Blockly.Firebird.INFINITE_LOOP_TRAP.replace(/%1/g,
        '\'' + block.id + '\'') + branch;
  }
  var returnValue = Blockly.Firebird.valueToCode(block, 'RETURN',
      Blockly.Firebird.ORDER_NONE) || '';
  if (returnValue) {
    returnValue = '  return ' + returnValue + ';\n';
  }
  var returnType = returnValue ? 'dynamic' : 'void';
  var args = [];
  for (var x = 0; x < block.arguments_.length; x++) {
    args[x] = block.arguments2_[x].substr(0,block.arguments2_[x].lastIndexOf(' '))+ ' ' +Blockly.Firebird.variableDB_.getName(block.arguments_[x],
        Blockly.Variables.NAME_TYPE);
  }
  var code = returnType + ' ' + funcName + '(' + args.join(', ') + ') {\n' +
      branch + returnValue + '\n}';
  code = Blockly.Firebird.scrub_(block, code);
  
  return code;
};


 Blockly.Firebird['procedures_callnoreturn'] = function(block) {
  // Call a procedure with no return value.
  var funcName = Blockly.Firebird.variableDB_.getName(block.getFieldValue('NAME'),
      Blockly.Procedures.NAME_TYPE);
  var args = [];
  for (var x = 0; x < block.arguments_.length; x++) {
    args[x] = Blockly.Firebird.valueToCode(block, 'ARG' + x,
        Blockly.Firebird.ORDER_NONE) || 'null';
  }
  var code = funcName + '(' + args.join(', ') + ');\n';
  return code;
};
Blockly.Firebird['function_defreturn'] = function(block) {
  // Define a procedure with a return value.
  var funcName = Blockly.Firebird.variableDB_.getName(block.getFieldValue('NAME'),
      Blockly.Procedures.NAME_TYPE);
  var returnType=block.getFieldValue('types');
  var branch = Blockly.Firebird.statementToCode(block, 'STACK');
  if (Blockly.Firebird.STATEMENT_PREFIX) {
    branch = Blockly.Firebird.prefixLines(
        Blockly.Firebird.STATEMENT_PREFIX.replace(/%1/g,
        '\'' + block.id + '\''), Blockly.Firebird.INDENT) + branch;
  }
  if (Blockly.Firebird.INFINITE_LOOP_TRAP) {
    branch = Blockly.Firebird.INFINITE_LOOP_TRAP.replace(/%1/g,
        '\'' + block.id + '\'') + branch;
  }
  var returnValue = Blockly.Firebird.valueToCode(block, 'RETURN',
      Blockly.Firebird.ORDER_NONE) || '';
  if (returnValue) {
    returnValue = '  return ' + returnValue + ';\n';
  }
  //var returnType = returnValue ? 'dynamic' : 'void';
  var args = [];
  for (var x = 0; x < block.arguments_.length; x++) {
    args[x] = block.arguments2_[x].substr(0,block.arguments2_[x].lastIndexOf(' '))+ " " +block.arguments_[x];
        
  }
  var code = returnType + ' ' + funcName + '(' + args.join(', ') + ') {\n' +
      branch + returnValue + '}';
  code = Blockly.Firebird.scrub_(block, code);
  
  return code;
};
Blockly.Firebird['procedures_callreturn'] = function(block) {
  // Call a procedure with a return value.
  var funcName = Blockly.Firebird.variableDB_.getName(block.getFieldValue('NAME'),
      Blockly.Procedures.NAME_TYPE);
  var args = [];
  for (var x = 0; x < block.arguments_.length; x++) {
    args[x] = Blockly.Firebird.valueToCode(block, 'ARG' + x,
        Blockly.Firebird.ORDER_NONE) || 'null';
  }
  var code = funcName + '(' + args.join(', ') + ')';
  return [code, Blockly.Firebird.ORDER_UNARY_POSTFIX];
};

Blockly.Firebird['return'] = function(block){
  var retval = Blockly.Firebird.valueToCode(block,"RETURN",
      Blockly.Firebird.ORDER_ATOMIC)|| '0';

  return "return " + retval + ";";
};


Blockly.Firebird['hex']  = function(block){
  var value = block.getFieldValue("HEX");
  if(!(value.length ==4 && value.substr(0,2) == '0x' && value.substr(2,2).match('^[0-9A-F][0-9A-F]'))){
    alert('give hex value');
  }
  return [value, Blockly.Firebird.ORDER_MULTIPLICATIVE];
};

Blockly.Firebird['incl_ude'] = function(block) {
  var arg =block.getFieldValue('header file');
  var code="#include\""+arg+"\""+"\n";
  return code;
};
//Blockly.Firebird[''] = function(block) 

Blockly.Firebird['define'] = function(block) {
  var arg0=block.getFieldValue('name');
  var arg1=block.getFieldValue('value');
  if(arg0 == 'F_CPU'){
    Blockly.Firebird.definitions_['defineFCPU'] = "#define "+arg0+" "+arg1+ "\n";
    return '';
  }
  var code ="#define "+arg0+" "+arg1+ "\n";
  return code;
};

