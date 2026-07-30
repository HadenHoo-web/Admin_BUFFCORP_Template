<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<div class="toolbar">
  <a href="JavaScript:doCreate()"><img border="0" src="templates/{skin}/images/button-article-create.gif" alt="Thêm mới" ><span>Thêm mới banner</span></a><a href="?option=common_lists/images&mode=image_list"><img border="0" src="templates/{skin}/images/image.png" alt="Quản trị tài nguyên ảnh" ><span>Quản trị tài nguyên ảnh</span></a>  
  <span style="display:none"><a href="?option=logo/upload_logo&mode=list"><img border="0" src="templates/{skin}/images/peace.png" alt="Logo" ><span>Logo</span></a></span>     
</div>
<div class="tabtitle">
<form action="main.php" method="POST" enctype="multipart/form-data" name="topForm" id="topForm" style="margin:0px;" > Danh mục banner <select name="place" onchange="reShow();">
    <option value="0">All</option>
  <option value="1">top</option>
  <option value="2">right</option>
</select>
</form>
<script>topForm.place.value = {place}</script>
</div>
<div style="overflow:auto; height:80%">
<table border="0" cellpadding="0" cellspacing="0" bordercolor="#111111" width="100%"  class="selector" style="border-collapse:collapse">
  <tr class="header">
    <td width="5%" align="center">#</td>
    <td width="25%" align="left">Thông tin banner</td>
    <td width="55%" align="center">Ảnh minh họa</td>
    <td width="10%" align="center">Mặc định</td>
    <td width="5%"   align="center" colspan="2"></td>
    
  </tr>
<!-- BEGIN list -->  
  <tr class="{list.className}">
    <td width="5%" >{list.order}</td>
    <td width="25%" align="center">{list.banner_name}<br>{list.filesize}({list.w} x {list.h})</td>
    <td width="55%" align="center" >{list.imgPath}</td>
    <td width="10%" align="center" ><input type="checkbox"  name="defaultbanner" id="{list.banner_id}" value="{list.banner_id}" onClick="setDefault('{list.banner_id}','{list.place}')"  {list.isactive} ></td>
    <td width="5" align="center"><a href="JavaScript:updateItem({list.banner_id})"><img border="0" src="templates/{skin}/images/editbutton.gif" alt="Update" width="20" height="20"></a></td>
    <td width="5%" ><a href="JavaScript:deleteItem({list.banner_id})"><img border="0" src="templates/{skin}/images/button-delete.gif" width="20" height="20" alt="Xóa " ></a></td>
  </tr>
<!-- END list -->  
</table>
  </center>
</div>
<form method="POST" action="main.php" name=mainForm enctype="multipart/form-data">
  <input type="hidden" name="l" value="{LANGUAGEID}">
<input type="hidden" name="option" value="{funname}">
<input type="hidden" name="place" value="{place}">
<input type="hidden" name="mode" value="save">
&nbsp;Upload new banner: <input type="file" name="new_banner" size="20"  notnull alt="new banner">
<input type="button" value="Upload baner" name="Upload"    onClick="if (verify(mainForm)) mainForm.submit() ">

</form>

<p align="center"><font color="#FF0000"><b>{MESSAGE}</b></font></p>


<Script Language="JavaScript">
var curdefault;
for(var i=0;i<defaultbanner.length;i++)
	if(defaultbanner[i].checked) curdefault=defaultbanner[i].value

function setDefault(id,place)
{	if (confirm ("Are you sure you want to set default this banner?"))
		document.location='?option={funname}&mode=setdefault&place='+place+'&l={LANGUAGEID}&id='+id
	else
		document.getElementById(curdefault).checked=true
}


function doCreate()
{	document.location='?option={funname}&mode=info&l={LANGUAGEID}&id={banner_id}'
}

function deleteItem(id)
{	if (confirm ("Bạn thực sự muốn xóa mục thông tin này ?."))
		document.location = '?option={funname}&mode=delete&l={LANGUAGEID}&id=' + id
}	

function updateItem(id)
{	document.location = '?option={funname}&mode=info&l={LANGUAGEID}&id=' + id
}	

function reShow()
{	
	document.location = '?option={funname}&mode=list&place=' + topForm.place.value
}

</Script>