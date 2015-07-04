'use strict';		
goog.provide('Blockly.Firebird.io');

goog.require('Blockly.Firebird');


Blockly.Blocks['io_buzzer'] = {
  init: function() {
    this.setHelpUrl('http://www.example.com/');
    this.setColour(0);
    this.appendValueInput("buzzer")
        .setCheck(["Number", "String"])
        .appendField("buzzer msec");
    this.setPreviousStatement(true);
    this.setNextStatement(true);
    this.setTooltip('');
  }
};

Blockly.Blocks['io_ledbargraph'] = {
  init: function() {
    this.setHelpUrl('http://www.example.com/');
    this.setColour(0);
    this.appendDummyInput()
        .appendField(new Blockly.FieldDropdown([["ON", "on"], ["OFF", "off"]]), "check")
        .appendField("bargraph LED")
        .appendField(new Blockly.FieldDropdown([["ALL", "all"], ["1", "1"], ["2", "2"], ["3", "3"], ["4", "4"], ["5", "5"], ["6", "6"], ["7", "7"], ["8", "8"]]), "LED_no");
    this.setPreviousStatement(true);
    this.setNextStatement(true);
    this.setTooltip('');
  }
};



Blockly.Blocks['io_switch'] = {
  init: function() {
    this.setHelpUrl('http://www.example.com/');
    this.setColour(0);
    this.appendDummyInput()
        .appendField("Switch is ")
        .appendField(new Blockly.FieldDropdown([["pressed", "press"], ["not pressed", "not press"]]), "status");
    this.setOutput(true);
    this.setTooltip('');
  }
};