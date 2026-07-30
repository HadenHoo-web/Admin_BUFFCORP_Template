<style>
.cuttpw-wrap{
    font-family:Tahoma, Arial;
    padding:12px;
}
.cuttpw-head{
    background:#fff;
    border:1px solid #cfcfcf;
    padding:12px;
    margin-bottom:10px;
}
.cuttpw-title{
    font-size:20px;
    font-weight:bold;
    margin-bottom:4px;
}
.cuttpw-status{
    display:inline-block;
    padding:5px 8px;
    border-radius:4px;
    font-weight:bold;
}
.cuttpw-status.ok{
    background:#e7f7e8;
    color:#137333;
    border:1px solid #b7e0bc;
}
.cuttpw-status.error{
    background:#ffecec;
    color:#b00020;
    border:1px solid #f5b5b5;
}
.cuttpw-grid{
    display:grid;
    grid-template-columns:repeat(5, minmax(140px, 1fr));
    gap:10px;
}
.cuttpw-card{
    background:#fff;
    border:1px solid #cfcfcf;
    padding:14px;
    min-height:86px;
}
.cuttpw-label{
    color:#666;
    font-weight:bold;
    margin-bottom:10px;
}
.cuttpw-num{
    font-size:28px;
    font-weight:bold;
    color:#0b57d0;
}
.cuttpw-note{
    margin-top:10px;
    color:#666;
}
@media(max-width:900px){
    .cuttpw-grid{grid-template-columns:repeat(2, minmax(140px, 1fr));}
}
</style>

<div class="cuttpw-wrap">
    <div class="cuttpw-head">
        <div class="cuttpw-title">Cutt.pw - Thống kê tổng quan</div>
        <span class="cuttpw-status {STATUS_CLASS}">{STATUS_TEXT}</span>
        <div class="cuttpw-note">Cập nhật lúc: {GENERATED_AT}</div>
    </div>

    <div class="cuttpw-grid">
        <div class="cuttpw-card">
            <div class="cuttpw-label">Tổng user</div>
            <div class="cuttpw-num">{TOTAL_USERS}</div>
        </div>
        <div class="cuttpw-card">
            <div class="cuttpw-label">User active</div>
            <div class="cuttpw-num">{ACTIVE_USERS}</div>
        </div>
        <div class="cuttpw-card">
            <div class="cuttpw-label">User deactive</div>
            <div class="cuttpw-num">{DEACTIVE_USERS}</div>
        </div>
        <div class="cuttpw-card">
            <div class="cuttpw-label">Tổng shortlink</div>
            <div class="cuttpw-num">{TOTAL_LINKS}</div>
        </div>
        <div class="cuttpw-card">
            <div class="cuttpw-label">Tổng click</div>
            <div class="cuttpw-num">{TOTAL_CLICKS}</div>
        </div>
    </div>
</div>
