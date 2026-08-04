# Modern UI Migration Notes

## 2026-08-01

- `bootrap/includes/ui_layout.php`
  - Chuyen list/form legacy sang layout moi tren server-side truoc khi tra HTML ve browser.
  - Khong con tra truc tiep cac khoi `.toolbar`, `.tabtitle` cu ra giao dien.
  - Chuyen icon thao tac trong bang sang class moi `buffcorp-row-action`.

- `bootrap/mainpage.php`
  - Gan title topbar tu backend bang `PAGE_TITLE`.
  - Truyen route that vao sidebar render qua `$GLOBALS['buffcorp_current_option']` de khong bi bam menu con roi nhay ve parent/cu.
  - Go bo topbar debug logger khoi runtime.

- `bootrap/templates/mainpage/default.tpl`
  - Go bo JS transform layout cu (`enhanceLegacyModule`, `wireServerRenderedModule`, `topbarLog`).
  - Giu lai JS nhe cho topbar/search/active-link, khong mutate layout module.
  - Xoa CSS ho tro truc tiep `.toolbar` va `.tabtitle` cu.

- `bootrap/includes/library.php`
  - Sidebar parent/child render icon va markup moi ngay tu server-side.
  - Link menu con duoc rewrite `menu`/`l` theo parent hien tai de tranh stale URL.

- `bootrap/templates/default/vietnam/navigation/navigation.tpl`
  - Sidebar hardcode items (`Tong quan`, `Quan ly Tin tuc`) duoc doi sang markup moi.
  - Support items canh theo kich thuoc/vi tri cua menu cha.

- `bootrap/templates/default/vietnam/common_lists/keyads/keyads_list.html`
  - Trang `Ads google - Web` da duoc dung truc tiep bang module card moi.

- `bootrap/db/mysql.php`
  - Set charset ket noi MySQL sang `utf8mb4` de giam loi kieu chu khi doc/ghi du lieu.
