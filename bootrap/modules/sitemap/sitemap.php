<?php	
	global $languageid, $template;
	$action      = mosGetParam( $_REQUEST, 'mode', '');

	if (!isset($template))
		$template = new Template();	
	
	$template->assign_vars(array(
		'ROOT'		=> $root_path,		
		'funname'	=> 'sitemap/sitemap',
		'LANGUAGEID'=> $languageid,
		
	));		

	switch( $action )
	{	
		case 'info'	:	mosInfo(); break;
		case 'save'	:	mosSave(); break;
		case 'update'	:	mosUpdate(); break;
		default:
			mosInvalidURL();
			exit;
	}
?>

<?php
function mosSave()
	{		
		global $db, $root_path, $skin, $languageid, $template;
		$noidung		= mosGetParam( $_REQUEST, 'noidung', '' );
		$noidung		= mosGetParam( $_REQUEST, 'noidung', '', 0x0003);	
		
		$filexml = "../sitemap.xml";
		$handle = @fopen($filexml, "w");
		echo $noidung;
		$numbytes = @fwrite($handle,$noidung);
		fclose($handle);

		$template->assign_vars(array('MESSAGE'	=>	SAVE_SUCCESS));
		mosInfo();
	}
function mosUpdate()
	{		
		global $db, $root_path, $skin, $languageid, $template;
		
		$sitemap = '<?xml version="1.0" encoding="UTF-8"?>
<urlset
      xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
      xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
      xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
      http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">

   <url>

      <loc>https://casauhoaca.com/</loc>

      <lastmod>2018-10-19T08:25:58+00:00</lastmod>

      <changefreq>daily</changefreq>

      <priority>1</priority>

   </url>';
   
   // sitemap product_cat 1
	$cat_product = "";
	$sql = "select * from tbl_product_types where product_type_id NOT IN (29, 67, 68) and slug != '' and active = 1 order by last_modified DESC, priority";
	if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
	while( $row = $db->sql_fetchrow($result) )
	{
		$cat_product .= "
   <url>
   
   <loc>https://casauhoaca.com/".$row['slug'].".htm</loc>
   
   <lastmod>".substr($row['last_modified'],0,10).'T'.substr($row['last_modified'],11,8).'+00:00'."</lastmod>
   
   <changefreq>weekly</changefreq>
   
   <priority>0.80</priority>
   
   </url>";
	}
	$sitemap .= $cat_product;
	
	// sitemap page_cat 2
	$page_cat = "";
	$sql = "select * from tbl_page_categories where slug != '' and visible = 1 and `lock` !=1 order by priority";
	if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
	while( $row = $db->sql_fetchrow($result) )
	{
		$page_cat .= "
   <url>
   
   <loc>https://casauhoaca.com/".$row['slug'].".htm</loc>
   
   <lastmod>".substr($row['last_modified'],0,10).'T'.substr($row['last_modified'],11,8).'+00:00'."</lastmod>
   
   <changefreq>weekly</changefreq>
   
   <priority>0.80</priority>
   
   </url>";
	}
   $sitemap .= $page_cat;
   
   // sitemap pages 3
	$page = "";
	$sql = "select * from tbl_pages where slug != '' and homeshow = 1 and noindex != 1 order by priority";
	if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
	while( $row = $db->sql_fetchrow($result) )
	{
		$page .= "
   <url>
   
   <loc>https://casauhoaca.com/".$row['slug'].".htm</loc>
   
   <lastmod>".substr($row['last_modified'],0,10).'T'.substr($row['last_modified'],11,8).'+00:00'."</lastmod>
   
   <changefreq>weekly</changefreq>
   
   <priority>0.80</priority>
   
   </url>";
	}
   $sitemap .= $page;
   
   // sitemap products 4
	$product = "";
	$sql = "select * from tbl_products where slug != '' and active = 1 and last_modified IS not NULL order by last_modified DESC";
	$sql = "SELECT * FROM tbl_products WHERE slug != '' AND product_type_id IN (SELECT product_type_id FROM tbl_product_types 
WHERE product_type_id NOT IN (29, 67, 68) AND active = 1) AND active = 1 AND last_modified IS NOT NULL ORDER BY last_modified DESC";echo $sql;
	if ( !($result = $db->sql_query($sql)) ) message_die( SERVER_BUSY );
	while( $row = $db->sql_fetchrow($result) )
	{
		$product .= "
   <url>
   
   <loc>https://casauhoaca.com/".$row['slug'].".htm</loc>
   
   <lastmod>".substr($row['last_modified'],0,10).'T'.substr($row['last_modified'],11,8).'+00:00'."</lastmod>
   
   <changefreq>weekly</changefreq>
   
   <priority>0.80</priority>
   
   </url>";
	}
   $sitemap .= $product;
   
   // sitemap trang co dinh
   $add = "";
   $list_add = array(
   		"https://casauhoaca.com/lien-he.htm", 
		"https://casauhoaca.com/mat-day-chuyen-nanh-ca-sau---5226-521.htm",
		"https://casauhoaca.com/that-lung-da-tran.htm", 
		"https://casauhoaca.com/bop-da-tran.htm",
		"https://casauhoaca.com/vi-nu-da-tran.htm",
		"https://casauhoaca.com/hang-giam-gia.htm",
		"https://casauhoaca.com/hang-khuyen-mai-nhan-dip-30-04-va-01-05.htm",
	);
   foreach($list_add as $value){
	   $add .= "
   <url>
   
   <loc>$value</loc>
   
   <lastmod>2016-09-24T10:28:46+00:00</lastmod>
   
   <changefreq>weekly</changefreq>
   
   <priority>0.80</priority>
   
   </url>";
   }
   $sitemap .= $add;
   
   $sitemap .= '

</urlset> ';		
		
		
		$filexml = "../sitemap.xml";
		$handle = @fopen($filexml, "w");
		$numbytes = @fwrite($handle,$sitemap);
		fclose($handle);

		$template->assign_vars(array('MESSAGE'	=>	SAVE_SUCCESS));
		mosInfo();
	}
function mosInfo()
	{		
		global $db, $root_path, $skin, $languageid, $template;
		
		$filexml = "../sitemap.xml";
		$fh = fopen($filexml, 'r');
		$tam = fread($fh, filesize($filexml));
		$noidung = $tam;
	
		$template->assign_vars(array(		
			'noidung'	=>	$noidung,
		));
	
		$template->set_filenames_new(array(
			'sitemap' => 'sitemap/sitemap.tpl')
		);
		$template->pparse('sitemap');
	}
?>