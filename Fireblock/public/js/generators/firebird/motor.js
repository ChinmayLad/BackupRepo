'use strict';

goog.provide('Blockly.Firebird.motor');

goog.require('Blockly.Firebird');


Blockly.Firebird['motor_forward'] = function(block) {
      //Blockly.Firebird.definitions_('port_restore') = 'port_restore = 0;\n'
  var code = 'forward();\n';
  return code;
};

Blockly.Firebird['motor_backward'] = function(block) {
      //Blockly.Firebird.definitions_('port_restore') = 'port_restore = 0;\n'
  var code = 'backward();\n';
  return code;
};

Blockly.Firebird['motor_forward_mm'] = function(block) {
  var mm_dist = Blockly.Firebird.valueToCode(block, 'VALUE', Blockly.Firebird.ORDER_NONE) || '1000';
  var code = 'forward_mm(' + mm_dist + ');\n';
  return code;
};

Blockly.Firebird['motor_backward_mm'] = function(block) {
  var mm_dist = Blockly.Firebird.valueToCode(block, 'VALUE', Blockly.Firebird.ORDER_NONE) || '1000';
  var code = 'backward_mm(' + mm_dist + ');\n';
  return code;
};
