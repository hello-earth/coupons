<!DOCTYPE html>

<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <link rel="stylesheet" href="./css/weui.css?f83d"/>
    <link rel="stylesheet" href="./css/weui.index.css"/>
    <link rel="shortcut icon" href="./favicon.ico" type="image/x-icon"/>
    <link rel="bookmark icon" href="./favicon.ico"/>

    <script src="./js/jquery-1.8.3.min.js"></script>
    <script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
    <script src="./js/robot-service.js?cic9"></script>
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
                <a class="weui_cell">
                    <div class="weui_cell_hd weui_icon_success_circle" style="width:20px;margin-right:5px;display:block"></div>
                    <div class="weui_cell_bd weui_cell_primary"><p>今日已开</p></div>
                    <div id='todayused' class="weui_cell_ft_text" style="color: #991100;">0 </div>
                </a>

                <a class="weui_cell">
                    <div class="weui_cell_hd weui_icon_success_circle" style="width:20px;margin-right:5px;display:block"></div>
                    <div class="weui_cell_bd weui_cell_primary"><p>可用数量</p></div>
                    <div id='stillhave' class="weui_cell_ft_text">0 </div>
                </a>
            </div>

            <div id='todaytstatus' class="weui_cells_title">今日暂无开包记录</div>
            <div class="weui_cells" id="cells_table">
            </div>

            <div class="weui_footer weui_footer_fixed_bottom">
                <p class="weui_footer_text" >Copyright &copy; 2016-2017 huakai.org</p>
            </div>
            <br>
        </div>
    </div>
</div>

<div id="dialogs">
    <div class="js_dialog" id="js_dialog1" style="display: none;">
        <div class="weui_mask"></div>
        <div class="weui_dialog">
            <div class="weui_dialog_bd"><br>今天你已打开15个分享红包了！<br>请明天再来。</div>
            <div class="weui_dialog_ft">
                <a href="javascript:;" class="weui_dialog_confirm">知道了</a>
            </div>
        </div>
    </div>

    <div class="js_dialog" id="js_dialog2" style="display: none;">
        <div class="weui_mask"></div>
        <div class="weui_dialog">
            <div class="weui_dialog_bd"><br>你目前没有可用红包数了！<br>请给机器人分享红包后再来。</div>
            <div class="weui_dialog_ft">
                <a href="javascript:;" class="weui_dialog_confirm">知道了</a>
            </div>
        </div>
    </div>

    <div class="js_dialog" id="js_dialog3" style="display: none;">
        <div class="weui_mask"></div>
        <div class="weui_dialog">
            <div class="weui_dialog_bd"><br>你的账号因违规已被冻结，请到“账户状态”中查看原因及解封方法。</div>
            <div class="weui_dialog_ft">
                <a href="javascript:;" class="weui_dialog_confirm">知道了</a>
            </div>
        </div>
    </div>

    <div class="js_dialog" id="js_dialog4" style="display: none;">
        <div class="weui_mask"></div>
        <div class="weui_dialog">
            <div class="weui_dialog_hd">提示</div>
            <div id="mymsg" class="weui_dialog_bd"></div>
            <div class="weui_dialog_ft">
                <a href="javascript:;" class="weui_dialog_confirm">知道了</a>
            </div>
        </div>
    </div>

    <div class="js_dialog" id="js_dialog5" style="display: none;">
        <div id="compid" name="compid" > </div>
        <div class="weui_mask"></div>
        <div class="weui_dialog">
            <div class="weui_dialog_bd">
                <div class="weui_cells_title" ><h3>请选择投诉原因</h3>恶意投诉会被拉黑处理</div>
                <div class="weui_cells weui_cells_radio">

                    <label class="weui_cell weui_check_label" for="x11">
                        <div class="weui_cell_bd"><p>提示被抢光</p></div>
                        &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;
                        <div class="weui_cell_ft">
                            <input type="radio" class="weui_check" name="radio1" id="x11" value="0" checked="checked"/>
                            <span class="weui_icon_checked"></span>
                        </div>
                    </label>

                    <label class="weui_cell weui_check_label" for="x12">
                        <div class="weui_cell_bd">
                            <p>提示超过15天</p>
                        </div>
                        &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;
                        <div class="weui_cell_ft">
                            <input type="radio" class="weui_check" name="radio1"  value="1"  id="x12"/>
                            <span class="weui_icon_checked"></span>
                        </div>
                    </label>
                    <label class="weui_cell weui_check_label" for="x13">
                        <div class="weui_cell_bd">
                            <p>提示已抢到过一个</p>
                        </div>
                        &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;
                        <div class="weui_cell_ft">
                            <input type="radio" class="weui_check" name="radio1" value="2"  id="x13"/>
                            <span class="weui_icon_checked"></span>
                        </div>
                    </label>
                </div>
            </div>
            <div class="weui_dialog_ft">
                <a class="weui_dialog_btn  weui_dialog_default">再想想</a>
                <a href="javascript:reportComplaint($('input:radio[name=\'radio1\']:checked').val());" class="weui_dialog_btn weui_dialog_confirm">提交</a>
            </div>
        </div>
    </div>

</div>

<div id="loadingToast" style="display:none;">
    <div class="weui_mask_transparent"></div>
    <div class="weui_toast">
        <i class="weui_loading weui_icon_toast"></i>
        <p class="weui_toast_content">数据加载中</p>
    </div>
</div>

</body>
</html>