/*
 * WeUI v0.4.0 (https://github.com/weui/weui)
 * Copyright 2016 Tencent, Inc.
 * Licensed under the MIT license
 *
 * Created by jfengjiang on 2015/9/11.
 *
 * AmMRLi 自定义样式
 * 2016-2-29 04:02:25
 * 配合WeUI 0.4.0 版本
 */

$(function () {
    // page stack
    var stack = [];
    var $container = $('.js_container');
    $container.on('click', '.js_cell[data-id]', function () {
        var id = $(this).data('id');
        go(id);
    });
	//增加九宫格js事件 开始 AmMRLi 2016年1月25日02:02:23
    $container.on('click', '.js_grid[data-id]', function () {
        var id = $(this).data('id');
        go(id);
    });
	//增加九宫格js事件 结束 AmMRLi 2016年1月25日02:02:23

    // location.hash = '#hash1' 和点击后退都会触发`hashchange`，这个demo页面只关心后退
    $(window).on('hashchange', function (e) {
        if (/#.+/gi.test(e.newURL)) {
            return;
        }
        var $top = stack.pop();
        if (!$top) {
            return;
        }
        $top.addClass('slideOut').on('animationend', function () {
            $top.remove();
        }).on('webkitAnimationEnd', function () {
            $top.remove();
        });
    });

    function go(id){
        var $tpl = $($('#tpl_' + id).html()).addClass('slideIn').addClass(id);
        $container.append($tpl);
        stack.push($tpl);
        // why not use `history.pushState`, https://github.com/weui/weui/issues/26
        //history.pushState({id: id}, '', '#' + id);
        location.hash = '#' + id;

        $($tpl).on('webkitAnimationEnd', function (){
            $(this).removeClass('slideIn');
        }).on('animationend', function (){
            $(this).removeClass('slideIn');
        });
        // tooltips
        if (id == 'cell') {
            $('.js_tooltips').show();
            setTimeout(function (){
                $('.js_tooltips').hide();
            }, 2000);
        }
    }

    if (/#.*/gi.test(location.href)) {
        go(location.hash.slice(1));
    }

    // toast
    $container.on('click', '#showToast', function () {
        $('#toast').show();
        setTimeout(function () {
            $('#toast').hide();
        }, 3000);
    });
    $container.on('click', '#showLoadingToast', function () {
        $('#loadingToast').show();
        setTimeout(function () {
            $('#loadingToast').hide();
        }, 3000);
    });

    // dialog
    $container.on('click', '#showDialog1', function () {
        $('#dialog1').show();
        $('#dialog1').find('.weui_btn_dialog').on('click', function () {
            $('#dialog1').hide();
        });
    });
    $container.on('click', '#showDialog2', function () {
        $('#dialog2').show();
        $('#dialog2').find('.weui_btn_dialog').on('click', function () {
            $('#dialog2').hide();
        });
    });

    // Actionsheet
    function hideActionSheet(weuiActionsheet, mask) {
        weuiActionsheet.removeClass('weui_actionsheet_toggle');
        mask.removeClass('weui_fade_toggle');
        weuiActionsheet.on('transitionend', function () {
            mask.hide();
        }).on('webkitTransitionEnd', function () {
            mask.hide();
        })
    }
    $container.on('click','#showActionSheet', function () {
        var mask = $('#mask');
        var weuiActionsheet = $('#weui_actionsheet');
        weuiActionsheet.addClass('weui_actionsheet_toggle');
        mask.show().addClass('weui_fade_toggle').click(function () {
            hideActionSheet(weuiActionsheet, mask);
        });
        $('#actionsheet_cancel').click(function () {
            hideActionSheet(weuiActionsheet, mask);
        });
        //AmMRLi 韵部搜索 2015年12月29日00:19:21
		/*
        $('#pingshuiyun').click(function () {
            window.location.href='tools/Yun/psy.php';
        });
        $('#zhonghuaxinyun').click(function () {
            window.location.href='tools/Yun/zhxy.php';
        });
        $('#cilinzhengyun').click(function () {
            window.location.href='tools/Yun/clzy.php';
        });
        $('#yunbusousuo').click(function () {
            window.location.href='tools/Yun/search.php';
        });
        */
        //AmMRLi 韵部搜索 2015年12月29日00:19:21
        weuiActionsheet.unbind('transitionend').unbind('webkitTransitionEnd');
    });

    // 增加 searchbar js事件 AmMRLi 2016-2-29 05:55:14
	/*
	$container.on('focus', '#search_input', function () {
            $('#search_text').hide();
            $('#search_bar').addClass('weui_search_focusing');
        }).on('blur', '#search_input', function () {
            $('#search_bar').removeClass('weui_search_focusing');
            !!$(this).val() ? $('#search_text').hide() : $('#search_text').show();
        }).on('input', '#search_input', function () {
			$('#search_text').show();
            !!$(this).val() ? $('#search_text').hide() : $('#search_text').show();
        }).on('submit', '#search_form', function () {
			var search_data = $('#search_input').val();
			alert(search_data);
            //if (typeof options.onsubmit == 'function') {
                //bindEvent($('#search_input'), 'onsubmit', options);
            //}
        }).on('click', '#search_cancel', function () {
            $('#search_input').val('');
            $('#search_bar').removeClass('weui_search_focusing');
            $('#search_text').hide();
        }).on('click', '#search_clear', function () {
            $('#search_input').val('');
            $('#search_show').hide();
        });
    */
    // 增加 searchbar js事件 AmMRLi 2016-2-29 05:55:14
});