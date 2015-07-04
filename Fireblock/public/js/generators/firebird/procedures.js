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
 * @fileoverview Generating Firebird for procedure blocks.
 * @author fraser@google.com (Neil Fraser)
 */
'use strict';

goog.provide('Blockly.Firebird.procedures');

goog.require('Blockly.Firebird');


Blockly.Firebird['procedures_defreturn'] = function(block) {
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
    args[x] = Blockly.Firebird.variableDB_.getName(block.arguments_[x],
        Blockly.Variables.NAME_TYPE);
  }
  var code = returnType + ' ' + funcName + '(' + args.join(', ') + ') {\n' +
      branch + returnValue + '}';
  code = Blockly.Firebird.scrub_(block, code);
  Blockly.Firebird.definitions_[funcName] = code;
  return null;
};

// Defining a procedure without a return value uses the same generator as
// a procedure with a return value.
Blockly.Firebird['procedures_defnoreturn'] = Blockly.Firebird['procedures_defreturn'];

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

Blockly.Firebird['procedures_ifreturn'] = function(block) {
  // Conditionally return value from a procedure.
  var condition = Blockly.Firebird.valueToCode(block, 'CONDITION',
      Blockly.Firebird.ORDER_NONE) || 'false';
  var code = 'if (' + condition + ') {\n';
  if (block.hasReturnValue_) {
    var value = Blockly.Firebird.valueToCode(block, 'VALUE',
        Blockly.Firebird.ORDER_NONE) || 'null';
    code += '  return ' + value + ';\n';
  } else {
    code += '  return;\n';
  }
  code += '}\n';
  return code;
};
