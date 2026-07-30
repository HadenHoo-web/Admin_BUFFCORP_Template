<?	
	global $languageid, $template;
	$action      = mosGetParam( $_REQUEST, 'mode', '');

	if (!isset($template))
		$template = new Template();	
	
	$template->assign_vars(array(
		'ROOT'		=> $root_path,		
		'funname'	=> 'vote/vote',
		'LANGUAGEID'=> $languageid,
		
	));		

	switch( $action )
	{	
		case 'info'			:	mosInfo(); break;	
		default:
			mosInvalidURL();
			exit;
	}
?>

<? 
function mosInfo()
	{	
		global $db, $root_path, $skin, $languageid, $template;
		$m = date('m');				
						
		//Thong ke 
		$sql = "SELECT tbl_products.member_id, COUNT(product_id) AS dem, tbl_products.created_by, tbl_member.fullname, tbl_member.cellphone, tbl_vote.* FROM (tbl_products RIGHT JOIN tbl_member ON tbl_products.member_id = tbl_member.member_id) inner join tbl_vote on tbl_member.member_id = tbl_vote.member_id where tbl_member.active=1 and tbl_member.member_id <>1 AND DAY(tbl_products.created_date) = DAY(NOW()) AND MONTH(tbl_products.created_date) = MONTH(NOW()) AND YEAR(tbl_products.created_date) = YEAR(NOW()) GROUP BY tbl_products.member_id";
		$sql = "SELECT tbl_products.member_id, COUNT(product_id) AS dem, tbl_products.created_by, tbl_member.fullname, tbl_member.cellphone, tbl_vote.* FROM (tbl_products RIGHT JOIN tbl_member ON tbl_products.member_id = tbl_member.member_id) inner join tbl_vote on tbl_member.member_id = tbl_vote.member_id where tbl_member.active=1 and tbl_member.member_id <>1 GROUP BY tbl_products.member_id";
		if ( !($result = $db->sql_query($sql)) ) die ( SERVER_BUSY );
		while ( $row = $db->sql_fetchrow($result))
		{		
			/*$sql2 = "SELECT SUM(tbl_banhang.banhang_name) AS doanhso, SUM(tbl_products.old_price) AS giagoc FROM tbl_banhang INNER JOIN tbl_products ON tbl_banhang.product_code = tbl_products.product_id WHERE SUBSTRING(ngay, 4, 2) = ".date(m)." AND tbl_banhang.member_id = ".$row['member_id'];
			if ( !($result2 = $db->sql_query($sql2)) ) die ( SERVER_BUSY );
			if ( $row2 = $db->sql_fetchrow($result2))
				$loinhuan	=	$row2['doanhso']*1 - $row2['giagoc']*1 ;
			$loinhuan = ($loinhuan < 0)?0:$loinhuan;*/
			
			
			$sql1 = "select count(*) as dem from tbl_pages where member_id = '".$row['member_id']."' and DAY(tbl_pages.created_date) = DAY(NOW()) and MONTH(tbl_pages.created_date) = MONTH(NOW()) and YEAR(tbl_pages.created_date) = YEAR(NOW())";
			if ( !($result1 = $db->sql_query($sql1)) ) die ( SERVER_BUSY );
			if ( $row1 = $db->sql_fetchrow($result1)){
				$post_page = $row1['dem'];
			}
			
			$template->assign_block_vars('post_list', array(
				'created_by'	=>	$row['fullname'],
				'dem'			=>	$row['dem'],
				'post_width'	=>	$row['dem'],
				'loinhuan'		=>	number_format($loinhuan/2100000, 0, ',', '.'),
				'vote_num'		=>	$row['vote_num'],
				'vote_width'	=>	$row['vote_num']/100,
				'diem_cong'		=>	($row['cellphone'])?"+ ".$row['cellphone']:'',
				'post_page'		=>	$post_page,
				'sum_month'		=>	$row['sum_month'],
				'n01'			=>	$row['n01'],
				'n02'			=>	$row['n02'],
				'n03'			=>	$row['n03'],
				'n04'			=>	$row['n04'],
				'n05'			=>	$row['n05'],
				'n06'			=>	$row['n06'],
				'n07'			=>	$row['n07'],
				'n08'			=>	$row['n08'],
				'n09'			=>	$row['n09'],
				'n10'			=>	$row['n10'],
				'n11'			=>	$row['n11'],
				'n12'			=>	$row['n12'],
				'n13'			=>	$row['n13'],
				'n14'			=>	$row['n14'],
				'n15'			=>	$row['n15'],
				'n16'			=>	$row['n16'],
				'n17'			=>	$row['n17'],
				'n18'			=>	$row['n18'],
				'n19'			=>	$row['n19'],
				'n20'			=>	$row['n20'],
				'n21'			=>	$row['n21'],
				'n22'			=>	$row['n22'],
				'n23'			=>	$row['n23'],
				'n24'			=>	$row['n24'],
				'n25'			=>	$row['n25'],
				'n26'			=>	$row['n26'],
				'n27'			=>	$row['n27'],
				'n28'			=>	$row['n28'],
				'n29'			=>	$row['n29'],
				'n30'			=>	$row['n30'],
				'n31'			=>	$row['n31'],
				'mausac'		=>	$row['mausac']
			));
		}
		
		//Giao viec
		$sql = "select * from tbl_giaoviec where active = 1 order by giaoviec_id";
		if ( !($result = $db->sql_query($sql)) ) die ( SERVER_BUSY );
		while ( $row = $db->sql_fetchrow($result))
		{	
			$template->assign_block_vars('giaoviec_list', array(
				'giaoviec_id'	=>	$row['giaoviec_id'],
				'giaoviec_name'	=>	$row['giaoviec_name'],
				'chitiet'		=>	$row['chitiet'],
			));
		}
		
		//Danh sach website
		$sql = "select * from tbl_website where active = 1 order by priority, website_name";
		if ( !($result = $db->sql_query($sql)) ) die ( SERVER_BUSY );
		while ( $row = $db->sql_fetchrow($result))
		{	
			$template->assign_block_vars('website_list', array(
				'website_name'	=>	$row['website_name'],
				'pro'			=>	substr($row['soluong'], 0, 7),
				'sec'			=>	substr($row['soluong'], 8),
			));
		}
		
		//ban hang hoa ca trong thang
		$ln_hc	= 0;
		for ($i = 1; $i <= 31; $i++) {
			$j = $i + 1;
			$from_date 	= ($i < 10)?"0".$i:$i;
			$to_date	= ($j < 10)?"0".$j:$j;
    		$sql = "select tbl_banhang.*, tbl_products.product_name, tbl_products.product_id, tbl_products.old_price, tbl_products.slug, tbl_products.image0, tbl_banhang_kind.color from (tbl_banhang inner join tbl_banhang_kind on tbl_banhang.banhang_kind_id = tbl_banhang_kind.banhang_kind_id) left join tbl_products on tbl_banhang.product_code = tbl_products.product_id where SUBSTRING(ngay, 7, 4) = '2017' and SUBSTRING(ngay, 4, 2) = '$m' and SUBSTRING(ngay, 1, 2) >= '$from_date' and SUBSTRING(ngay, 1, 2) < '$to_date' and tbl_banhang.banhang_kind_id = 3 order by banhang_id DESC";
			$ln = 0;
			if ( !($result = $db->sql_query($sql)) ) die ( SERVER_BUSY );
			while ( $row = $db->sql_fetchrow($result))
			{
				$ln += $row['banhang_name']*1 - $row['old_price']*1 ;	
			}
			$template->assign_block_vars('list_hoaca', array(
				'loinhuan'	=>	$ln,
			));
			$ln_hc += $ln;
		}
		
		//ban hang kieu hung trong thang
		$ln_kh	= 0;
		for ($i = 1; $i <= 31; $i++) {
			$j = $i + 1;
			$from_date 	= ($i < 10)?"0".$i:$i;
			$to_date	= ($j < 10)?"0".$j:$j;
    		$sql = "select tbl_banhang.*, tbl_products.product_name, tbl_products.product_id, tbl_products.old_price, tbl_products.slug, tbl_products.image0, tbl_banhang_kind.color from (tbl_banhang inner join tbl_banhang_kind on tbl_banhang.banhang_kind_id = tbl_banhang_kind.banhang_kind_id) left join tbl_products on tbl_banhang.product_code = tbl_products.product_id where SUBSTRING(ngay, 7, 4) = '2017' and SUBSTRING(ngay, 4, 2) = '$m' and SUBSTRING(ngay, 1, 2) >= '$from_date' and SUBSTRING(ngay, 1, 2) < '$to_date' and tbl_banhang.banhang_kind_id = 1 order by banhang_id DESC";
			$ln = 0;
			if ( !($result = $db->sql_query($sql)) ) die ( SERVER_BUSY );
			while ( $row = $db->sql_fetchrow($result))
			{
				$ln += $row['banhang_name']*1 - $row['old_price']*1 ;	
			}
			$template->assign_block_vars('list_kieuhung', array(
				'loinhuan'	=>	$ln,
			));
			$ln_kh += $ln;
		}
		
		//ban hang cao thuoc trong thang
		$ln_cao	= 0;
		for ($i = 1; $i <= 31; $i++) {
			$j = $i + 1;
			$from_date 	= ($i < 10)?"0".$i:$i;
			$to_date	= ($j < 10)?"0".$j:$j;
    		$sql = "select tbl_banhang.*, tbl_products.product_name, tbl_products.product_id, tbl_products.old_price, tbl_products.slug, tbl_products.image0, tbl_banhang_kind.color from (tbl_banhang inner join tbl_banhang_kind on tbl_banhang.banhang_kind_id = tbl_banhang_kind.banhang_kind_id) left join tbl_products on tbl_banhang.product_code = tbl_products.product_id where SUBSTRING(ngay, 7, 4) = '2017' and SUBSTRING(ngay, 4, 2) = '$m' and SUBSTRING(ngay, 1, 2) >= '$from_date' and SUBSTRING(ngay, 1, 2) < '$to_date' and tbl_banhang.banhang_kind_id = 8 order by banhang_id DESC";
			$ln = 0;
			if ( !($result = $db->sql_query($sql)) ) die ( SERVER_BUSY );
			while ( $row = $db->sql_fetchrow($result))
			{
				$ln += $row['banhang_name']*1 - $row['old_price']*1 ;	
			}
			$template->assign_block_vars('list_cao', array(
				'loinhuan'	=>	$ln,
			));
			$ln_cao += $ln;
		}
		
		$template->assign_vars(array(
			'ln_hc'		=> $ln_hc,
			'ln_kh'		=> $ln_kh,
			'ln_cao'	=> $ln_cao,
		));
		
		$template->set_filenames_new(array(
			'vote' => 'vote/vote_info.tpl')
		);
		$template->pparse('vote');
	}
?>