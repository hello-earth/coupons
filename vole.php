<html lang="zh-cmn-Hans">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <title>赌神计划</title>
    <link rel="stylesheet" href="./css/weui.css"/>
    <script src="./js/jquery-1.8.3.min.js"></script>
    <style type="text/css">body{background-color: #FBF9FE;}.weui_label{width: 6em;}.page-bd{ padding:0;}.page-hd-title {padding:20px; font-size: 20px; font-weight: 400; text-align: left; margin-bottom: 15px; } </style>
</head>
<body>
<form name="cookie_data">
    <h1 class="page-hd-title">
        Cookie数据提交
    </h1>
    <div class="page-bd">

        <div class="weui_cells_title">任务类型</div>
        <div class="weui_cells weui_cells_radio">
            <label class="weui_cell weui_cell weui_check_label" for="x11">
                <div class="weui_cell_bd weui_cell_primary"><p>喜鹊（白金钻，188分）</p></div>
                &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;
                <div class="weui_cell_ft">
                    <input type="radio" class="weui_check" name="radio1" id="x11" value="0" checked="checked"/>
                    <span class="weui_icon_checked"></span>
                </div>
            </label>

            <label class="weui_cell weui_check_label" for="x12">
                <div class="weui_cell_bd weui_cell_primary">
                    <p>保龄球（100分）</p>
                </div>
                &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;
                <div class="weui_cell_ft">
                    <input type="radio" class="weui_check" name="radio1"  value="1"  id="x12"/>
                    <span class="weui_icon_checked"></span>
                </div>
            </label>
        </div>

        <div class="weui_cells weui_cells_form">
            <div class="weui_cell">
                <div class="weui_cell_hd">
                    <label class="weui_label">通关纸条</label>
                </div>
                <div class="weui_cell_bd weui_cell_primary">
                    <input class="weui_input" name="pw" type="text" placeholder="请输入我给你们的悄悄话" />
                </div>
            </div>
            <div class="weui_cell">
                <div class="weui_cell_hd">
                    <label class="weui_label">SESSION</label>
                </div>
                <div class="weui_cell_bd weui_cell_primary">
                    <input class="weui_input" name="session" type="text" placeholder="SESSION=8dd2ae36-ced2-431a-a0c7-14cd1a42ef7" />
                </div>
            </div>
        </div>

        <p class="weui_cells_tips">提示:只支持特定用户提交数据，其他人提交无效</p>
        <p class="weui_cells_tips"></p>
        <div class="weui_btn_area">
            <a href="javascript:submit_click();"  class="weui_btn weui_btn_primary" id="button" href="javascript:">提交</a>
            <a href="javascript:getlog();"  class="weui_btn weui_btn_primary" id="button" href="javascript:">查看日志</a>
        </div>
    </div>
</form>
<script type="text/javascript">
    function submit_click(){
        if((cookie_data.pw.value.length * cookie_data.session.value.length)>0) {
            $.ajax({
                //提交数据的类型 POST GET
                type: "POST",
                //提交的网址
                url: "./ctrl/saveSession.php",
                //提交的数据
                data: {method:$('input:radio[name=\'radio1\']:checked').val(), pw: cookie_data.pw.value, "sess": cookie_data.session.value},
                //返回数据的格式
                datatype: "json",
                //在请求之前调用的函数
                //成功返回之后调用的函数
                success: function (data) {
                    alert(data.msg);
                },
                //调用出错执行的函数
                error: function () {
                    //请求出错处理
                }
            });
        }else{
            alert("参数不完整");
        }
    }

    function getlog() {
        if(0==$('input:radio[name=\'radio1\']:checked').val()){
            window.location = "http://j5.huakai.org/index2.php?log=logg";
        }else{
            window.location = "http://j5.huakai.org/index2.php?log=logq";
        }
    }
</script>
</body>

</html>
