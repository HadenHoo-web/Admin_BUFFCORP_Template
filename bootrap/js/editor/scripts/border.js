/***********************************************************
	Copyright © 2003, InnovaStudio.com. All rights reserved.
************************************************************/

/*****************************
	Common
*****************************/
function doApplyBorder(oElement)
	{
	sStyle=idSelBorderStyle.value
	sWidth=idSelBorderWidth.value
	sApplyTo=idSelBorderApplyTo.value
	sColor=idSelBorderColor.style.backgroundColor
	sShadingColor=idSelShadingColor.style.backgroundColor
	
	switch(sApplyTo)
		{
		case "No Border":
			oElement.style.border="none";
			break;
		case "Outside Border":
			if(sStyle=="none")oElement.style.border="none";
			else oElement.style.border=sColor + " " + sWidth + " " + sStyle;
			break;
		case "Left Border":
			if(sStyle=="none") oElement.style.borderLeft="none";
			else oElement.style.borderLeft=sColor + " " + sWidth + " " + sStyle;
			break;
		case "Top Border":
			if(sStyle=="none")oElement.style.borderTop="none";
			else oElement.style.borderTop=sColor + " " + sWidth + " " + sStyle;
			break;
		case "Right Border":
			if(sStyle=="none")oElement.style.borderRight="none";
			else oElement.style.borderRight=sColor + " " + sWidth + " " + sStyle;
			break;
		case "Bottom Border":
			if(sStyle=="none")oElement.style.borderBottom="none";
			else oElement.style.borderBottom=sColor + " " + sWidth + " " + sStyle;
			break;
		}
	
	oElement.style.backgroundColor=sShadingColor;
	}	
function doOver(me)
	{
	if(me.style.backgroundColor!='#f1f1f1')	
		{
		me.style.backgroundColor='#f0f0f0';
		me.style.border='#708090 1px solid';
		}
	}
function doOut(me)
	{
	if(me.style.backgroundColor!='#f1f1f1')	
		{
		me.style.backgroundColor='#ffffff';
		me.style.border='#ffffff 1 solid';
		}
	}

/*****************************
	Border Style
*****************************/
function drawBorderStyleSelection()
	{
	arrStyleOptions=[   ["idStyle_Solid","border-bottom:black 1 solid;height:10;"],
						["idStyle_Dotted","border-bottom:black dotted;height:10"],
						["idStyle_Dashed","border-bottom:black dashed;height:10"],
						["idStyle_Double","border-bottom:black double;height:10"],
						["idStyle_Groove","border-style:groove;height:18"],
						["idStyle_Ridge","border-style:ridge;height:18"],
						["idStyle_Inset","border-style:inset;height:18"],
						["idStyle_Outset","border-style:outset;height:18"]];
	sHTML="<div style='overflow:auto;border:gray 1 solid;width:125;height:127;'>"
	sHTML+="<table id=tblBorderStyle cellpadding=0 cellspacing=0 width=100% style='table-layout:fixed;background:white'>"
	sHTML+="<tr>"
	sHTML+="<td valign=middle onclick=\"doSelectBorderStyle(this)\" style=\"cursor:default;height:25;padding:4;border:#708090 1 solid;background-color:#f1f1f1\" onmouseover=\"doOver(this);\" onmouseout=\"doOut(this);\">"
	sHTML+="	<table id=idStyle_None name=idStyle_None style='border:none' cellpadding=0 cellspacing=0 width=100%><tr><td valign=top>No Border</td></tr></table>"
	sHTML+="</td>"
	sHTML+="</tr>"	
	for(var i=0;i<arrStyleOptions.length;i++)
		{
		sHTML+="<tr>"
		sHTML+="<td valign=top onclick=\"doSelectBorderStyle(this)\" style=\"height:25;padding:4;border:white 1 solid;\" onmouseover=\"doOver(this);\" onmouseout=\"doOut(this);\">"
		sHTML+="	<table id="+arrStyleOptions[i][0]+" name="+arrStyleOptions[i][0]+" style='"+arrStyleOptions[i][1]+"' width=100%><tr><td></td></tr></table>"
		sHTML+="</td>"
		sHTML+="</tr>"
		}
	sHTML+="</table><input type=hidden name=idSelBorderStyle value='none'>"
	sHTML+="</div>"
	document.write(sHTML)
	}
function doSelectBorderStyle(me)
	{
	oNodes=tblBorderStyle.childNodes(0).childNodes
	for(var i=0;i<oNodes.length;i++)
		{
		oNodes(i).childNodes(0).style.backgroundColor='#ffffff';
		oNodes(i).childNodes(0).style.border='#ffffff 1 solid';
		}
	me.style.backgroundColor='#f1f1f1';
	me.style.border='#708090 1px solid';
	idSelBorderStyle.value=me.childNodes(0).style.borderBottomStyle;
	}
	
/*****************************
	Border Width
*****************************/
function drawBorderWidthSelection()
	{
	arrWidthOptions=[["idWidth_1","1pt","border-bottom:black 1pt solid;height:16;"],
					["idWidth_2","2pt","border-bottom:black 2pt solid;height:16;"],
					["idWidth_3","3pt","border-bottom:black 3pt solid;height:16;"],
					["idWidth_4","4pt","border-bottom:black 4pt solid;height:16;"],
					["idWidth_5","5pt","border-bottom:black 5pt solid;height:16;"],
					["idWidth_6","6pt","border-bottom:black 6pt solid;height:16;"],
					["idWidth_7","7pt","border-bottom:black 7pt solid;height:16;"]];
	sHTML="<div style='overflow:auto;border:gray 1 solid;width:125;height:127'>"
	sHTML+="<table id=tblBorderWidth cellpadding=0 cellspacing=0 width=100% style='table-layout:fixed;background:white'>"
	for(var i=0;i<arrWidthOptions.length;i++)
		{
		if(i==0)
			{//DEFAULT
			sHTML+="<tr>"
			sHTML+="<td id="+arrWidthOptions[i][0]+" name="+arrWidthOptions[i][0]+" style=\"height:25;padding:1;border:white 1 solid;border:#708090 1 solid;background-color:#f1f1f1\" onclick=\"doSelectBorderWidth(this)\" onmouseover=\"doOver(this);\" onmouseout=\"doOut(this);\">"
			sHTML+="	<table width=100%><tr><td style=\"height:25\" >"+arrWidthOptions[i][1]+"</td><td valign=top width=100%> <table style='"+arrWidthOptions[i][2]+"' width=100%><tr><td></td></tr></table> </td></tr></table>"
			sHTML+="</td>"
			sHTML+="</tr>"
			}
		else
			{
			sHTML+="<tr>"
			sHTML+="<td id="+arrWidthOptions[i][0]+" name="+arrWidthOptions[i][0]+" style=\"height:25;padding:1;border:white 1 solid;\" onclick=\"doSelectBorderWidth(this)\" onmouseover=\"doOver(this);\" onmouseout=\"doOut(this);\">"
			sHTML+="	<table width=100%><tr><td style=\"height:25\" >"+arrWidthOptions[i][1]+"</td><td valign=top width=100%> <table style='"+arrWidthOptions[i][2]+"' width=100%><tr><td></td></tr></table> </td></tr></table>"
			sHTML+="</td>"
			sHTML+="</tr>"
			}
		}
	sHTML+="</table><input type=hidden name=idSelBorderWidth value='1pt'>"
	sHTML+="</div>"
	document.write(sHTML)
	}
function doSelectBorderWidth(me)
	{
	oNodes=tblBorderWidth.childNodes(0).childNodes
	for(var i=0;i<oNodes.length;i++)
		{
		oNodes(i).childNodes(0).style.backgroundColor='#ffffff';
		oNodes(i).childNodes(0).style.border='#ffffff 1 solid';
		}
	me.style.backgroundColor='#f1f1f1';
	me.style.border='#718191 1px solid';
	
	idSelBorderWidth.value=me.childNodes(0).childNodes(0).childNodes(0).childNodes(1).childNodes(0).style.borderBottomWidth;
	}

/*****************************
	Border Apply To
*****************************/
function drawBorderApplyToSelection()
	{
	arrApplyToOptions=[["idApplyTo_None","No Border","border/border_none.gif"],
					["idApplyTo_Outside","Outside Border","border/border_outside.gif"],
					["idApplyTo_Left","Left Border","border/border_left.gif"],
					["idApplyTo_Top","Top Border","border/border_top.gif"],
					["idApplyTo_Right","Right Border","border/border_right.gif"],
					["idApplyTo_Bottom","Bottom Border","border/border_bottom.gif"]];
	sHTML="<div style='overflow:auto;border:gray 1 solid;width:55;height:127'>"
	sHTML+="<table id=tblBorderApplyTo cellpadding=0 cellspacing=0 width=100% style='table-layout:fixed;background:white'>"
	for(var i=0;i<arrApplyToOptions.length;i++)
		{
		if(i==1)
			{//DEFAULT
			sHTML+="<tr>"
			sHTML+="<td id="+arrApplyToOptions[i][0]+" name="+arrApplyToOptions[i][0]+" valign=top style=\"height:30;padding:4;border:white 1 solid;border:#708090 1 solid;background-color:#f1f1f1\" onclick=\"doSelectBorderApplyTo(this)\" onmouseover=\"doOver(this);\" onmouseout=\"doOut(this);\">"
			sHTML+="	<img src='"+arrApplyToOptions[i][2]+"' alt='"+arrApplyToOptions[i][1]+"'>"
			sHTML+="</td>"
			sHTML+="</tr>"
			}
		else
			{
			sHTML+="<tr>"
			sHTML+="<td id="+arrApplyToOptions[i][0]+" name="+arrApplyToOptions[i][0]+" valign=top style=\"height:30;padding:4;border:white 1 solid;\" onclick=\"doSelectBorderApplyTo(this)\" onmouseover=\"doOver(this);\" onmouseout=\"doOut(this);\">"
			sHTML+="	<img src='"+arrApplyToOptions[i][2]+"' alt='"+arrApplyToOptions[i][1]+"'>"
			sHTML+="</td>"
			sHTML+="</tr>"
			}
		}
	sHTML+="</table><input type=hidden name=idSelBorderApplyTo value='Outside Border'>"
	sHTML+="</div>"

	document.write(sHTML)
	}
function doSelectBorderApplyTo(me)
	{
	oNodes=tblBorderApplyTo.childNodes(0).childNodes
	for(var i=0;i<oNodes.length;i++)
		{
		oNodes(i).childNodes(0).style.backgroundColor='#ffffff';
		oNodes(i).childNodes(0).style.border='#ffffff 1 solid';
		}
	me.style.backgroundColor='#f1f1f1';
	me.style.border='#718191 1px solid';
	
	idSelBorderApplyTo.value=me.childNodes(0).alt;
	}	
	
/*****************************
	Shading Color
*****************************/
function drawShadingColorSelection()
	{
	sHTML="<span style='background:white'><span id='idSelShadingColor' style='border:gray 1 solid;width:20;margin-right:3;'></span></span>"
	sHTML+="<INPUT type=button value='Pick' onclick=\"window.showModalDialog('colors.htm',idSelShadingColor,'dialogWidth:380px;dialogHeight:242px;edge:Raised;center:Yes;help:No;resizable:No;status:No')\">"

	document.write(sHTML)
	}
	
/*****************************
	Border Color
*****************************/
function drawBorderColorSelection()
	{
	sHTML="<span style='background:white'><span id='idSelBorderColor' style='border:gray 1 solid;width:20;margin-right:3;background-color:000000'></span></span>"
	sHTML+="<INPUT type=button value='Pick' onclick=\"window.showModalDialog('colors.htm',idSelBorderColor,'dialogWidth:380px;dialogHeight:242px;edge:Raised;center:Yes;help:No;resizable:No;status:No')\">"

	document.write(sHTML)
	}
