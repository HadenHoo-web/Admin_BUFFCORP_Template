<?php
	global $languageid, $template;
	$action = mosGetParam($_REQUEST, 'mode', '');

	if (!isset($template))
		$template = new Template();

	$template->assign_vars(array(
		'ROOT' => $root_path,
		'funname' => 'common_lists/autocheck_customer',
		'LANGUAGEID' => $languageid,
	));

	switch ($action)
	{
		case 'list'   : mosList(); break;
		case 'info'   : mosInfo(); break;
		case 'save'   : mosSave(); break;
		case 'delete' : mosDelete(); break;

		default:
			mosInvalidURL();
			exit;
	}

function mosList()
	{
		global $db, $template;

		$sql = "select * from tbl_autocheck_customers order by autocheck_customer_id desc";
		if (!($result = $db->sql_query($sql))) message_die(SERVER_BUSY);

		$order = 0;
		while ($row = $db->sql_fetchrow($result))
		{
			$order++;
			$template->assign_block_vars('list', array(
				'className' => ($order % 2 == 1) ? 'alt' : 'inv',
				'order' => $order,
				'autocheck_customer_id' => $row['autocheck_customer_id'],
				'customer_name' => $row['customer_name'],
				'phone' => $row['phone'],
				'email' => $row['email'],
			));
		}

		$template->set_filenames_new(array(
			'share' => 'common_lists/autocheck_customer/autocheck_customer_list.tpl')
		);
		$template->pparse('share');
	}

function mosInfo()
	{
		global $db, $template;
		$autocheck_customer_id = mosGetParam($_REQUEST, 'id', 0);

		if ($autocheck_customer_id != 0)
		{
			$sql = "select * from tbl_autocheck_customers where autocheck_customer_id = $autocheck_customer_id";
			if (!($result = $db->sql_query($sql))) message_die(SERVER_BUSY);
			if ($row = $db->sql_fetchrow($result))
			{
				$template->assign_vars(array(
					'autocheck_customer_id' => $autocheck_customer_id,
					'customer_name' => $row['customer_name'],
					'phone' => $row['phone'],
					'email' => $row['email'],
				));
			} else {
				message_die(ID_NOTFOUND);
			}
		}

		$template->set_filenames_new(array(
			'share' => 'common_lists/autocheck_customer/autocheck_customer_info.tpl')
		);
		$template->pparse('share');
	}

function mosSave()
	{
		global $db, $template;
		$autocheck_customer_id = mosGetParam($_REQUEST, 'id', '0');
		$customer_name = mosGetParam($_REQUEST, 'customer_name', '');
		$phone = mosGetParam($_REQUEST, 'phone', '');
		$email = mosGetParam($_REQUEST, 'email', '');

		if ($autocheck_customer_id == '')
		{
			mosInvalidURL();
			exit;
		}

		if ($autocheck_customer_id == '0')
		{
			$sql = "insert into tbl_autocheck_customers (customer_name, phone, email) values ('$customer_name', '$phone', '$email')";
		} else {
			$sql = "update tbl_autocheck_customers set customer_name = '$customer_name', phone = '$phone', email = '$email' where autocheck_customer_id = $autocheck_customer_id";
		}

		if (!($result = $db->sql_query($sql))) message_die(SERVER_BUSY);
		$template->assign_vars(array('MESSAGE' => SAVE_SUCCESS));
		mosList();
	}

function mosDelete()
	{
		global $template, $db;
		$autocheck_customer_id = mosGetParam($_REQUEST, 'id', '0');
		if ($autocheck_customer_id == 0)
		{
			mosInvalidURL();
			exit;
		}

		if (strtolower($_SESSION['membername']) == "administrator")
		{
			deleteByID("tbl_autocheck_customers", "autocheck_customer_id", $autocheck_customer_id);
		} else {
			$template->assign_vars(array('MESSAGE' => CANT_NOT_DELETE));
		}
		mosList();
	}
?>
