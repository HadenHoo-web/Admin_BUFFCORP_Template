<?php
global $languageid, $template, $root_path;

$action = mosGetParam($_REQUEST, 'mode', 'view');
if (!isset($template)) $template = new Template();

$template->assign_vars(array(
    'ROOT' => $root_path,
    'funname' => 'common_lists/sodocongty',
    'LANGUAGEID' => $languageid,
));

switch ($action) {
    case 'edit':
        mosOrgChartEdit();
        break;
    case 'save':
        mosOrgChartSave();
        break;
    case 'view':
    default:
        mosOrgChartView();
        break;
}

function mosOrgChartEnsureUtf8()
{
    global $db;
    static $done = false;
    if ($done) return;
    if (isset($db)) {
        @$db->sql_query("SET NAMES utf8mb4");
    }
    $done = true;
}

function mosOrgChartSql($value)
{
    return addslashes($value);
}

function mosOrgChartHtml($value)
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function mosOrgChartIsAdmin()
{
    return (isset($_SESSION['loginname']) && $_SESSION['loginname'] == 'administrator');
}

function mosOrgChartCanAccess()
{
    $loginId = (int)(isset($_SESSION['login_id']) ? $_SESSION['login_id'] : 0);
    return mosOrgChartIsAdmin() || in_array((int)$loginId, array(71, 34));
}

function mosOrgChartTableReady()
{
    global $db;
    mosOrgChartEnsureUtf8();
    if (!($result = $db->sql_query("show tables like 'tbl_company_org_chart'"))) return false;
    return ($db->sql_fetchrow($result)) ? true : false;
}

function mosOrgChartDefaults()
{
    return array(
        array('person_key' => 'tro_ly_trieu_anh', 'parent_key' => '', 'display_name' => 'Ms.Trợ lý Triêu Anh', 'role_title' => 'Trưởng phòng Kinh Doanh / Trợ lý TGĐ', 'department' => 'Kinh Doanh', 'mission' => 'Điều phối team kinh doanh, theo dõi doanh thu, kiểm tra thông tin khách hàng, admin website và hosting khách hàng.', 'sort_order' => 1),
        array('person_key' => 'hang', 'parent_key' => '', 'display_name' => 'Ms.Hằng', 'role_title' => 'Trưởng phòng Marketing', 'department' => 'Marketing', 'mission' => 'Quản lý Code web, Content, ADS, Tool Hệ Thống và SEO mở rộng. Theo dõi tiến độ, chất lượng đầu ra và phối hợp nhân sự trong nhóm.', 'sort_order' => 2),
        array('person_key' => 'tu', 'parent_key' => '', 'display_name' => 'Ms.Tú', 'role_title' => 'Trưởng phòng SEO', 'department' => 'KT SEO', 'mission' => 'Theo dõi kết quả SEO, quản lý website đang SEO, kiểm soát doanh thu và hiệu suất các website do team SEO phụ trách.', 'sort_order' => 3),

        array('person_key' => 'quang', 'parent_key' => 'tro_ly_trieu_anh', 'display_name' => 'Mr.Quang', 'role_title' => 'Nhân viên Kinh Doanh', 'department' => 'Kinh Doanh', 'mission' => 'Tìm kiếm, chăm sóc và cập nhật thông tin khách hàng. Phối hợp xử lý nhu cầu website, hosting và dịch vụ liên quan.', 'sort_order' => 1),
        array('person_key' => 'truc_anh', 'parent_key' => 'tro_ly_trieu_anh', 'display_name' => 'Ms.Trúc Anh', 'role_title' => 'Nhân viên Kinh Doanh', 'department' => 'Kinh Doanh', 'mission' => 'Theo dõi khách hàng, cập nhật tiến độ tư vấn, phối hợp bàn giao thông tin cho các bộ phận triển khai.', 'sort_order' => 2),
        array('person_key' => 'ngan_kd', 'parent_key' => 'tro_ly_trieu_anh', 'display_name' => 'Ms.Ngân KD', 'role_title' => 'Nhân viên Kinh Doanh', 'department' => 'Kinh Doanh', 'mission' => 'Quản lý thông tin khách hàng, hỗ trợ tư vấn, theo dõi tình trạng thanh toán và nhu cầu dịch vụ.', 'sort_order' => 3),
        array('person_key' => 'giao', 'parent_key' => '', 'display_name' => 'Ms.Kỳ Giao', 'role_title' => 'Hành chính - Nhân sự', 'department' => 'Hành chính / Nhân sự', 'mission' => 'Phụ trách các đầu việc hành chính, nhân sự, hỗ trợ vận hành nội bộ và phối hợp thông tin giữa các phòng ban.', 'sort_order' => 3),

        array('person_key' => 'xuyen', 'parent_key' => 'hang', 'display_name' => 'Ms.Xuyến', 'role_title' => 'Nhân viên Content', 'department' => 'Content', 'mission' => 'Viết content, audit content, nghiên cứu từ khóa và đảm bảo task đủ điều kiện KPI theo quy định.', 'sort_order' => 1),
        array('person_key' => 'ngan', 'parent_key' => 'hang', 'display_name' => 'Ms.Ngân', 'role_title' => 'Nhân viên Content', 'department' => 'Content', 'mission' => 'Triển khai bài viết, audit nội dung, nghiên cứu từ khóa và cập nhật link hoàn thành đúng tiêu chí KPI.', 'sort_order' => 2),
        array('person_key' => 'nga', 'parent_key' => 'hang', 'display_name' => 'Ms.Nga', 'role_title' => 'Nhân viên KT SEO', 'department' => 'KT SEO', 'mission' => 'Theo dõi website SEO, xử lý task kỹ thuật SEO và phối hợp báo cáo kết quả theo website phụ trách.', 'sort_order' => 3),
        array('person_key' => 'khanh', 'parent_key' => 'hang', 'display_name' => 'Mr.Khánh', 'role_title' => 'Nhân viên KT SEO', 'department' => 'KT SEO', 'mission' => 'Quản lý task SEO, kiểm tra website, xử lý lỗi kỹ thuật và theo dõi hiệu quả SEO.', 'sort_order' => 4),
        array('person_key' => 'danh', 'parent_key' => 'hang', 'display_name' => 'Mr.Danh', 'role_title' => 'Nhân viên Dev', 'department' => 'Website', 'mission' => 'Phụ trách xử lý website, chỉnh sửa giao diện, phối hợp triển khai kỹ thuật và bảo trì hệ thống web.', 'sort_order' => 5),
        array('person_key' => 'huy_code', 'parent_key' => 'hang', 'display_name' => 'Mr.Huy Code', 'role_title' => 'Nhân viên Dev', 'department' => 'Website', 'mission' => 'Xử lý code, tool hệ thống, nâng cấp chức năng nội bộ và hỗ trợ các đầu việc kỹ thuật phức tạp.', 'sort_order' => 6),
        array('person_key' => 'duy', 'parent_key' => 'hang', 'display_name' => 'Mr.Duy', 'role_title' => 'Nhân viên Dev', 'department' => 'Website', 'mission' => 'Triển khai, chỉnh sửa và hỗ trợ vận hành website theo yêu cầu nội bộ và khách hàng.', 'sort_order' => 7),

        array('person_key' => 'tam', 'parent_key' => 'tu', 'display_name' => 'Mr.Tâm', 'role_title' => 'Nhân viên SEO', 'department' => 'KT SEO', 'mission' => 'Quản lý website SEO được phân công, theo dõi kết quả, cập nhật tiến độ và xử lý task SEO.', 'sort_order' => 1),
        array('person_key' => 'thu', 'parent_key' => 'tu', 'display_name' => 'Ms.Thư', 'role_title' => 'Nhân viên SEO', 'department' => 'KT SEO', 'mission' => 'Theo dõi website SEO, hỗ trợ triển khai task kỹ thuật SEO và cập nhật kết quả theo phân công.', 'sort_order' => 2),
    );
}

function mosOrgChartData()
{
    global $db;
    mosOrgChartEnsureUtf8();
    $items = mosOrgChartDefaults();
    if (!mosOrgChartTableReady()) return $items;

    $rows = array();
    $sql = "select * from tbl_company_org_chart where active = 1 order by case when parent_key = '' then 0 else 1 end, parent_key, sort_order, display_name";
    if ($result = $db->sql_query($sql)) {
        while ($row = $db->sql_fetchrow($result)) {
            $rows[] = array(
                'person_key' => $row['person_key'],
                'parent_key' => $row['parent_key'],
                'display_name' => $row['display_name'],
                'role_title' => $row['role_title'],
                'department' => $row['department'],
                'mission' => $row['mission'],
                'sort_order' => (int)$row['sort_order'],
            );
        }
    }
    return count($rows) ? $rows : $items;
}

function mosOrgChartVirtualPerson($key, $name, $role, $department, $mission)
{
    return array(
        'person_key' => $key,
        'parent_key' => '',
        'display_name' => $name,
        'role_title' => $role,
        'department' => $department,
        'mission' => $mission,
        'sort_order' => 0,
    );
}

function mosOrgChartLookup($items)
{
    $lookup = array();
    foreach ($items as $item) {
        $lookup[$item['person_key']] = $item;
    }
    return $lookup;
}

function mosOrgChartChildren($items)
{
    $children = array();
    foreach ($items as $item) {
        $parent = $item['parent_key'];
        if (!isset($children[$parent])) $children[$parent] = array();
        $children[$parent][] = $item;
    }
    return $children;
}

function mosOrgChartTextContains($text, $keywords)
{
    $text = function_exists('mb_strtolower') ? mb_strtolower($text, 'UTF-8') : strtolower($text);
    foreach ($keywords as $keyword) {
        $keyword = function_exists('mb_strtolower') ? mb_strtolower($keyword, 'UTF-8') : strtolower($keyword);
        if ($keyword !== '' && strpos($text, $keyword) !== false) return true;
    }
    return false;
}

function mosOrgChartMarkUsed(&$used, $item)
{
    if (isset($item['person_key'])) $used[$item['person_key']] = true;
}

function mosOrgChartPeopleByKeys($lookup, $keys, &$used)
{
    $people = array();
    foreach ($keys as $key) {
        if (isset($lookup[$key]) && !isset($used[$key])) {
            $people[] = $lookup[$key];
            mosOrgChartMarkUsed($used, $lookup[$key]);
        }
    }
    return $people;
}

function mosOrgChartPeopleFromChildren($children, $parentKey, $excludeKeys, &$used)
{
    $people = array();
    $exclude = array();
    foreach ($excludeKeys as $key) $exclude[$key] = true;
    if (!isset($children[$parentKey])) return $people;
    foreach ($children[$parentKey] as $person) {
        $key = $person['person_key'];
        if (isset($exclude[$key]) || isset($used[$key])) continue;
        $people[] = $person;
        mosOrgChartMarkUsed($used, $person);
    }
    return $people;
}

function mosOrgChartPeopleByText($items, $keywords, &$used)
{
    $people = array();
    foreach ($items as $person) {
        $key = $person['person_key'];
        if (isset($used[$key])) continue;
        $text = $person['display_name'].' '.$person['role_title'].' '.$person['department'].' '.$person['mission'];
        if (mosOrgChartTextContains($text, $keywords)) {
            $people[] = $person;
            mosOrgChartMarkUsed($used, $person);
        }
    }
    return $people;
}

function mosOrgChartPersonButton($item, $className, $groupName)
{
    return '<button type="button" class="org-person '.$className.'"'
        .' data-person="'.mosOrgChartHtml($item['person_key']).'"'
        .' data-name="'.mosOrgChartHtml($item['display_name']).'"'
        .' data-role="'.mosOrgChartHtml($item['role_title']).'"'
        .' data-department="'.mosOrgChartHtml($item['department']).'"'
        .' data-group="'.mosOrgChartHtml($groupName).'"'
        .' data-mission="'.mosOrgChartHtml($item['mission']).'"'
        .' onmouseover="orgShowPerson(this)" onfocus="orgShowPerson(this)" onclick="orgShowPerson(this);return false;">'
        .'<span class="org-role-line">'.mosOrgChartHtml($item['role_title']).'</span>'
        .'<strong>'.mosOrgChartHtml($item['display_name']).'</strong>'
        .'<em>'.mosOrgChartHtml($item['department']).'</em>'
        .'</button>';
}

function mosOrgChartPlaceholder($text)
{
    return '<div class="org-empty-box">'.str_replace("\n", "<br>", mosOrgChartHtml($text)).'</div>';
}

function mosOrgChartPeopleList($people, $groupName, $className)
{
    $count = count($people);
    $html = '<div class="org-member-list '.$className.' org-count-'.$count.'">';
    if (!$count) {
        $html .= mosOrgChartPlaceholder('Chưa cập nhật');
    } else {
        foreach ($people as $person) {
            $html .= mosOrgChartPersonButton($person, 'org-member', $groupName);
        }
    }
    $html .= '</div>';
    return $html;
}

function mosOrgChartDepartment($title, $htmlInside, $className)
{
    return '<div class="org-dept '.$className.'"><div class="org-dept-title">'.mosOrgChartHtml($title).'</div>'.$htmlInside.'</div>';
}

function mosOrgChartBuildTreeHtml($items)
{
    $lookup = mosOrgChartLookup($items);
    $children = mosOrgChartChildren($items);
    $used = array();

    $ceo = mosOrgChartVirtualPerson('ceo_nguyen_the_hung', 'Nguyễn Thế Hưng', 'CEO', 'Ban Giám Đốc', 'Điều hành tổng thể công ty, định hướng chiến lược và kiểm soát hoạt động các phòng ban.');

    $salesLead = isset($lookup['tro_ly_trieu_anh']) ? $lookup['tro_ly_trieu_anh'] : mosOrgChartVirtualPerson('tro_ly_trieu_anh', 'Ms.Trợ lý Triêu Anh', 'Trưởng phòng Kinh Doanh / Trợ lý TGĐ', 'Kinh Doanh, HC Nhân Sự', 'Điều phối team kinh doanh và hỗ trợ vận hành nội bộ.');
    $marketingLead = isset($lookup['hang']) ? $lookup['hang'] : mosOrgChartVirtualPerson('hang', 'Ms.Hằng', 'Trưởng phòng Marketing', 'Marketing', 'Quản lý Marketing, Content, Ads, Website và các đầu việc mở rộng.');
    $seoLead = isset($lookup['tu']) ? $lookup['tu'] : mosOrgChartVirtualPerson('tu', 'Ms.Tú', 'Trưởng phòng SEO', 'KT SEO', 'Quản lý team SEO và hiệu quả website SEO.');
    $adminPerson = isset($lookup['giao']) ? $lookup['giao'] : mosOrgChartVirtualPerson('giao', 'Ms.Kỳ Giao', 'Hành chính - Nhân sự', 'Hành chính / Nhân sự', 'Hỗ trợ vận hành, hành chính và nhân sự.');

    mosOrgChartMarkUsed($used, $salesLead);
    mosOrgChartMarkUsed($used, $marketingLead);
    mosOrgChartMarkUsed($used, $seoLead);
    mosOrgChartMarkUsed($used, $adminPerson);

    $salesStaff = mosOrgChartPeopleByKeys($lookup, array('truc_anh', 'ngan_kd', 'quang'), $used);
    $salesStaff = array_merge($salesStaff, mosOrgChartPeopleFromChildren($children, 'tro_ly_trieu_anh', array('giao'), $used));

    $seoCompany = mosOrgChartPeopleByKeys($lookup, array('nga', 'khanh'), $used);
    $seoStaff = mosOrgChartPeopleByKeys($lookup, array('tam', 'thu'), $used);
    $seoStaff = array_merge($seoStaff, mosOrgChartPeopleFromChildren($children, 'tu', array(), $used));

    $adsStaff = mosOrgChartPeopleByText($items, array('ads', 'digital mkt', 'digital marketing', 'quảng cáo'), $used);
    $websiteStaff = mosOrgChartPeopleByKeys($lookup, array('duy', 'huy_code', 'danh', 'yasuo'), $used);
    $websiteStaff = array_merge($websiteStaff, mosOrgChartPeopleByText($items, array('website', 'dev', 'code', 'kt website', 'hệ thống'), $used));
    $contentStaff = mosOrgChartPeopleByKeys($lookup, array('xuyen', 'ngan'), $used);
    $contentStaff = array_merge($contentStaff, mosOrgChartPeopleByText($items, array('content', 'bài viết'), $used));

    $newServiceStaff = array();
    foreach ($items as $person) {
        if (!isset($used[$person['person_key']])) {
            $newServiceStaff[] = $person;
            mosOrgChartMarkUsed($used, $person);
        }
    }

    $html = '<div class="org-chart org-chart-v2">';

    $html .= '<div class="org-ceo-row">';
    $html .= mosOrgChartPersonButton($ceo, 'org-ceo', 'Ban Giám Đốc');
    $html .= '</div>';

    $html .= '<div class="org-main-row">';

    $html .= '<div class="org-main-cell org-sales-cell">';

    $salesAssistant = $salesLead;
    $salesAssistant['role_title'] = 'Trợ lý Giám Đốc';
    $salesAssistant['department'] = 'Kiêm Hành chính, nhân sự';

    $salesManager = $salesLead;
    $salesManager['role_title'] = 'Trưởng phòng Kinh Doanh';
    $salesManager['department'] = 'Kinh Doanh';

    $salesInside = mosOrgChartPersonButton($salesAssistant, 'org-leader org-sales-leader org-sales-assistant', 'Trợ lý Giám Đốc');

    $salesInside .= '<div class="org-sales-title-lower">PHÒNG KINH DOANH</div>';

    $salesInside .= mosOrgChartPersonButton($salesManager, 'org-leader org-sales-leader org-sales-manager', 'Phòng Kinh Doanh');

    $salesInside .= mosOrgChartPeopleList($salesStaff, 'Nhân viên Kinh Doanh', 'org-sales-staff');

    $html .= '<div class="org-dept org-sales-dept org-sales-custom">'.$salesInside.'</div>';

    $html .= '</div>';

    $html .= '<div class="org-main-cell org-admin-cell">';
    $adminInside = mosOrgChartPersonButton($adminPerson, 'org-leader org-admin-person', 'Phòng Hành chính - Nhân sự');
    $html .= mosOrgChartDepartment('PHÒNG HÀNH CHÍNH - NHÂN SỰ', $adminInside, 'org-admin-dept');
    $html .= '</div>';

    $html .= '<div class="org-main-cell org-marketing-cell">';
    $marketingInside = mosOrgChartPersonButton($marketingLead, 'org-leader org-marketing-leader', 'Phòng Marketing');
    $html .= mosOrgChartDepartment('PHÒNG MARKETING', $marketingInside, 'org-marketing-dept');
    $html .= '</div>';

    $html .= '</div>';

    $html .= '<div class="org-unit-row">';

    $seoInside = '<div class="org-seo-groups">';
    $seoInside .= '<div class="org-seo-sub"><div class="org-sub-title">DA CÔNG TY</div>'.mosOrgChartPeopleList($seoCompany, 'DA Công Ty', 'org-seo-company-list').'</div>';
    $seoInside .= '<div class="org-seo-sub org-seo-lead-sub"><div class="org-sub-title org-sub-title-dark">TRƯỞNG PHÒNG SEO</div>'.mosOrgChartPersonButton($seoLead, 'org-sub-leader', 'Phòng SEO').mosOrgChartPeopleList($seoStaff, 'Nhân viên SEO', 'org-seo-staff-list').'</div>';
    $seoInside .= '<div class="org-seo-sub"><div class="org-sub-title">DA OUTSOURCE</div>'.mosOrgChartPlaceholder("CTV SEO\nChưa cập nhật").'</div>';
    $seoInside .= '</div>';
    $html .= mosOrgChartDepartment('PHÒNG SEO', $seoInside, 'org-unit org-seo-unit');

    $adsInside = mosOrgChartPeopleList($adsStaff, 'ADS', 'org-ads-list');
    $html .= mosOrgChartDepartment('ADS', $adsInside, 'org-unit org-ads-unit');

    $websiteInside = mosOrgChartPeopleList($websiteStaff, 'Website', 'org-website-list');
    $html .= mosOrgChartDepartment('WEBSITE', $websiteInside, 'org-unit org-website-unit');

    $contentInside = mosOrgChartPeopleList($contentStaff, 'Content', 'org-content-list');
    $html .= mosOrgChartDepartment('CONTENT', $contentInside, 'org-unit org-content-unit');

    $serviceInside = mosOrgChartPeopleList($newServiceStaff, 'SP/DV Mới', 'org-service-list');
    $html .= mosOrgChartDepartment('SP/DV MỚI', $serviceInside, 'org-unit org-service-unit');

    $html .= '</div>';
    $html .= '</div>';
    return $html;
}

function mosOrgChartInitials($name)
{
    $parts = preg_split('/\s+/', trim($name));
    if (!$parts || !count($parts)) return '';
    $last = $parts[count($parts) - 1];
    return function_exists('mb_substr') ? mb_substr($last, 0, 2, 'UTF-8') : substr($last, 0, 2);
}

function mosOrgChartJson($items)
{
    $rows = array();
    foreach ($items as $item) {
        $rows[] = "{"
            ."'person_key':'".mosOrgChartJs($item['person_key'])."',"
            ."'parent_key':'".mosOrgChartJs($item['parent_key'])."',"
            ."'display_name':'".mosOrgChartJs($item['display_name'])."',"
            ."'role_title':'".mosOrgChartJs($item['role_title'])."',"
            ."'department':'".mosOrgChartJs($item['department'])."',"
            ."'mission':'".mosOrgChartJs($item['mission'])."'"
            ."}";
    }
    return "[".implode(",", $rows)."]";
}

function mosOrgChartJs($value)
{
    $value = str_replace("\\", "\\\\", $value);
    $value = str_replace("'", "\\'", $value);
    $value = str_replace("\r", "", $value);
    $value = str_replace("\n", "\\n", $value);
    return $value;
}

function mosOrgChartFindPerson($items, $personKey)
{
    foreach ($items as $item) {
        if ($item['person_key'] == $personKey) return $item;
    }
    return isset($items[0]) ? $items[0] : false;
}

function mosOrgChartParentName($items, $parentKey)
{
    if ($parentKey == '') return 'Cấp quản lý';
    foreach ($items as $item) {
        if ($item['person_key'] == $parentKey) return $item['display_name'];
    }
    return 'Cấp quản lý';
}

function mosOrgChartView()
{
    global $template, $languageid;
    if (!mosOrgChartCanAccess()) {
        $template->assign_vars(array(
            'TITLE' => 'Sơ đồ công ty',
            'MESSAGE' => 'Bạn không có quyền xem sơ đồ công ty.',
            'MESSAGE_DISPLAY' => 'block',
            'CONTENT_DISPLAY' => 'none',
            'EDIT_DISPLAY' => 'none',
            'EDIT_URL' => '#',
            'CONTENT' => '',
        ));
        $template->set_filenames_new(array('share' => 'common_lists/document/document_view.html'));
        $template->pparse('share');
        return;
    }

    $items = mosOrgChartData();
    $template->assign_vars(array(
        'funname' => 'common_lists/sodocongty',
        'LANGUAGEID' => $languageid,
        'ORG_TREE' => mosOrgChartBuildTreeHtml($items),
        'ORG_DATA' => mosOrgChartJson($items),
        'EDIT_URL' => '?option=common_lists/sodocongty&mode=edit&l='.$languageid,
        'EDIT_DISPLAY' => mosOrgChartCanAccess() ? 'inline-block' : 'none',
        'TABLE_MESSAGE' => mosOrgChartTableReady() ? '' : 'Chưa có bảng dữ liệu riêng. Trang đang dùng dữ liệu mặc định trong source.',
    ));
    $template->set_filenames_new(array('share' => 'common_lists/sodocongty/sodocongty_view.html'));
    $template->pparse('share');
}

function mosOrgChartEdit()
{
    global $template, $languageid;
    if (!mosOrgChartCanAccess()) {
        mosInvalidURL();
        exit;
    }
    $items = mosOrgChartData();
    foreach ($items as $item) {
        $template->assign_block_vars('person', array(
            'person_key' => mosOrgChartHtml($item['person_key']),
            'parent_key' => mosOrgChartHtml($item['parent_key']),
            'display_name' => mosOrgChartHtml($item['display_name']),
            'role_title' => mosOrgChartHtml($item['role_title']),
            'department' => mosOrgChartHtml($item['department']),
            'mission' => mosOrgChartHtml($item['mission']),
            'sort_order' => (int)$item['sort_order'],
        ));
    }
    $template->assign_vars(array(
        'funname' => 'common_lists/sodocongty',
        'LANGUAGEID' => $languageid,
        'SAVE_MODE' => 'save',
        'BACK_URL' => '?option=common_lists/sodocongty&mode=view&l='.$languageid,
        'MESSAGE' => mosOrgChartTableReady() ? '' : 'Chưa có bảng tbl_company_org_chart. Chạy SQL trước khi lưu.',
    ));
    $template->set_filenames_new(array('share' => 'common_lists/sodocongty/sodocongty_edit.html'));
    $template->pparse('share');
}

function mosOrgChartSave()
{
    global $db, $template, $languageid;
    mosOrgChartEnsureUtf8();
    if (!mosOrgChartCanAccess() || !mosOrgChartTableReady()) {
        mosInvalidURL();
        exit;
    }

    $keys = isset($_REQUEST['person_key']) && is_array($_REQUEST['person_key']) ? $_REQUEST['person_key'] : array();
    $parents = isset($_REQUEST['parent_key']) && is_array($_REQUEST['parent_key']) ? $_REQUEST['parent_key'] : array();
    $names = isset($_REQUEST['display_name']) && is_array($_REQUEST['display_name']) ? $_REQUEST['display_name'] : array();
    $roles = isset($_REQUEST['role_title']) && is_array($_REQUEST['role_title']) ? $_REQUEST['role_title'] : array();
    $departments = isset($_REQUEST['department']) && is_array($_REQUEST['department']) ? $_REQUEST['department'] : array();
    $missions = isset($_REQUEST['mission']) && is_array($_REQUEST['mission']) ? $_REQUEST['mission'] : array();
    $orders = isset($_REQUEST['sort_order']) && is_array($_REQUEST['sort_order']) ? $_REQUEST['sort_order'] : array();

    if (!($result = $db->sql_query("update tbl_company_org_chart set active = 0, last_modified = now()"))) message_die(SERVER_BUSY);

    for ($i = 0; $i < count($keys); $i++) {
        $key = trim($keys[$i]);
        if ($key == '') continue;
        $sql = "
            insert into tbl_company_org_chart
            (person_key, parent_key, display_name, role_title, department, mission, sort_order, active, last_modified)
            values (
                '".mosOrgChartSql($key)."',
                '".mosOrgChartSql(isset($parents[$i]) ? $parents[$i] : '')."',
                '".mosOrgChartSql(isset($names[$i]) ? $names[$i] : '')."',
                '".mosOrgChartSql(isset($roles[$i]) ? $roles[$i] : '')."',
                '".mosOrgChartSql(isset($departments[$i]) ? $departments[$i] : '')."',
                '".mosOrgChartSql(isset($missions[$i]) ? $missions[$i] : '')."',
                ".(int)(isset($orders[$i]) ? $orders[$i] : 0).",
                1,
                now()
            )
            on duplicate key update
                parent_key = values(parent_key),
                display_name = values(display_name),
                role_title = values(role_title),
                department = values(department),
                mission = values(mission),
                sort_order = values(sort_order),
                active = 1,
                last_modified = now()
        ";
        if (!($result = $db->sql_query($sql))) message_die(SERVER_BUSY);
    }
    $template->assign_vars(array('MESSAGE' => SAVE_SUCCESS));
    mosOrgChartView();
}
?>
