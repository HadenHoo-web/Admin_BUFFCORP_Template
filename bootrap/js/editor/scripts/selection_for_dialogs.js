/***********************************************************
	Copyright © 2003, InnovaStudio.com. All rights reserved.
************************************************************/

var oSELUtil=new SEL;
	
function SEL()
	{
	this.check=check;
	this.oSel;
	this.sType;
	this.oName;
	}
		
function check()
	{
	var oName=dialogArguments.oUtil.oName;
	var oEditor=eval("dialogArguments.idContent"+oName);
	var oSel=oEditor.document.selection.createRange();
	var sType=oEditor.document.selection.type;

	//********* Check Focus *********/
	isInside=true;
	if(oSel.parentElement!=null) 
		{
		if(!IsInsideEditor(oSel.parentElement())) isInside=false;
		}
	else 
		{
		if(!IsInsideEditor(oSel.item(0))) isInside=false;
		}

	if(!isInside)
		{
		if(dialogArguments.oUtil.useSelection)
			{
			/*
			Jika kita lakukan: Refresh, Check HTML mode, isi dgn ooo, Uncheck HTML mode (cursor jangan diletakkan dimana2), buka Styles dialog.
			Maka seharusnya oSel=null.
			Tapi justru masuk kesini, shg terjadi Error: Unpositioned markup pointer for this operation.
			*/
			oSel=dialogArguments.oUtil.oSel;
			sType=dialogArguments.oUtil.sType;
			
			/*
			Untuk insert table tdk berlaku. Walaupun oSel tdk null tapi tetap error: Incompatible markup pointers for this operation.
			Ini terjadi pada saat: Refresh klik di suatu cell dlm editor, buka "Create Table" dialog, Insert table, select text "HTML"< klik insert lagi.
			Reason: krn wkt klik insert yg pertama cursor langsung kehilangan focus. Jadi mesti difocuskan lagi baru seluruh prosedur
			berjln lancar.
			Solusi: Setelah insert table, lakukan:
				var oName=dialogArguments.oUtil.oName;
				var oEditor=eval("dialogArguments.idContent"+oName);
				oEditor.focus()			
			*/
			try
				{
				if (oSel.parentElement)	oElement=oSel.parentElement();
				else oElement=oSel.item(0);
				}
			catch(e)
				{
				oSel=null;
				sType=null;
				}
			}
		else
			{
			oSel=null;
			sType=null;
			}
		}
	//********* /Check Focus *********/
		
	this.oName=oName
	this.oSel=oSel;
	this.sType=sType;	
	}
		
function IsInsideEditor(oElement)
	{
	while(oElement!=null)
		{
		if(oElement.contentEditable=="true")return true;
		oElement=oElement.parentElement;
		}
	return false;
	}