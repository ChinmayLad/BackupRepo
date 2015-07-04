'use strict';

goog.provide('Blockly.Blocks.position');

goog.require('Blockly.Blocks');

Blockly.Blocks['position_motion'] = {
  init: function() {
    this.setHelpUrl('http://www.example.com/');
    this.setColour(260);
    this.appendValueInput("motion")
        .setCheck("Number")
        .setAlign(Blockly.ALIGN_RIGHT)
        .appendField(" ")
        .appendField(new Blockly.FieldDropdown([["forward", "fwd"], ["back", "back"]]), "forward")
        .appendField("distance mm");
    this.setInputsInline(true);
    this.setPreviousStatement(true);
    this.setNextStatement(true);
    this.setTooltip('');
  }
};

Blockly.Blocks['position_turn'] = {
  init: function() {
    this.setHelpUrl('http://www.example.com/');
    this.setColour(260);
    this.appendValueInput("motion")
        .setCheck("Number")
        .setAlign(Blockly.ALIGN_RIGHT)
        .appendField(" ")
        .appendField(new Blockly.FieldDropdown([["left", "left"], ["right", "right"]]), "forward")
        .appendField("turn degrees");
    this.setPreviousStatement(true);
    this.setNextStatement(true);
    this.setTooltip('');
  }
};

Blockly.Blocks['position_turn_soft'] = {
  init: function() {
    this.setHelpUrl('http://www.example.com/');
    this.setColour(260);
    this.appendValueInput("soft_turn")
        .setCheck("Number")
        .setAlign(Blockly.ALIGN_RIGHT)
        .appendField("soft")
        .appendField(new Blockly.FieldDropdown([["left", "left"], ["right", "right"]]), "forward")
        .appendField("turn degrees");
    this.setPreviousStatement(true);
    this.setNextStatement(true);
    this.setTooltip('');
  }
}

Blockly.Blocks['position_turn_back'] = {
  init: function() {
    this.setHelpUrl('http://www.example.com/');
    this.setColour(260);
    this.appendValueInput("back_turn")
        .setCheck("Number")
        .setAlign(Blockly.ALIGN_RIGHT)
        .appendField("back")
        .appendField(new Blockly.FieldDropdown([["left", "left"], ["right", "right"]]), "forward")
        .appendField("turn degrees");
    this.setPreviousStatement(true);
    this.setNextStatement(true);
    this.setTooltip('');
  }
}
