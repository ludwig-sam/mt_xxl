<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>扫码支付</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdn.bootcss.com/bootstrap/4.0.0/css/bootstrap.min.css"
          integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://cdn.bootcss.com/jquery/3.3.1/jquery.js" type="text/javascript"></script>
    <!-- 最新版本的 Bootstrap 核心 CSS 文件 -->
    <link rel="stylesheet" href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css"
          integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <script src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <!-- Styles -->
    <style>
        html, body {
            background-color: #f5f5f5;
            font-family: '微软雅黑';
            font-weight: 100;
            height: 100vh;
            margin: 0;
            font-size: 16px;
        }

        input {
            color: coral !important;
            font-size: 20px !important;
        }

        form {
            margin-top: 10px;
        }

        .alert-danger.show {
            top: 0;
        }

        .alert-danger {
            top: -100px;
            transition: top 0.3s ease-in-out;
        }

        button {
            outline: none !important;
        }

        .form-inline {
            margin-bottom: 8px;
        }

        .coupon .left {
            width: 33%;
            float: left;
            font-size: 12px;
            color: #333;
        }

        .coupon .amount {
            font-family: "微软雅黑";
            color: #49ce2d;
            font-size: 20px;
            font-weight: bold;
        }

        .coupon .mch_name {
            font-size: 14px;
        }

        .coupon .date_range {
            color: #827777;
            font-size: 13px;
        }

        .coupon img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            position: absolute;
            right: 2px;
            top: 2px;
        }

        .list-group-item {
            margin-bottom: 5px;
            border: 1px dashed #e8e8e8;
            border-radius: 3px;
        }

        .coupon.out {
            border: 1px solid #dee2de;
            border-radius: 3px;
            background: #efefef;
            padding: 5px;
        }

        .coupon.out img {
            display: none;
        }

        .selected .sign {
            position: absolute;
            left: 0;
            top: 0;
            color: #08bb08;
            font-size: 16px;
            display: inline;
        }

        .sign {
            display: none;
        }

        .amount {
            color: #20895e;
            font-size: 20px;
        }

    </style>
</head>
<body>

<div class="alert alert-danger" role="alert">错误</div>

<div class="container-fluid">
    <form action="javascript:submits()" id="from">

        <input type="hidden" class="form-control" placeholder="" name="exe_id" value="{{$exe_id}}">
        <input type="hidden" class="form-control" placeholder="" name="key" value="{{$key}}">
        <input type="hidden" class="form-control" placeholder="" name="code_id" value="0">

        <div class="form-group">
            <label for="">支付方式</label>
            <input type="radio" checked name="channel" value="upay_wx_scan_code">
            微信
            <input type="radio" name="channel" value="upay_alipay_scan_code">
            支付宝
        </div>

        <div class="form-group">
            <label for="">输入金额</label>
            <input type="text" onblur="calculation();" class="form-control" placeholder="" name="total_amount"
                   value="0.01">
        </div>

        <div class="form-inline">
            <label for="" style="width: 30%">优惠券</label>
            <p style="width: 70%;text-align: right" onclick="showCoupons()"><i
                        class="glyphicon glyphicon-menu-right"></i></p>
        </div>

        <div class="form-group" id="coupon_selected">

        </div>

        <div class="form-inline">
            <label for="" style="width: 30%">实际支付</label>
            <p style="width: 70%;text-align: right">
                        <span id="amount" class="amount">
                            ￥0.01
                        </span>
            </p>
        </div>

        <button type="submit" class="submit btn btn-success btn-block">确认</button>
    </form>
</div>

</body>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">选择电子券</h4>
            </div>
            <div class="modal-body">
                <ul class="list-group">
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

</html>

<script>

    function getList(fun) {
        $.get('/api/h5pay/card_list?key={{$key}}&exe_id={{$exe_id}}', function (ret) {
            if (ret.retcode != 'success') {
                msg(ret.msg);
                return false;
            }

            fun(ret.data.data);
        });
        return [
            {
                code_id: 1,
                card_id: 1,
                reduce_cost: 2,
                mch_name: "鸭脖",
                start_at: "2018-09-09",
                end_at: "2018-10-10",
                logo_url: "http://img.zcool.cn/community/0153155544df6c0000019ae9b250b8.jpg@900w_1l_2o_100sh.jpg"
            },
            {
                code_id: 2,
                card_id: 1,
                reduce_cost: 2,
                mch_name: "鸭脖2",
                start_at: "2018-09-09",
                end_at: "2018-10-10",
                logo_url: "http://img.zcool.cn/community/0153155544df6c0000019ae9b250b8.jpg@900w_1l_2o_100sh.jpg"
            }
        ];
    }

    var list = [];
    var code_id = 0;

    getList(function (data) {
        list = data;
        init();
    });

    function init() {
        list.forEach(function (row) {
            row.start_at = timeToDate(row.start_time);
            row.end_at = timeToDate(row.end_time);

            $(".list-group").append(createItem(row));
        });
    }

    function select(id) {

        code_id = id;

        $('.selected').removeClass('selected');
        $(this).addClass('selected');

        $('#myModal').modal('hide');

        let row = find(list, id);

        $("#coupon_selected").html('').append(createItem(row, true));

        calculation();
    }

    function calculation() {
        $.post('/api/h5pay/calculation', {
            exe_id: '{{$exe_id}}',
            key: '{{$key}}',
            code_id: code_id,
            total_amount: $("input[name=total_amount]").val()
        }, function (ret) {

            if (ret.retcode !== 'success') {
                msg(ret.msg);
                return false;
            }

            $('#amount').html(ret.data.amount);

        }, 'json');
    }

    function timeToDate(t) {
        var oD = new Date();

        oD.setTime(t * 1000);

        return oD.getFullYear() + '-' + (oD.getMonth() + 1) + '-' + oD.getDate();
    }

    function createItem(data, is_out = false) {

        let container;

        if (data.type == 'DISCOUNT') {
            var name = '折扣';
            var val = (data.discount / 10) + '折';
        } else {
            var name = '满￥' + data.least_cost + '减';
            var val = '￥' + data.reduce_cost;
        }

        let tpl = `<div class="left">
                        <i class="glyphicon glyphicon-screenshot sign"></i>
                        <div class="amount">` + val + `</div>
                        <div class="condition">` + name + `</div>
                    </div>
                    <div class="right">
                        <h4 class="mch_name">` + data.title + `</h4>
                        <small class="date_range">
                            ` + data.start_at + `/` + data.end_at + `
                        </small>
                        <img src="` + data.logo_url + `">
                    </div>`;

        if (is_out) {

            container = document.createElement("div");
            container.className = 'coupon out';
            container.innerHTML = tpl;

            return container;
        }

        let box = document.createElement('li');
        container = document.createElement("div");

        box.appendChild(container);
        box.className = 'list-group-item';
        container.className = 'coupon';

        container.innerHTML = tpl;

        box.onclick = function () {
            select.call(this, data.code_id);
        };

        return box;
    }

    function find(arr, id) {
        for (let i = 0; i < arr.length; i++) {
            if (arr[i].code_id == id) return arr[i];
        }
    }

    function showCoupons() {
        $('#myModal').modal();
    }

    function msg(msg) {
        let elm = $(".alert-danger").get();

        $(".alert-danger").addClass('show').html(msg);

        clearTimeout(elm.t);

        elm.t = setTimeout(function () {
            $(".alert-danger").removeClass('show');
        }, 2000);
    }

    function submits() {

        $("input[name=code_id]").val(code_id);

        if ($('.submit').attr('disabled')) return false;

        $('.submit').attr('disabled', 'disabled');

        $.post('/api/h5pay/create', $("#from").serialize(), function (ret) {

            $('.submit').attr('disabled', false);

            if (ret.retcode != 'success') {
                msg(ret.msg);
                return false;
            }

            window.location.href = ret.data.api_result.qr_code;
        }, 'json');
    }
</script>
