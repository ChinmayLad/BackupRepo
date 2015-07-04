'use strict';		
goog.provide('Blockly.Firebird.display');

goog.require('Blockly.Firebird');

Blockly.Firebird['display_init'] = function(block) {
  
  var code= 'lcd_init();\n lcd_set_4_bit();\n';
  
  return (code);
};

Blockly.Firebird['display_cursor'] = function(block) {
  // Math operators with single operand.
  var operator = block.getFieldValue('position');
  var code;
  var arg;
  
      arg = Blockly.Firebird.valueToCode(block, 'Cursor',
        Blockly.Firebird.ORDER_MULTIPLICATIVE) || '0';
 
  // First, handle cases which generate values that don't need parentheses
  // wrapping the code.
  switch (operator) {
    case 'row':
      code = 'row_pos= ' + arg + ';\n';
      break;
    case 'column':
      code = 'column_pos= ' + arg + ';\n';
      break;
   
  }
 
    return (code);
};

Blockly.Firebird['display_channel'] = function(block) {
  // Math operators with single operand.
  
  var code;
  
  var arg = Blockly.Firebird.valueToCode(block, 'channel',
        Blockly.Firebird.ORDER_MULTIPLICATIVE) || '0';
  var row = Blockly.Firebird.valueToCode(block, 'row',
        Blockly.Firebird.ORDER_MULTIPLICATIVE) || '0';
  var col = Blockly.Firebird.valueToCode(block, 'column',
        Blockly.Firebird.ORDER_MULTIPLICATIVE) || '0';
  var digit = block.getFieldValue('digit');
  arg = arg.replace("(","");
  arg = arg.replace(")","");  
 // alert(arg);
  // First, handle cases which generate values that don't need parentheses
  // wrapping the code.
  switch (arg) {
  case '0' : 
      code = 'Block not connected to display value of()';
      break;
  case 'ADC_Conversion(2)':
      code = 'lcd_print('+ row + ',' + col + ',' + arg +',' + digit + ');\n';
      break;
  case 'ADC_Conversion(3)':
      code = 'lcd_print('+ row + ',' + col + ',' + arg +',' + digit + ');\n';
      break;
	case 'ADC_Conversion(1)':
      code = 'lcd_print('+ row + ',' + col + ',' + arg +',' + digit + ');\n';
      break;
  case 'ADC_Conversion(4)':
      code = 'lcd_print('+ row + ',' + col + ',' + arg +',' + digit + ');\n';
	  break;
	case 'ADC_Conversion(5)':
      code = 'lcd_print('+ row + ',' + col + ',' + arg +',' + digit + ');\n';
	  break;
	case 'ADC_Conversion(6)':
      code = 'lcd_print('+ row + ',' + col + ',' + arg +',' + digit + ');\n';
	  break;
	case 'ADC_Conversion(7)':
      code = 'lcd_print('+ row + ',' + col + ',' + arg +',' + digit + ');\n';
	  break;
	case 'ADC_Conversion(8)':
      code = 'lcd_print('+ row + ',' + col + ',' + arg +',' + digit + ');\n';
	  break;
	case 'spi_master_tx_and_rx(5)':
      code = 'lcd_print('+ row + ',' + col + ',' + arg +',' + digit + ');\n';
	  break;
	case 'spi_master_tx_and_rx(6)':
      code = 'lcd_print('+ row + ',' + col + ',' + arg +',' + digit + ');\n';
	  break;
	case 'spi_master_tx_and_rx(7)':
      code = 'lcd_print('+ row + ',' + col + ',' + arg +',' + digit + ');\n';
      break;
  case 'ADC_Conversion(9)':
      code = 'lcd_print('+ row + ',' + col + ',' + arg +',' + digit + ');\n';
      break;
  case 'ADC_Conversion(10)':
      code = 'lcd_print('+ row + ',' + col + ',' + arg +',' + digit + ');\n';
      break;
	case 'ADC_Conversion(11)':
      code = 'lcd_print('+ row + ',' + col + ',' + arg +',' + digit + ');\n';
      break;
  case 'ADC_Conversion(12)':
      code = 'lcd_print('+ row + ',' + col + ',' + arg +',' + digit + ');\n';
	  break;
	case 'ADC_Conversion(13)':
      code = 'lcd_print('+ row + ',' + col + ',' + arg +',' + digit + ');\n';
  default:
      code = 'lcd_print('+ row + ',' + col + ',' + arg +',' + digit + ');\n';
  }
 
  return code;
};

Blockly.Firebird['logic_compare1'] = function(block) {
  // Comparison operator.
  var OPERATORS = {
    'EQ': '==',
    'NE': '!=',
    'LT': '<',
    'LE': '<=',
    'GT': '>',
    'GE': '>='
  };
  var operator = OPERATORS[block.getFieldValue('OP')];
 
  var argument0 = Blockly.Firebird.valueToCode(block, 'A', Blockly.Firebird.ORDER_MULTIPLICATIVE) || '0';
  var argument1 = Blockly.Firebird.valueToCode(block, 'B', Blockly.Firebird.ORDER_MULTIPLICATIVE) || '0';
  var code = argument0 + ' ' + operator + ' ' + argument1;
  return [code, Blockly.Firebird.ORDER_MULTIPLICATIVE];
};