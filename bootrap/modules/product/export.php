<?php	
	global $languageid, $template;
	$action      = mosGetParam( $_REQUEST, 'mode', '');

	if (!isset($template))
		$template = new Template();	
	
	$template->assign_vars(array(
		'ROOT'		=> $root_path,		
		'funname'	=> 'product/export', 
		'LANGUAGEID'=> $languageid,
		
	));		

	switch( $action )
	{	
		case 'info'		:	mosInfo(); break;
		case 'site'		:	mosSiteMap(); break;
		default:
			mosInvalidURL();
			exit;
	}
?>
<?php
	function mosInfo()
	{	
		global $db, $root_path, $skin, $languageid, $template, $website;
		$orderby	= mosGetParam( $_REQUEST, 'orderby', '');
		$desc		= mosGetParam( $_REQUEST, 'desc', 1);
		$web		= mosGetParam( $_REQUEST, 'web', 'http://thatlung.org');
		$product_type_id	= mosGetParam( $_REQUEST, 'product_type_id', '');
		$cond = '';
		$cond .= ($product_type_id)?" and tbl_products.product_type_id = ".$product_type_id:'';
		$desc = ($desc == 1)?' DESC':'';
		$content = "";
		$CountFile = "export.htm";
		$CF = fopen ($CountFile, "r");
		$content .= fread ($CF, filesize ($CountFile));
		fclose ($CF);
		$order = 1;
		$sql = "select * from ((tbl_products inner join tbl_product_kinds on tbl_products.product_kind_id = tbl_product_kinds.product_kind_id) inner join tbl_skin on tbl_products.skin_id = tbl_skin.skin_id) inner join tbl_product_types on tbl_products.product_type_id = tbl_product_types.product_type_id where tbl_products.soluong > 0 $cond and tbl_products.active = 1 order by tbl_product_types.priority, tbl_products.product_id $desc";
		if ( !($result = $db->sql_query($sql)) ) die ( SERVER_BUSY );
		if ( $orderby == '' )
		{
			while ( $row = $db->sql_fetchrow($result) ) 
			{
				$class  = ($order % 2 == 0)?'rowodd':'rowround';
				$product_id		= $row['product_id'];
				$product_name 	= $row['product_name'];
				$product_code	= $row['product_code'];
				$strip_product_name	= mosStrip($row['product_name']);
				$skin_id		= $row['skin_id'];
				$skin_name		= $row['skin_name'];
				$strip_skin_name= mosStrip($row['skin_name']);
				$product_kind_id= $row['product_kind_id'];
				$product_kind_name	= $row['product_kind_name'];
				$strip_product_kind_name	= mosStrip($row['product_kind_name']);
				$description	= $row['description'];
				$image1			= $row['image0'];
				$price			=	number_format($row['price'], 0, ',', '.');
				$content .= "<div id=$class><div><a href=".$web."/".$strip_product_name."_".$product_id."_detail.htm>$product_name</a></div><div id=leftimg><img src=http://bopda.net/images/product/".$image1." width=96 height=71 /><br></div><div id=righttxt><div><span class=txtprice>".$product_code." - ".$price." vnd</span></div><div><span class=txt11pt>".$description."</span></div><div><a href=".$web."/".$strip_skin_name."_".$skin_id."_skin.htm>".$skin_name."</a> |<a href=".$web."/".$strip_product_kind_name."_".$product_kind_id."_kind.htm>".$product_kind_name."</a></div></div></div>";
				$order ++;
			}
		}else
		{
			while ( $row = $db->sql_fetchrow($result) ) 
			{
				$class  = ($order % 2 == 0)?'rowodd':'rowround';
				$product_id		= $row['product_id'];
				$product_name 	= $row['product_name'];
				$product_code	= $row['product_code'];
				$strip_product_name	= mosStrip($row['product_name']);
				$skin_id		= $row['skin_id'];
				$skin_name		= $row['skin_name'];
				$strip_skin_name= mosStrip($row['skin_name']);
				$product_kind_id= $row['product_kind_id'];
				$product_kind_name	= $row['product_kind_name'];
				$strip_product_kind_name	= mosStrip($row['product_kind_name']);
				$description	= $row['description'];
				$image1			= $row['image0'];
				$price			=	number_format($row['price'], 0, ',', '.');
				$content .= '<div id='.$class.'><div>[URL='.$web.'/'.$strip_product_name.'_'.$product_id.'_detail.htm]'.$product_name.'[/URL]</div><div id=leftimg>[IMG]http://bopda.net/images/product/'.$image1.'[/IMG]<br></div><div id=righttxt><div><span class=txtprice>'.$product_code.' - '.$price.' vnd</span></div><div><span class=txt11pt>'.$description.'</span></div><div>[URL='.$web.'/'.$strip_skin_name.'_'.$skin_id.'_skin.htm]'.$skin_name.'[/URL] |[URL='.$web.'/'.$strip_product_kind_name.'_'.$product_kind_id.'_kind.htm]'.$product_kind_name.'[/URL]</div></div></div>';
				$order ++;
			}
		}
		$content .= '</div></div>';
		$template->assign_vars(array(
			'content'	=>	$content,	
			'orderby'	=>	$orderby,	
			'web'		=>	$web,	
			'product_type_id'	=>	($product_type_id)?$product_type_id:0,		
		));
		$template->set_filenames_new(array(
			'export' => 'product/export.tpl')
		);
		$template->pparse('export');
	}
	
	function mosSiteMap()
	{	
		global $db, $root_path, $skin, $languageid, $template, $website;
		$content = "";
	//uu tien kind
		$sql = "select * from tbl_product_kinds where active = 1 order by priority";
		if ( !($result = $db->sql_query($sql)) ) die ( SERVER_BUSY );
		while ( $row = $db->sql_fetchrow($result) ) 
		{
			$content .= '<url>
	<loc>http://casaukieuhung.com/'.$row['slug'].'</loc>
</url>
';
			$order ++;
		}
		$content .= '';
		$template->assign_vars(array(
			'content'	=>	$content,		
		));
		$template->set_filenames_new(array(
			'export' => 'product/sitemap.tpl')
		);
		$template->pparse('export');
	}
?>