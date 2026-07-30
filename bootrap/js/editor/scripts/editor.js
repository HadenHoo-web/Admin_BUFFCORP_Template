var oUtil=new InnovaEditorUtil();
function InnovaEditorUtil()
	{
	this.oName;
	this.oSel;
	this.sType;
	this.bInside=bInside;
	this.useSelection=true;

	this.arrEditor=[];
	}

function doBlur()
	{
	var oEditor=eval("idContent"+this.oName);
	var oSel=oEditor.document.selection.createRange();
	var sType=oEditor.document.selection.type;

	oUtil.useSelection=true;
	if(oSel.parentElement!=null)
		{
		if(!bInside(oSel.parentElement())) oUtil.useSelection=false;
		}
	else
		{
		if(!bInside(oSel.item(0))) oUtil.useSelection=false;
		}

	if(oUtil.useSelection)
		{
		oUtil.oSel=oSel;
		oUtil.sType=sType;
		}
	}
function bInside(oElement)
	{
	while(oElement!=null)
		{
		if(oElement.contentEditable=="true")return true;
		oElement=oElement.parentElement;
		}
	return false;
	}

/*********************
	checkFocus() & focus()
**********************/
function checkFocus()
	{
	var oEditor=eval("idContent"+this.oName);
	var oSel=oEditor.document.selection.createRange();
	var sType=oEditor.document.selection.type;

	isInside=true;
	if(oSel.parentElement!=null)
		{
		if(!bInside(oSel.parentElement()))isInside=false;
		}
	else
		{
		if(!bInside(oSel.item(0)))isInside=false;
		}

	if(!isInside)
		{
		if(oUtil.useSelection)
			{
			oSel=oUtil.oSel;
			sType=oUtil.sType;
			try
				{
				if (oSel.parentElement)	oElement=oSel.parentElement();
				else oElement=oSel.item(0);
				}
			catch(e)
				{
				return false;
				}
			}
		else
			{
			return false;
			}
		}
	return true;
	}
function focus()
	{
	var oEditor=eval("idContent"+this.oName);
	oEditor.focus()
	}

function InnovaEditor(oName)
	{
	this.oName=oName;
	this.RENDER=RENDER;

	this.loadHTML=loadHTML;
	this.getHTMLBody=getHTMLBody;
	this.getXHTMLBody=getXHTMLBody;
	this.getHTML=getHTML;
	this.getXHTML=getXHTML;
	this.putHTML=putHTML;

	this.doBlur=doBlur;
	this.bInside=bInside;
	this.checkFocus=checkFocus;
	this.focus=focus;

	this.doCmd=doCmd;
	this.applyParagraph=applyParagraph;
	this.applyFontName=applyFontName;
	this.applyFontSize=applyFontSize;

	this.hide=hide;
	this.dropShow=dropShow;

	this.publishingPath="";

	this.width="580";
	this.height="350";

	this.scriptPath="scripts/";

	this.writeIcon=writeIcon;
	this.writeIcon2=writeIcon2;
	this.iconPath="icons/";
	this.iconWidth=21;this.iconHeight=21;

	this.runtimeBorder=runtimeBorder;
	this.runtimeBorderOn=runtimeBorderOn;
	this.runtimeBorderOff=runtimeBorderOff;
	this.IsRuntimeBorderOn=true;

	this.useLastForeColor=false;
	this.useLastBackColor=false;
	this.stateForeColor="";
	this.stateBackColor="";

	this.btnTagSelector=true;//1.5.1
	this.useTagSelector=false;//1.5.1
	
	this.fullScreen=fullScreen;//1.5.1
	this.stateFullScreen=false;//1.5.1

	this.btnPreview=true;//1.5.1
	this.btnFullScreen=true;//1.5.1
	this.btnTextFormatting=true;this.btnCssText=true;
	this.btnParagraph=true;this.btnFontName=true;this.btnFontSize=true;
	this.btnCut=true;this.btnCopy=true;this.btnPaste=true;this.btnUndo=true;this.btnRedo=true;
	this.btnBold=true;this.btnItalic=true;this.btnUnderline=true;
	this.btnJustifyLeft=true;this.btnJustifyCenter=true;this.btnJustifyRight=true;this.btnJustifyFull=true;
	this.btnNumbering=true;this.btnBullets=true;this.btnIndent=true;this.btnOutdent=true;
	this.btnForeColor=true;this.btnBackColor=true;
	this.btnHyperlink=true;	
	this.btnImage=true;
	this.btnInternalImage=true;
	this.btnTable=true;this.btnGuidelines=true;
	this.btnPasteWord=true;this.btnLine=true;this.btnClean=true;
	this.btnXHTMLSource=true;
	this.btnCharacters=true;

	this.btnStyles=false;this.btnStrikethrough=false;
	this.btnSuperscript=false;this.btnSubscript=false;
	this.btnAssets=false;this.btnAssetManager=false;
	this.btnInternalLink=false;this.btnBorder=true;
	this.btnHTMLSource=false;this.btnHTMLFullSource=false;
	this.btnXHTMLFullSource=false;this.btnClearAll=false;
	this.btnForm=true;//1.5.1
	this.btnBookmark=true;//1.5.1

	//ADDITIONAL BUTTONS for COMMON CMS FUNCTIONS *********
	this.btnAssetsPublic=false;
	this.btnAssetManagerPublic=false;
	this.btnContentBlock=false;
	
	this.cmdAssetsPublic="";
	this.cmdAssetManagerPublic="";
	this.cmdContentBlock="";
	//*****************************************************

	this.runtimeStyles=runtimeStyles;//1.5.1


	this.cmdAssets="modalDialogShow('innova/assets.asp',680,520)";//1.5.1
	this.cmdAssetManager="modalDialogShow('innova/assetmanager/assetmanager.asp',617,480)";

	this.pasteWord=pasteWord;
	this.insertHTML=insertHTML;
	this.cmdInternalLink="";
	this.insertLink=insertLink;
	//iatek updates
	this.insertLink2=insertLink2;
	this.clearAll=clearAll;

	this.arrStyle=[];

	this.arrParagraph= [["Heading 1","H1"],
						["Heading 2","H2"],
						["Heading 3","H3"],
						["Heading 4","H4"],
						["Heading 5","H5"],
						["Heading 6","H6"],
						["Preformatted","PRE"],
						["Normal (P)","P"],
						["Normal (DIV)","DIV"]];

	this.arrFontName = ["Arial",
						"Arial Black",
						"Book Antiqua",
						"Comic Sans MS",
						"Courier New",
						"Georgia",
						"Impact",
						"Symbol",
						"Tahoma",
						"Times New Roman",
						"Trebuchet MS",
						"Verdana",
						"Webdings",
						"Wingdings",
						"serif",
						"sans-serif",
						"cursive",
						"fantasy",
						"monoscape",
						"Palatino Linotype"];

	this.arrFontSize = [["Size 1","1"],
						["Size 2","2"],
						["Size 3","3"],
						["Size 4","4"],
						["Size 5","5"],
						["Size 6","6"],
						["Size 7","7"]];

	this.XHTMLTemplate = "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n" +
			"<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" " +
			"\"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\n" +
			"<html  xmlns=\"http://www.w3.org/1999/xhtml\">";

	this.initialRefresh = false;
	}

/*********************
	RENDER
**********************/
function RENDER()
	{
	/******** ICONS ********/
	var sHTMLDropMenus="";
	var sHTMLIcons="";

	var sTmp="";

	if(this.btnPreview)
		sHTMLIcons+=this.writeIcon("Preview","window.showModalDialog('"+this.scriptPath+"preview.htm',window," +
			"'dialogWidth:600px;dialogHeight:555px;edge:Raised;center:Yes;help:No;resizable:Yes;status:No;maximize:Yes')","btn_preview.gif");//1.5.1
	if(this.btnFullScreen)
		sHTMLIcons+=this.writeIcon("Full Screen",this.oName+".fullScreen()","btn_fullscreen.gif");//1.5.1

	if(this.btnTextFormatting)
		sTmp+= "<tr><td onclick=\"window.showModelessDialog('"+this.scriptPath+"text1.htm',window," +
				"'dialogWidth:505px;dialogHeight:565px;edge:Raised;center:Yes;help:No;resizable:No;status:No');" +
				" dropStyle.style.display='none'\""+
				" style=\"padding:2px;padding-top:1px;font-family:Tahoma;font-size:11px;background-color:#F8F8FF\" " +
				"onmouseover=\"this.style.backgroundColor='#708090';this.style.color='#FFFFFF';\" " +
				"onmouseout=\"this.style.backgroundColor='';this.style.color='#000000';\" unselectable=on>Text Formatting</td></tr>";

	if(this.btnStyles)
		sTmp+= "<tr><td onclick=\"window.showModelessDialog('"+this.scriptPath+"styles.htm',window," +
				"'dialogWidth:357px;dialogHeight:390px;edge:Raised;center:Yes;help:No;resizable:No;status:No');" +
				" dropStyle.style.display='none'\""+
				" style=\"padding:2px;padding-top:1px;font-family:Tahoma;font-size:11px;background-color:#F8F8FF\" " +
				"onmouseover=\"this.style.backgroundColor='#708090';this.style.color='#FFFFFF';\" " +
				"onmouseout=\"this.style.backgroundColor='';this.style.color='#000000';\" unselectable=on>Styles</td></tr>";

	if(this.btnCssText)
		sTmp+= "<tr><td onclick=\"window.showModelessDialog('"+this.scriptPath+"styles_cssText.htm',window," +
				"'dialogWidth:456px;dialogHeight:443px;edge:Raised;center:Yes;help:No;resizable:No;status:No');" +
				" dropStyle.style.display='none'\""+
				" style=\"padding:2px;padding-top:1px;font-family:Tahoma;font-size:11px;background-color:#F8F8FF\" " +
				"onmouseover=\"this.style.backgroundColor='#708090';this.style.color='#FFFFFF';\" " +
				"onmouseout=\"this.style.backgroundColor='';this.style.color='#000000';\" unselectable=on>Custom CSS</td></tr>";

	if(this.btnTextFormatting || this.btnStyles || this.btnCssText)
		{
		sHTMLIcons+=this.writeIcon("Styles & Formatting",this.oName+".dropShow(this,dropStyle)","btn_style.gif");
		if(!document.getElementById("dropStyle"))
			sHTMLDropMenus+="<table id=dropStyle cellpadding=0 cellspacing=0 " +
				"style='z-index:1;display:none;position:absolute;border:#716F64 1px solid;" +
				"cursor:default;background-color:#F8F8FF;' unselectable=on>"+
				sTmp + "</table>";
		}

	if(this.btnParagraph)
		{
		sHTMLDropMenus+= "<table id=dropParagraph"+this.oName+" cellpadding=0 cellspacing=0 " +
			"style='z-index:1;display:none;position:absolute;border:#716F64 1px solid;" +
			"cursor:default;background-color:ghostwhite;' unselectable=on>";
		for(var i=0;i<this.arrParagraph.length;i++)
			{
			sHTMLDropMenus+= "<tr><td  onclick=\""+this.oName+".applyParagraph('<"+this.arrParagraph[i][1]+">')\" " +
				"style=\"padding:0;padding-left:5px;padding-right:5px;font-family:tahoma;background-color:ghostwhite\" " +
				"onmouseover=\"this.style.backgroundColor='#708090';this.style.color='#FFFFFF';\" " +
				"onmouseout=\"this.style.backgroundColor='';this.style.color='#000000';\" unselectable=on align=center>" +
				"<"+this.arrParagraph[i][1]+" style=\"\margin-bottom:4px\"  unselectable=on> " +
				this.arrParagraph[i][0] + "</"+this.arrParagraph[i][1]+"></td></tr>";
			}
		sHTMLDropMenus+= "</table>";
		sHTMLIcons+=this.writeIcon2("Paragraph","Paragraph",this.oName+".dropShow(this,dropParagraph"+this.oName+")","btn_paragraph.gif",89);
		}

	if(this.btnFontName)
		{
		sHTMLDropMenus+= "<table id=dropFontName"+this.oName+" cellpadding=0 cellspacing=0 " +
			"style='z-index:1;display:none;position:absolute;border:#716F64 1px solid;" +
			"cursor:default;background-color:ghostwhite;' unselectable=on>";
		for(var i=0;i<this.arrFontName.length;i++)
			{
			sHTMLDropMenus+= "<tr><td onclick=\""+this.oName+".applyFontName('"+this.arrFontName[i]+"')\" " +
				"style=\"padding:2px;padding-top:1px;font-family:"+ this.arrFontName[i] +";font-size:11px;background-color:ghostwhite\" " +
				"onmouseover=\"this.style.backgroundColor='#708090';this.style.color='#FFFFFF';\" " +
				"onmouseout=\"this.style.backgroundColor='';this.style.color='#000000';\" unselectable=on>" +
				this.arrFontName[i] + " <span  unselectable=on style='font-family:tahoma'>("+ this.arrFontName[i] +")</span></td></tr>";
			}
		sHTMLDropMenus+= "</table>";
		sHTMLIcons+=this.writeIcon2("Font Name","Font Name",this.oName+".dropShow(this,dropFontName"+this.oName+")","btn_fontname.gif",90);
		}

	if(this.btnFontSize)
		{
		sHTMLDropMenus+= "<table id=dropFontSize"+this.oName+" cellpadding=0 cellspacing=0 " +
			"style='z-index:1;display:none;position:absolute;border:#716F64 1px solid;" +
			"cursor:default;background-color:ghostwhite;' unselectable=on>";
		for(var i=0;i<this.arrFontSize.length;i++)
			{
			sHTMLDropMenus+= "<tr><td onclick=\""+this.oName+".applyFontSize('"+this.arrFontSize[i][1]+"')\" " +
				"style=\"padding:0;padding-left:5px;padding-right:5px;font-family:tahoma;background-color:ghostwhite\" " +
				"onmouseover=\"this.style.backgroundColor='#708090';this.style.color='#FFFFFF';\" " +
				"onmouseout=\"this.style.backgroundColor='';this.style.color='#000000';\" unselectable=on align=center>" +
				"<font unselectable=on size=\""+this.arrFontSize[i][1]+"\"> " +
				this.arrFontSize[i][0] + "</font></td></tr>";
			}
		sHTMLDropMenus+= "</table>";
		sHTMLIcons+=this.writeIcon2("Font Size","Size",this.oName+".dropShow(this,dropFontSize"+this.oName+")","btn_fontsize.gif",48);
		}

	if(this.btnCut) sHTMLIcons+=this.writeIcon("Cut",this.oName+".doCmd('Cut')","btn_cut.gif");
	if(this.btnCopy) sHTMLIcons+=this.writeIcon("Copy",this.oName+".doCmd('Copy')","btn_copy.gif");
	if(this.btnPaste) sHTMLIcons+=this.writeIcon("Paste",this.oName+".doCmd('Paste')","btn_paste.gif");
	if(this.btnUndo) sHTMLIcons+=this.writeIcon("Undo",this.oName+".doCmd('Undo')","btn_undo.gif");
	if(this.btnRedo) sHTMLIcons+=this.writeIcon("Redo",this.oName+".doCmd('Redo')","btn_redo.gif");
	if(this.btnBold) sHTMLIcons+=this.writeIcon("Bold",this.oName+".doCmd('Bold')","btn_bold.gif");
	if(this.btnItalic) sHTMLIcons+=this.writeIcon("Italic",this.oName+".doCmd('Italic')","btn_italic.gif");
	if(this.btnUnderline) sHTMLIcons+=this.writeIcon("Underline",this.oName+".doCmd('Underline')","btn_underline.gif");
	if(this.btnStrikethrough) sHTMLIcons+=this.writeIcon("Strikethrough",this.oName+".doCmd('Strikethrough')","btn_strikethrough.gif");
	if(this.btnSuperscript) sHTMLIcons+=this.writeIcon("Superscript",this.oName+".doCmd('Superscript')","btn_superscript.gif");
	if(this.btnSubscript) sHTMLIcons+=this.writeIcon("Subscript",this.oName+".doCmd('Subscript')","btn_subscript.gif");
	if(this.btnJustifyLeft) sHTMLIcons+=this.writeIcon("Justify Left",this.oName+".doCmd('JustifyLeft')","btn_left.gif");
	if(this.btnJustifyCenter) sHTMLIcons+=this.writeIcon("Justify Center",this.oName+".doCmd('JustifyCenter')","btn_center.gif");
	if(this.btnJustifyRight) sHTMLIcons+=this.writeIcon("Justify Right",this.oName+".doCmd('JustifyRight')","btn_right.gif");
	if(this.btnJustifyFull) sHTMLIcons+=this.writeIcon("Justify Full",this.oName+".doCmd('JustifyFull')","btn_full.gif");
	if(this.btnNumbering) sHTMLIcons+=this.writeIcon("Numbering",this.oName+".doCmd('InsertOrderedList')","btn_numbering.gif");
	if(this.btnBullets) sHTMLIcons+=this.writeIcon("Bullets",this.oName+".doCmd('InsertUnorderedList')","btn_bullets.gif");
	if(this.btnIndent) sHTMLIcons+=this.writeIcon("Indent",this.oName+".doCmd('Indent')","btn_indent.gif");
	if(this.btnOutdent) sHTMLIcons+=this.writeIcon("Outdent",this.oName+".doCmd('Outdent')","btn_outdent.gif");

	if(this.btnForeColor)
		sHTMLIcons+=this.writeIcon("Foreground Color",
			"modelessDialogShow('"+this.scriptPath+"colors_foreground.htm',380,310)","btn_forecolor.gif");
	if(this.btnBackColor)
		sHTMLIcons+=this.writeIcon("Background Color",
			"modelessDialogShow('"+this.scriptPath+"colors_background.htm',380,310)","btn_backcolor.gif");
	if(this.btnHyperlink)
		sHTMLIcons+=this.writeIcon("Hyperlink",
			"modelessDialogShow('"+this.scriptPath+"hyperlink.htm',312,242)","btn_hyperlink.gif");//1.5.1
	if(this.btnBookmark) //1.5.1
		sHTMLIcons+=this.writeIcon("Bookmark",
			"modelessDialogShow('"+this.scriptPath+"bookmark.htm',300,200)","btn_bookmark.gif");
			
	if(this.btnCharacters)
		sHTMLIcons+=this.writeIcon("Characters",
			"modelessDialogShow('"+this.scriptPath+"characters.htm',495,140)","btn_character.gif");
			
	if(this.btnImage)
		sHTMLIcons+=this.writeIcon("External Image",
			"modelessDialogShow('"+this.scriptPath+"image.htm',416,283)","btn_image.gif");
	if(this.btnInternalImage)	
		sHTMLIcons+=this.writeIcon("Internal Image",
			"modelessDialogShow1('test.php',700,600)","btn_image.gif");
		

			//"myWin=window.open('view_image.php','InternalImages')","btn_image.gif");
	//if(this.btnInternalImage)	
		//sHTMLIcons+=this.writeIcon("Internal Image",
			//"alert(" + this.oName + ")", "btn_image.gif");
			
	if(this.btnAssets) sHTMLIcons+=this.writeIcon("Assets",this.cmdAssets,"btn_assets.gif");	
	if(this.btnAssetManager) sHTMLIcons+=this.writeIcon("Asset Manager",this.cmdAssetManager,"btn_assetmanager.gif");
	
	//ADDITIONAL BUTTONS for COMMON CMS FUNCTIONS *********
	if(this.btnAssetsPublic) sHTMLIcons+=this.writeIcon("Public Assets",this.cmdAssetsPublic,"btn_assets2.gif");	
	if(this.btnAssetManagerPublic) sHTMLIcons+=this.writeIcon("Public Asset Manager",this.cmdAssetManagerPublic,"btn_assetmanager2.gif");
	if(this.btnContentBlock) sHTMLIcons+=this.writeIcon("Content Block",this.cmdContentBlock,"btn_customtag.gif");	
	//*****************************************************
	
	if(this.btnInternalLink) sHTMLIcons+=this.writeIcon("Internal Link",this.cmdInternalLink,"btn_internallink.gif");

	if(this.btnTable)
		{
		if(!document.getElementById("dropTable"))
			sHTMLDropMenus+="<table id=dropTable cellpadding=0 cellspacing=0 " +
				"style='display:none;position:absolute;border:#716F64 1px solid;" +
				"cursor:default;background-color:#F8F8FF;' unselectable=on>" +
				"<tr><td onclick=\"window.showModelessDialog('"+this.scriptPath+"table_insert.htm',window," +
				"	'dialogWidth:391px;dialogHeight:393px;edge:Raised;center:Yes;help:No;resizable:No;status:No');" +
				"	dropTable.style.display='none'\""+
				"	style=\"padding:2px;padding-top:1px;font-family:Tahoma;font-size:11px;background-color:#F8F8FF\""+
				"	onmouseover=\"this.style.backgroundColor='#708090';this.style.color='#FFFFFF';\"" +
				"	onmouseout=\"this.style.backgroundColor='';this.style.color='#000000';\" unselectable=on>Insert Table </td></tr>"+
				"<tr><td id=\"mnuTableEdit\" onclick=\"if(this.style.color!='gray'){window.showModelessDialog('"+this.scriptPath+"table_edit.htm',window," +
				"	'dialogWidth:432px;dialogHeight:435px;edge:Raised;center:Yes;help:No;resizable:No;status:No');" +
				"	dropTable.style.display='none'}\""+
				"	style=\"padding:2px;padding-top:1px;font-family:Tahoma;font-size:11px;background-color:#F8F8FF;color:gray\""+
				"	onmouseover=\"if(this.style.color!='gray'){this.style.backgroundColor='#708090';this.style.color='#FFFFFF';}\"" +
				"	onmouseout=\"if(this.style.color!='gray'){this.style.backgroundColor='';this.style.color='#000000';}\" unselectable=on>Edit Table </td></tr>" +
				"<tr><td id=\"mnuCellEdit\" onclick=\"if(this.style.color!='gray'){window.showModelessDialog('"+this.scriptPath+"table_editCell.htm',window," +
				"	'dialogWidth:433px;dialogHeight:460px;edge:Raised;center:Yes;help:No;resizable:No;status:No');" +
				"	dropTable.style.display='none'}\""+
				"	style=\"padding:2px;padding-top:1px;font-family:Tahoma;font-size:11px;background-color:#F8F8FF;color:gray\""+
				"	onmouseover=\"if(this.style.color!='gray'){this.style.backgroundColor='#708090';this.style.color='#FFFFFF';}\""+
				"	onmouseout=\"if(this.style.color!='gray'){this.style.backgroundColor='';this.style.color='#000000';}\" unselectable=on>Edit Cell </td></tr>"+
				"</table>";
		sHTMLIcons+=this.writeIcon("Table",this.oName+".dropShow(this,dropTable)","btn_table.gif");
		}

	if(this.btnBorder)
		sHTMLIcons+=this.writeIcon("Border & Shading","modelessDialogShow('"+this.scriptPath+"border1.htm',449,305)","btn_border.gif");
	if(this.btnGuidelines) sHTMLIcons+= this.writeIcon("Show/Hide Guidelines",this.oName+".runtimeBorder(true)","btn_guidelines.gif");
	if(this.btnPasteWord) sHTMLIcons+=this.writeIcon("Paste from Word",this.oName+".pasteWord()","btn_pasteword.gif");
	if(this.btnLine) sHTMLIcons+=this.writeIcon("Line",this.oName+".doCmd('InsertHorizontalRule')","btn_line.gif");

	if(this.btnForm)
		{
		var arrFormMenu = [["Form","form_form.htm","300px","225px"],
						["Text Field","form_text.htm","300px","330px"],
						["List","form_list.htm","300px","370px"],
						["Checkbox","form_check.htm","300px","225px"],
						["Radio Button","form_radio.htm","300px","225px"],
						["Hidden Field","form_hidden.htm","300px","200px"],
						["File Field","form_file.htm","300px","200px"],				
						["Button","form_button.htm","300px","225px"]];
		sHTMLIcons+=this.writeIcon("Form Editor",this.oName+".dropShow(this,dropForm)","btn_form.gif");
		if(!document.getElementById("dropForm"))
			{
			sHTMLDropMenus+="<table id=dropForm cellpadding=0 cellspacing=0 " +
				"style='z-index:1;display:none;position:absolute;border:#716F64 1px solid;" +
				"cursor:default;background-color:#F8F8FF;' unselectable=on>"
				for(var i=0;i<arrFormMenu.length;i++)
					{
					sHTMLDropMenus+="<tr><td onclick=\"window.showModelessDialog('"+this.scriptPath + arrFormMenu[i][1]+"',window," +
					"'dialogWidth:"+arrFormMenu[i][2]+";dialogHeight:"+arrFormMenu[i][3]+";edge:Raised;center:Yes;help:No;resizable:No;status:No');" +
					" dropForm.style.display='none'\""+
					" style=\"padding:2px;padding-top:1px;font-family:Tahoma;font-size:11px;background-color:#F8F8FF\" " +
					"onmouseover=\"this.style.backgroundColor='#708090';this.style.color='#FFFFFF';\" " +
					"onmouseout=\"this.style.backgroundColor='';this.style.color='#000000';\" unselectable=on>"+arrFormMenu[i][0]+"</td></tr>";
					}
			sHTMLDropMenus+="</table>";
			}
		}

	if(this.btnClean) sHTMLIcons+=this.writeIcon("Clean",this.oName+".doCmd('RemoveFormat')","btn_clean.gif");
	if(this.btnHTMLFullSource)
		sHTMLIcons+=this.writeIcon("View/Edit Source","window.showModalDialog('"+this.scriptPath+"source_html_full.htm',window," +
			"'dialogWidth:600px;dialogHeight:555px;edge:Raised;center:Yes;help:No;resizable:Yes;status:No;Maximize:Yes')","btn_html.gif");
	if(this.btnHTMLSource)
		sHTMLIcons+=this.writeIcon("View/Edit Source","window.showModalDialog('"+this.scriptPath+"source_html.htm',window," +
			"'dialogWidth:600px;dialogHeight:555px;edge:Raised;center:Yes;help:No;resizable:Yes;status:No;Maximize:Yes')","btn_html.gif");
	if(this.btnXHTMLFullSource)
		sHTMLIcons+=this.writeIcon("View/Edit Source","window.showModalDialog('"+this.scriptPath+"source_xhtml_full.htm',window," +
			"'dialogWidth:600px;dialogHeight:555px;edge:Raised;center:Yes;help:No;resizable:Yes;status:No;Maximize:Yes')","btn_xhtml.gif");
	if(this.btnXHTMLSource)
		sHTMLIcons+=this.writeIcon("View/Edit Source","window.showModalDialog('"+this.scriptPath+"source_xhtml.htm',window," +
			"'dialogWidth:600px;dialogHeight:555px;edge:Raised;center:Yes;help:No;resizable:Yes;status:No;Maximize:Yes')","btn_xhtml.gif");
	if(this.btnTagSelector)
		sHTMLIcons+=this.writeIcon("Tag Selector","window.showModelessDialog('"+this.scriptPath+"tag_selector.htm',window," +
				"'dialogWidth:150px;dialogHeight:150px;edge:Raised;center:Yes;help:No;resizable:Yes;status:No;maximize:No;border:thin;statusbar:no')","btn_tags.gif");//1.5.1

	if(this.btnClearAll) sHTMLIcons+=this.writeIcon("Clear All",this.oName+".clearAll()","btn_clearall.gif");
	/******** /ICONS ********/

	var sHTML="";
	sHTML+="<table id=idArea"+this.oName+" name=idArea"+this.oName+" border=0 " +
			"cellpadding=0 cellspacing=0 width='"+this.width+"' height='"+this.height+"'>" +
			"<tr><td bgcolor=#F0F0F0 style='padding-top:3px;padding-left:1px;'>" + //info: adjustment left here, 1.5.1
//			"filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=#f1f1f1,endColorstr=#d4d4d4);'>" +
			"<span>" +
			sHTMLIcons + //icons
			"</span>" +
			"</td></tr><tr><td valign=top height=100% style='background:white'>";//1.5.1

	//Add security='restricted' =>
	//Your current security settings prohibit running ActiveX controls on this page.
	//As a result, the page may not display correctly.
	sHTML+="<iframe style='width:100%;height:100%;margin-top:1px;' src='"+this.scriptPath+"blank.gif'" +
			" name=idContent"+ this.oName + " id=idContent"+ this.oName +
			" contentEditable=true onfocus=\"oUtil.oName='"+ this.oName +"';"+this.oName+".hide()\" " +
			" onblur=\""+this.oName+".doBlur();" +
			"if("+this.oName+".useTagSelector) " +
			"idElNavigate"+ this.oName +".innerHTML='&nbsp;';\"></iframe>";//idElNavigate, 1.5.1

	//Paste From Word Temp. Area
	sHTML+="<iframe style='width:1px;height:1px;overflow:auto;' src='"+this.scriptPath+"blank.gif'" +
					" name=idContentWord"+ this.oName +" id=idContentWord"+ this.oName +
					" contentEditable=true onfocus='"+this.oName+".hide()'></iframe>";
	
	//XHTML Conversion Temp. Area
	if(!document.getElementById("idSourceTmp"))
		{
		sHTML+="<iframe style='width:1px;height:1px;overflow:auto;' src='"+this.scriptPath+"blank.gif'" +
					" name=idSourceTmp id=idSourceTmp contentEditable=true></iframe>";			
		}
		
	//realTime
	if(!document.getElementById("btnRealTime"))
		{
		document.write("<input type=button style='position:absolute;width:1px;height:1px' id=btnRealTime name=btnRealTime>");
		}

	sHTML+="</td></tr><tr><td id=idElNavigate"+ this.oName +" style='font-family:arial;font-size:10px;background:#F0F0F0;padding:1px'>&nbsp;</td></tr></table>";//1.5.1

	sHTML+=sHTMLDropMenus; //dropdown

	document.write(sHTML);

	//paste from word temp storage
	var oWord = eval("idContentWord"+this.oName);
	oWord.document.designMode = "on";
	oWord.document.open("text/html","replace");
	oWord.document.write("<html><head></head><body></body></html>");
	oWord.document.close();
	oWord.document.body.contentEditable=true;//1.5.1

	oUtil.oName=this.oName;//default active editor
	
	oUtil.arrEditor.push(this.oName)
	}

/*********************
	CONTENT
**********************/
function loadHTML(sHTML)//hanya utk first load.
	{
	var oEditor=eval("idContent"+this.oName);

	var oDoc = oEditor.document.open("text/html", "replace");
	if(this.publishingPath!="")
		{
		if(sHTML.indexOf("<BASE")==-1)
			{
			//sHTML="<BASE HREF=\""+this.publishingPath+"\"/>" + sHTML;
			sHTML="<HTML><HEAD><BASE HREF=\""+this.publishingPath+"\"/></HEAD><BODY contentEditable=true>" + sHTML + "</BODY></HTML>";
			//kalau cuma tambah <HTML><HEAD></HEAD><BODY.. tdk apa2.
			}
		}
	else
		{
		sHTML="<HTML><HEAD></HEAD><BODY contentEditable=true>" + sHTML + "</BODY></HTML>";
		}
	oDoc.write(sHTML);
	oDoc.close();

	oEditor.document.body.contentEditable=true;
	oEditor.document.execCommand("2D-Position", true, true);//make focus
	oEditor.document.execCommand("MultipleSelection", true, true);//make focus
	oEditor.document.execCommand("LiveResize", true, true);//make focus

	//realTime
	oEditor.document.body.onkeydown = new Function("editorDoc_onkeydown('" + this.oName + "')");
	oEditor.document.body.onkeyup = new Function("editorDoc_onkeyup('" + this.oName + "')");
	oEditor.document.body.onmouseup = new Function("editorDoc_onmouseup('" + this.oName + "')");

	//Styles
	//if(this.btnStyles)

	if(this.arrStyle.length>0)//1.5.1
	{
		var oElement=oEditor.document.createElement("<STYLE>");
		//oEditor.document.childNodes[0].childNodes[0].appendChild(oElement);
		oEditor.document.documentElement.childNodes[0].appendChild(oElement);//1.5.1
		for(var i=0;i<this.arrStyle.length;i++)
		{
			selector = this.arrStyle[i][0];
			style = this.arrStyle[i][3];		
			oEditor.document.styleSheets(0).addRule(selector,style);
		}
	}

	//*** RUNTIME STYLES ***
	eval(this.oName).runtimeBorder(false);
	eval(this.oName).runtimeStyles();//1.5.1
	//***********************

	//fix undisplayed content
	if(this.initialRefresh)
		{
		oEditor.document.execCommand("SelectAll");
		window.setTimeout("eval('idContentWord"+this.oName+"').document.execCommand('SelectAll');",0);
		}

	oEditor.document.body.style.width=50;
	oEditor.document.body.style.height=50;
	oEditor.document.body.style.width="";
	oEditor.document.body.style.height="";
	//oEditor.document.body.style.cssText="overflow-x:scroll;overflow-y:scroll";
	}

function putHTML(sHTML)
	{
	var oEditor=eval("idContent"+this.oName);

	var oDoc = oEditor.document.open("text/html", "replace");
	oDoc.write(sHTML);
	oDoc.close();
	oEditor.document.body.contentEditable=true;
	oEditor.document.execCommand("2D-Position", true, true);
	oEditor.document.execCommand("MultipleSelection", true, true);
	oEditor.document.execCommand("LiveResize", true, true);

	//realTime
	oEditor.document.body.onkeydown = new Function("editorDoc_onkeydown('" + this.oName + "')");
	oEditor.document.body.onkeyup = new Function("editorDoc_onkeyup('" + this.oName + "')");
	oEditor.document.body.onmouseup = new Function("editorDoc_onmouseup('" + this.oName + "')");

	//*** RUNTIME STYLES *** //1.5.1
	eval(this.oName).runtimeBorder(false);
	eval(this.oName).runtimeStyles();
	//***********************
	}
function getHTML()//RICH Mode
	{
	var oEditor=eval("idContent"+this.oName);
	sHTML = oEditor.document.documentElement.outerHTML;
	sHTML = jsReplace(sHTML," contentEditable=true","");
	return sHTML;
	}
function getHTMLBody()//RICH Mode
	{
	var oEditor=eval("idContent"+this.oName);
	sHTML = oEditor.document.body.innerHTML;
	sHTML = jsReplace(sHTML," contentEditable=true","");
	return sHTML;
	}
function getXHTML()
	{
	var oEditor=eval("idContent"+this.oName);
	sHTML = this.XHTMLTemplate;
	sHTML += recur(oEditor.document.documentElement,""); //content inside html tag (head, body)
	sHTML += "\n</html>";
	return sHTML;
	}
function getXHTMLBody()
	{
	var oEditor=eval("idContent"+this.oName);
	sHTML = recur(oEditor.document.body,""); //content inside html tag (head, body)
	return sHTML;
	}
function fullScreen()//1.5.1
	{
	this.hide();
	if(this.stateFullScreen)
		{
		this.stateFullScreen=false;

		eval("idArea" + this.oName).style.position = "";
		eval("idArea" + this.oName).style.top = "0";
		eval("idArea" + this.oName).style.left = "0";
		eval("idArea" + this.oName).style.width = this.width;
		eval("idArea" + this.oName).style.height = this.height;

		for(var i=0;i<oUtil.arrEditor.length;i++)
			{
			if(oUtil.arrEditor[i]!=this.oName) eval("idArea" + oUtil.arrEditor[i]).style.display="block";
			}
		}
	else
		{
		this.stateFullScreen=true;

		eval("idArea" + this.oName).style.position = "absolute";
		eval("idArea" + this.oName).style.top = "0";
		eval("idArea" + this.oName).style.left = "0";
		if(document.body.style.overflow=="hidden")
			{
			w=(document.body.offsetWidth)
			}
		else
			{
			w=(document.body.offsetWidth-22)
			}
		h=(document.body.offsetHeight-4)
		eval("idArea" + this.oName).style.width = w;
		eval("idArea" + this.oName).style.height = h;

		for(var i=0;i<oUtil.arrEditor.length;i++)
			{
			if(oUtil.arrEditor[i]!=this.oName) eval("idArea" + oUtil.arrEditor[i]).style.display="none";
			}
			
		var oEditor=eval("idContent"+this.oName);
		oEditor.focus();//1.7.2
		}
	}

/*********************
	REALTIME
**********************/
function editorDoc_onkeydown(oName)
	{
	realTime(oName);
	}
function editorDoc_onkeyup(oName)
	{
	realTime(oName);
	}
function editorDoc_onmouseup(oName)
	{
	realTime(oName);
	}
var arrTmp = []//1.5.1
function GetElement(oElement,sMatchTag)//Used in realTime() only. 1.5.1
	{
	while (oElement!=null && oElement.tagName!=sMatchTag)
		{
		if(oElement.tagName=="BODY") return null;
		oElement = oElement.parentElement;
		}
	return oElement;
	}
function realTime(oName)//1.5.1
	{
	document.all.btnRealTime.click();

	//Focus stuff
	if(!eval(oName).checkFocus())return;

	var oEditor=eval("idContent"+oName);
	var oSel=oEditor.document.selection.createRange();

	//Enable/Disable Table Edit & Cell Edit Menu
	if(eval(oName).btnTable)
		{
		document.all.mnuTableEdit.style.color="gray";
		document.all.mnuCellEdit.style.color="gray";
		var oTable = (oSel.parentElement != null ? GetElement(oSel.parentElement(),"TABLE") : GetElement(oSel.item(0),"TABLE"))
		if (oTable)
			{
			document.all.mnuTableEdit.style.color="black";
			document.all.mnuCellEdit.style.color="gray";
			}
		var oTD = (oSel.parentElement != null ? GetElement(oSel.parentElement(),"TD") : GetElement(oSel.item(0),"TD"))
		if (oTD)
			{
			document.all.mnuTableEdit.style.color="black";
			document.all.mnuCellEdit.style.color="black";
			}
		}

	if(!eval(oName).useTagSelector) return;

	if (oSel.parentElement)	oElement=oSel.parentElement();
	else oElement=oSel.item(0);

	var sHTML="";

	var i=0;
	while (oElement!=null && oElement.tagName!="BODY")
		{
		arrTmp[i]=oElement;
		sHTML = "&nbsp;&nbsp;&lt;<span unselectable=on style='text-decoration:underline;cursor:hand' onclick=\"selectElement('"+oName+"',"+i+")\">" + oElement.tagName + "</span>&gt;" + sHTML
		oElement = oElement.parentElement;
		i++;
		}
	sHTML = "&nbsp;&lt;BODY&gt;" + sHTML
	eval("idElNavigate"+oName).innerHTML = sHTML;
	}
function selectElement(oName,i)//1.5.1
	{
	var oEditor=eval("idContent"+oName);
	var oSelRange = oEditor.document.body.createControlRange()
	try
		{
		oSelRange.add(arrTmp[i]);
		oSelRange.select();
		}
	catch(e)
		{
		var oSelRange = oEditor.document.body.createTextRange();
		oSelRange.moveToElementText(arrTmp[i]);
		oSelRange.select();
		}

	realTime(oName);
	}
/*********************
	RUNTIME BORDERS
**********************/
function runtimeBorderOn()
	{
	eval(this.oName).runtimeBorderOff();//reset

	var oEditor=eval("idContent"+this.oName);
	var oTables = oEditor.document.getElementsByTagName("TABLE");
	for (i=0;i<oTables.length;i++)
		{
		if(oTables[i].border==0)
			{
			for(j=0;j<oTables[i].getElementsByTagName("TD").length;j++)
				{
				if(oTables[i].getElementsByTagName("TD")[j].style.borderLeftWidth=="0px"||
					oTables[i].getElementsByTagName("TD")[j].style.borderLeftWidth=="" ||
					oTables[i].getElementsByTagName("TD")[j].style.borderLeftWidth=="medium")
						{
						oTables[i].getElementsByTagName("TD")[j].runtimeStyle.borderLeftWidth=1;
						oTables[i].getElementsByTagName("TD")[j].runtimeStyle.borderLeftColor = "#BCBCBC";
						oTables[i].getElementsByTagName("TD")[j].runtimeStyle.borderLeftStyle = "dotted";
						}
				if(oTables[i].getElementsByTagName("TD")[j].style.borderRightWidth=="0px"||
					oTables[i].getElementsByTagName("TD")[j].style.borderRightWidth=="" ||
					oTables[i].getElementsByTagName("TD")[j].style.borderRightWidth=="medium")
						{
						oTables[i].getElementsByTagName("TD")[j].runtimeStyle.borderRightWidth=1;
						oTables[i].getElementsByTagName("TD")[j].runtimeStyle.borderRightColor = "#BCBCBC";
						oTables[i].getElementsByTagName("TD")[j].runtimeStyle.borderRightStyle = "dotted";
						}
				if(oTables[i].getElementsByTagName("TD")[j].style.borderTopWidth=="0px"||
					oTables[i].getElementsByTagName("TD")[j].style.borderTopWidth=="" ||
					oTables[i].getElementsByTagName("TD")[j].style.borderTopWidth=="medium")
						{
						oTables[i].getElementsByTagName("TD")[j].runtimeStyle.borderTopWidth=1;
						oTables[i].getElementsByTagName("TD")[j].runtimeStyle.borderTopColor = "#BCBCBC";
						oTables[i].getElementsByTagName("TD")[j].runtimeStyle.borderTopStyle = "dotted";
						}
				if(oTables[i].getElementsByTagName("TD")[j].style.borderBottomWidth=="0px"||
					oTables[i].getElementsByTagName("TD")[j].style.borderBottomWidth=="" ||
					oTables[i].getElementsByTagName("TD")[j].style.borderBottomWidth=="medium")
						{
						oTables[i].getElementsByTagName("TD")[j].runtimeStyle.borderBottomWidth=1;
						oTables[i].getElementsByTagName("TD")[j].runtimeStyle.borderBottomColor = "#BCBCBC";
						oTables[i].getElementsByTagName("TD")[j].runtimeStyle.borderBottomStyle = "dotted";
						}
				}
			}
		}
	}
function runtimeBorderOff()
	{
	var oEditor=eval("idContent"+this.oName);
	var oTables = oEditor.document.getElementsByTagName("TABLE");
	for (i=0;i<oTables.length;i++)
		{
		if(oTables[i].border==0)
			{
			for(j=0;j<oTables[i].getElementsByTagName("TD").length;j++)
				{
				oTables[i].getElementsByTagName("TD")[j].runtimeStyle.borderWidth = "";
				oTables[i].getElementsByTagName("TD")[j].runtimeStyle.borderColor = "";
				oTables[i].getElementsByTagName("TD")[j].runtimeStyle.borderStyle = "";
				}
			}
		}
	}
function runtimeBorder(bToggle)
	{
	if(bToggle)
		{
		if(this.IsRuntimeBorderOn)
			{
			this.runtimeBorderOff();
			this.IsRuntimeBorderOn=false;
			}
		else
			{
			this.runtimeBorderOn();
			this.IsRuntimeBorderOn=true;
			}
		}
	else
		{
		if(this.IsRuntimeBorderOn)
			{
			this.runtimeBorderOn();
			}
		else
			{
			this.runtimeBorderOff();
			}
		}
	}

/*********************
	TOOLBAR ICONS
**********************/
function writeIcon(title,command,img)
	{
	w = this.iconWidth;
	h = this.iconHeight;
	imgPath = this.scriptPath+this.iconPath+img;
	sHTML="" +
		"<span unselectable='on' style='margin-left:0;margin-right:1;margin-bottom:1;width:"+w+";height:"+h+";'>" +
		"<span unselectable='on' style='position:absolute;clip: rect(0 "+w+"px "+h+"px 0)'>" +
		"<img unselectable='on' btntoggle=true title='"+title+"' src='"+imgPath+"' style='position:absolute;top:-0;width:"+w+"'" +
		"onmouseover='this.style.top=-"+h+"' onmouseout='this.style.top=0'" +
		"onmousedown='this.style.top=-"+2*h+"' onmouseup=\"this.style.top=0;"+command+";\">" +
		"</span></span>";
	return sHTML;
	}
function writeIcon2(title,caption,command,img,width)
	{
	h = this.iconHeight;
	imgPath = this.scriptPath+this.iconPath+img;
	sHTML="" +
		"<span unselectable='on' style='margin-left:0;margin-right:1;margin-bottom:1;width:"+width+";height:"+h+";'>" +
		"<span unselectable='on' style='position:absolute;clip: rect(0 "+width+"px "+h+"px 0)'>" +
		"<img unselectable='on' btntoggle=true title='"+title+"' src='"+imgPath+"' style='position:absolute;top:-0;width:"+width+"'" +
		"onmouseover='this.style.top=-"+h+"' onmouseout='this.style.top=0'" +
		"onmousedown='this.style.top=-"+2*h+"' onmouseup=\"this.style.top=0;"+command+";\">" +
		"</span></span>";
	return sHTML;
	}

/*********************
	OTHERS
**********************/
function doCmd(sCmd,sOption)
	{
	//Focus stuff
	if(!eval(this.oName).checkFocus())return;

	var oEditor=eval("idContent"+this.oName);
	var oSel=oEditor.document.selection.createRange();
	var sType=oEditor.document.selection.type;
	var oTarget=(sType=="None" ? oEditor.document : oSel);
	oTarget.execCommand(sCmd, false, sOption);
	}
function applyParagraph(val)//1.5.1
	{
	var oEditor=eval("idContent"+this.oName);

	var oSel=oEditor.document.selection.createRange();
	eval(this.oName).hide();
	oSel.select()

	eval(this.oName).doCmd("FormatBlock",val);
	}
function applyFontName(val)//1.5.1
	{
	var oEditor=eval("idContent"+this.oName);

	var oSel=oEditor.document.selection.createRange();
	eval(this.oName).hide();//ini menyebabkan text yg ter-select menjadi tdk ter-select di framed-page.
	//Solusi: oSel di select lagi
	oSel.select()

	eval(this.oName).doCmd("fontname",val);
	}
function applyFontSize(val)//1.5.1
	{
	var oEditor=eval("idContent"+this.oName);

	var oSel=oEditor.document.selection.createRange();
	eval(this.oName).hide();
	oSel.select();

	eval(this.oName).doCmd("fontsize",val);
	}
function dropShow(oEl,box)//1.5.1
	{
	eval(this.oName).hide();

	box.style.display="block";
	var nTop=0;
	var nLeft=0;

	oElTmp = oEl;
	while(oElTmp.tagName!="BODY" && oElTmp.tagName!="HTML")
		{
		if(oElTmp.style.top!="")
			{
			nTop += oElTmp.style.top.substring(1,oElTmp.style.top.length-2) * 1;
			}
		else nTop +=oElTmp.offsetTop;
		oElTmp = oElTmp.offsetParent;
		}

	oElTmp = oEl;
	while(oElTmp.tagName!="BODY" && oElTmp.tagName!="HTML")
		{
		if(oElTmp.style.left!="")
			{
			nLeft += oElTmp.style.left.substring(1,oElTmp.style.left.length-2) * 1;
			}
		else nLeft +=oElTmp.offsetLeft;
		oElTmp = oElTmp.offsetParent;
		}

	box.style.left=nLeft;
	box.style.top=nTop + 22;
	box.focus();
	}

function modelessDialogShow(url,width,height)
	{
		window.showModelessDialog(url,window,
		"dialogWidth:"+width+"px;dialogHeight:"+height+"px;edge:Raised;center:Yes;help:No;resizable:No;status:No");
		
	}
function modelessDialogShow1(url,width,height)
	{	
		window.showModelessDialog(url,window,
		"dialogWidth:"+width+"px;dialogHeight:"+height+"px;edge:Raised;center:Yes;help:No;resizable:No;status:No;scroll:No");
		
	}

function modalDialogShow(url,width,height)
	{
	window.showModalDialog(url,window,
		"dialogWidth:"+width+"px;dialogHeight:"+height+"px;edge:Raised;center:Yes;help:No;resizable:No;status:No");
	}

function hide()
	{
	if(this.btnTextFormatting || this.btnStyles || this.btnCssText) eval("dropStyle").style.display="none";
	if(this.btnParagraph) eval("dropParagraph"+this.oName).style.display="none";
	if(this.btnFontName) eval("dropFontName"+this.oName).style.display="none";
	if(this.btnFontSize) eval("dropFontSize"+this.oName).style.display="none";
	if(this.btnTable) eval("dropTable").style.display="none";
	if(this.btnForm) eval("dropForm").style.display="none";//1.5.1
	}

function jsReplace(sText, sFind, sReplace)
	{
	var arrTmp = sText.split(sFind);
	if (arrTmp.length > 1) sText = arrTmp.join(sReplace);
	return sText;
	}

function pasteWord()
	{
	//Focus stuff
	if(!eval(this.oName).checkFocus())return;

	var oEditor = eval("idContent"+this.oName);
	var oWord = eval("idContentWord"+this.oName);
	oEditor.focus();
	var oSel = oEditor.document.selection.createRange();

	if(oSel.parentElement)
		{
		oWord.focus();
		oWord.document.execCommand("SelectAll");
		oWord.document.execCommand("Paste");

		for (var i = 0; i < oWord.document.body.all.length; i++)
			{
			oWord.document.body.all[i].removeAttribute("className","",0);
			oWord.document.body.all[i].removeAttribute("style","",0);
			}
		var str = oWord.document.body.innerHTML;

		str = String(str).replace(/<\\?\?xml[^>]*>/g, "");
		str = String(str).replace(/<\/?o:p[^>]*>/g, "");
		str = String(str).replace(/<\/?v:[^>]*>/g, "");
		str = String(str).replace(/<\/?o:[^>]*>/g, "");

		str = String(str).replace(/&nbsp;/g, "");//<p>&nbsp;</p>

		str = String(str).replace(/<\/?SPAN[^>]*>/g, "");
		str = String(str).replace(/<\/?FONT[^>]*>/g, "");
		str = String(str).replace(/<\/?STRONG[^>]*>/g, "");

		str = String(str).replace(/<\/?H1[^>]*>/g, "");
		str = String(str).replace(/<\/?H2[^>]*>/g, "");
		str = String(str).replace(/<\/?H3[^>]*>/g, "");
		str = String(str).replace(/<\/?H4[^>]*>/g, "");
		str = String(str).replace(/<\/?H5[^>]*>/g, "");
		str = String(str).replace(/<\/?H6[^>]*>/g, "");

		str = String(str).replace(/<\/?P[^>]*><\/P>/g, "");
		oSel.pasteHTML(str);
		}
	}

function insertHTML(sHTML)
	{
	//Focus stuff
	if(!eval(this.oName).checkFocus())return;

	var oEditor = eval("idContent"+this.oName);
	var oSel = oEditor.document.selection.createRange();
	if(oSel.parentElement) oSel.pasteHTML(sHTML);
	else oSel.item(0).outerHTML = sHTML;
	}

function insertLink(url)
	{
	//Focus stuff
	if(!eval(this.oName).checkFocus())return;

	var oEditor = eval("idContent"+this.oName);
	var oSel = oEditor.document.selection.createRange();

	if(oSel.parentElement)
		{
		if(oSel.text=="")//If no (text) selection, then build selection using the typed URL
			{
			var oSelTmp=oSel.duplicate();
			oSel.text=url;
			oSel.setEndPoint("StartToStart",oSelTmp);
			oSel.select();
			}
		}
	oSel.execCommand("CreateLink",false,url);
	}
	
//iatek updates - changes output of inserted link
function insertLink2(url)
{
	//Focus stuff
	if(!eval(this.oName).checkFocus())return;

	var oEditor = eval("idContent"+this.oName);
	var oSel = oEditor.document.selection.createRange();

	if(oSel.parentElement)
	{
		if(oSel.text=="")//If no (text) selection, then build selection using the typed URL
		{
			var oSelTmp=oSel.duplicate();
			oSel.text=url;
			oSel.setEndPoint("StartToStart",oSelTmp);
			oSel.select();
		}
	}

	//oSel.execCommand("CreateLink",false,url);
	//changed above line to below line, 
	//changes needed in InteralLinks.asp also
	oSel.pasteHTML(url);
}	

function clearAll()
	{
	if (confirm("Are you sure you wish to delete all content?") == true)
		{
		var oEditor=eval("idContent"+this.oName);
		oEditor.document.body.innerHTML="";
		}
	}

function runtimeStyles()//1.5.1
	{
	var oEditor=eval("idContent"+this.oName);
	var oForms = oEditor.document.getElementsByTagName("FORM");
	for (i=0;i<oForms.length;i++)
		{
		oForms[i].runtimeStyle.border = "#7bd158 1 dotted";
		}

	var oBookmarks = oEditor.document.getElementsByTagName("A");
	for (i=0;i<oBookmarks.length;i++)
		{
		if(oBookmarks[i].name || oBookmarks[i].NAME)
			{
			if(oBookmarks[i].innerHTML=="") oBookmarks[i].runtimeStyle.width = "1px";
			oBookmarks[i].runtimeStyle.padding = "0px";
			oBookmarks[i].runtimeStyle.paddingLeft = "1px";
			oBookmarks[i].runtimeStyle.paddingRight = "1px";
			oBookmarks[i].runtimeStyle.border = "#888888 1 dotted";
			oBookmarks[i].runtimeStyle.borderLeft = "#cccccc 10 solid";
			}
		}
	}

/************************
	HTML to XHTML
************************/
function trimComment1(sTmp)// => SCRIPT
	{
	//Remove "//<![CDATA[", "//]]>" and spaces at the beginning & end of text, why?
	//karena nanti akan ditambahkan, utk memastikan spy tidak double
	var arrTmp = sTmp.split("//<![CDATA[");
	if (arrTmp.length > 1)
		{
		if(arrTmp[0].replace(/^\s+/,'').replace(/\s+$/,'')=="")
			sTmp = arrTmp[1];
		}
	arrTmp = sTmp.split("//]]>");
	if (arrTmp.length > 1)
		{
		if(arrTmp[1].replace(/^\s+/,'').replace(/\s+$/,'')=="")
			sTmp = arrTmp[0];
		}
	sTmp=sTmp.replace(/^\s+/,'').replace(/\s+$/,'');
	return sTmp;
	}
function trimComment2(sTmp)// => STYLE
	{
	//Remove "<!--", "-->" and spaces at the beginning & end of text, why?
	//karena nanti akan ditambahkan, utk memastikan spy tidak double
	var arrTmp = sTmp.split("<!--");
	if (arrTmp.length > 1)
		{
		if(arrTmp[0].replace(/^\s+/,'').replace(/\s+$/,'')=="")
			sTmp = arrTmp[1];
		}
	arrTmp = sTmp.split("-->");
	if (arrTmp.length > 1)
		{
		if(arrTmp[1].replace(/^\s+/,'').replace(/\s+$/,'')=="")
			sTmp = arrTmp[0];
		}
	sTmp=sTmp.replace(/^\s+/,'').replace(/\s+$/,'');
	return sTmp;
	}
function fixXMLa(s)//attribute
	{
	s = String(s).replace(/&/g, "&amp;");
	s = String(s).replace(/</g, "&lt;");
	s = String(s).replace(/"/g, "&quot;");
	return s;
	}
function fixXMLb(s)//node value
	{
	s = String(s).replace(/&/g, "&amp;");
	s = String(s).replace(/</g, "&lt;");
	return s;
	}
function lineBreak1(tag) //[0]<TAG>[1]text[2]</TAG>
	{
	arrReturn = ["\n", "", ""];
	if(	tag=="A" || tag=="B" || tag=="CITE" || tag=="CODE" || tag=="EM" || tag=="FONT" || tag=="I" ||
		tag=="SMALL" || tag=="STRIKE" || tag=="STRONG" || tag=="SUB" || tag=="SUP" || tag=="U" || tag=="BIG" ||
		tag=="S" || tag=="VAR" || tag=="BASEFONT" || tag=="KBD" || tag=="SAMP" || tag=="TT") arrReturn = ["", "", ""];

	if(	tag=="TEXTAREA" || tag=="TABLE" || tag=="THEAD" || tag=="TBODY" || tag=="TR" || tag=="OL" || tag=="UL" ||
		tag=="DIR" || tag=="MENU" || tag=="FORM" || tag=="SELECT" || tag=="SCRIPT" || tag=="MAP" || tag=="DL" ||
		tag=="STYLE"  || tag=="HEAD" || tag=="BODY" || tag=="HTML") arrReturn = ["\n", "", "\n"];

	//Special
	if(tag=="BR" || tag=="HR") arrReturn = ["", "\n", ""];
	//if(tag=="P") arrReturn = ["<br><br>", "", ""];
	//if(tag=="TABLE") arrReturn = ["<br><br>", "", "<br>"];

	return arrReturn;
	}

var bContainP;//1.5.1
var bDoNotProcess;//1.5.1
function recP(oEl)//1.5.1
	{
	if(bContainP) return;

	for(var k=0;k<oEl.childNodes.length;k++)
		{
		ss=oEl.childNodes(k).nodeName;
		if(ss=="P")
			{
			bContainP = true;
			return;
			}
		else if(ss=="TABLE"){;}//1.7.2
		else
			{
			recP(oEl.childNodes(k));
			}
		}
	}
var nCount = 0;
function recur(oEl)//1.5.1
	{
	nCount++;
	if(nCount==300||nCount==600||nCount==900||nCount==1200||nCount==1500||nCount==1800||nCount==2100||nCount==2400||nCount==2700) alert(" In progress...Click OK to continue.");
	
	var sHTML="";
	bContainP=false

	for(var i=0;i<oEl.childNodes.length;i++)
		{
		var oNode=oEl.childNodes(i);
		
		
		if(oNode.nodeType==1 && oNode.nodeName=="P") recP(oNode)
		
		if(bContainP)
			{
			/*********** Tdk Sama ************/
			//alert(oNode.outerHTML)
			//alert(oNode.childNodes[0].outerHTML)
			/*********************************/
			//i=oEl.childNodes.length-1;
			
			/*********** Normalize ************/
			var oDocTmp = idSourceTmp.document.open("text/html", "replace");
			oDocTmp.write(oNode.outerHTML);//Pakai yg asli
			oDocTmp.close();

			sHTML += recur(idSourceTmp.document.body);
			/**********************************/			
			}
		else
			{
			if(oNode.nodeType==1)//tag
				{
				//Extract Attributes
				var sAttrName = "";
				var arrAttrName = [];
				var sTmp = oNode.outerHTML.split(">")[0];
				for(var k=0;k<oNode.attributes.length;k++)
					{
					attrName=oNode.attributes[k].nodeName;		
					if(sTmp.indexOf(" "+attrName+"=")!=-1)
						{
						if(attrName.toLowerCase()!="contenteditable")
							sAttrName+=",'" + attrName + "'";
						}
					}
				if(sAttrName!="") arrAttrName = eval("["+sAttrName.substr(1)+"]");

				var sTagName = oNode.nodeName;

				bDoNotProcess=false;//1.5.1
				if(oEl.nodeName=="TR")//1.5.1
					{
					if(oNode.nodeName!="TD")
						{
						bDoNotProcess=true;
						}
					}

				//special fix : <THEAD></THEAD><TBODY></TBODY><TR></TR>
				//if(sTagName.substr(0,1)!="/")
				if(sTagName.substr(0,1)!="/" && !bDoNotProcess) //1.5.1
					{
					//~~~~~~~~~
					sHTML+= lineBreak1(sTagName)[0];
					//~~~~~~~~~

					//OPENING TAG
					sHTML+= "<" + sTagName.toLowerCase();

					/*****************************************************
									IMPORTANT
					******************************************************/

					//NAVIGATE THE ATTRIBUTES
					for(var j=0;j<arrAttrName.length;j++)
						{
						var sName = arrAttrName[j];

						//UNSUPPORTED Attributes (LEAVE the Attributes)
						if(sTagName.toLowerCase()=="hr" && ( sName.toLowerCase()=="noshade" || sName.toLowerCase()=="size" ) ) sName="";
						else if(sTagName.toLowerCase()=="select" && sName.toLowerCase()=="multiple" ) sName="";
						else if(sTagName.toLowerCase()=="option" && sName.toLowerCase()=="selected") sName="";
						else if(sTagName.toLowerCase()=="a" && sName.toLowerCase()=="href")//FIX THE VALUE
							{
							sTmp=oNode.outerHTML;
							sTmp=sTmp.substring(sTmp.indexOf(" href=")+7);
							sTmp=sTmp.substring(0,sTmp.indexOf('"'));
							var arrTmp = sTmp.split("&amp;");
							if (arrTmp.length > 1) sTmp = arrTmp.join("&");
							var sValue=sTmp
							}
						else if(sTagName.toLowerCase()=="img" && sName.toLowerCase()=="src")//FIX THE VALUE
							{
							sTmp=oNode.outerHTML;
							sTmp=sTmp.substring(sTmp.indexOf(" src=")+6);
							sTmp=sTmp.substring(0,sTmp.indexOf('"'));
							var arrTmp = sTmp.split("&amp;");
							if (arrTmp.length > 1) sTmp = arrTmp.join("&");
							var sValue=sTmp
							}
						else if(sName.toLowerCase()=="class")//FIX THE VALUE
							{
							var sValue = oNode.className;
							}
						else if(sName.toLowerCase()=="style")//FIX THE VALUE
							{
							var sValue=oNode.style.cssText;
							}
						else
							{
							try {var sValue = eval("oNode.attributes."+arrAttrName[j]+".nodeValue");}//OK (Set the Value)
							catch(e) {sName="";}//UNSUPPORTED Attributes (LEAVE the Attributes)
							}

						if(sName!="")//OK
							{
							sHTML+= " " + sName.toLowerCase() + "=\"" + fixXMLa(sValue) + "\"";
							}
						}

					//UNSUPPORTED; CATCH the UNSUPPORTED Attributes & Add before the closing tag
					if(sTagName.toLowerCase()=="hr" && oNode.noShade) sHTML+= " noshade=\"noshade\"";
					if(sTagName.toLowerCase()=="hr" && oNode.size!="") sHTML+= " size=\""+oNode.size+"\"";
					if(sTagName.toLowerCase()=="select" && oNode.multiple) sHTML+= " multiple=\"multiple\"";
					if(sTagName.toLowerCase()=="option" && oNode.selected) sHTML+= " selected=\"selected\"";
					if(oNode.outerHTML.indexOf(" CHECKED ")!=-1 && oNode.nodeName!="BODY" && oNode.nodeName!="BASE")
						sHTML+= " checked=\"checked\"";//ternyata BODY ada attr CHECKED

					/*****************************************************
									/IMPORTANT
					******************************************************/
					//CLOSING TAG
					if(sTagName=="IMG"||sTagName=="BR"||sTagName=="AREA"||
						sTagName=="HR"||sTagName=="INPUT"||sTagName=="BASE")//If doesn't need Closing Tag
						{
						sHTML+= " />"; //doesn't need closing tag

						//~~~~~~~~~
						sHTML+= lineBreak1(sTagName)[1];
						//~~~~~~~~~
						}
					else
						{
						sHTML+= ">";

						//~~~~~~~~~
						sHTML+= lineBreak1(sTagName)[1];
						//~~~~~~~~~

						if(sTagName=="SCRIPT")
							{
							if(oNode.attributes.src.nodeValue=="")
								{
								var sTmp=trimComment1(oNode.innerHTML);
								sHTML+= "//<![CDATA[\n"+sTmp+"\n//]]>";
								}
							else
								{//external script
								sHTML+= "";
								}
							}
						else if(sTagName=="STYLE")
							{
							var sTmp=trimComment2(oNode.innerHTML);
							sHTML+= "<!--\n"+sTmp+"\n-->";
							}
						else if(sTagName=="TITLE")
							sHTML+= oNode.innerHTML;
						else
							sHTML+=recur(oNode);

						//~~~~~~~~~
						sHTML+= lineBreak1(sTagName)[2];
						//~~~~~~~~~

						sHTML+= "</" + sTagName.toLowerCase() + ">"; //example: </TABLE>
						}
					}
				}
			else if(oNode.nodeType==3)//text
				sHTML+= fixXMLb(oNode.nodeValue);
			else if(oNode.nodeType==8)//comments
				{
				if(oNode.outerHTML.substring(0,2)=="<"+"%")
					{
					sHTML+= oNode.outerHTML;
					}
				else
					{
					var sTmp=trimComment2(oNode.nodeValue);
					sHTML+= "<!--\n"+sTmp+"\n-->";
					}
				}
			else
				{
				sHTML+= "<!-- Not Processed :"+ sTagName + " - " + oNode.nodeValue+"-->";
				}
			}
		}
	return sHTML;
	}
	
	function InitEditor(oEdit, hiddenField, scriptPath, width, height) 
	{	oEdit.scriptPath = scriptPath;
		oEdit.width = width;
		oEdit.height = height;
		
	// turn off assets button
		oEdit.btnAssets = false;
		oEdit.btnAssetManager=false;
		oEdit.btnCharacters=false;
		oEdit.btnTagSelector=false;
	
	//turn off internal link button
		oEdit.btnInternalLink=false;
//		oEdit1.cmdInternalLink = "modelessDialogShow('innova/InternalLinks.asp',465,250)"
	
		oEdit.RENDER();
		oEdit.loadHTML(hiddenField.innerHTML);
	}
