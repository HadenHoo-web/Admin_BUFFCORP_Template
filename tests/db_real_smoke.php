<?php
require dirname(__DIR__) . '/bootrap/config.php';

if ($dbname !== 'admin_buffcorp_real') {
    throw new RuntimeException('Local app is not using the real database.');
}

$db = @mysqli_connect($dbhost, $dbuser, $dbpasswd, $dbname);
if (!$db) throw new RuntimeException('Cannot connect to the real database.');

$checks = array(
    array('tbl_customer', 'customer_name', 600),
    array('tbl_website', 'website_name', 400),
    array('tbl_member', 'fullname', 50),
);

foreach ($checks as $check) {
    $sql = "SELECT COUNT(*) total, SUM(`{$check[1]}` LIKE '%demo%') demo FROM `{$check[0]}`";
    $result = mysqli_query($db, $sql);
    if (!$result) throw new RuntimeException('Cannot verify ' . $check[0]);
    $row = mysqli_fetch_assoc($result);
    if ((int)$row['total'] < $check[2] || (int)$row['demo'] >= (int)$row['total'] / 10) {
        throw new RuntimeException('Demo data is still active in ' . $check[0]);
    }
}

$result = mysqli_query($db, "SELECT COUNT(*) total, SUM(parent_id = 0) roots,
    SUM(parent_id <> 0 AND NOT EXISTS (
        SELECT 1 FROM tbl_function_menu parent WHERE parent.fun_id = tbl_function_menu.parent_id
    )) orphans
    FROM tbl_function_menu");
$menu = $result ? mysqli_fetch_assoc($result) : null;
if (!$menu || (int)$menu['total'] < 1 || (int)$menu['roots'] < 1 || (int)$menu['orphans'] !== 0) {
    throw new RuntimeException('Function-menu tree is missing or invalid.');
}

echo "Real DB smoke OK: customers, websites, members and function-menu tree loaded.\n";
