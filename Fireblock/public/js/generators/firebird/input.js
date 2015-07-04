'use strict';	
goog.provide('Blockly.Firebird.input');

goog.require('Blockly.Firebird');

Blockly.Firebird['io_buzzer'] = function(block) {
  // Math operators with single operand.
  
  var code;
  var arg;
  
      arg = Blockly.Firebird.valueToCode(block, 'buzzer',
        Blockly.Firebird.ORDER_MULTIPLICATIVE) || '0';
 
  // First, handle cases which generate values that don't need parentheses
  // wrapping the code.
  
      code = 'buzzer_ms(' + arg + ');\n';
     
 
    return [code, Blockly.Firebird.ORDER_ATOMIC];
};

Blockly.Firebird['io_switch'] = function(block) {
  // Math operators with single operand.
  
  
  var operator = block.getFieldValue('status');
      
  // First, handle cases which generate values that don't need parentheses
  // wrapping the code.
  
  var code = '(' + operator+ ');\n';
     
 
    return [code, Blockly.Firebird.ORDER_ATOMIC];
};