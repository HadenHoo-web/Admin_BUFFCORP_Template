<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" media="all" href="js/jscalendar/calendar-system.css" title="system" />
<script type="text/javascript" src="js/jscalendar/calendar.js"></script>
<script type="text/javascript" src="js/jscalendar/calendar-vn.js"></script>
<script type="text/javascript" src="js/jscalendar/calendar-setup.js"></script>
<script type="text/javascript" src="js/ckeditor/ckeditor.js"></script>
<script src="js/ckeditor/_samples/sample.js" type="text/javascript"></script>
<link href="js/ckeditor/_samples/sample.css" rel="stylesheet" type="text/css" />
 <div class="toolbar">
<a href="JavaScript:doSave()"><img border="0" src="templates/{skin}/images/button-save.gif" width="20" height="20" alt="Lưu"><span>Lưu</span></a>
<a href="JavaScript:returnToList()"><img border="0" src="templates/{skin}/images/back.gif" alt="Về danh sách" width="20" height="20"><span>Về danh sách</span></a>

</div>
 <div class="tabtitle">
 Giao việc : &quot;{parent_name}&quot; </div>
 <div style="overflow:auto; height:80%">
<form action="main.php" method="POST" enctype="multipart/form-data" name="mainForm" >
	<input type="hidden" name="l" value="{LANGUAGEID}">
	<input type="hidden" name="option" value="{funname}">
	<input type="hidden" name="id" value="{xacnhan_id}">
	<input type="hidden" name="parent_id" value="{parent_id}">
	<input type="hidden" name="mode" value="save">
<table width="100%">
  <tr >
    <td width="22%" height="25" >Tiêu đề:<font color="#FF0000">*</font></td>
    <td width="78%"><input name="xacnhan_name" notnull = 1 type="text" size="50" alt="Tên định mức" value="{xacnhan_name}" /> Người thực hiện <select size="1" name="member_id" alt="Member">
        <option value=0>Chọn Người thực hiện</option>
        <!-- BEGIN member_list -->
        <option value="{member_list.member_id}">{member_list.member_name}</option>
        <!-- END member_list -->
      </select> Website <select size="1" name="website_id" alt="Website">
        <option value=0>Chọn Website</option>
        <!-- BEGIN website_list -->
        <option value="{website_list.website_id}">{website_list.website_name}</option>
        <!-- END website_list -->
      </select> Deadline<font color="#FF0000">*</font> <input name="ngay" id="ngay" type="text" notnull="1" alt="Ngày" size="10" readonly="1" value="{ngay}"> 
	  	<img id="img_ngay" src="templates/{skin}/images/cal.png" width="20" height="20" align="absmiddle" style="border:0px; margin:0px;"> ( yyyy-mm-dd )</td>
  </tr>
  <tr >
    <td width="22%" height="25" >Chi tiết:</td>
    <td width="78%">
    <textarea cols="80" id="chitiet" name="chitiet" rows="8">{chitiet}</textarea>
<script type="text/javascript">CKEDITOR.replace( 'chitiet',{height : 350});</script>
    </td>
  </tr>
  <tr >
    <td width="22%" height="25" >Trang Thái:</td>
    <td width="78%"><select size="1" name="soluong" alt="Member"><option value=0>Chưa thực hiện</option><option value=1>Đang thực hiện</option><option value=2>Đã xong</option></select></td>
  </tr>
  <tr>
    <td ></td>
    <td ><input type="checkbox" name="active" value="1" id="fp1" {active} />
        <label for="fp1"> Show</label></td>
  </tr>
<tr style="visibility:{allow}">
      <td  colspan="2" >&nbsp;</td>
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
    if(mainForm.xacnhan_name.value == 0 || mainForm.xacnhan_name.value == ''){	
      alert('Nhập Tiêu Đề')
			mainForm.xacnhan_name.focus()
			return
		}
    if(mainForm.member_id.value == 0 || mainForm.member_id.value == ''){	
      alert('chọn Người Thực Hiện')
			mainForm.member_id.focus()
			return
		}
    if(mainForm.website_id.value == 0 || mainForm.website_id.value == ''){	
      alert('Chọn Website')
			mainForm.website_id.focus()
			return
		}
    if(mainForm.ngay.value == 0 || mainForm.ngay.value == ''){	
      alert('Chọn Deadline*')
			mainForm.ngay.focus()
			return
		}
		if (verify(mainForm))	
			mainForm.submit()
	}

	function returnToList()
	{	document.location='?option={funname}&mode=list&l={LANGUAGEID}'
	}
  Calendar.setup({
  	inputField     :    "ngay",     // id of the input field
    ifFormat       :    "%d-%m-%Y",      // format of the input field
    button         :    "img_ngay",  // trigger for the calendar (button ID)
    align          :    "Tl",           // alignment (defaults to "Bl")
    singleClick    :    true
});
	mainForm.xacnhan_name.focus()
  mainForm.member_id.value 	  = {member_id}
  mainForm.website_id.value 	= {website_id}
  mainForm.soluong.value 	    = {soluong}
</Script>