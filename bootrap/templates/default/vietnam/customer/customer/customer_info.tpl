<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" media="all" href="js/jscalendar/calendar-system.css" title="system" />
  <script type="text/javascript" src="js/jscalendar/calendar.js"></script>
  <script type="text/javascript" src="js/jscalendar/calendar-vn.js"></script>
  <script type="text/javascript" src="js/jscalendar/calendar-setup.js"></script>
<script type="text/javascript" src="js/ckeditor/ckeditor.js"></script>
<script src="js/ckeditor/_samples/sample.js" type="text/javascript"></script>
<link href="js/ckeditor/_samples/sample.css" rel="stylesheet" type="text/css" />
 <div class="toolbar">
<a href="JavaScript:doSave()"><img border="0" src="templates/{skin}/images/button-save.gif" width="20" height="20" alt="Save"><span>Save</span></a>
<a href="JavaScript:returnToList()"><img border="0" src="templates/{skin}/images/back.gif" alt="Trở về danh sách phân loại đối tác" width="20" height="20"><span>Trở về danh sách</span></a>

</div>
 <div class="tabtitle">
 Thông tin khách hàng </div>
 <div style="overflow:auto; height:80%">
<form action="main.php" method="POST" enctype="multipart/form-data" name="mainForm" >
	<input type="hidden" name="l" value="{LANGUAGEID}">
	<input type="hidden" name="option" value="{funname}">
	<input type="hidden" name="id" value="{customer_id}">
	<input type="hidden" name="mode" value="save">
<table width="100%">
  <tr >
    <td width="16%" height="25" >Khách hàng:<font color="#FF0000">*</font></td>
    <td width="84%"><input name="customer_name" notnull = "1" type="text" size="70" alt="Khách hàng" value="{customer_name}" /></td>
  </tr>
  <tr >
    <td width="16%" height="25" >Loại khách hàng:<font color="#FF0000">*</font></td>
    <td width="84%">
<!-- DO ComboFromTable("customer_type_id", "tbl_customer_type", "customer_type_id", "customer_type_name", "customer_type_id", 0, "" , "" , "active=1 and language_id=2 order by priority" , "", 1) -->	
	NV quản lý :
<!-- DO ComboFromTable("member_id", "tbl_member", "member_id", "fullname", "member_id", 0, "" , "" , "active=1 order by member_id" , "", 1) -->
	</td>
  </tr>
  <tr >
    <td width="16%" height="25" >Địa chỉ:</td>
    <td width="84%"><input name="address" type="text" size="70" alt="Địa chỉ" value="{address}" /></td>
  </tr>
  <tr >
    <td width="16%" height="25" >Điện thoại:</td>
    <td width="84%"><input name="tel" type="text" size="70" alt="Điện thoại" value="{tel}" /></td>
  </tr>
  <tr >
    <td width="16%" height="15" >Ghi chú:</td>
    <td width="84%">
    <textarea cols="80" id="fax" name="fax" rows="10">{fax}</textarea>
	<script type="text/javascript">CKEDITOR.replace( 'fax',{height : 250});</script>
    </td>
  </tr>
  <tr >
    <td width="16%" height="25" >Facebook:</td>
    <td width="84%"><input name="face" type="text" size="70" value="{face}" /></td>
  </tr>
  <tr >
    <td width="16%" height="25" >Email:</td>
    <td width="84%"><input name="email" email=1 type="text" size="30" alt="Email" value="{email}" /> Sinh nhật <input name="sinhnhat" id="sinhnhat" type="text" notnull="1" alt="Ngày" size="10" readonly="1" value="{sinhnhat}"> 
	  	<img id="img_sinhnhat" src="templates/{skin}/images/cal.png" width="20" height="20" align="absmiddle" style="border:0px; margin:0px;"> ( yyyy-mm-dd )</td>
  </tr>
  <tr >
    <td width="16%" height="25" >Số lần:</td>
    <td width="84%"><input name="web" type="text" size="5" alt="Web" value="{web}" /> ID Sản phẩm <input name="list_id" type="text" size="70" alt="Web" value="{list_id}" /></td>
  </tr> 
  <tr>
    <td ></td>
    <td ><input type="checkbox" name="active" value="1" id="fp1" {active} /><label kind="fp1"> Chuẩn</label> <input type="checkbox" name="ishc" value="1" id="fp1" {ishc} /><label kind="fp1"> Hoa Cà</label> <input type="checkbox" name="iskh" value="1" id="fp1" {iskh} /><label kind="fp1"> Kiều Hưng</label> <input type="checkbox" name="istp" value="1" id="fp1" {istp} /><label kind="fp1"> Tồn Phát</label></td>
  </tr>
<tr style="visibility:{allow}">
      <td  colspan="2" >&nbsp;</td>
    </tr>
    <tr>
      <td width="100%" style="padding-left: 10" colspan="2" height="23">&nbsp;</td>
    </tr>
    <tr style="display:expression(('{is_new}' == '1') ? 'none' : '')">
      <td width="100%" style="padding-left: 10" colspan="2" height="23"><b>Administration</b><hr></td>
    </tr>
    <tr style="display:expression(('{is_new}' == '1') ? 'none' : '')">
      <td width="20%" style="padding-left: 10" height="23">Create date:</td>
      <td width="80%" height="23"><input type="text" name="T1" size="33" class="is_label" readonly value="{created_date}"></td>
    </tr>
    <tr style="display:expression(('{is_new}' == '1') ? 'none' : '')">
      <td width="20%" style="padding-left: 10" height="23">Create by:</td>
      <td width="80%" height="23"><input type="text" name="created_by" size="33" class="is_label" readonly value="{created_by}"></td>
    </tr>
    <tr style="display:expression(('{is_new}' == '1') ? 'none' : '')">
      <td width="20%" style="padding-left: 10" height="23">Last_modified</td>
      <td width="80%" height="23"> 
        <input type="text" name="last_modified" size="33" class="is_label" readonly value="{last_modified}">
      </td>
    </tr>
    <tr style="display:expression(('{is_new}' == '1') ? 'none' : '')">
      <td width="20%" style="padding-left: 10" height="23">Modified by</td>
      <td width="80%" height="23"><input type="text" name="modified_by" size="33" class="is_label" readonly value="{modified_by}"></td>
    </tr>
  </table>
   
</form><p align="center"><font color="#FF0000"><b>{MESSAGE}</b></font></p>
</div>
<Script Language="JavaScript">

	function doSave()
	{	
		if (verify(mainForm))	
			mainForm.submit()
	}

	function returnToList()
	{	document.location='?option={funname}&mode=list&l={LANGUAGEID}'
	}
  //document.getElementById("member_id").style.visibility = "visible";
  
	mainForm.customer_type_id.value = {customer_type_id};
	mainForm.member_id.value = {member_id};
  document.getElementById("member_id").disabled = {allow_member_id};
	mainForm.customer_name.focus()
	Calendar.setup({
  	inputField     :    "sinhnhat",     // id of the input field
    ifFormat       :    "%d-%m-%Y",      // format of the input field
    button         :    "img_sinhnhat",  // trigger for the calendar (button ID)
    align          :    "Tl",           // alignment (defaults to "Bl")
    singleClick    :    true
});
</Script>