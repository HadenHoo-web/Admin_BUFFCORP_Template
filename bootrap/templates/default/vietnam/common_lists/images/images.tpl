<meta http-equiv="Content-Type" content="text/html; charset=charset=utf-8">
<div class="toolbar" >
  <a href="?option={funname}&mode=cat_info&cid={cat_id}" style="display:{show} ">
      <img border="0" src="templates/{skin}/images/editbutton.gif" alt="Cập nhật thông tin chủ đề" width="20" ><span>Cập 
  nhật thông tin chủ đề</span></a>&nbsp;
  <a href="#" onClick="doDelete()" style="display:{show} ">
      <img border="0" src="templates/{skin}/images/button-delete.gif" alt="Xóa chủ đề" width="32" ><span>Xóa 
      chủ đề</span></a>
  <a href="?option=banners/banners&mode=list"><img border="0" src="templates/{skin}/images/32.png" width="20" height="20" alt="Cập nhật banner"><span>Cập nhật banner</span></a>
  <span style="display:none"><a href="?option=logo/upload_logo&mode=list"><img border="0" src="templates/{skin}/images/peace.png" alt="Logo" ><span>Logo</span></a>
  <a href="?option=collections/collections&mode=collections_list&l={LANGUAGEID}"><img border="0" src="templates/{skin}/images/button-article-list.gif" alt="Bộ sưu tập ảnh" ><span>Bộ sưu tập ảnh</span></a> </span>
 </div>
<div class="tabtitle"><a href="?option={funname}&mode=image_list&cid=0" target="_self"><img border="0" src="templates/{skin}/images/con_address.png" width="16" height="16" style="{list.up}" align="middle"></a>
<!-- BEGIN catChain -->
<b>\</b><a href="?option={funname}&mode=image_list&cid={catChain.cat_id}">{catChain.cat_name}</a>
<!-- END catChain -->
<input type="text" name="cat_name"  size="20" OnBlur="mainForm.cat_name.value=this.value">&nbsp;&nbsp;<input type="button" value="Tạo chủ đề mới" name="create" onClick="if (verify(mainForm)) mainForm.submit() "> 
</div>
<script language="javascript">
	var numcol=5
	var folderList=[
<!-- BEGIN folderList --> 
{folderList.cat_phay}[{folderList.cat_id},"{folderList.cat_name}"] 
<!-- END folderList -->
]
var imgList=[
<!-- BEGIN imgList --> 
{imgList.img_phay}[{imgList.img_id},"{imgList.img_name}","{imgList.img_w}","{imgList.img_h}","{imgList.img_size}","{imgList.img_thumb}"] 
<!-- END imgList -->
]
</script>

<table border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111" width="100%" id="folder" class="image-library-folders">
</table>
<div class="image-library-content">
<table border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111" width="100%" id="imgtb" class="image-library-items">
</table>
</div>
<form method="POST" action="main.php" name=mainForma enctype="multipart/form-data" class="image-upload-form">
  <input type="hidden" name="option" value="{funname}">
  <input type="hidden" name="mode" value="image_upload">
  <input type="hidden" name="language_id" value="{LANGUAGEID}">
  <input type="hidden" name="cid" value="{cat_id}">
<p>&nbsp;Tên ảnh: <input type="file" name="filename" size="20"   notnull=1 alt="Tên ảnh"     >
<input type="button" value="Tải ảnh lên" name="Upload" onClick="if (verify(mainForma)) mainForma.submit() "></p>
</form>

  
  <p align="center" style="margin-top: 5; margin-bottom: 0"><font color="#FF0000"><b><span lang="en-us">{MESSAGE}</span></b></font></p>
<form method="POST" action="main.php" name=mainForm>
  <input type="hidden" name="option" value="{funname}">
  <input type="hidden" name="mode" value="cat_save">
  <input type="hidden" name="language_id" value="{LANGUAGEID}">
  <input type="hidden" name="pr" value="{cat_id}">
  <input type="hidden" name="cid" value="{cat_id}">
 <input type="hidden" name="cat_name" notnull=1 alt="Tên chủ đề">
</form>
<script language="javascript">
makeFolder()
makeImage()

	function makeFolder()
	{
			var perCent = 100 / numcol;
			var newRow,newCell;

			for(var i=0;i<folderList.length;i++)
			{
				if( i % numcol == 0)
					newRow =	folder.insertRow()
				newCell=newRow.insertCell()
				newCell .width = perCent+"%"
				newCell.innerHTML="<a href=?option={funname}&mode=image_list&cid="+folderList[i][0]+" target='_self' > <img border='0' src='templates/{skin}/images/folder.png' align='absmiddle' width='32' height='32'>"+folderList[i][1]+"</a>"
			}
		if (folderList.length>0)
			for (i = newRow.cells.length; i <  numcol; i++)
			{	newCell = newRow.insertCell();
				newCell .width = perCent+"%"
			}
	}	
function makeImage()
	{		var perCent = 100 / numcol;
			var newRow,newCell;
			
			for(var i=0;i<imgList.length;i++)
			{
				if( i % numcol == 0)
					newRow =	imgtb.insertRow()
				newCell=newRow.insertCell()
				newCell.align="center"
				newCell .width = perCent+"%"			
				newCell.innerHTML="<a href='#'  onClick=\"imgview('../images/image_manager/cat{cat_id}/"+imgList[i][1]+"')\"> <img border='0' src='"+imgList[i][5] +"' align='absmiddle' ></a><br>"
				newCell.innerHTML+= imgList[i][1]+" <a href='#' onClick=\"if(confirm ('Bạn thực sự muốn xóa ?')){	document.location ='?option={funname}&mode=image_delete&cid={cat_id}&imgid="+imgList[i][0]+"'}\" target='_self' ><img border='0' src='templates/{skin}/images/button-delete.gif' width='16' height='16'></a><br>"
				newCell.innerHTML+=imgList[i][2]+" x "+imgList[i][3]+" ("+Math.ceil(imgList[i][4]/1024)+ "Kb)"
			}
		if (imgList.length>0)
			for (i = newRow.cells.length; i <  numcol; i++)
			{	newCell = newRow.insertCell();
				newCell .width = perCent+"%"
			}
	}	

function doDelete()
{
	if(confirm ('Bạn thực sự muốn xóa ?'))
	{	document.location = '?option={funname}&mode=cat_delete&cid={cat_id}&pr={parent_id}' 
	}
	
}
</script>
