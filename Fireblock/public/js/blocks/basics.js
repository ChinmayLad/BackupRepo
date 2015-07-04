'use strict';

goog.provide('Blockly.Blocks.buzzer');

goog.require('Blockly.Blocks');

Blockly.Blocks['basics_ddr'] = {
  init: function() {
    this.setHelpUrl('http://www.example.com/');
    this.setColour(290);
    this.appendValueInput("DDRS")
        .setCheck("String")
        .appendField(new Blockly.FieldDropdown([["DDR A", "DDRA"], ["DDR B", "DDRB"], ["DDR C", "DDRC"]]), "DDR");
    this.setInputsInline(true);
    this.setPreviousStatement(true, "null");
    this.setNextStatement(true, "null");
    this.setTooltip('');
  }
};

Blockly.Blocks['basics_port'] = {
  init: function() {
    this.setHelpUrl('http://www.example.com/');
    this.setColour(290);
    this.appendValueInput("PORTS")
        .setCheck("String")
        .appendField(new Blockly.FieldDropdown([["PORT A", "PORTA"], ["PORT B", "PORTB"], ["PORT C", "PORTC"]]), "PORT");
    this.setInputsInline(true);
    this.setPreviousStatement(true, "null");
    this.setNextStatement(true, "null");
    this.setTooltip('');
  }
};

Blockly.Blocks['basics_pin'] = {
  init: function() {
    this.setHelpUrl('http://www.example.com/');
    this.setColour(290);
    this.appendValueInput("PINS")
        .setCheck("String")
        .appendField(new Blockly.FieldDropdown([["PIN A", "PINA"], ["PIN B", "PINB"], ["PIN C", "PINC"]]), "PIN");
    this.setInputsInline(true);
    this.setPreviousStatement(true, "null");
    this.setNextStatement(true, "null");
    this.setTooltip('');
  }
};


Blockly.Blocks['hex'] = {
  /**
   * Block for HEX value.
   * @this Blockly.Block
   */
  init: function() {
    this.setHelpUrl('http://www.example.com/');
    this.setColour(280);
    this.appendDummyInput()
        .appendField(new Blockly.FieldTextInput(''), 'HEX');
    this.setOutput(true, 'String');
    this.setTooltip('');
  }
 };