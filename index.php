<!DOCTYPE html>

<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <link rel="stylesheet" href="./css/weui.css?vver"/>
    <link rel="stylesheet" href="./css/weui.index.css?f3cvvc"/>
    <link rel="shortcut icon" href="./favicon.ico" type="image/x-icon"/>
    <link rel="bookmark icon" href="./favicon.ico"/>

    <script src="./js/jquery-1.8.3.min.js"></script>
    <script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
    <script src="./js/robot-service.js"></script>
</head>
<body ontouchstart>

<div id="uid" name="uid" > <?php echo $_GET['uid']; ?> </div>

<div class="container">
    <div class="page">
        <div class="weui_toptips weui_warn">服务升级中，暂不可使用！</div>
        <div class="hd">
            <p class="page_desc"><a href="javascript:alert('hd');"><img style="height:60px; width:60px;border-radius:90px;" src="./image/robot-logo.jpg"></a></p>
            <h1 id='nickname' class="page_title"  style="font-size: 24px;">欢迎使用</h1>
            <p class="page_desc">共累计分享 <b id='tottalused'>0 </b>次</p>
        </div>
        <div class="bd">
            <div class="weui_cells weui_cells_access global_navs">
                <a class="weui_cell" href="javascript:alert($('#todayused').text());">
                    <div class="weui_cell_hd weui_icon_success_circle" style="width:20px;margin-right:5px;display:block"></div>
                    <div class="weui_cell_bd weui_cell_primary"><p>今日已开</p></div>
                    <div id='todayused' class="weui_cell_ft" style="color: #991100;">0 </div>
                </a>

                <a class="weui_cell">
                    <div class="weui_cell_hd weui_icon_success_circle" style="width:20px;margin-right:5px;display:block"></div>
                    <div class="weui_cell_bd weui_cell_primary"><p>可用数量</p></div>
                    <div id='stillhave' class="weui_cell_ft_text">0 </div>
                </a>

                <!--<a class="weui_cell" href="javascript:alert(105);">-->
                <!--<div class="weui_cell_hd weui_icon_success_circle" style="width:20px;margin-right:5px;display:block"></div>-->
                <!--<div class="weui_cell_bd weui_cell_primary"><p>账户设置</p></div>-->
                <!--<div class="weui_cell_ft"></div>-->
                <!--</a>-->
            </div>

            <div class="weui_cells_title">今日新开5条记录</div>
            <div class="weui_cells" id="cells_table">
            </div>

            <a href="javascript:open_click();" class="weui_btn weui_btn_primary weui_btn_margin">再开一个</a>

            <br>
            <div class="weui-footer">
                <p class="weui-footer__text" >Copyright &copy; 2016-2017 huakai.org</p>
            </div>
            <br>
        </div>
    </div>
</div>

</body>
</html>