'use strict'

goog.provide('Blockly.Firebird.interrupts');

goog.require('Blockly.Firebird');

Blockly.Firebird['int_serv_routine'] = function(block){
	var vector = block.getFieldValue('VECTOR');
	var attribute = block.getFieldValue('ATTR');
	var bloc = Blockly.Firebird.statementToCode(block,'BLOC');
	if(attribute != 'attribute'){
		attribute = ', '+ attribute;
	}else{
		attribute = '';
	}
	return "ISR("+vector+attribute+"){\n"+bloc+"\n}";
};

Blockly.Firebird['int_signal'] = function(block){
	var vector = block.getFieldValue('VECTOR');
	var bloc = Blockly.Firebird.statementToCode(block,'BLOC');
	return "SIGNAL("+vector+"){\n"+bloc+"\n}";
};