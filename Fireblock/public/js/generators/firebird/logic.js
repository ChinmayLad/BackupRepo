/**
 * @license
 * Visual Blocks Language
 *
 * Copyright 2014 Google Inc.
 * https://developers.google.com/blockly/
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/**
 * @fileoverview Generating Firebird for logic blocks.
 * @author q.neutron@gmail.com (Quynh Neutron)
 */
'use strict';

goog.provide('Blockly.Firebird.logic');

goog.require('Blockly.Firebird');


Blockly.Firebird['controls_if'] = function(block) {
  // If/elseif/else condition.
  var n = 0;
  var argument = Blockly.Firebird.valueToCode(block, 'IF' + n,
      Blockly.Firebird.ORDER_NONE) || 'false';
  var branch = Blockly.Firebird.statementToCode(block, 'DO' + n);
  var code = 'if (' + argument + ') {\n' + branch + '}';
  for (n = 1; n <= block.elseifCount_; n++) {
    argument = Blockly.Firebird.valueToCode(block, 'IF' + n,
      Blockly.Firebird.ORDER_NONE) || 'false';
    branch = Blockly.Firebird.statementToCode(block, 'DO' + n);
    code += ' else if (' + argument + ') {\n' + branch + '}';
  }
  if (block.elseCount_) {
    branch = Blockly.Firebird.statementToCode(block, 'ELSE');
    code += ' else {\n' + branch + '}';
  }
  return code + '\n';
};

Blockly.Firebird['logic_compare'] = function(block) {
  // Comparison operator.
  var OPERATORS = {
    'EQ': '==',
    'NEQ': '!=',
    'LT': '<',
    'LTE': '<=',
    'GT': '>',
    'GTE': '>='
  };
  var operator = OPERATORS[block.getFieldValue('OP')];
  var order = (operator == '==' || operator == '!=') ?
      Blockly.Firebird.ORDER_EQUALITY : Blockly.Firebird.ORDER_RELATIONAL;
  var argument0 = Blockly.Firebird.valueToCode(block, 'A', order) || '0';
  var argument1 = Blockly.Firebird.valueToCode(block, 'B', order) || '0';
  var code = argument0 + ' ' + operator + ' ' + argument1;
  return [code, order];
};

Blockly.Firebird['logic_operation'] = function(block) {
  // Operations 'and', 'or'.
  var operator = (block.getFieldValue('OP') == 'AND') ? '&&' : '||';
  var order = (operator == '&&') ? Blockly.Firebird.ORDER_LOGICAL_AND :
      Blockly.Firebird.ORDER_LOGICAL_OR;
  var argument0 = Blockly.Firebird.valueToCode(block, 'A', order);
  var argument1 = Blockly.Firebird.valueToCode(block, 'B', order);
  if (!argument0 && !argument1) {
    // If there are no arguments, then the return value is false.
    argument0 = 'false';
    argument1 = 'false';
  } else {
    // Single missing arguments have no effect on the return value.
    var defaultArgument = (operator == '&&') ? 'true' : 'false';
    if (!argument0) {
      argument0 = defaultArgument;
    }
    if (!argument1) {
      argument1 = defaultArgument;
    }
  }
  var code = argument0 + ' ' + operator + ' ' + argument1;
  return [code, order];
};

Blockly.Firebird['logic_negate'] = function(block) {
  // Negation.
  var order = Blockly.Firebird.ORDER_UNARY_PREFIX;
  var argument0 = Blockly.Firebird.valueToCode(block, 'BOOL', order) || 'true';
  var code = '!' + argument0;
  return [code, order];
};

Blockly.Firebird['logic_boolean'] = function(block) {
  // Boolean values true and false.
  var code = (block.getFieldValue('BOOL') == 'TRUE') ? '1' : '0';
  return [code, Blockly.Firebird.ORDER_ATOMIC];
};

Blockly.Firebird['logic_null'] = function(block) {
  // Null data type.
  return ['null', Blockly.Firebird.ORDER_ATOMIC];
};

Blockly.Firebird['logic_ternary'] = function(block) {
  // Ternary operator.
  var value_if = Blockly.Firebird.valueToCode(block, 'IF',
      Blockly.Firebird.ORDER_CONDITIONAL) || 'false';
  var value_then = Blockly.Firebird.valueToCode(block, 'THEN',
      Blockly.Firebird.ORDER_CONDITIONAL) || 'null';
  var value_else = Blockly.Firebird.valueToCode(block, 'ELSE',
      Blockly.Firebird.ORDER_CONDITIONAL) || 'null';
  var code = value_if + ' ? ' + value_then + ' : ' + value_else;
  return [code, Blockly.Firebird.ORDER_CONDITIONAL];
};

/*
Blockly.Firebird['buzzer'] = function(block) {

 // Blockly.Firebird.definitions_['define_buzzer'] = "#include<buzzer.h>\n";

  // Port Initializer
  var code = 'buzzer_port_config();\n';
  return code;
};*/

Blockly.Firebird['delay_ms'] = function(block) {
  var delay_time = Blockly.Firebird.valueToCode(block, 'delay_value', Blockly.Firebird.ORDER_NONE) || '1000';
  var code = '_delay_ms(' + delay_time + ');\n';
  return code;
};

Blockly.Firebird['buzzer_on'] = function(block) {
      Blockly.Firebird.definitions_['include_firebird']= '#include "firebird.h"';
  var code = 'buzzer_on();\n';
  return code;
};

Blockly.Firebird['buzzer_off'] = function(block) {
   Blockly.Firebird.definitions_['include_firebird']= '#include "firebird.h"';
      //Blockly.Firebird.definitions_('port_restore') = 'port_restore = 0;\n'
  var code = 'buzzer_off();\n';
  return code;
};


Blockly.Firebird['bitwise_operator'] = function(block) {
  var A = Blockly.Firebird.valueToCode(block,"A",Blockly.Firebird.ORDER_NONE);
  var B = Blockly.Firebird.valueToCode(block,"B",Blockly.Firebird.ORDER_NONE);
  var OP = block.getFieldValue('OP');
  var code ='';
  switch(OP){
    case 'BITAND':{code = A+' & '+B;break;}
    case 'BITOR':{code = A+' | '+B;break;}
  }
  
  return [code, Blockly.Firebird.ORDER_CONDITIONAL];
};