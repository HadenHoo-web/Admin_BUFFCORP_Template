document.onkeydown = hotkeyHandler;
function hotkeyHandler() 
{	switch ( event.keyCode )
	{	case 112:
			if ( typeof(doHelp) == 'function' )
			{	doHelp(); 
			}
			break;
		case 113:	
			if ( typeof(doSave) == 'function' )
			{	doSave(); 
			}
			break;
		case 115:
			if ( typeof(doCreate) == 'function' )
			{	doCreate(); 
			}
			break;
		case 117:
			if ( typeof(doEdit) == 'function' )
			{	doEdit(); 
			}
			break;			
		case 119:
			if ( typeof(doDelete) == 'function' )
			{	doDelete(); 
			}
			break;		
		case 121:
			if ( typeof(returnToList) == 'function' )
			{	returnToList(); 
			}
			break;
		case 123:
			if ( typeof(_F12_) == 'function' )
			{	_F12_(); 
			}
			break;			
			
	}	
}

function radioSelected( cid)
{	if ( typeof(cid) == "undefined" ) 
		return	null
	if ( typeof( cid.length ) == "undefined" )
		return (cid.checked ? cid : null)
	else
	{	for ( var i = 0;  i < cid.length; i++)
			if (cid[i].checked)
				return cid[i]
	}
	return null
}

function getObj(name)
{	if (document.getElementById) { return document.getElementById(name); }
	    else if (document.all)          { return document.all[name]; }
	    else if (document.layers)     { return document.layers[name]; }
}

function imgview(filename)
{	window.open("view_picture.htm?fn="+filename ,"ViewPic", "toolbar=no,scrollbars=no,status=no")
}
