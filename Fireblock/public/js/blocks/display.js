'use strict';

goog.provide('Blockly.Blocks.display');

goog.require('Blockly.Blocks');

Blockly.Blocks['display_init'] = {
  init: function() {
    this.setHelpUrl('http://www.example.com/');
    this.setColour(65);
    this.appendDummyInput()
        .appendField("LCD_init");
    this.setPreviousStatement(true);
    this.setNextStatement(true);
    this.setTooltip('');
  }
};

Blockly.Blocks['display_cursor'] = {
  init: function() {
    this.setHelpUrl('http://www.example.com/');
    this.setColour(65);
    this.appendValueInput("Cursor")
        .setCheck(["Number", "String"])
        .appendField("cursor position")
        .appendField(new Blockly.FieldDropdown([["Row", "row"], ["Column", "column"]]), "position");
    this.setPreviousStatement(true);
    this.setNextStatement(true);
    this.setTooltip('');
  }
};

Blockly.Blocks['display_channel'] = {
  init: function() {
    this.setHelpUrl('http://www.example.com/');
    this.setColour(65);
    this.appendValueInput("row")
        .appendField("print")
        .appendField("row")
    this.setInputsInline(true);
    this.appendValueInput("column")
        .appendField("column")
    this.setInputsInline(true);
    this.appendValueInput("channel")
        .setCheck("String")
        .appendField("digits")
        .appendField(new Blockly.FieldTextInput("3"),"digit");
    this.setPreviousStatement(true,"null");
    this.setNextStatement(true,"null");
    this.setTooltip('');
  }
};
