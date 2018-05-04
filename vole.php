<html lang="zh-cmn-Hans">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <title>赌神计划</title>
    <link rel="stylesheet" href="./css/weui.css"/>
    <script src="./js/jquery-1.8.3.min.js"></script>
    <style type="text/css">body{background-color: #FBF9FE;}.weui_label{width: 6em;} </style>
</head>
<body>
<form name="cookie_data">
    <div class="weui_cells_title">喜鹊Cookie数据提交</div>
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
                data: {pw: cookie_data.pw.value, "sess": cookie_data.session.value},
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
</script>
</body>

</html>
