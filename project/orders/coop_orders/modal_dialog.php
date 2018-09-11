<?php

  $today = date("Y-m-d", time());

?>


<div id="dialog-confirm" title="�������� ����� ������" class='hidden'>

<div class="ui-widget">
  <div class='label_div'><label for="tagsByOrderDSEName">����������� : </label></div>
  <input id="tagsByOrderDSEName" class='error'>
</div>

<div class="ui-widget">
  <div class='label_div'><label>���������� : </label></div>
  <select id="aim_select" class='error'>
    <option value="-1">...</option>
    <option value="1">�����</option>
    <option value="2">��������</option>
    <option value="3">�����</option>    
    <option value="4">���. ������.</option>    
    <option value="5">���. �����</option>    
    <option value="6">������</option>    
  </select>
</div>

<hr>

<div class="ui-widget">
  <div class='label_div'><label>������������ ��� : </label></div>
  <select id="tagsByDSE" class='error'>
    <option value="-1">...</option>
  </select>
</div>

<div class="ui-widget">
  <div class='label_div'>
  <label>����������</label></div>
  <input type='text' id="count" value='0' class='error'>
</div>

<hr>

<div class="ui-widget">
  <div class='label_div'><label>��� ���������</label></div>
  <select id="ProcKind"  class='error'>
    <option value="-1">...</option>
  </select>
</div>

<div class="ui-widget">
  <div class='label_div'><label>��� ���������</label></div>
  <select id="ProcType" class='error'>
    <option value="-1">...</option>
  </select>
</div>

<div class="ui-widget">
  <div class='label_div'><label>���� ����������</label></div>
  <input type='date' id="exec_date" class='error' min='<?= $today; ?>'>  
</div>

<hr>

<div class="ui-widget">
  <div class='label_div'><label>��� ���������</label></div>
  <select id="MaterialKind" class='error'>
    <option value="-1">...</option>
  </select>
</div>

<div class="ui-widget">
  <div class='label_div'><label>������ ���������</label></div>
  <select id="MaterialSubKind" class='error'>
    <option value="-1">...</option>
  </select>
</div>


<div class="ui-widget">
  <div class='label_div'><label>��� ���������</label></div>
  <select id="MaterialType" class='error'>
    <option value="-1">...</option>
  </select>
</div>

<div class="ui-widget">
  <div class='label_div'><label>������ ��������</label></div>
  <input id="OtherMaterial" class='allowed'>
</div>

<hr>

<div class="ui-widget">

<span >��������� �� �� : </span>
<input id="labor_times_for_item" type='text' value='0' class='error'>

<span>��������� �� ������ : </label>
<input id="labor_times_for_group" type='text' value='0' disabled class='error'>

</div>

<hr>
<div class="ui-widget">
<div class='label_div'><label for="labor_times_for_group">���������� : </label></div>
<textarea id="notes"></textarea>
</div>

<div class="ui-widget" hidden>
  <div class='label_div'><label for="tagsByOrderName">�������� ������ : </label></div>
  <input id="tagsByOrderName" class='error' disabled>
</div>

<div class="ui-widget hidden">
  <div class='label_div'><label for="curindex">Current index : </label></div>
  <input id="curindex" disabled>
</div>

<div class="ui-widget hidden">
  <div class='label_div'><label for="curdse">Current DSE : </label></div>
  <input id="curdse" disabled>
</div>

<div class="ui-widget hidden">
  <div class='label_div'><label for="zn_zag">ZN_ZAG : </label></div>
  <input id="zn_zag" disabled>
</div>
 
</div>

<?php

?>