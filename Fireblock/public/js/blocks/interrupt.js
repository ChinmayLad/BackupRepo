'use strict';

goog.provide('Blockly.Blocks.interrupts');

goog.require('Blockly.Blocks');

Blockly.Blocks.interrupts.HUE = 290;

Blockly.Blocks['int_serv_routine'] = {
  init: function() {
    this.appendDummyInput()
        .appendField("ISR")
        .appendField(new Blockly.FieldTextInput("vector"), "VECTOR")
        .appendField(new Blockly.FieldTextInput("attribute"), "ATTR");
    this.appendStatementInput("BLOC")
        .setCheck(null);
    this.setColour(290);  
    this.setTooltip('');
    this.setHelpUrl('http://www.example.com/');
  }
};

Blockly.Blocks['int_signal'] = {
  init: function() {
    this.appendDummyInput()
        .appendField("SIGNAL")
        .appendField(new Blockly.FieldTextInput("vector"), "VECTOR");
    this.appendStatementInput("BLOC")
        .setCheck(null);
    this.setColour(290);  
    this.setTooltip('');
    this.setHelpUrl('http://www.example.com/');
  }
};