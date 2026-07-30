<?php
	session_name('admintool');
	session_start();
	define('MY_WEB', true);

	$root_path = '../../../';
	global $website;
	include($root_path . 'common.php');	
?>
<html>
<head>
<title>Image</title>
<style>
	body {font:8pt verdana,arial,sans-serif}
	.inpChk {width:13;height:13;margin-right:3;margin-bottom:1}
	.inpRdo {width:13;height:13;margin-right:3;margin-bottom:1}
	input {font:8pt verdana,arial,sans-serif}
	td {font-size:10}
	select {font:8pt verdana,arial,sans-serif}
</style>
<script language="JavaScript" src="selection_for_dialogs.js"></script>
<script>
/************************
	REALTIME
************************/
function doWindowFocus()
	{
	dialogArguments.document.all.btnRealTime.onclick=new Function("realTime()");
	}
function bodyOnLoad()
	{	
	window.onfocus=doWindowFocus;	
	dialogArguments.document.all.btnRealTime.onclick=new Function("realTime()");
	
	realTime()
	}
	
function realTime()
	{
	//*** SELECTION *********
	oSELUtil.check();
	if(!oSELUtil.oSel)return;
	oSel=oSELUtil.oSel;
	sType=oSELUtil.sType;
	//***********************
	
	var oName=dialogArguments.oUtil.oName;
	
	
	if (oSel.parentElement)	oElement=oSel.parentElement();
	else oElement=oSel.item(0);
	
	
	if(oElement.tagName=="IMG")
		{
		clearAllProperties()
		
		//~~~~~~~~~~~~~~~~~~~~~~~~
		sTmp=oElement.outerHTML;
		sTmp=sTmp.substring(sTmp.indexOf("src")+5);
		sTmp=sTmp.substring(0,sTmp.indexOf('"'));
		var arrTmp = sTmp.split("&amp;");
		if (arrTmp.length > 1) sTmp = arrTmp.join("&");		
		inpImgURL.value=sTmp
		//inpImgURL.value = oElement.src;
		
		inpImgAlt.value = oElement.alt;
		inpImgAlign.value = oElement.align;
		inpImgBorder.value = oElement.border;
		inpImgWidth.value = oElement.width;
		inpImgHeight.value = oElement.height;
		inpImgHSpace.value = oElement.hspace;
		inpImgVSpace.value = oElement.vspace;

		btnUpdate.style.display="block";
		btnUpdateAndClose.style.display="block";
		btnInsert.style.display="none";
		}
	else
		{
		btnUpdate.style.display="none";
		btnUpdateAndClose.style.display="none";
		btnInsert.style.display="block";
		}	
	}
	
function clearAllProperties()
	{
	inpImgURL.value="";//always updated
	inpImgAlt.value="";//not set, krn tdk harus
	inpImgAlign.value="";
	inpImgBorder.value="";
	inpImgWidth.value="";
	inpImgHeight.value="";
	inpImgHSpace.value="";
	inpImgVSpace.value="";
	}
	
/************************
	INSERT & UPDATE
************************/
function doInsert()
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
	
	eval("dialogArguments."+oName).doCmd("InsertImage",inpImgURL.value);

	var oSel=oEditor.document.selection.createRange();
	var sType=oEditor.document.selection.type;
		
	if (oSel.parentElement)	oElement=oSel.parentElement();
	else oElement=oSel.item(0);
			
	if (oElement.tagName=="IMG")
		{
		if(inpImgAlt.value!="") oElement.alt = inpImgAlt.value;
		oElement.align = inpImgAlign.value;
		oElement.border = inpImgBorder.value;
		if(inpImgWidth.value!="") oElement.width = inpImgWidth.value;
		if(inpImgHeight.value!="") oElement.height = inpImgHeight.value;
		if(inpImgHSpace.value!="") oElement.hspace = inpImgHSpace.value;
		if(inpImgVSpace.value!="") oElement.vspace = inpImgVSpace.value;
		}	
	
	realTime()
	}
	
function doUpdate()
	{
	//*** SELECTION *********
	oSELUtil.check();
	if(!oSELUtil.oSel)return;
	oSel=oSELUtil.oSel;
	sType=oSELUtil.sType;
	//***********************

	var oName=dialogArguments.oUtil.oName;
	var oEditor=eval("dialogArguments.idContent"+oName);
	
	if (oSel.parentElement)	oElement=oSel.parentElement();
	else oElement=oSel.item(0);
	
	if (oElement.tagName=="IMG")
		{
		oElement.style.width="";
		oElement.style.height="";

		oElement.src = inpImgURL.value;
		
		if(inpImgAlt!="") oElement.alt = inpImgAlt.value;
		else oElement.removeAttribute("alt",0);
	
		oElement.align = inpImgAlign.value;
			
		oElement.border = inpImgBorder.value;
			
		if(inpImgWidth!="") oElement.width = inpImgWidth.value;
		else oElement.removeAttribute("width",0);
				
		if(inpImgHeight!="") oElement.height = inpImgHeight.value;
		else oElement.removeAttribute("height",0);	
				
		if(inpImgHSpace!="") oElement.hspace = inpImgHSpace.value;
		else oElement.removeAttribute("hspace",0);	
				
		if(inpImgVSpace!="") oElement.vspace = inpImgVSpace.value;
		else oElement.removeAttribute("vspace",0);
		}	

	realTime()
	}				
</script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head>
<body onload="bodyOnLoad()" style="overflow:hidden;margin:0;background: #ffffff;filter: progid:DXImageTransform.Microsoft.gradient(startColorstr=#ededed, endColorstr=#f4f4f4)">
<table width=410 align=center style="" cellpadding=0 cellspacing=0 ID="Table1">
<tr>
<td style="border-bottom:2px solid #f7f7f7;background-color:#7e94ca;height:50;filter: progid:DXImageTransform.Microsoft.gradient(startColorstr=#f1f1f1, endColorstr=#d4d4d4)" valign=middle>
	<font style="font-family:Arial Black;color:#3d4d5d;font-size:25;LETTER-SPACING:1px">&nbsp;Image</font>
</td>
</tr>
<tr>
<td valign=top style="padding:15;">
		<table>
		<tr>
		<td colspan=2  valign=top style="padding-top:5">
					<table cellpadding=1 cellspacing=0>
					<tr>
						<td colspan=2>Image: <select size="1" NAME="inpImgage" style="margin-left:50" OnChange="Javascript: inpImgURL.value = this.value">
<?php 	
		showImgCat(0, 0);
?>
                        </select></td>
					</tr>
					<tr>
						<td colspan=2>Source: <input type="text" id="inpImgURL" name=inpImgURL size=40 style="margin-left:50"></td>
					</tr>
					<tr>
						<td colspan=2>Alternate Text: <input type="text" id="inpImgAlt" name=inpImgAlt size=40 style="margin-left:8"></td>
					</tr>
					<tr>
						<td>
						Alignment:	<select ID="inpImgAlign" NAME="inpImgAlign" style="margin-left:31">
								<option value="" selected>&lt;Not Set&gt;</option>
								<option value="absBottom">absBottom</option>
								<option value="absMiddle">absMiddle</option>
								<option value="baseline">baseline</option>
								<option value="bottom">bottom</option>
								<option value="left">left</option>
								<option value="middle">middle</option>
								<option value="right">right</option>
								<option value="textTop">textTop</option>
								<option value="top">top</option>						
								</select>
						</td>
						<td>&nbsp;
						Image border: <input type="text" id="inpImgBorder" name=inpImgBorder size=2 style="margin-left:26"> pixels
						</td>
					</tr>
					<tr>
						<td>
						Width: <input type="text" ID="inpImgWidth" NAME="inpImgWidth" size=2 style="margin-left:54"> pixels
						</td>
						<td>&nbsp;
						Horizontal Spacing: <input type="text" ID="inpImgHSpace" NAME="inpImgHSpace" size=2> pixels
						</td>
					</tr>
					<tr>
						<td>
						Height: <input type="text" ID="inpImgHeight" NAME="inpImgHeight" size=2 style="margin-left:50"> pixels
						</td>
						<td>&nbsp;
						Vertical Spacing: <input type="text" ID="inpImgVSpace" NAME="inpImgVSpace" size=2 style="margin-left:16"> pixels
						</td>
					</tr>
					</table>


		</td>
		</tr>
		</table>

</td>
</tr>
<tr>
<td style="background-color:#efefef;border-top:1px solid #e6e6e6;height:40;padding-right:18" align=right valign=middle>
	<table cellpadding=1 cellspacing=0>
	<tr>
	<td>
	<input type="button" value="CLOSE" onclick="self.close()">
	</td>
	<td>
	<input type="button" value="INSERT" name=btnInsert onclick="doInsert()" style="display:block">
	</td>
	<td>	
	<input type="button" value="UPDATE" name=btnUpdate onclick="doUpdate()" style="display:none">
	</td>
	<td>	
	<input type="button" value="UPDATE & CLOSE" name=btnUpdateAndClose onclick="doUpdate();self.close()" style="display:none;width:120">	
	</td>
	</tr>
	</table>
</td>
</tr>
</table>
</body>
</html>
<?php
	function showImgCat($cat_ID, $level = 0)
	{	global $website, $root_path, $db;

		$sql = "select * from tbl_images where cat_ID = $cat_ID order by posted_date desc";
		if ( !($result = $db->sql_query($sql)) ) message_die("Database Error !. Please try again.");	
		while ( $row = $db->sql_fetchrow($result) )
		{	$img_path = $website . "/images/" . md5($cat_ID) . "/";			
?>
		<option value="<?php echo $img_path . $row['img_name']; ?>"><?php echo str_repeat('&nbsp;', $level * 2) . $row['img_name']; ?></option>
<?php		
		}
		$sql = "select * from tbl_image_categories where parent_id = $cat_ID order by priority";
		if ( !($result = $db->sql_query($sql)) ) message_die("Database Error !. Please try again.");				
		while ( $row = $db->sql_fetchrow($result) )
		{
?>
		<option label="<?php echo $row['cat_name']; ?>"><?php echo str_repeat('&nbsp;', $level * 2) . $row['cat_name']; ?></option>
<?php			
				showImgCat($row['cat_ID'], $level + 1);
		}

	}
?>