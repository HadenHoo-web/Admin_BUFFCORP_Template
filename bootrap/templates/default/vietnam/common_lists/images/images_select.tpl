<html>
<head>
<meta http-equiv="Content-Language" content="vi">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>::: HK-CMS :::</title>
<link rel="stylesheet" type="text/css" href="templates/{skin}/css/{theme}">
<link rel="stylesheet" type="text/css" href="css/admintool.css">
<Script language="JavaScript" src="js/commoncheck.js"></Script>
<Script language="JavaScript" src="js/admintool.js"></Script>
<script language="JavaScript" src="js/editor/scripts/selection_for_dialogs.js"></script>
</head>
<body>
<div class="tabtitle"><select size="1" name="cid_1" onChange="makeFolder(this.value)">
		 <option value="0">Root</option>
        <!-- BEGIN catlist -->
        <option value="{catlist.cat_id}">{catlist.cat_name}</option>
        <!-- END catlist -->
</select>
</div>
<form method="POST" action="main.php" name=mainForma enctype="multipart/form-data" style="margin:0px;">
  <input type="hidden" name="option" value="{funname}">
  <input type="hidden" name="mode" value="image_select_upload">
  <input type="hidden" name="language_id" value="{LANGUAGEID}">
  <input type="hidden" name="cid" value="{cat_id}">
<p>&nbsp;Tên ảnh: <input type="file" name="filename" size="20"   notnull=1 alt="Tên ảnh"     >
<input type="button" value="Upload ảnh" name="Upload" onClick="if (verify(mainForma)) mainForma.submit() "></p>
</form>
<p align="center" style="margin-top: 5; margin-bottom: 0"><font color="#FF0000"><b><span lang="en-us">{MESSAGE}</span></b></font></p>
<script language="javascript">
	var numcol=4
	var folderList=[
<!-- BEGIN folderList --> 
{folderList.cat_phay}[{folderList.cat_id},"{folderList.cat_name}","{folderList.parent_id}"] 
<!-- END folderList -->
]
var imgList=[
<!-- BEGIN imgList --> 
{imgList.img_phay}[{imgList.img_id},"{imgList.img_name}","{imgList.cat_id}","{imgList.img_thumb}"] 
<!-- END imgList -->
]
var sURL = unescape(window.location);
function refresh()
{	document.location = sURL;
}
</script>

<table border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111" width="100%" id="folder">
</table>
<br>
<div  style="overflow:auto;height:25% ">
<table border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111" width="100%" id="imgtb">  
</table>
</div>

  
  
<form method="POST" action="main.php" name=mainForm>
  <input type="hidden" name="option" value="{funname}">
  <input type="hidden" name="mode" value="cat_save">
  <input type="hidden" name="language_id" value="{LANGUAGEID}">
  <input type="hidden" name="pr" value="{cat_id}">
  <input type="hidden" name="cid" value="{cat_id}">
 <input type="hidden" name="cat_name" notnull=1 alt="Tên chủ đề">
</form>
<script language="javascript">
	makeFolder(0)
	function makeFolder(parent_id)
	{      
			var perCent = 100 / numcol;
			var newRow,newCell;
			var k=0;
			for(var j=folder.rows.length-1;j>=0;j--)
			{
				folder.deleteRow(j)
			}
			for(var i=0;i<folderList.length;i++)
			{			
				if(folderList[i][2]==parent_id)
				{							
						if( k%numcol==0)
							newRow =	folder.insertRow()
						newCell=newRow.insertCell()
						newCell .width = perCent+"%"
						newCell.innerHTML="<div class='imgbutton' ><a href='#'   onClick=\"makeFolder("+folderList[i][0]+")\" > <img border='0' src='templates/{skin}/images/folder.png' align='absmiddle' width='22' height='22'><span>"+folderList[i][1]+"</span></a></div>"
						k++
				}
			}
		if (k>0)
			for (var l = newRow.cells.length; l <  numcol; l++)
			{	newCell = newRow.insertCell();
				newCell .width = perCent+"%"
			}
		
		makeImage(parent_id)
		mainForma.cid.value=parent_id
		document.all.cid_1.value = parent_id;
	}	

function makeImage(cat_id)
	{		var perCent = 100 / numcol;
			var newRow,newCell;
			var k=0;
			for(var j=imgtb.rows.length-1;j>=0;j--)
			{
				imgtb.deleteRow(j)
			}
			for(var i=0;i<imgList.length;i++)
			{
				if(imgList[i][2]==cat_id)
				{
						if( k%numcol ==0)
							newRow =	imgtb.insertRow()
						newCell=newRow.insertCell()
						newCell.align="center"
						newCell .width = perCent+"%"			
						newCell.innerHTML="<img border='0' src='"+imgList[i][3] +"' align='absmiddle'  alt='"+imgList[i][1]+"'><br>"
						newCell.innerHTML+= "<div class='imgbutton' ><a href='#'  onClick=\"imageSelect('"+cat_id+"','"+imgList[i][1]+"')\"><img border='0' src='templates/{skin}/images/32.png' width='20' height='20'  align='absmiddle'><span>Chọn ảnh</span></a><a href='#' onClick=\"if(confirm ('Bạn thực sự muốn xóa ?')){	document.location ='?option={funname}&mode=image_select_delete&cid=" + document.all.cid_1.value + "&imgid="+imgList[i][0]+"'}\" target='_self' ><img border='0' src='templates/{skin}/images/button-delete.gif' width='16' height='16'></a></div>"
					k++
				}
			}
		if (k>0)
			for (i = newRow.cells.length; i <  numcol; i++)
			{	newCell = newRow.insertCell();
				newCell .width = perCent+"%"
			}
	}	
	
	
function imageSelect(cat_id,filename)
{	
	//*** SELECTION *********
	oSELUtil.check();
	if(!oSELUtil.oSel) return;
	oSel=oSELUtil.oSel;
	sType=oSELUtil.sType;
	//***********************
	
	var oName=dialogArguments.oUtil.oName;
	var oEditor=eval("dialogArguments.idContent"+oName);
	
	if (oSel.parentElement)	oElement=oSel.parentElement();
	else oElement=oSel.item(0);
	
	eval("dialogArguments."+oName).doCmd("InsertImage","{domain}images/image_manager/cat"+cat_id+"/"+filename);

	var oSel=oEditor.document.selection.createRange();
	var sType=oEditor.document.selection.type;
		
	if (oSel.parentElement)	oElement=oSel.parentElement();
	else oElement=oSel.item(0);
	self.close()	
}
document.all.cid_1.value = '{cat_id}';
makeFolder({cat_id})
</script>
</body>
</html>