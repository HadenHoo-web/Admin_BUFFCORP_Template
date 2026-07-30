<?php
global $languageid, $template, $root_path, $db;

$action = mosGetParam($_REQUEST, 'mode', 'list');
if (!isset($template)) $template = new Template();

$template->assign_vars(array(
    'ROOT' => $root_path,
    'funname' => 'common_lists/tainguyencongty',
    'LANGUAGEID' => $languageid,
));

switch ($action) {
    case 'info':
        mosTaiNguyenInfo();
        break;
    case 'save':
        mosTaiNguyenSave();
        break;
    case 'delete':
        mosTaiNguyenDelete();
        break;
    case 'list':
    default:
        mosTaiNguyenList();
        break;
}

function mosTaiNguyenSql($value)
{
    return addslashes($value);
}

function mosTaiNguyenHtml($value)
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function mosTaiNguyenIsAdmin()
{
    return (isset($_SESSION['loginname']) && $_SESSION['loginname'] == 'administrator');
}

function mosTaiNguyenLoginId()
{
    return intval(isset($_SESSION['login_id']) ? $_SESSION['login_id'] : 0);
}

function mosTaiNguyenCategoryValue($value)
{
    $value = intval($value);
    if ($value < 1 || $value > 3) return 1;
    return $value;
}

function mosTaiNguyenCategoryName($value)
{
    $value = mosTaiNguyenCategoryValue($value);
    if ($value == 2) return 'B';
    if ($value == 3) return 'C';
    return 'A';
}

function mosTaiNguyenRedirectList($msg)
{
    global $languageid;
    $url = "main.php?option=common_lists/tainguyencongty&mode=list&l=".intval($languageid);
    if ($msg != '') $url .= "&msg=".$msg;
    if (!headers_sent()) {
        header("Location: ".$url);
        exit;
    }
    echo '<script type="text/javascript">window.location.href="'.htmlspecialchars($url, ENT_QUOTES, 'UTF-8').'";</script>';
    exit;
}

function mosTaiNguyenTableReady()
{
    global $db;
    $sql = "
        CREATE TABLE IF NOT EXISTS tbl_company_resources (
            resource_id int(11) NOT NULL AUTO_INCREMENT,
            resource_link text NOT NULL,
            resource_name varchar(255) NOT NULL DEFAULT '',
            resource_category varchar(180) NOT NULL DEFAULT '',
            share_user_ids varchar(255) NOT NULL DEFAULT '',
            active tinyint(1) NOT NULL DEFAULT 1,
            priority int(11) NOT NULL DEFAULT 0,
            language_id int(11) NOT NULL DEFAULT 2,
            created_member_id int(11) NOT NULL DEFAULT 0,
            created_by varchar(120) NOT NULL DEFAULT '',
            created_date datetime DEFAULT NULL,
            modified_by varchar(120) NOT NULL DEFAULT '',
            last_modified datetime DEFAULT NULL,
            PRIMARY KEY (resource_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ";
    return $db->sql_query($sql) ? true : false;
}

function mosTaiNguyenCanEdit($row)
{
    if (mosTaiNguyenIsAdmin()) return true;
    return intval($row['created_member_id']) == mosTaiNguyenLoginId();
}

function mosTaiNguyenList()
{
    global $db, $template, $languageid;
    mosTaiNguyenTableReady();

    $loginId = mosTaiNguyenLoginId();
    $cond = " where active = 1";
    if (!mosTaiNguyenIsAdmin()) {
        $cond .= " and (created_member_id = ".$loginId." or concat(',', replace(share_user_ids, ' ', ''), ',') like '%,".$loginId.",%')";
    }

    $sql = "select * from tbl_company_resources ".$cond." order by priority, resource_id desc";
    if (!($result = $db->sql_query($sql))) message_die(SERVER_BUSY);

    $order = 0;
    while ($row = $db->sql_fetchrow($result)) {
        $order++;
        $template->assign_block_vars('list', array(
            'className' => ($order % 2 == 1) ? 'alt' : 'inv',
            'order' => $order,
            'resource_id' => intval($row['resource_id']),
            'resource_link' => mosTaiNguyenHtml($row['resource_link']),
            'resource_name' => mosTaiNguyenHtml($row['resource_name']),
            'resource_category' => mosTaiNguyenHtml(mosTaiNguyenCategoryName($row['resource_category'])),
            'share_user_ids' => mosTaiNguyenHtml($row['share_user_ids']),
            'is_owner' => mosTaiNguyenCanEdit($row) ? '' : 'none',
        ));
    }

    $msg = mosGetParam($_REQUEST, 'msg', '');
    $message = '';
    if ($msg == 'save') $message = SAVE_SUCCESS;
    if ($msg == 'delete') $message = DELETE_SUCCESS;

    $template->assign_vars(array(
        'MESSAGE' => $message,
    ));

    $template->set_filenames_new(array(
        'share' => 'common_lists/tainguyencongty/tainguyencongty_list.html'
    ));
    $template->pparse('share');
}

function mosTaiNguyenInfo()
{
    global $db, $template, $languageid;
    mosTaiNguyenTableReady();

    $resourceId = intval(mosGetParam($_REQUEST, 'id', 0));

    $sql = "select * from tbl_member where active = 1 and member_id not in (1, 2, 62, 73, 81) order by fullname";
    if (!($result = $db->sql_query($sql))) message_die(SERVER_BUSY);
    while ($row = $db->sql_fetchrow($result)) {
        $template->assign_block_vars('share_member_note', array(
            'member_id' => intval($row['member_id']),
            'member_name' => mosTaiNguyenHtml($row['fullname']),
        ));
    }

    if ($resourceId > 0) {
        $sql = "select * from tbl_company_resources where resource_id = ".$resourceId." limit 1";
        if (!($result = $db->sql_query($sql))) message_die(SERVER_BUSY);
        if (!($row = $db->sql_fetchrow($result))) message_die(ID_NOTFOUND);
        if (!mosTaiNguyenCanEdit($row)) {
            mosInvalidURL();
            exit;
        }
        $template->assign_vars(array(
            'resource_id' => intval($row['resource_id']),
            'resource_link' => mosTaiNguyenHtml($row['resource_link']),
            'resource_name' => mosTaiNguyenHtml($row['resource_name']),
            'resource_category' => mosTaiNguyenHtml($row['resource_category']),
            'resource_category_1' => mosTaiNguyenCategoryValue($row['resource_category']) == 1 ? 'selected' : '',
            'resource_category_2' => mosTaiNguyenCategoryValue($row['resource_category']) == 2 ? 'selected' : '',
            'resource_category_3' => mosTaiNguyenCategoryValue($row['resource_category']) == 3 ? 'selected' : '',
            'share_user_ids' => mosTaiNguyenHtml($row['share_user_ids']),
            'active' => intval($row['active']) == 1 ? 'checked' : '',
            'created_date' => mosTaiNguyenHtml($row['created_date']),
            'created_by' => mosTaiNguyenHtml($row['created_by']),
            'last_modified' => mosTaiNguyenHtml($row['last_modified']),
            'modified_by' => mosTaiNguyenHtml($row['modified_by']),
            'is_new' => '0',
        ));
    } else {
        $template->assign_vars(array(
            'resource_id' => 0,
            'resource_link' => '',
            'resource_name' => '',
            'resource_category' => '',
            'resource_category_1' => 'selected',
            'resource_category_2' => '',
            'resource_category_3' => '',
            'share_user_ids' => '',
            'active' => 'checked',
            'created_date' => '',
            'created_by' => '',
            'last_modified' => '',
            'modified_by' => '',
            'is_new' => '1',
        ));
    }

    $template->assign_vars(array(
        'funname' => 'common_lists/tainguyencongty',
        'LANGUAGEID' => $languageid,
    ));

    $template->set_filenames_new(array(
        'share' => 'common_lists/tainguyencongty/tainguyencongty_info.html'
    ));
    $template->pparse('share');
}

function mosTaiNguyenSave()
{
    global $db, $template, $languageid;
    mosTaiNguyenTableReady();

    $resourceId = intval(mosGetParam($_REQUEST, 'id', 0));
    $resourceLink = mosGetParam($_REQUEST, 'resource_link', '');
    $resourceName = mosGetParam($_REQUEST, 'resource_name', '');
    $resourceCategory = mosTaiNguyenCategoryValue(mosGetParam($_REQUEST, 'resource_category', 1));
    $shareUserIds = mosGetParam($_REQUEST, 'share_user_ids', '');
    $active = intval(mosGetParam($_REQUEST, 'active', 0)) == 1 ? 1 : 0;
    $memberName = isset($_SESSION['membername']) ? $_SESSION['membername'] : '';
    $loginId = mosTaiNguyenLoginId();
    $languageId = intval($languageid) > 0 ? intval($languageid) : 2;

    if ($resourceId > 0) {
        $sql = "select * from tbl_company_resources where resource_id = ".$resourceId." limit 1";
        if (!($result = $db->sql_query($sql))) message_die(SERVER_BUSY);
        if (!($row = $db->sql_fetchrow($result))) message_die(ID_NOTFOUND);
        if (!mosTaiNguyenCanEdit($row)) {
            mosInvalidURL();
            exit;
        }
        $sql = "
            update tbl_company_resources set
                resource_link = '".mosTaiNguyenSql($resourceLink)."',
                resource_name = '".mosTaiNguyenSql($resourceName)."',
                resource_category = '".mosTaiNguyenSql($resourceCategory)."',
                share_user_ids = '".mosTaiNguyenSql($shareUserIds)."',
                active = ".$active.",
                modified_by = '".mosTaiNguyenSql($memberName)."',
                last_modified = now()
            where resource_id = ".$resourceId."
        ";
    } else {
        $priority = mosGetPriority('tbl_company_resources', 'priority', '');
        $sql = "
            insert into tbl_company_resources
                (resource_link, resource_name, resource_category, share_user_ids, active, priority, language_id, created_member_id, created_by, created_date, modified_by, last_modified)
            values
                ('".mosTaiNguyenSql($resourceLink)."', '".mosTaiNguyenSql($resourceName)."', '".mosTaiNguyenSql($resourceCategory)."', '".mosTaiNguyenSql($shareUserIds)."', ".$active.", ".$priority.", ".$languageId.", ".$loginId.", '".mosTaiNguyenSql($memberName)."', now(), '".mosTaiNguyenSql($memberName)."', now())
        ";
    }

    if (!($result = $db->sql_query($sql))) message_die(SERVER_BUSY);
    mosTaiNguyenRedirectList('save');
}

function mosTaiNguyenDelete()
{
    global $db, $template;
    mosTaiNguyenTableReady();

    $resourceId = intval(mosGetParam($_REQUEST, 'id', 0));
    if ($resourceId <= 0) {
        mosInvalidURL();
        exit;
    }

    $sql = "select * from tbl_company_resources where resource_id = ".$resourceId." limit 1";
    if (!($result = $db->sql_query($sql))) message_die(SERVER_BUSY);
    if (!($row = $db->sql_fetchrow($result))) message_die(ID_NOTFOUND);

    if (mosTaiNguyenCanEdit($row)) {
        $sql = "delete from tbl_company_resources where resource_id = ".$resourceId;
        if (!($result = $db->sql_query($sql))) message_die(SERVER_BUSY);
        mosTaiNguyenRedirectList('delete');
    } else {
        $template->assign_vars(array('MESSAGE' => CANT_NOT_DELETE));
    }

    mosTaiNguyenList();
}
?>
