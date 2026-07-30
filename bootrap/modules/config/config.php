<?php
	global $root_path,$languageid, $template, $languageid;
	$action      = mosGetParam( $_REQUEST, 'mode', '');			
	
	if (!isset($template))
		$template = new Template();
	$template->assign_vars(array(
		'ROOT'		=> $root_path,		
		'funname'	=> 'config/config',
		'LANGUAGEID'=> $languageid,		
	));		
	switch( $action )
	{	
		case 'list':		mosInfo(); break;
		case 'save':		mosSave(); break;
		case 'expKH':		mosExpKH(); break;
		default:
			mosInvalidURL();
			exit;
	}
?>
<?php
function mosSave()
{	
	global $db, $root_path, $skin, $template;
	$languageid		= mosGetParam( $_REQUEST, 'languageid', 0 );
	$config_id		= mosGetParam( $_REQUEST, 'config_id', 0 );
	$company_name	= mosGetParam( $_REQUEST, 'company_name', '' );
	$address		= mosGetParam( $_REQUEST, 'address', '' );
	$phone			= mosGetParam( $_REQUEST, 'phone', '' );
	$fax			= mosGetParam( $_REQUEST, 'fax', '' );
	$email			= mosGetParam( $_REQUEST, 'email', '' );
	$website		= mosGetParam( $_REQUEST, 'website', '' );
	$website_name	= mosGetParam( $_REQUEST, 'website_name', '' );
	$url			= mosGetParam( $_REQUEST, 'url', '' );
	$email_admin	= mosGetParam( $_REQUEST, 'email_admin', '' );
	$skin			= mosGetParam( $_REQUEST, 'skin', 'default' );
	$maincss		= mosGetParam( $_REQUEST, 'maincss', 'default' );
	$IMG_PATH		= mosGetParam( $_REQUEST, 'IMG_PATH', 'images' );
	$IMGPATH		= mosGetParam( $_REQUEST, 'IMGPATH', '' );
	$TEMPLATE_PATH	= mosGetParam( $_REQUEST, 'TEMPLATE_PATH', '' );
	
	$cat_bopda		= mosGetParam( $_REQUEST, 'cat_bopda', 0 );
	$num_url		= mosGetParam( $_REQUEST, 'num_url', 0 );
	$cat_hanghieu	= mosGetParam( $_REQUEST, 'cat_hanghieu', 0 );
	$cat_khachhang	= mosGetParam( $_REQUEST, 'cat_khachhang', 0 );
	$cat_thugian	= mosGetParam( $_REQUEST, 'cat_thugian', 0 );
	$news_supply	= mosGetParam( $_REQUEST, 'news_supply', 0 );
	$news			= mosGetParam( $_REQUEST, 'news', 0 );
	
	$default_title		= mosGetParam( $_REQUEST, 'default_title', '' );
	$default_keyword	= mosGetParam( $_REQUEST, 'default_keyword', '' );
	$default_description= mosGetParam( $_REQUEST, 'default_description', '' );
	
	$law_link		= mosGetParam( $_REQUEST, 'law_link', '' );
	$first_news		= mosGetParam( $_REQUEST, 'first_news', '' );
	$second_news	= mosGetParam( $_REQUEST, 'second_news', '' );
	
	$langname=getLangPath($languageid);
	if(strtolower($langname)=="vietnam")
		$fileconfig="../hk_config_vn.ini";
	if(strtolower($langname)=="english")
		$fileconfig="../hk_config_eng.ini";
	writeFile($fileconfig,"company_name", $company_name);
	writeFile($fileconfig,"address", $address);
	writeFile($fileconfig,"phone", $phone);
	writeFile($fileconfig,"fax", $fax);
	writeFile($fileconfig,"email", $email);
	writeFile($fileconfig,"website", $website);
	writeFile($fileconfig,"website_name", $website_name);
	writeFile($fileconfig,"url", $url);
	writeFile($fileconfig,"email_admin", $email_admin);
	writeFile($fileconfig,"skin", $skin);
	writeFile($fileconfig,"maincss", $maincss);
	writeFile($fileconfig,"IMG_PATH", $IMG_PATH);
	writeFile($fileconfig,"IMGPATH", $IMGPATH);
	writeFile($fileconfig,"TEMPLATE_PATH", $TEMPLATE_PATH);
	
	writeFile($fileconfig,"cat_bopda", $cat_bopda);
	writeFile($fileconfig,"num_url", $num_url);
	writeFile($fileconfig,"cat_hanghieu", $cat_hanghieu);
	writeFile($fileconfig,"cat_khachhang", $cat_khachhang);
	writeFile($fileconfig,"cat_thugian", $cat_thugian);
	writeFile($fileconfig,"news_supply", $news_supply);
	writeFile($fileconfig,"news", $news);
	
	writeFile($fileconfig,"default_title", $default_title);
	writeFile($fileconfig,"default_keyword", $default_keyword);
	writeFile($fileconfig,"default_description", $default_description);
	
	writeFile($fileconfig,"law_link", $law_link);
	writeFile($fileconfig,"first_news", $first_news);
	writeFile($fileconfig,"second_news", $second_news);
	
	$template->assign_vars(array('MESSAGE'	=>	UPDATE_SUCCESS));	
	mosInfo();					
}
//----------------------------------------------------------------------------------------------
	
function writeFile($filePath, $key, $value)
{	global $db, $template,  $root_path, $languageid;
	if (file_exists( $filePath)) 
	{	$ini_config = file_get_contents( $filePath); 
		$lines = explode("\n", $ini_config); 
		$line_count = sizeof($lines); 
		$have=0;
		for ($i = 0; $i < $line_count; $i++) 
		{ 	if (preg_match('#(.*?)\s*=\s*(.*)#', $lines[$i], $m)) 
			{ 	if($m[1] == $key)
				{	$have=1;					
					$text .= ($key . " = " . $value . "\n");
				} else
					$text .= $lines[$i] . "\n";
			}
		}
		if ($have == 0) 
			$text .= ($key . " = " . $value . "\n");

		$filename = $filePath;
		if (is_writable($filename))
		{	
			if ($handle = fopen($filename,'w')) 
			{	if (fwrite($handle, $text) == TRUE) 
				{	fclose($handle);
					return;
				}
			}
		}
	}
	$template->assign_var('MESSAGE', WRITE_TO_FILE_ERROR);
}
function mosInfo()
{	
	global $db, $root_path, $skin, $template, $languageid;
	if ( $languageid == 0 )	$languageid = mosGetParam( $_REQUEST, 'l', 2 );
	$langpath   = getLangPath($languageid);
	
	if(strtolower($langpath)=="vietnam"){
		$fileconfig="../hk_config_vn.ini";
	}
	else if(strtolower($langpath)=="english"){
		$fileconfig="../hk_config_eng.ini";
	}		

	if (file_exists($fileconfig))
	{	$ini_config = file_get_contents($fileconfig);
		$lines 		= explode("\n", $ini_config);
		$line_count = sizeof($lines);
		for ($i = 0; $i < $line_count; $i++)
			{	if (preg_match('#(.*?)\s*=\s*(.*)#', $lines[$i], $m))
				{	$GLOBALS[$m[1]] = trim($m[2]);
				}
			}		
	}
	$template->assign_vars( array(
		'skin'			=> $skin,
		'maincss'		=>	$maincss,
		'company_name'	=>	$company_name,
		'address'		=>	$address,
		'phone'			=>	$phone,
		'fax'			=>	$fax,
		'email'			=>	$email,
		'website'		=>	$website,
		'website_name'	=>	$website_name,
		'url'			=>	$url,
		'email_admin'	=>	$email_admin,
		'IMG_PATH'		=>	$IMG_PATH,
		'IMGPATH'		=>	$IMGPATH,
		'TEMPLATE_PATH'	=>	$TEMPLATE_PATH,
		
		'cat_bopda'		=>	$cat_bopda,
		'num_url'		=>	$num_url,
		'cat_hanghieu'	=>	$cat_hanghieu,
		'cat_khachhang'	=>	$cat_khachhang,
		'cat_thugian'	=>	$cat_thugian,
		'news_supply'	=>	$news_supply,
		'news'			=>	$news,
		
		'default_title'	=>	$default_title,
		'default_keyword'	=>	$default_keyword,
		'default_description'	=>	$default_description,
		
		'law_link'		=>	$law_link,
		'first_news'	=>	$first_news,
		'second_news'	=>	$second_news,
	));

	$template->set_filenames_new(array(
		'config' => 'config/config.tpl')
	);	
	$template->pparse('config');
}
?>
