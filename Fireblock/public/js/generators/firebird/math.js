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
 * @fileoverview Generating Firebird for math blocks.
 * @author q.neutron@gmail.com (Quynh Neutron)
 */
'use strict';

goog.provide('Blockly.Firebird.math');

goog.require('Blockly.Firebird');


Blockly.Firebird.addReservedWords('Math');

Blockly.Firebird['math_number'] = function(block) {
  // Numeric value.
  var code = window.parseFloat(block.getFieldValue('NUM'));
  // -4.abs() returns -4 in Firebird due to strange order of operation choices.
  // -4 is actually an operator and a number.  Reflect this in the order.
  var order = code < 0 ?
      Blockly.Firebird.ORDER_UNARY_PREFIX : Blockly.Firebird.ORDER_ATOMIC;
  return [code, order];
};

Blockly.Firebird['math_arithmetic'] = function(block) {
  // Basic arithmetic operators, and power.
  var OPERATORS = {
    'ADD': [' + ', Blockly.Firebird.ORDER_ADDITIVE],
    'MINUS': [' - ', Blockly.Firebird.ORDER_ADDITIVE],
    'MULTIPLY': [' * ', Blockly.Firebird.ORDER_MULTIPLICATIVE],
    'DIVIDE': [' / ', Blockly.Firebird.ORDER_MULTIPLICATIVE],
    'POWER': [null, Blockly.Firebird.ORDER_NONE]  // Handle power separately.
  };
  var tuple = OPERATORS[block.getFieldValue('OP')];
  var operator = tuple[0];
  var order = tuple[1];
  var argument0 = Blockly.Firebird.valueToCode(block, 'A', order) || '0';
  var argument1 = Blockly.Firebird.valueToCode(block, 'B', order) || '0';
  var code;
  // Power in Firebird requires a special case since it has no operator.
  if (!operator) {
    Blockly.Firebird.definitions_['import_Firebird_math'] =
        'import \'Firebird:math\' as Math;';
    code = 'Math.pow(' + argument0 + ', ' + argument1 + ')';
    return [code, Blockly.Firebird.ORDER_UNARY_POSTFIX];
  }
  code = argument0 + operator + argument1;
  return [code, order];
};

Blockly.Firebird['math_single'] = function(block) {
  // Math operators with single operand.
  var operator = block.getFieldValue('OP');
  var code;
  var arg;
  if (operator == 'NEG') {
    // Negation is a special case given its different operator precedence.
    arg = Blockly.Firebird.valueToCode(block, 'NUM',
        Blockly.Firebird.ORDER_UNARY_PREFIX) || '0';
    if (arg[0] == '-') {
      // --3 is not legal in Firebird.
      arg = ' ' + arg;
    }
    code = '-' + arg;
    return [code, Blockly.Firebird.ORDER_UNARY_PREFIX];
  }
  Blockly.Firebird.definitions_['import_Firebird_math'] =
      'import \'Firebird:math\' as Math;';
  if (operator == 'ABS' || operator.substring(0, 5) == 'ROUND') {
    arg = Blockly.Firebird.valueToCode(block, 'NUM',
        Blockly.Firebird.ORDER_UNARY_POSTFIX) || '0';
  } else if (operator == 'SIN' || operator == 'COS' || operator == 'TAN') {
    arg = Blockly.Firebird.valueToCode(block, 'NUM',
        Blockly.Firebird.ORDER_MULTIPLICATIVE) || '0';
  } else {
    arg = Blockly.Firebird.valueToCode(block, 'NUM',
        Blockly.Firebird.ORDER_NONE) || '0';
  }
  // First, handle cases which generate values that don't need parentheses
  // wrapping the code.
  switch (operator) {
    case 'ABS':
      code = arg + '.abs()';
      break;
    case 'ROOT':
      code = 'Math.sqrt(' + arg + ')';
      break;
    case 'LN':
      code = 'Math.log(' + arg + ')';
      break;
    case 'EXP':
      code = 'Math.exp(' + arg + ')';
      break;
    case 'POW10':
      code = 'Math.pow(10,' + arg + ')';
      break;
    case 'ROUND':
      code = arg + '.round()';
      break;
    case 'ROUNDUP':
      code = arg + '.ceil()';
      break;
    case 'ROUNDDOWN':
      code = arg + '.floor()';
      break;
    case 'SIN':
      code = 'Math.sin(' + arg + ' / 180 * Math.PI)';
      break;
    case 'COS':
      code = 'Math.cos(' + arg + ' / 180 * Math.PI)';
      break;
    case 'TAN':
      code = 'Math.tan(' + arg + ' / 180 * Math.PI)';
      break;
  }
  if (code) {
    return [code, Blockly.Firebird.ORDER_UNARY_POSTFIX];
  }
  // Second, handle cases which generate values that may need parentheses
  // wrapping the code.
  switch (operator) {
    case 'LOG10':
      code = 'Math.log(' + arg + ') / Math.log(10)';
      break;
    case 'ASIN':
      code = 'Math.asin(' + arg + ') / Math.PI * 180';
      break;
    case 'ACOS':
      code = 'Math.acos(' + arg + ') / Math.PI * 180';
      break;
    case 'ATAN':
      code = 'Math.atan(' + arg + ') / Math.PI * 180';
      break;
    default:
      throw 'Unknown math operator: ' + operator;
  }
  return [code, Blockly.Firebird.ORDER_MULTIPLICATIVE];
};

Blockly.Firebird['math_constant'] = function(block) {
  // Constants: PI, E, the Golden Ratio, sqrt(2), 1/sqrt(2), INFINITY.
  var CONSTANTS = {
    'PI': ['Math.PI', Blockly.Firebird.ORDER_UNARY_POSTFIX],
    'E': ['Math.E', Blockly.Firebird.ORDER_UNARY_POSTFIX],
    'GOLDEN_RATIO':
        ['(1 + Math.sqrt(5)) / 2', Blockly.Firebird.ORDER_MULTIPLICATIVE],
    'SQRT2': ['Math.SQRT2', Blockly.Firebird.ORDER_UNARY_POSTFIX],
    'SQRT1_2': ['Math.SQRT1_2', Blockly.Firebird.ORDER_UNARY_POSTFIX],
    'INFINITY': ['double.INFINITY', Blockly.Firebird.ORDER_ATOMIC]
  };
  var constant = block.getFieldValue('CONSTANT');
  if (constant != 'INFINITY') {
    Blockly.Firebird.definitions_['import_Firebird_math'] =
        'import \'Firebird:math\' as Math;';
  }
  return CONSTANTS[constant];
};

Blockly.Firebird['math_number_property'] = function(block) {
  // Check if a number is even, odd, prime, whole, positive, or negative
  // or if it is divisible by certain number. Returns true or false.
  var number_to_check = Blockly.Firebird.valueToCode(block, 'NUMBER_TO_CHECK',
      Blockly.Firebird.ORDER_MULTIPLICATIVE);
  if (!number_to_check) {
    return ['false', Blockly.Python.ORDER_ATOMIC];
  }
  var dropdown_property = block.getFieldValue('PROPERTY');
  var code;
  if (dropdown_property == 'PRIME') {
    // Prime is a special case as it is not a one-liner test.
    Blockly.Firebird.definitions_['import_Firebird_math'] =
        'import \'Firebird:math\' as Math;';
    var functionName = Blockly.Firebird.provideFunction_(
        'math_isPrime',
        [ 'bool ' + Blockly.Firebird.FUNCTION_NAME_PLACEHOLDER_ + '(n) {',
          '  // https://en.wikipedia.org/wiki/Primality_test#Naive_methods',
          '  if (n == 2 || n == 3) {',
          '    return true;',
          '  }',
          '  // False if n is null, negative, is 1, or not whole.',
          '  // And false if n is divisible by 2 or 3.',
          '  if (n == null || n <= 1 || n % 1 != 0 || n % 2 == 0 ||' +
            ' n % 3 == 0) {',
          '    return false;',
          '  }',
          '  // Check all the numbers of form 6k +/- 1, up to sqrt(n).',
          '  for (var x = 6; x <= Math.sqrt(n) + 1; x += 6) {',
          '    if (n % (x - 1) == 0 || n % (x + 1) == 0) {',
          '      return false;',
          '    }',
          '  }',
          '  return true;',
          '}']);
    code = functionName + '(' + number_to_check + ')';
    return [code, Blockly.Firebird.ORDER_UNARY_POSTFIX];
  }
  switch (dropdown_property) {
    case 'EVEN':
      code = number_to_check + ' % 2 == 0';
      break;
    case 'ODD':
      code = number_to_check + ' % 2 == 1';
      break;
    case 'WHOLE':
      code = number_to_check + ' % 1 == 0';
      break;
    case 'POSITIVE':
      code = number_to_check + ' > 0';
      break;
    case 'NEGATIVE':
      code = number_to_check + ' < 0';
      break;
    case 'DIVISIBLE_BY':
      var divisor = Blockly.Firebird.valueToCode(block, 'DIVISOR',
          Blockly.Firebird.ORDER_MULTIPLICATIVE);
      if (!divisor) {
        return ['false', Blockly.Python.ORDER_ATOMIC];
      }
      code = number_to_check + ' % ' + divisor + ' == 0';
      break;
  }
  return [code, Blockly.Firebird.ORDER_EQUALITY];
};

Blockly.Firebird['math_change'] = function(block) {
  // Add to a variable in place.
  var argument0 = Blockly.Firebird.valueToCode(block, 'DELTA',
      Blockly.Firebird.ORDER_ADDITIVE) || '0';
  var varName = Blockly.Firebird.variableDB_.getName(block.getFieldValue('VAR'),
      Blockly.Variables.NAME_TYPE);
  return varName + ' = (' + varName + ' is num ? ' + varName + ' : 0) + ' +
      argument0 + ';\n';
};

// Rounding functions have a single operand.
Blockly.Firebird['math_round'] = Blockly.Firebird['math_single'];
// Trigonometry functions have a single operand.
Blockly.Firebird['math_trig'] = Blockly.Firebird['math_single'];

Blockly.Firebird['math_on_list'] = function(block) {
  // Math functions for lists.
  var func = block.getFieldValue('OP');
  var list = Blockly.Firebird.valueToCode(block, 'LIST',
      Blockly.Firebird.ORDER_NONE) || '[]';
  var code;
  switch (func) {
    case 'SUM':
      var functionName = Blockly.Firebird.provideFunction_(
          'math_sum',
          [ 'num ' + Blockly.Firebird.FUNCTION_NAME_PLACEHOLDER_ +
              '(List myList) {',
            '  num sumVal = 0;',
            '  myList.forEach((num entry) {sumVal += entry;});',
            '  return sumVal;',
            '}']);
      code = functionName + '(' + list + ')';
      break;
    case 'MIN':
      Blockly.Firebird.definitions_['import_Firebird_math'] =
          'import \'Firebird:math\' as Math;';
      var functionName = Blockly.Firebird.provideFunction_(
          'math_min',
          [ 'num ' + Blockly.Firebird.FUNCTION_NAME_PLACEHOLDER_ +
              '(List myList) {',
            '  if (myList.isEmpty) return null;',
            '  num minVal = myList[0];',
            '  myList.forEach((num entry) ' +
              '{minVal = Math.min(minVal, entry);});',
            '  return minVal;',
            '}']);
      code = functionName + '(' + list + ')';
      break;
    case 'MAX':
      Blockly.Firebird.definitions_['import_Firebird_math'] =
          'import \'Firebird:math\' as Math;';
      var functionName = Blockly.Firebird.provideFunction_(
          'math_max',
          [ 'num ' + Blockly.Firebird.FUNCTION_NAME_PLACEHOLDER_ +
              '(List myList) {',
            '  if (myList.isEmpty) return null;',
            '  num maxVal = myList[0];',
            '  myList.forEach((num entry) ' +
                  '{maxVal = Math.max(maxVal, entry);});',
            '  return maxVal;',
            '}']);
      code = functionName + '(' + list + ')';
      break;
    case 'AVERAGE':
      // This operation exclude null and values that are not int or float:
      //   math_mean([null,null,"aString",1,9]) == 5.0.
      var functionName = Blockly.Firebird.provideFunction_(
          'math_average',
          [ 'num ' + Blockly.Firebird.FUNCTION_NAME_PLACEHOLDER_ +
              '(List myList) {',
            '  // First filter list for numbers only.',
            '  List localList = new List.from(myList);',
            '  localList.removeMatching((a) => a is! num);',
            '  if (localList.isEmpty) return null;',
            '  num sumVal = 0;',
            '  localList.forEach((num entry) {sumVal += entry;});',
            '  return sumVal / localList.length;',
            '}']);
      code = functionName + '(' + list + ')';
      break;
    case 'MEDIAN':
      var functionName = Blockly.Firebird.provideFunction_(
          'math_median',
          [ 'num ' + Blockly.Firebird.FUNCTION_NAME_PLACEHOLDER_ +
              '(List myList) {',
            '  // First filter list for numbers only, then sort, ' +
              'then return middle value',
            '  // or the average of two middle values if list has an ' +
              'even number of elements.',
            '  List localList = new List.from(myList);',
            '  localList.removeMatching((a) => a is! num);',
            '  if (localList.isEmpty) return null;',
            '  localList.sort((a, b) => (a - b));',
            '  int index = localList.length ~/ 2;',
            '  if (localList.length % 2 == 1) {',
            '    return localList[index];',
            '  } else {',
            '    return (localList[index - 1] + localList[index]) / 2;',
            '  }',
            '}']);
      code = functionName + '(' + list + ')';
      break;
    case 'MODE':
      Blockly.Firebird.definitions_['import_Firebird_math'] =
          'import \'Firebird:math\' as Math;';
      // As a list of numbers can contain more than one mode,
      // the returned result is provided as an array.
      // Mode of [3, 'x', 'x', 1, 1, 2, '3'] -> ['x', 1].
      var functionName = Blockly.Firebird.provideFunction_(
          'math_modes',
          [ 'List ' + Blockly.Firebird.FUNCTION_NAME_PLACEHOLDER_ +
              '(List values) {',
            '  List modes = [];',
            '  List counts = [];',
            '  int maxCount = 0;',
            '  for (int i = 0; i < values.length; i++) {',
            '    var value = values[i];',
            '    bool found = false;',
            '    int thisCount;',
            '    for (int j = 0; j < counts.length; j++) {',
            '      if (counts[j][0] == value) {',
            '        thisCount = ++counts[j][1];',
            '        found = true;',
            '        break;',
            '      }',
            '    }',
            '    if (!found) {',
            '      counts.add([value, 1]);',
            '      thisCount = 1;',
            '    }',
            '    maxCount = Math.max(thisCount, maxCount);',
            '  }',
            '  for (int j = 0; j < counts.length; j++) {',
            '    if (counts[j][1] == maxCount) {',
            '        modes.add(counts[j][0]);',
            '    }',
            '  }',
            '  return modes;',
            '}']);
      code = functionName + '(' + list + ')';
      break;
    case 'STD_DEV':
      Blockly.Firebird.definitions_['import_Firebird_math'] =
          'import \'Firebird:math\' as Math;';
      var functionName = Blockly.Firebird.provideFunction_(
          'math_standard_deviation',
          [ 'num ' + Blockly.Firebird.FUNCTION_NAME_PLACEHOLDER_ +
              '(List myList) {',
            '  // First filter list for numbers only.',
            '  List numbers = new List.from(myList);',
            '  numbers.removeMatching((a) => a is! num);',
            '  if (numbers.isEmpty) return null;',
            '  num n = numbers.length;',
            '  num sum = 0;',
            '  numbers.forEach((x) => sum += x);',
            '  num mean = sum / n;',
            '  num sumSquare = 0;',
            '  numbers.forEach((x) => sumSquare += ' +
                  'Math.pow(x - mean, 2));',
            '  return Math.sqrt(sumSquare / n);',
            '}']);
      code = functionName + '(' + list + ')';
      break;
    case 'RANDOM':
      Blockly.Firebird.definitions_['import_Firebird_math'] =
          'import \'Firebird:math\' as Math;';
      var functionName = Blockly.Firebird.provideFunction_(
          'math_random_item',
          [ 'dynamic ' + Blockly.Firebird.FUNCTION_NAME_PLACEHOLDER_ +
              '(List myList) {',
            '  int x = new Math.Random().nextInt(myList.length);',
            '  return myList[x];',
            '}']);
      code = functionName + '(' + list + ')';
      break;
    default:
      throw 'Unknown operator: ' + func;
  }
  return [code, Blockly.Firebird.ORDER_UNARY_POSTFIX];
};

Blockly.Firebird['math_modulo'] = function(block) {
  // Remainder computation.
  var argument0 = Blockly.Firebird.valueToCode(block, 'DIVIDEND',
      Blockly.Firebird.ORDER_MULTIPLICATIVE) || '0';
  var argument1 = Blockly.Firebird.valueToCode(block, 'DIVISOR',
      Blockly.Firebird.ORDER_MULTIPLICATIVE) || '0';
  var code = argument0 + ' % ' + argument1;
  return [code, Blockly.Firebird.ORDER_MULTIPLICATIVE];
};

Blockly.Firebird['math_constrain'] = function(block) {
  // Constrain a number between two limits.
  Blockly.Firebird.definitions_['import_Firebird_math'] =
      'import \'Firebird:math\' as Math;';
  var argument0 = Blockly.Firebird.valueToCode(block, 'VALUE',
      Blockly.Firebird.ORDER_NONE) || '0';
  var argument1 = Blockly.Firebird.valueToCode(block, 'LOW',
      Blockly.Firebird.ORDER_NONE) || '0';
  var argument2 = Blockly.Firebird.valueToCode(block, 'HIGH',
      Blockly.Firebird.ORDER_NONE) || 'double.INFINITY';
  var code = 'Math.min(Math.max(' + argument0 + ', ' + argument1 + '), ' +
      argument2 + ')';
  return [code, Blockly.Firebird.ORDER_UNARY_POSTFIX];
};

Blockly.Firebird['math_random_int'] = function(block) {
  // Random integer between [X] and [Y].
  Blockly.Firebird.definitions_['import_Firebird_math'] =
      'import \'Firebird:math\' as Math;';
  var argument0 = Blockly.Firebird.valueToCode(block, 'FROM',
      Blockly.Firebird.ORDER_NONE) || '0';
  var argument1 = Blockly.Firebird.valueToCode(block, 'TO',
      Blockly.Firebird.ORDER_NONE) || '0';
  var functionName = Blockly.Firebird.provideFunction_(
      'math_random_int',
      [ 'int ' + Blockly.Firebird.FUNCTION_NAME_PLACEHOLDER_ + '(num a, num b) {',
        '  if (a > b) {',
        '    // Swap a and b to ensure a is smaller.',
        '    num c = a;',
        '    a = b;',
        '    b = c;',
        '  }',
        '  return new Math.Random().nextInt(b - a + 1) + a;',
        '}']);
  var code = functionName + '(' + argument0 + ', ' + argument1 + ')';
  return [code, Blockly.Firebird.ORDER_UNARY_POSTFIX];
};

Blockly.Firebird['math_random_float'] = function(block) {
  // Random fraction between 0 and 1.
  Blockly.Firebird.definitions_['import_Firebird_math'] =
      'import \'Firebird:math\' as Math;';
  return ['new Math.Random().nextDouble()', Blockly.Firebird.ORDER_UNARY_POSTFIX];
};


Blockly.Firebird['type_casting'] = function(block){
  var value = Blockly.Firebird.valueToCode(block,'VAL', Blockly.Firebird.ORDER_ATOMIC);
  var type = block.getFieldValue('TYPECAST');
  var code = '('+ type +') '+ value;
  return [code, Blockly.Firebird.ORDER_ATOMIC];
};