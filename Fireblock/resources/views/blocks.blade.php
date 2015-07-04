@extends('fireblock')

@section('head')
<table width="100%" height="100%">
    <tr>
      <td>
      	<h1>
          <a href="{{ url('/') }}">Fireblocks</a>&rlm; &gt;
          <a id="title">...</a>
    
        </h1>
      </td>
      <td class="farSide">
        <select id="languageMenu"></select>
      </td>
    </tr>
    <tr>
      <td colspan=2>
        <table width="100%">
          <tr id="tabRow" height="1em">
            <td id="tab_blocks" class="tabon">...</td>
            <td class="tabmin">&nbsp;</td>
            <td id="tab_firebird" class="taboff">FirebirdV</td>
            <td class="tabmin">&nbsp;</td>
            <td id="tab_xml" class="taboff">XML</td>
            <td class="tabmax">
             <!-- <button id="parseButton" data-link="{{ url('/parsing') }}" class="text" title="...">
               Parse
              </button>-->
              <button id="linkButton" class="notext" title="...">
                <img src="{{ asset('/media/1x1.gif') }}" class="link icon21">
              </button>
              <button id="runButton" class="text primary" title="...">
                Execute
              </button>
              <button id="trashButton" class="text" title="...">
                Clear
              </button>
              <button id="saveButton" class="text" onclick="saveTextAsFile()" title="Download and Save C File">
              Save Code
              </button>
              <input type="file" id="files" name="open" >
              <button id='openButton' class="text">Open Xml</button>
              <button id="execButton" onclick="saveXml()">Xml Save</button>
            </td>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td height="99%" colspan=2 id="content_area">
      </td>
    </tr>
  </table>
  <div id="content_blocks" class="content"></div>
  <pre id="content_firebird" class="content"></pre>
  <textarea id="content_xml" class="content" wrap="off"></textarea>

  <xml id="toolbox" style="display: none">
    <category id="catLogic">
      <block type="controls_if"></block>
      <block type="logic_compare"></block>
      <block type="logic_operation"></block>
      <block type="bitwise_operator"></block>
      <block type="logic_negate"></block>
      <block type="logic_boolean"></block>
      <block type="logic_null"></block>
      <block type="logic_ternary"></block>
    </category>
    <category id="catio">
      <block type="io_buzzer"></block>
      <block type="io_ledbargraph"></block>
      <block type="io_switch"></block>
    </category>
    <category id="catDisplay">
      <block type="display_init"></block>
      <block type="display_cursor"></block>
      <block type="display_channel">
        <value name="row">
          <block type="math_number" id="23">
            <field name="NUM">0</field>
          </block>
        </value>
        <value name="column">
          <block type="math_number" id="23">
            <field name="NUM">0</field>
          </block>
        </value>
      </block>
    </category>
    <category id="catMotion">
      <block type="motion"></block>
      <block type="turn"></block>
      <block type="soft_turn"></block>
      <block type="back_turn"></block>
      <block type="velocity">
        <value name="left">
          <block type="math_number">
            <field name="NUM">255</field>
          </block>
        </value>
        <value name="right">
          <block type="math_number">
            <field name="NUM">255</field>
          </block>
        </value>
      </block>
    </category>
    <category id="catPosition">
      <block type="position_motion">
        <value name="motion">
          <block type="math_number">
            <field name="NUM">1000</field>
          </block>
        </value>
      </block>
      <block type="position_turn">
        <value name="motion">
          <block type="math_number">
            <field name="NUM">90</field>
          </block>
        </value>
      </block>
      <block type="position_turn_soft">
        <value name="soft_turn">
          <block type="math_number">
            <field name="NUM">90</field>
          </block>
        </value>
      </block>
      <block type="position_turn_back">
        <value name="back_turn">
          <block type="math_number">
            <field name="NUM">90</field>
          </block>
        </value>
      </block>
    </category>
    <category id="catSensor">
      <block type="sensor_white"></block>
      <block type="sensor_sharp"></block>
      <block type="sensor_ir"></block>
    </category>
    <category id="catBuzzer">
      <block type="buzzer_on"></block>
      <block type="buzzer_off"></block>
      <block type="delay_ms">
        <value name="delay_value">
          <block type="math_number">
            <field name="NUM">1000</field>
          </block>
        </value>
      </block>
    </category>
    <category id="catLoops">
      <block type="controls_repeat_ext">
        <value name="TIMES">
          <block type="math_number">
            <field name="NUM">10</field>
          </block>
        </value>
      </block>
      <block type="controls_whileUntil"></block>
      <block type="controls_for">
        <value name="FROM">
          <block type="math_number">
            <field name="NUM">1</field>
          </block>
        </value>
        <value name="TO">
          <block type="math_number">
            <field name="NUM">10</field>
          </block>
        </value>
        <value name="BY">
          <block type="math_number">
            <field name="NUM">1</field>
          </block>
        </value>
      </block>
      <block type="controls_forEach"></block>
      <block type="controls_flow_statements"></block>
    </category>
    <category id="catMath">
      <block type="math_number"></block>
      <block type="math_arithmetic"></block>
      <block type="math_single"></block>
      <block type="math_trig"></block>
      <block type="math_constant"></block>
      <block type="math_number_property"></block>
      <block type="math_change">
        <value name="DELTA">
          <block type="math_number">
            <field name="NUM">1</field>
          </block>
        </value>
      </block>
      <block type="math_round"></block>
      <block type="math_on_list"></block>
      <block type="math_modulo"></block>
      <block type="math_constrain">
        <value name="LOW">
          <block type="math_number">
            <field name="NUM">1</field>
          </block>
        </value>
        <value name="HIGH">
          <block type="math_number">
            <field name="NUM">100</field>
          </block>
        </value>
      </block>
      <block type="math_random_int">
        <value name="FROM">
          <block type="math_number">
            <field name="NUM">1</field>
          </block>
        </value>
        <value name="TO">
          <block type="math_number">
            <field name="NUM">100</field>
          </block>
        </value>
      </block>
      <block type="math_random_float"></block>
      <block type="type_casting"></block>
    </category>
    <category id="catText">
      <block type="text"></block>
      <block type="text_join"></block>
      <block type="text_append">
        <value name="TEXT">
          <block type="text"></block>
        </value>
      </block>
      <block type="text_length"></block>
      <block type="text_isEmpty"></block>
      <block type="text_indexOf">
        <value name="VALUE">
          <block type="variables_get">
            <field name="VAR" class="textVar">...</field>
          </block>
        </value>
      </block>
      <block type="text_charAt">
        <value name="VALUE">
          <block type="variables_get">
            <field name="VAR" class="textVar">...</field>
          </block>
        </value>
      </block>
      <block type="text_getSubstring">
        <value name="STRING">
          <block type="variables_get">
            <field name="VAR" class="textVar">...</field>
          </block>
        </value>
      </block>
      <block type="text_changeCase"></block>
      <block type="text_trim"></block>
      <block type="text_print"></block>
      <block type="text_prompt_ext">
        <value name="TEXT">
          <block type="text"></block>
        </value>
      </block>
    </category>
    <category id="catLists">
      <block type="lists_create_empty"></block>
      <block type="lists_create_with"></block>
      <block type="lists_repeat">
        <value name="NUM">
          <block type="math_number">
            <field name="NUM">5</field>
          </block>
        </value>
      </block>
      <block type="lists_length"></block>
      <block type="lists_isEmpty"></block>
      <block type="lists_indexOf">
        <value name="VALUE">
          <block type="variables_get">
            <field name="VAR" class="listVar">...</field>
          </block>
        </value>
      </block>
      <block type="lists_getIndex">
        <value name="VALUE">
          <block type="variables_get">
            <field name="VAR" class="listVar">...</field>
          </block>
        </value>
      </block>
      <block type="lists_setIndex">
        <value name="LIST">
          <block type="variables_get">
            <field name="VAR" class="listVar">...</field>
          </block>
        </value>
      </block>
      <block type="lists_getSublist">
        <value name="LIST">
          <block type="variables_get">
            <field name="VAR" class="listVar">...</field>
          </block>
        </value>
      </block>
      <block type="lists_split">
        <value name="DELIM">
          <block type="text">
            <field name="TEXT">,</field>
          </block>
        </value>
      </block>
    </category>
    <category id="catColour">
      <block type="colour_picker"></block>
      <block type="colour_random"></block>
      <block type="colour_rgb">
        <value name="RED">
          <block type="math_number">
            <field name="NUM">100</field>
          </block>
        </value>
        <value name="GREEN">
          <block type="math_number">
            <field name="NUM">50</field>
          </block>
        </value>
        <value name="BLUE">
          <block type="math_number">
            <field name="NUM">0</field>
          </block>
        </value>
      </block>
      <block type="colour_blend">
        <value name="COLOUR1">
          <block type="colour_picker">
            <field name="COLOUR">#ff0000</field>
          </block>
        </value>
        <value name="COLOUR2">
          <block type="colour_picker">
            <field name="COLOUR">#3333ff</field>
          </block>
        </value>
        <value name="RATIO">
          <block type="math_number">
            <field name="NUM">0.5</field>
          </block>
        </value>
      </block>
    </category>
    <sep></sep>
    <category id="catVariables" custom="VARIABLE"></category>
    <category id="catFunctions" custom="PROCEDURE"></category>
    <category id="catInterrupts">
      <block type="int_serv_routine"><block>
      <block type="int_signals"><block>
    </category>
    <category id="catBasics">
      <block type="function_defnoreturn"></block>
      <block type="function_defreturn"></block>
      <block type="register"></block>
      <block type="pin"></block>
      <block type="set_item"></block>
      <block type="call_function"></block>
      <block type="call_function_with_return"></block>
      <block type="return"></block>
      <block type="hex"></block>
    </category>
    <category id="catPrecursor">
      <block type="incl_ude"></block>
      <block type="define"></block>
    </category>
  </xml>
@endsection