	function InitEditor(controlname, hiddenField, scriptPath, width, height) 
	{	var oEdit0 = new InnovaEditor(controlname);
		oEdit0.scriptPath = scriptPath;
		oEdit0.width = width;
		oEdit0.height = height;
		
	// turn off assets button
		oEdit0.btnAssets = false;
		oEdit0.btnAssetManager=false;
		oEdit0.btnCharacters=false;
		oEdit0.btnTagSelector=false;
	
	//turn off internal link button
		oEdit1.btnInternalLink=false;
//		oEdit1.cmdInternalLink = "modelessDialogShow('innova/InternalLinks.asp',465,250)"
	
		oEdit0.RENDER();
		oEdit0.loadHTML(hiddenField.innerHTML);
		return oEdit0;
	}
