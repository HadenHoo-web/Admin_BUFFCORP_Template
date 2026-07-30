<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<div class="toolbar"> <a href="JavaScript:doCreate()"><img border="0" src="templates/{skin}/images/button-article-create.gif" alt="Create new"><span>Thêm mới</span></a>
</div>

<div class="tabtitle"><form action="main.php" method="POST" enctype="multipart/form-data" name="mainForm" id="mainForm" style="margin:0px;" >
	<input type="hidden" name="option" id="option" value="{funname}">
	<input type="hidden" name="mode" id="mode" value="list">
  Danh sách Nghỉ Phép + Vắng Mặt : 
  NV
  <!-- DO ComboFromTable("member_id1", "tbl_member", "member_id", "fullname", "member_id", 0, " All " , "0" , "active=1 order by member_id" , "doReShow()", 1) -->
  NV Tạo
  <!-- DO ComboFromTable("created_by_id1", "tbl_member", "member_id", "fullname", "member_id", 0, " All " , "0" , "active=1 order by member_id" , "doReShow()", 1) -->
  <span style="display: none;">Website <select size="1" name="website_id1" alt="Website">
        <option value=0>Chọn Website</option>
        <!-- BEGIN website_list -->
        <option value="{website_list.website_id}">{website_list.website_name}</option>
        <!-- END website_list -->
      </select>
   Show Ẩn <input type="checkbox" name="active1" value="1" {active} onChange="doReShow()" /></span> <input name="" type="submit" value="Search" style="height:18px; margin-top:4px;" /></form>
</div><script>mainForm.website_id1.value = '15';mainForm.member_id1.value = '{member_id}';mainForm.created_by_id1.value = '{created_by_id}';</script>
<div style="overflow:auto; height:80%">
  <table border="0" cellpadding="0" cellspacing="0" bordercolor="#111111" width="100%"  class="selector" style="border-collapse:collapse">
    <tr class="header">
      <td width="50" align="center" >#</td>
      <td width="300" align="left" >Lý do</td>
      <td width="120" align="left" >Thời gian</td>
      <td width="90" align="left" >Người thực hiện</td>
      <td width="90" align="left" >Trang Thái</td>
      <td width="80" align="left" >Deadline</td>
      <td width="70" align="left" >Người tạo</td>
      <td width="110" align="left" >Lúc tạo</td>
      <td width="70" align="left" >Người sửa</td>
      <td width="110" align="left" >Lúc sửa</td>
      <td  colspan="3" ></td>
    </tr>
    <!-- BEGIN list -->
    <tr class="{list.className}{list.status}">
      <td align="center" style="vertical-align:middle">{list.order}</td>
      <td style="vertical-align:middle"><a href="JavaScript:updateItem({list.nghiphep_id})">{list.nghiphep_name}</a></td>
      <td style="vertical-align:middle">{list.nghiphep_time}</td>
      <td style="vertical-align:middle">{list.member_name}</td>
      <td style="vertical-align:middle">{list.soluong}</td>
      <td style="vertical-align:middle">{list.ngay}</td>
      <td style="vertical-align:middle">{list.created_by}</td>
      <td style="vertical-align:middle">{list.created_date}</td>
      <td style="vertical-align:middle">{list.modified_by}</td>
      <td style="vertical-align:middle">{list.last_modified}</td>
      <td align="center"> {list.active} </td>
      <td width="23" align="center"><a href="JavaScript:updateItem({list.nghiphep_id})"><img border="0" src="templates/{skin}/images/editbutton.gif" alt="Update" width="20" height="20"></a></td>
      <td width="35" align="center"><a href="JavaScript:deleteItem({list.nghiphep_id})"><img border="0" src="templates/{skin}/images/button-delete.gif" width="20" height="20" alt="Delete"></a></td>
    </tr>
    <!-- END list -->
  </table>
  </center>
</div>
<p align="center"><font color="#FF0000"><b>{MESSAGE}</b></font></p>
<Script Language="JavaScript">
function doReShow()
{	
	mainForm.submit();
}
function doCreate()
{	
	document.location='?option={funname}&parent_id={parent_id}&mode=info&l={LANGUAGEID}'
}
function updateItem(id)
{	document.location = '?option={funname}&mode=info&l={LANGUAGEID}&id=' + id
}

function subItem(id)
{	document.location = '?option={funname}&mode=list&l={LANGUAGEID}&parent_id=' + id
}

function deleteItem(id)
{	if (confirm ("Are you sure you want to delete ?."))
		document.location = '?option={funname}&mode=delete&l={LANGUAGEID}&id=' + id
}	
function doThongKe()
{ alert();
	document.location='?option={funname}&mode=thongke'
}
</Script>

