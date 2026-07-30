<?php
function deleteByID($tableName, $IDField, $theID)
{	
	global $db, $template;
	if ($theID == 0)
	{	mosInvalidURL();
		exit;
	}
	$sql = "delete from $tableName where $IDField = $theID";
	if ( !($result = $db->sql_query($sql)) ) 
		message_die( DATABASE_BUSY );
	if (isset($template))
		$template->assign_vars(array('MESSAGE'	=>	DELETED_SUCCESS));
	return true;
}

function mosGetItemChain($item_ID, $table, $indexField, $nameField, $parentField, $hasLanguage = 1, $link = "", $level = 0)
{	global $languageid, $db;
	$html_code = "";
	$sql = "select * from $table where $indexField =  $item_ID";

	if ( !($result = $db->sql_query($sql)) ) message_die(SERVER_BUSY);
	if ( $row = $db->sql_fetchrow($result) )
	{	if ($level != 0)
			$item_name = "<a href=\"". str_replace("{0}", $row[$indexField], $link) ."\">" . $row[$nameField] . "</a>";
		else
			$item_name = $row[$nameField];
		if ($row[$parentField] != 0)
			$html_code = mosGetItemChain($row[$parentField], $table, $indexField, $nameField, $parentField, $hasLanguage, $link, 1) . "&nbsp;&#8594;&nbsp;"  . $item_name;
		else
			$html_code = $item_name;
	}
	return $html_code;		
}

function checkDuplicate($tableName, $arrField, $IDField, $theID = 0, $update = false, $extraCondition = "")
{	
	global $db;
	$start = true;
	$condition = "";
	if (!is_array( $arrField ))
		return false;
	foreach ($arrField as $key => $val)
	{	$conj = ($start) ? "" : " or ";	
		$condition =  $condition .  $conj . $key . " = '" . $val . "'";
		$start = false;
	}

	if (($theID == '0') && (!$update))
		$sql = "select * from $tableName where ($condition)" . ((strlen($extraCondition) > 0) ? (" and " . $extraCondition) : "");
	else
		$sql = "select * from $tableName where ($condition) and ($IDField <> $theID)" . ((strlen($extraCondition) > 0) ? (" and " . $extraCondition) : "");
	if ( !($result = $db->sql_query($sql)) ) message_die( DATABASE_BUSY );
	
	return ( $row = $db->sql_fetchrow($result) );
}


define( "_MOS_NOTRIM", 0x0001 );
define( "_MOS_ALLOWHTML", 0x0002 );
function mosGetParam( &$arr, $name, $def=null, $mask=0 ) {
	$return = null;
	if (!isset($arr[$name]) || is_array($arr[$name]) || (is_string($arr[$name]) && trim($arr[$name]) == '')) {
		return $def;
	}
	if (trim((string)$arr[$name]) != '') {
		if (is_string( $arr[$name] )) {
			if (!($mask&_MOS_NOTRIM)) {
				$arr[$name] = trim( $arr[$name] );
			}
			if (!($mask&_MOS_ALLOWHTML)) {
				$arr[$name] = strip_tags( $arr[$name] );
			}
			if (!function_exists('get_magic_quotes_gpc') || !get_magic_quotes_gpc()) {
				$arr[$name] = addslashes( $arr[$name] );
			}
		}
		return $arr[$name];
	} else {
		return $def;
	}
}

function ComboFromTable($controlName, $tableName, $idField, $showField, $orderField, $defaultID, $extraName = "", $extraValue = "", $condition = "", $onChangeEvent = "", $echo = 0)
{	
	global $db;
	$code = '';
	$code .= "<select size=\"1\" name=\"" . $controlName . "\" id=\"" . $controlName . "\" value=\"".$defaultID ."\"" . (($onChangeEvent != '') ? "onChange=\"$onChangeEvent\"" : "") . ">";

	$hasOrderBy = (preg_match('/\border\s+by\b/i', $condition) === 1);
	if ($condition != "")
		$condition = " where " . $condition;
	if (strlen($orderField) != 0 && !$hasOrderBy)
		$sql = "select " .$idField. ", " . $showField. " from " . $tableName . $condition . " order by " . $orderField;
	else
		$sql = "select " .$idField. ", " . $showField. " from " .$tableName . $condition;		
	if ( !($result = $db->sql_query($sql)) )
	{	message_die(GENERAL_ERROR, 'Error in obtaining userdata', '', __LINE__, __FILE__, $sql);
	}

	if (strlen($extraName) != 0)
	{	$select = ($extraValue == $defaultID) ? "selected" : "";
		$code .= "<option value=\"" . $extraValue . "\" " . $select . ">" . $extraName . "</option>";		
	}
	while( $row = $db->sql_fetchrow($result) )
	{	$select = ($row[$idField] == $defaultID) ? "selected" : "";
		$code .= "<option value=\"".$row[$idField]."\" " . $select . ">" . $row[$showField] . "</option>";
	}
	$code .= "</select>";
	if ($echo == 1) 
		echo $code;
	else
		return $code;		
}

function mosPageBreak($tableName, $condition, $page, $items, $url) 
{	global $languageid, $db, $rec_count;
	$sql = "select count(*) as rec_count from $tableName " . ((strlen(trim((string)$condition)) > 0) ? (" where " . $condition) : "");
	if ( !($result = $db->sql_query($sql)) )
		message_die(GENERAL_ERROR, 'Error in obtaining userdata', '', __LINE__, __FILE__, $sql);
	if( $row = $db->sql_fetchrow($result) )
		$rec_count = $row['rec_count'];
	else
		$rec_count = 0;	
		
	if ($rec_count <= $items)
	{	$num_of_page = 1;
	} else
	{	$num_of_page = ceil($rec_count / $items);
		$page = ($page > $num_of_page) ? $num_of_page : (($page < 1) ? 1 : $page);
		$rec_start = ($page - 1) * $item_per_page + 1;
		$rec_end =   (($page * $items) > $rec_count) ? $rec_count : ($page * $items);
	}
	$start_page = (($page - 3) < 1) ? 1 : ($page - 3);
	$end_page   = (($start_page + 9) > $num_of_page) ? $num_of_page : ($start_page + 9);
	$returnCode = "";
	for ($iCount = $start_page; $iCount <= $end_page; $iCount++)
	{	if (strlen($returnCode) > 0)
		{	if ($iCount == $page)
				$returnCode .= " | $iCount";
			else
				$returnCode .= " | <a href=\"$url&p=$iCount\">$iCount</a>";
		} else
		{	if ($iCount == $page)
				$returnCode .= "$iCount";
			else
				$returnCode = "<a href=\"$url&p=$iCount\">$iCount</a>";		
		}
	}
	return $returnCode;
}

function mosMessagePage($err = 1)
{	global $root_path, $skin, $db; 
	$template = new Template();	
	$template->set_filenames(array(
		'page_body' => $root_path . "admintool/error/messagebody.tpl")
	);
	$sql = "select * from tbl_message where err_ID = $err";		
	if ( !($result = $db->sql_query($sql)))	return "General Error";
	if( $row = $db->sql_fetchrow($result) )
		$mess = $row['message'];	
	else
		$mess = "General Error !";	

	$template->assign_vars(array(
		'MESSAGE'	=> $mess,
		'SKIN'		=> (isset($skin)) ? $skin : 'default',
		'LANGUAGEID'=> $languageid,		
		'ROOT'		=> $root_path
	));
	$template->pparse('page_body');
}

function mosTemplateList($type, $echo = true)
{	
	global $db, $template_name, $languageid;
	$sql = "select template_name from tbl_template where type = '$type' and language_id = $languageid";
	if ( !($result = $db->sql_query($sql)) ) message_die(SERVER_BUSY);
	$html_code = '<select size="1" name="template" style="width:150" value="'.$template_name.'">';
	$html_code .= '<option value="default.tpl">default.tpl</option>';
	while( $row = $db->sql_fetchrow($result) )
		$html_code .= '<option value="'.$row['template_name'].'">'.$row['template_name'].'</option>';
	$html_code .= '</select>';
	if ($echo)
		echo $html_code;
	else
		return $html_code;
}

function mosThemeList( $echo = true )
{	global $db, $theme_name;
	$sql = "select * from tbl_theme";
	if ( !($result = $db->sql_query($sql)) ) message_die(SERVER_BUSY);
	$html_code = '<select size="1" name="theme" class="input" style="width:150" value="'.$theme_name.'">';
	if ($db->sql_numrows($result) == 0)
		$html_code .= '<option value="default.css">default.css</option>';	
	while( $row = $db->sql_fetchrow($result) )
		$html_code .= '<option value="'.$row['theme_name'].'">'.$row['theme_name'].'</option>';
	$html_code .= '</select>';
	if ($echo) 
		echo $html_code; 
	else
		return $html_code;
}

function mosChangePriority( $id, $direction = 'up', $tableName = "", $idField = "", $orderField = "", $condition = "")
{	
	global $db;
	if (strlen($condition) > 0) $condition = " and (" . $condition . ") ";
	if ($direction == 'up')
		$sql = "select a." . $orderField . " as priority_1, b." . $orderField . " as priority_2, b." . $idField . " from $tableName a, $tableName b where (a." . $idField . " = $id) and (b." . $orderField . " < a." . $orderField . ") $condition order by b." . $orderField . " desc limit 0, 1";
	else
		$sql = "select a." . $orderField . " as priority_1, b." . $orderField . " as priority_2, b." . $idField . " from $tableName a, $tableName b where (a." . $idField . " = $id) and (b." . $orderField . " > a." . $orderField . ") $condition order by b." . $orderField . " limit 0, 1";

	if ( !($result = $db->sql_query($sql)) ) message_die("Could not connect to database. Please try again !");
	if( $row = $db->sql_fetchrow($result) )
	{	$priority_1 = $row['priority_1'];
		$priority_2 = $row['priority_2'];
		$item_id	= $row[$idField];
		$sql = "update $tableName set priority = $priority_1 where $idField = $item_id";

		if ( !($result = $db->sql_query($sql)) ) message_die("Could not connect to database. Please try again !");
		$sql = "update $tableName set priority = $priority_2 where $idField = $id";

		if ( !($result = $db->sql_query($sql)) ) message_die("Could not connect to database. Please try again !");		
	}
}

function mosGetPriority($tableName, $orderField, $condition)
{	global $db;
	$sql = "select if(max($orderField) is null, 1, max($orderField) + 1) as priority from $tableName " . ((strlen($condition) > 0) ? "where" : "") . " $condition";
	if ( !($result = $db->sql_query($sql)) ) message_die("database Error");		
	if( $row = $db->sql_fetchrow($result) )
		$priority = $row['priority'];
	else
		$priority = 1;
	return $priority;
}

function mosInvalidURL($page = "err404.tpl")
{	global $template; 
	$template->set_filenames_new(array('invalid' => "errorpages/" . $page));
	$template->pparse('invalid');
}

function showErrorMessage()
{	if (isset($_GET['err']) && $_GET['err'])
{	$errorMessage = getErrorMessage($_GET['err']);
		echo "<Script Language='JavaScript'>";
		echo "  alert('".$errorMessage."')";
		echo "</Script>";
	}
}

function mosMessagePage_str($mess = "")
{	global $root_path, $skin; 
	$template = new Template();	
	$template->set_filenames(array(
		'page_body' => $root_path . "error/messagepage.tpl")
	);
	$template->assign_vars(array(
		'MESSAGE'	=> $mess,
		'LANGUAGEID'=> $languageid,		
		'ROOT'		=> $root_path
	));
	$template->pparse('page_body');
}


function getErrorMessage($errorCode)
{	global $db;
	$sql = "select message from tbl_message where errorcode = " . $errorCode;
	if ( !($result = $db->sql_query($sql)) )
	{	message_die(GENERAL_ERROR, 'Error in obtaining userdata', '', __LINE__, __FILE__, $sql);
	}
	if( $row = $db->sql_fetchrow($result) )
		return $row['message'];
	return	"General error !";
}

function checkDeleteOldFile($newFile, $oldFile, $deleteFlag, $filePath, $tableName, $arrField, $IDField, $currentID, $condition = "")
{	
	if (strlen($oldFile) == 0)
		return $newFile;
	if (strcmp($newFile, $oldFile) == 0)
		return $newFile;
		
	if ((($deleteFlag == 1) && (strlen($oldFile) > 0)) || (strlen($newFile) > 0))
	{   
		checkDelete($oldFile, $filePath, $tableName, $arrField, $IDField, $currentID, $condition);
		return $newFile;
	}
	return $oldFile;
}

function checkDelete($oldFile, $filePath, $tableName, $arrField, $IDField, $currentID, $prmCondition = "")
{	
	global $db;
	$start = true;
	$condition = "";
	foreach ($arrField as $key => $val)
	{	$conj = (($start) ? "" : " or ");	
		$condition =  $condition .  $conj . $val . " = '" . $oldFile . "'";
		$start = false;
	}
	$sql = "select * from " . $tableName . " where ((" . $condition .") and (" . $IDField . " != " . $currentID . "))" . (strlen($prmCondition) > 0 ? " and " : "") . $prmCondition; 
	
	if ( !($result = $db->sql_query($sql)) )
		message_die(GENERAL_ERROR, 'Error in obtaining userdata', '', __LINE__, __FILE__, $sql);
	if( !$db->sql_fetchrow($result) )
	{	
		if (file_exists($filePath . "/" . $oldFile))
		{	unlink ($filePath . "/" . $oldFile);}
	}
}

function getLangPath($languageid)
{	global $db;
	$sql = "select langpath from tbl_languages where language_id = " . $languageid;
	if ( !($result = $db->sql_query($sql)) )
		message_die(GENERAL_ERROR, 'Error in obtaining userdata', '', __LINE__, __FILE__, $sql);
	if ( $row = $db->sql_fetchrow($result) )
		return $row['langpath'];
	else
		return 'vietnam';	// default is 
}

function checkLanguage()
{	$languageid = 2;
	if (isset($_GET['l']) && strlen($_GET['l']) > 0)
		$languageid = $_GET['l'];
	else if (isset($_POST['l']) && strlen($_POST['l']) > 0)
		$languageid = $_POST['l'];
	else if (isset($_SESSION['language_id']))
		$languageid = $_SESSION['language_id'];
	$_SESSION['language_id'] = $languageid;
	return $languageid;			
}

function showTemplate($template_path, $handle = 'temp')
{	global $template, $root_path, $skin, $languageid;
	if (!(isset($template))) $template = new Template();	
	$template_path = str_replace("[ROOT]", $root_path, $template_path);
	$template_path = str_replace("[SKIN]", $skin, $template_path);
	$template->set_filenames(array(
		$handle => $template_path)
	);	
	$template->assign_vars(array(
		'ROOT'		 => $root_path,
		'LANGUAGEID' => $languageid,
	));
	$template->pparse($handle);
}

function isLoged()
{	if ( !isset($_SESSION['cms_loged']) || ($_SESSION['cms_loged'] != true))	
	{	return false;
	} else
	{	if (!isset($_SESSION['session_id'], $_SESSION['loginname']) || $_SESSION['session_id'] != md5($_SESSION['loginname']))
			return false;
	}
	return true;
}

function mosDateFormatCompat($format)
{
	$replace = array(
		'%Y' => 'Y',
		'%y' => 'y',
		'%m' => 'm',
		'%d' => 'd',
		'%H' => 'H',
		'%M' => 'i',
		'%S' => 's',
	);
	return strtr($format, $replace);
}

function mosFormatDate( $date, $format = null ){
	global $mosConfig_offset;
	if (!isset($mosConfig_offset)) $mosConfig_offset = 0;
	if ($format === null) $format = defined('_DATE_FORMAT_LC') ? _DATE_FORMAT_LC : '%Y-%m-%d';
	if ( $date && preg_match("/([0-9]{4})-([0-9]{2})-([0-9]{2})[ ]([0-9]{2}):([0-9]{2}):([0-9]{2})/", $date, $regs ) ) {
		$date = mktime( $regs[4], $regs[5], $regs[6], $regs[2], $regs[3], $regs[1] );
		$date = $date > -1 ? date( mosDateFormatCompat($format), $date + ($mosConfig_offset * 3600) ) : '';
	}
	return $date;
}

function mosDataCount( $tableName, $countCondition)
{	global $db;
	if (strlen($countCondition) > 0)
		$sql = "select count(*) as iCount from $tableName where $countCondition";
	else
		$sql = "select count(*) as iCount from $tableName";
	if ( !($result = $db->sql_query($sql)) ) message_die(SERVER_BUSY);
	if( $row = $db->sql_fetchrow($result) )
		return $row['iCount'];
	return 0;
}
function mosCreateThumbnail( $image_path, $thumb_path, $thumb_size_w = 150, $thumb_size_h = 100)
{	$type = PixType( $image_path );
	if ( $type == "jpeg" || $type == "jpg" )
		$srcImg = ImageCreateFromJPEG( $image_path );
	else if ( $type == "png" ) 
		$srcImg = ImageCreateFromPNG( $image_path );	
	else
		$srcImg = ImageCreateFromGIF( $image_path );	
	$width = ImageSX($srcImg);
	$height = ImageSY($srcImg);
	if (($width < $thumb_size_w) || ($height < $thumb_size_h))
	{	$off_x = ($thumb_size_w - $width) / 2;
		$off_y = ($thumb_size_h - $height) / 2;
	}
	else
	{	if ((ImageSX($srcImg) * $thumb_size_h ) >  (ImageSY($srcImg) *  $thumb_size_w ))
		{	$off_y = ($thumb_size_h - ((ImageSY($srcImg) * $thumb_size_w) / ImageSX($srcImg))) / 2;
			$off_x = 0;
		} else
		{	$off_x = ($thumb_size_w - ((ImageSX($srcImg) * $thumb_size_h) / ImageSY($srcImg))) / 2;
			$off_y = 0;		
		}
	}
	$destImg = @imagecreatetruecolor($thumb_size_w, $thumb_size_h);
	
	ImageCopyResized ( $destImg, $srcImg, $off_x, $off_y, 0, 0, $thumb_size_w - (2 * $off_x), $thumb_size_h - (2 * $off_y), ImageSX($srcImg), ImageSY($srcImg));
	if ( $type == "jpeg" || $type == "jpg" )
		ImageJPEG( $destImg, $thumb_path, 99);
	else
		ImagePNG( $destImg, $thumb_path );
	return true;
}
function encode_ip($dotquad_ip)
{	$ip_sep = explode('.', (string)$dotquad_ip);
	if (count($ip_sep) < 4) {
		$ip_sep = array(0, 0, 0, 0);
	}
	return sprintf('%02x%02x%02x%02x', (int)$ip_sep[0], (int)$ip_sep[1], (int)$ip_sep[2], (int)$ip_sep[3]);
}

function decode_ip($int_ip)
{	$hexipbang = explode('.', chunk_split($int_ip, 2, '.'));
	if (count($hexipbang) < 4) {
		$hexipbang = array(0, 0, 0, 0);
	}
	return hexdec($hexipbang[0]). '.' . hexdec($hexipbang[1]) . '.' . hexdec($hexipbang[2]) . '.' . hexdec($hexipbang[3]);
}

function mosUploadImage($imgDir, $imageField, $image_name)
{	
	if (!is_dir($imgDir)) 
		mosmkdir($imgDir, 0777);
	$image = isset($_FILES[$imageField]['name']) ? $_FILES[$imageField]['name'] : ""; 
	//if( $image_name != NULL ){echo $image_name;exit;}
	if ( $image )
	{
		$type = PixType( $image );
		if(stristr('jpg,gif,png,doc,xle', $type) === FALSE) {
    		echo "$image khong hop le ! chi cap nhat cac loai file jpg, gif, png, doc, xls !";exit;
  		}
		//$image = substr(md5(date('H:m:s, d-m-y')),0, 5).'_'.$image; old
		$image = substr($image,0,-4).'-'.substr(md5(date('H:m:s, d-m-y')),0, 3).substr($image,-4);
		if($image_name != NULL )$image = $image_name;

		if (isset($_FILES[$imageField]['size']) && $_FILES[$imageField]['size'] > 0)
		{	
			if (!isset($_FILES[$imageField]['tmp_name']) || !move_uploaded_file($_FILES[$imageField]['tmp_name'], $imgDir . $image)) 
				$image = "";
		} else
		{
			$image = "";	
		}	
	}
	
	return $image;
}

function message_die($msg_code, $msg_text = '', $msg_title = '', $err_line = '', $err_file = '', $sql = '')
{	global $db, $root_path, $adminLanguage;
	$sql_store = $sql;
	$debug_text = '';
	$sql_error = array('code' => '', 'message' => '');
	
	if (  $msg_code == GENERAL_ERROR || $msg_code == CRITICAL_ERROR ) 
	{	$sql_error = (isset($db) && is_object($db)) ? $db->sql_error() : array('code' => '', 'message' => '');
		if ( $sql_error['message'] != '' )
			$debug_text .= '<br /><br />SQL Error : ' . $sql_error['code'] . ' ' . $sql_error['message'];

		if ( $sql_store != '' )
			$debug_text .= "<br /><br />$sql_store";

		if ( $err_line != '' && $err_file != '' )
			$debug_text .= '</br /><br />Line : ' . $err_line . '<br />File : ' . $err_file;
	}
	switch($msg_code)
	{	case GENERAL_MESSAGE:
			if ( $msg_title == '' )
				$msg_title = $lang['Information'];
			break;

		case CRITICAL_MESSAGE:
			if ( $msg_title == '' )
				$msg_title = $lang['Critical_Information'];
			break;

		case GENERAL_ERROR:
			if ( $msg_text == '' )
				$msg_text = $lang['An_error_occured'];

			if ( $msg_title == '' )
				$msg_title = $lang['General_Error'];
			break;

		case CRITICAL_ERROR:
			if ( $msg_text == '' )
				$msg_text = $lang['A_critical_error'];

			if ( $msg_title == '' )
				$msg_title = 'Error : <b>' . $lang['Critical_Error'] . '</b>';

			break;
	}

	if ( $msg_code != CRITICAL_ERROR )
	{	if ( !empty($lang[$msg_text]) )
		{	$msg_text = $lang[$msg_text];
		}
		}

	mosLogApplicationError($msg_code, $msg_text, $msg_title, $err_line, $err_file, $sql_store, $sql_error);
	echo "<html>\n<body>\n" . $msg_title . "\n<br /><br />\n" . $msg_text . "</body>\n</html>";
	echo "<html>\n<body>\n" . $debug_text . "</body>\n</html>";	
	exit;
}

function mosLogApplicationError($msg_code, $msg_text = '', $msg_title = '', $err_line = '', $err_file = '', $sql = '', $sql_error = array())
{
	$logDir = dirname(__DIR__) . '/logs';
	if (!is_dir($logDir)) {
		@mkdir($logDir, 0775, true);
	}
	$requestUri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
	$remoteAddr = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
	$errorCode = isset($sql_error['code']) ? $sql_error['code'] : '';
	$errorMessage = isset($sql_error['message']) ? $sql_error['message'] : '';
	$line = '[' . date('Y-m-d H:i:s') . ']'
		. ' ip=' . $remoteAddr
		. ' uri=' . $requestUri
		. ' msg_code=' . $msg_code
		. ' msg_title=' . $msg_title
		. ' msg_text=' . $msg_text
		. ' sql_error_code=' . $errorCode
		. ' sql_error_message=' . $errorMessage
		. ' file=' . $err_file
		. ' line=' . $err_line
		. ' sql=' . str_replace(array("\r", "\n"), ' ', $sql)
		. PHP_EOL;
	@file_put_contents($logDir . '/php_errors.log', $line, FILE_APPEND);
}

//------------------------------------------------------------------------------------

function checkLogin()
{	global $root_path;
	if (!isLoged())
		header("Location: index.html");
}

function mosSetRecord(&$template, &$rec_count, &$item_per_page, &$page, $link) 
{	global $languageid;
	if ($rec_count == 0)
	{	$template->assign_vars(array(
		'item_per_page'	=> $item_per_page,
		'page'			=> '1',
		'rec_count'		=> '0',		
		'rec_start'		=> '0',
		'rec_end'		=> '0'));
	} else
	{	$num_of_page = ceil($rec_count / $item_per_page);
		$page = ($page > $num_of_page) ? $num_of_page : (($page < 1) ? 1 : $page);
		$rec_end = (($page * $item_per_page) > $rec_count) ? $rec_count : ($page * $item_per_page);

		if ($page > 1)
		{	$top_start  = '<a href="?'.$link.'&l=' . $languageid . '&items=' . $item_per_page . '&p=1">';
			$top_end 	= '</a>';
			$prev_start = '<a href="?'.$link.'&l=' . $languageid . '&items=' . $item_per_page . '&p=' . ($page - 1) . '">';
			$prev_end 	= '</a>';
		}
		if ($page < $num_of_page)
		{	$next_start = '<a href="?'.$link.'&l=' . $languageid . '&items=' . $item_per_page . '&p=' . $num_of_page . '">';
			$next_end 	= '</a>';
			$last_start = '<a href="?'.$link.'&l=' . $languageid . '&items=' . $item_per_page . '&p=' . ($page + 1) . '">';
			$last_end 	= '</a>';
		}
		
		$template->assign_vars(array(
			'item_per_page'	=> $item_per_page,
			'page'			=> $page,
			'rec_count'		=> $rec_count,		
			'rec_start'		=> ($page - 1) * $item_per_page + 1,
			'rec_end'		=> $rec_end,
			'top_start'		=> $top_start,
			'top_end'		=> $top_end,
			'prev_start'	=> $prev_start,
			'prev_end'		=> $prev_end,
			'next_start'	=> $next_start,
			'next_end'		=> $next_end,
			'last_start'	=> $last_start,
			'last_end'		=> $last_end
			));		
	}
}

function ThemeList()
{	global $db, $theme_name;
	$sql = "select * from tbl_theme";
	if ( !($result = $db->sql_query($sql)) )	message_die("SQL error.");
	$html_code = '<select size="1" name="theme_name" class="input" style="width:150" value="'.$theme_name.'">';
	while( $row = $db->sql_fetchrow($result) )
		$html_code .= '<option value="'.$row['theme_name'].'">'.$row['theme_name'].'</option>';
	$html_code .= '</select>';
	echo $html_code;	
}

function TemplateList($type)
{	global $db, $template_name;
	$sql = "select template_name from tbl_template where type = '$type'";
	if ( !($result = $db->sql_query($sql)) ) message_die("SQL error.");
	$html_code = '<select size="1" name="template_name" class="input" style="width:150" value="'.$template_name.'">';
	while( $row = $db->sql_fetchrow($result) )
		$html_code .= '<option value="'.$row['template_name'].'">'.$row['template_name'].'</option>';
	$html_code .= '</select>';
	echo $html_code;	
}

function PixType($pixfilename) {
	return substr(strrchr(strtolower($pixfilename),"."),1);
}

function mosCreateBannerThumbnail( $image_path, $thumb_path, $thumb_h = 75)
{	$type = PixType( $image_path );	
	if ( $type == "jpeg" || $type == "jpg" )
		$srcImg = ImageCreateFromJPEG( $image_path );
	else if ( $type == "png" ) 
		$srcImg = ImageCreateFromPNG( $image_path );	
	else if ( $type == "gif" ) 
		$srcImg = ImageCreateFromGIF( $image_path );
	else
		return false;
	$img_w = ImageSX( $srcImg );
	$img_h = ImageSY( $srcImg );

	$thumb_w = round($img_w * $thumb_h / $img_h);
	$destImg = @imagecreatetruecolor($thumb_w, $thumb_h);
	ImageCopyResized ( $destImg, $srcImg, 0, 0, 0, 0, $thumb_w, $thumb_h, $img_w, $img_h);
	if ( $type == "jpeg" || $type == "jpg" )
		ImageJPEG( $destImg, $thumb_path, 99);
	else if ( $type == "png" )
		ImagePNG( $destImg, $thumb_path );
	return true;
}

function mosOurFormatDate( $date, $format = "DMY" )
{	$strReturn = "";
	if ( $date && preg_match("/([0-9]{4})-([0-9]{2})-([0-9]{2})[ ]([0-9]{2}):([0-9]{2}):([0-9]{2})/", $date, $regs ) ) 
	{	switch( $format )
		{	case "DMY":	return ($regs[3] . '-' . $regs[2] . '-' . $regs[1]); break;
			case "MDY":	return ($regs[2] . '-' . $regs[3] . '-' . $regs[1]); break;
			case "YMD":	return ($regs[1] . '-' . $regs[2] . '-' . $regs[3]); break;
			case "YDM":	return ($regs[1] . '-' . $regs[3] . '-' . $regs[2]); break;
			case "HDMY":return ($regs[4] . ':' . $regs[5] . ' ' . $regs[3] . '-' . $regs[2] . '-' . $regs[1]); break;

		}
	}
	return $date;
}

function mosConvertDate($dateValue, $type)
{	$date_sep = explode('-', $dateValue);
	switch($type)
	{	case 'DMY':
			return ($date_sep[2] . '-' . $date_sep[1] . '-' . $date_sep[0]); break;
		case 'MDY':
			return ($date_sep[2] . '-' . $date_sep[0] . '-' . $date_sep[1]); break;
		case 'YMD':
			return ($date_sep[0] . '-' . $date_sep[1] . '-' . $date_sep[2]); break;
		case 'YDM':
			return ($date_sep[0] . '-' . $date_sep[2] . '-' . $date_sep[1]); break;
	}
}

function mosDBDate($dateValue, $type)
{	$date_sep = explode('-', $dateValue);
	switch($type)
	{	case 'DMY':
			return ($date_sep[0] . '-' . $date_sep[1] . '-' . $date_sep[2]); break;
		case 'MDY':
			return ($date_sep[1] . '-' . $date_sep[2] . '-' . $date_sep[0]); break;
		case 'YMD':
			return ($date_sep[2] . '-' . $date_sep[1] . '-' . $date_sep[0]); break;
		case 'YDM':
			return ($date_sep[2] . '-' . $date_sep[0] . '-' . $date_sep[1]); break;
	}
}

function mosMadeSearchCondition($keyword, $arrFieldList)
{	$start = true;	$condition = "";
	foreach ($arrFieldList as $key => $val)
	{	$conj = (($start) ? "" : " or ");	
		$condition =  $condition .  $conj . "(" . $val . " like '%" . $keyword . "%')";
		$start = false;
	}
	return "(" . $condition . ")";
}

function mosCopyFile($prmOldFile, $prmNewFile)
{	if (!file_exists($prmOldFile))
		return false;
	$path_parts = pathinfo($prmNewFile);
	if (!is_dir($path_parts['dirname']))
		mkdir($path_parts['dirname'], 0666);
	return copy($prmOldFile, $prmNewFile);
}

function mosDeleteDirectory($pathName)
{	if (!is_dir($pathName))
		return false;
	if (mosEmptyDirectory($pathName))
		return rmdir($pathName);
}

function mosEmptyDirectory($pathName)
{	if (!is_dir($pathName))
		return false;
	if ($dh = opendir($pathName)) 
	{	while (($file = readdir($dh)) !== false) 
		{	if (is_file($pathName . "/" . $file))
				unlink($pathName . "/" . $file);
		}
        closedir($dh);
    }
	return true;
}

function mosmkdir($pathName, $mode)
{	$oldumask = umask(0);
	if (is_dir($pathName))
		return true;
	$path_parts = pathinfo($pathName);
	if (!is_dir($path_parts['dirname']))
	{	if (mosmkdir($path_parts['dirname'], $mode))
			mkdir($pathName, 0777);			
	} else
		mkdir($pathName, 0777);
	umask($oldumask);
}



function mosFileList($cat_id)
{	global $db;
	$_htmlCode = "";
	$role=getRole("and");
	$sql = "select page_id, title from tbl_pages where cat_id = $cat_id $role order by created_date desc";
	if ( !($result = $db->sql_query($sql)))	message_die(SERVER_BUSY);
	while( $row = $db->sql_fetchrow($result) )
	{	$page_id 	= $row['page_id'];
		$title		= $row['title'];
		$_htmlCode	.= (", [2, $page_id, '$title']");
	}
	return $_htmlCode;
}

	function getRole($prefix = "", $alias="")
	{	$Condition = "";
		$can_create  = isset($_SESSION['can_create']) ? $_SESSION['can_create'] : 0;
		$can_edit 	 = isset($_SESSION['can_edit']) ? $_SESSION['can_edit'] : 0;
		$can_approve = isset($_SESSION['can_approve']) ? $_SESSION['can_approve'] : 0;
		$can_publish = isset($_SESSION['can_publish']) ? $_SESSION['can_publish'] : 0;
		$can_delete  = isset($_SESSION['can_delete']) ? $_SESSION['can_delete'] : 0;
		$membername  = isset($_SESSION['membername']) ? $_SESSION['membername'] : "";	
		if ($can_create == 1)
		{	if ($membername == 'administrator')
				$Condition = "(status_id = 1)";
			else			
				$Condition = "((status_id = 1) and (" . $alias . "created_by = '$membername'))";
		}
		if ($can_edit == 1)
			$Condition = ($Condition != '') ? "($Condition OR (status_id = 2))" : "(status_id = 2)";
		if ($can_approve == 1)
			$Condition = ($Condition != '') ? "($Condition OR (status_id = 3))" : "(status_id = 3)";
		if ($can_publish == 1)
			$Condition = ($Condition != '') ? "($Condition OR (status_id >= 4))" : "(status_id >= 4)";		
		return ($Condition == "") ? "" : $prefix . $Condition;
	}

function hasRight($function)
{	return true;
}

function mosNavigation($cat_id = 0, $cat_name = "Root")
{	global $db, $languageid;
	
	if ($cat_id == 0)
	{	$sql = "select cat_id, cat_name from tbl_page_categories where parent_id = 0 and language_id = $languageid order by priority";
		if ( !($result = $db->sql_query($sql)))	message_die(SERVER_BUSY);
		if ( $row = $db->sql_fetchrow($result) )
			$cat_id = $row['cat_id'];
			$cat_name = $row['cat_name'];
	}
	$returnValue = "[1, $cat_id, '$cat_name'\r\n";
	//$returnValue .= mosFileList($cat_id);	
	$sql = "select cat_id, cat_name from tbl_page_categories where parent_id = $cat_id and language_id = $languageid order by priority";
	if ( !($result = $db->sql_query($sql)))	message_die(SERVER_BUSY);
	while( $row = $db->sql_fetchrow($result) )
		$returnValue .= (", " . mosNavigation($row['cat_id'], $row['cat_name']));
	return $returnValue. "]";	
}

function mosGetName($tbl,$id_name,$id,$name)
{	
	global $db, $template_name, $languageid;
	
	$sql = "select * from $tbl where $id_name=$id";

	if ( !($result = $db->sql_query($sql)) ) message_die(SERVER_BUSY);
	$row = $db->sql_fetchrow($result);

	return $row[$name];
}
function mosFunctionMenu($fun_id = 0, $fun_name = "Root")
{
	global $db;
	$returnValue = "";

	// active menu: ưu tiên từ URL, rồi tới session
	if (isset($_REQUEST['menu']) && (int)$_REQUEST['menu'] > 0) {
		$_SESSION['active_fun_id'] = (int)$_REQUEST['menu'];
	}
	$active = isset($_SESSION['active_fun_id']) ? (int)$_SESSION['active_fun_id'] : 0;

	if ($fun_id == 0)
	{
		$member_id = isset($_SESSION["login_id"]) ? (int)$_SESSION["login_id"] : 0;
		$loginname = isset($_SESSION["loginname"]) ? $_SESSION["loginname"] : "";
		if ($loginname != 'administrator')
			$sql = "select a.* from tbl_function_menu a, tbl_permission b
			        where a.code = b.code and b.member_id = $member_id and parent_id = 0
			        order by priority";
		else
			$sql = "select * from tbl_function_menu where parent_id = 0 order by priority";

		if (!($result = $db->sql_query($sql))) message_die(SERVER_BUSY);

		while ($row = $db->sql_fetchrow($result))
		{
			$fid = (int)$row['fun_id'];

			// Nếu đã có active -> dùng active. Nếu chưa có -> fallback default=1
			$isActive = ($active > 0) ? ($fid === $active) : ((int)$row['default'] === 1);

			$classname  = $isActive ? "mainrow" : "normalrow";
			$defaultRow = $isActive ? "staticpages" : "";

			$imageDir = "templates/" . $GLOBALS['skin'] . "/images/menu/";
			$image = ($row['image'] == '') ? '' : '<img border="0" src="' . $imageDir . $row['image'] . '" align="absmiddle" >';

			$returnValue .= "<tr><td valign='top' id='$defaultRow' class='$classname' OnClick='changeClass(this)'><div class='header'>";
			$returnValue .= $image . $row['fun_name'];
			$returnValue .= mosFunctionDetail($fid);
		}
	}

	return $returnValue;
}
function mosFunctionDetail($fun_id)
{
	global $db, $languageid, $skin;

	$parent_id = (int)$fun_id;
	$member_id = isset($_SESSION["login_id"]) ? (int)$_SESSION["login_id"] : 0;
	$loginname = isset($_SESSION["loginname"]) ? $_SESSION["loginname"] : "";

	if ($loginname != 'administrator')
		$sql = "select a.* from tbl_function_menu a, tbl_permission b
		        where a.code = b.code and b.member_id = $member_id and parent_id = $parent_id
		        order by priority";
	else
		$sql = "select * from tbl_function_menu where parent_id = $parent_id order by priority";

	$returnValue = "</div><div class='children'>";

	if (!($result = $db->sql_query($sql))) message_die(SERVER_BUSY);

	while ($row = $db->sql_fetchrow($result))
	{
		$link = trim((string)$row['link']);

		// thêm menu=parent_id để nhớ menu đang mở
		if (strpos($link, 'menu=') === false) {
			$link .= (strpos($link, '?') === false ? '?' : '&') . 'menu=' . $parent_id;
		}

		// add language
		$link .= (strpos($link, '?') === false ? '?' : '&') . 'l=' . (int)$languageid;

		$fun_name = isset($row['fun_name']) ? $row['fun_name'] : "";
		$image = isset($row['image']) ? $row['image'] : "";

		$returnValue .= '<a target="main" href="' . htmlspecialchars($link) . '">';
		if ($image != '') {
			$returnValue .= '<img src="templates/' . $skin . '/images/menu/' . htmlspecialchars($image) . '" border="0" align="absmiddle" /> <span>';
		} else {
			$returnValue .= '<span>';
		}
		$returnValue .= htmlspecialchars($fun_name) . '</span></a>';
	}

	$returnValue .= "</div> </td></tr>";
	return $returnValue;
}
function convert($str)
{
$a=array(á, à, ã, ả, ạ, â, ấ, ầ, ẩ, ẫ, ậ, ă, ắ, ằ, ẵ, ẳ, ặ, Á, À, Ã, Ả, Ạ, Â, Ấ, Ầ, Ẩ, Ẫ, Ậ, Ă, Ắ, Ằ, Ẵ, Ẳ, Ặ);
$e=array(é, è, ẽ, ẻ, ẹ, ê, ế, ề, ễ, ể, ệ, É, È, Ẽ, Ẻ, Ẹ, Ê, Ề, Ế, Ễ, Ể, Ệ);
$o=array(ó, ò, õ, ỏ, ọ, ô, ố, ồ, ỗ, ổ, ộ, ơ, ớ, ờ, ỡ, ở, ợ, Ô, Ố, Ồ, Ỗ, Ổ, Ộ, Ơ, Ớ, Ờ, Ỡ, Ở, Ợ, Ó, Ò, Õ, Ỏ, Ọ);
$i= array(í, ì, ĩ, ỉ , ị,Í, Ì, Ĩ, Ỉ , Ị);
$y=array(ý, ỳ, ỹ, ỷ, ỵ, Ý, Ỳ, Ỹ, Ỷ, Ỵ);
$u=array(ú, ù, ũ, ủ, ụ, ư, ứ, ừ, ữ, ử, ự, Ú, Ù, Ũ, Ủ, Ụ, Ư, Ứ, Ừ, Ữ, Ử, Ự);
$d=array(đ,Đ);

$str = str_replace($a, "a", $str);
$str = str_replace($i, "i", $str);
$str = str_replace($e, "e", $str);
$str = str_replace($o, "o", $str);
$str = str_replace($u, "u", $str);
$str = str_replace($y, "y", $str);
$str = str_replace($d, "d", $str);
$str = str_replace(",", "", $str);
$str = str_replace("'", "", $str);
$char_sp=Array('“','?',':',',','"','/','\\','+','=','.','~','!','@','#','$','%','^','&','*','(',')','”','_');
$str = str_replace($char_sp, '', $str);
$str = strtolower($str);
return $str;	
}
function mosStrip($str = '')
{
	global $num_url;
	$num_url = 100;
	return str_replace(" ","-", convert(getFirstNCharacters($str,$num_url)));
}
function getFirstNCharacters($str1, $n)
{	if (strlen($str1) <= $n)
		return $str1;
	$temp = substr($str1, 0, $n);
	$len = strrpos($temp, " "); 
	return rtrim(substr($temp, 0, $len)) . "...";
}
?>
