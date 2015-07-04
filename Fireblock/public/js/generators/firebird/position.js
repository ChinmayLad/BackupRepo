
'use strict';		
goog.provide('Blockly.Firebird.position');

goog.require('Blockly.Firebird');


Blockly.Firebird['position_motion'] = function(block) {
  // Math operators with single operand.
  var operator = block.getFieldValue('forward');
  var code;
  var arg;
  
      arg = Blockly.Firebird.valueToCode(block, 'motion',
        Blockly.Firebird.ORDER_MULTIPLICATIVE) || '0';
 
  // First, handle cases which generate values that don't need parentheses
  // wrapping the code.
  switch (operator) {
    case 'fwd':
      code = 'forward_mm(' + arg + ');\n';
      break;
    case 'back':
      code = 'back_mm(' + arg + ');\n';
      break;
   
  }
   Blockly.Firebird.definitions_['include_firebird']= '#include "firebird.h"';
 
    return (code);
};

Blockly.Firebird['position_turn'] = function(block) {
  // Math operators with single operand.
  var operator = block.getFieldValue('forward');
  var code;
  var arg;
  
      arg = Blockly.Firebird.valueToCode(block, 'motion',
        Blockly.Firebird.ORDER_MULTIPLICATIVE) || '0';
 
  // First, handle cases which generate values that don't need parentheses
  // wrapping the code.
  switch (operator) {
    case 'left':
      code = 'left_degrees(' + arg + ');\n';
      break;
    case 'right':
      code = 'right_degrees(' + arg + ');\n';
      break;
   
  }
  Blockly.Firebird.definitions_['include_firebird']= '#include "firebird.h"';
    return (code);
};

Blockly.Firebird['position_turn_soft'] = function(block) {
  // Math operators with single operand.
  var operator = block.getFieldValue('forward');
  var code;
  var arg;
  
      arg = Blockly.Firebird.valueToCode(block, 'soft_turn',
        Blockly.Firebird.ORDER_MULTIPLICATIVE) || '0';
 
  // First, handle cases which generate values that don't need parentheses
  // wrapping the code.
  switch (operator) {
    case 'left':
      code = 'soft_left_degrees(' + arg + ');\n';
      break;
    case 'right':
      code = 'soft_right_degrees(' + arg + ');\n';
      break;
   
  }
  Blockly.Firebird.definitions_['include_firebird']= '#include "firebird.h"';
    return (code);
};

Blockly.Firebird['position_turn_back'] = function(block) {
  // Math operators with single operand.
  var operator = block.getFieldValue('forward');
  var code;
  var arg;
   Blockly.Firebird.definitions_['include_firebird']= '#include "firebird.h"';
      arg = Blockly.Firebird.valueToCode(block, 'back_turn',
        Blockly.Firebird.ORDER_MULTIPLICATIVE) || '0';
 
  // First, handle cases which generate values that don't need parentheses
  // wrapping the code.
  switch (operator) {
    case 'left':
      code = 'soft_left_2_degrees(' + arg + ');\n';
      break;
    case 'right':
      code = 'soft_right_2_degrees(' + arg + ');\n';
      break;
   
  }
 
    return (code);
};
