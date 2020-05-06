<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.bootcss.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://cdn.bootcss.com/jquery/3.3.1/jquery.js" type="text/javascript"></script>
    <title>激活会员卡</title>
</head>
<body>




<form action="javascript:void(0)" id="form">

    <div class="form-group">
        <label for="exampleInputPassword1">用户名</label>
        <input type="text" class="form-control" id="name" placeholder="" name="name" value="blue12856278582">
    </div>

    <div class="form-group">
        <label for="exampleInputPassword1">密码</label>
        <input type="text" class="form-control" id="pwd" placeholder="" name="pwd" value="123456">
    </div>

    <div class="form-group">
        <label for="exampleInputPassword1">card_id</label>
        <input type="text" class="form-control" placeholder="" name="card_id" value="{{$card_id}}">
    </div>

    <div class="form-group">
        <label for="exampleInputPassword1">encrypt_code</label>
        <input type="text" class="form-control" placeholder="" name="encrypt_code" value="{{$encrypt_code}}">
    </div>

    <div class="form-group">
        <label for="exampleInputPassword1">手机</label>
        <input type="text" class="form-control" placeholder="" name="mobile" value="13127503298">
    </div>

    <div class="form-group">
        <label for="exampleInputPassword1">姓名</label>
        <input type="text" class="form-control" placeholder="" name="person_name" value="lixingbo">
    </div>

    <div class="form-group">
        <label for="exampleInputPassword1">身份证</label>
        <input type="text" class="form-control" placeholder="" name="id_card" value="420698989898989898">
    </div>

    <div class="form-group">
        <label for="exampleInputPassword1">兴趣爱好</label>
        <input type="text" class="form-control" placeholder="" name="interest[]" value="1">
        <input type="text" class="form-control" placeholder="" name="interest[]" value="12">
    </div>


    <button type="button" onclick="doSubmit()" class="btn btn-primary">Submit</button>
</form>

</body>
</html>

<script>
    var token = null;

    function doSubmit() {

        login(function (tk) {
            token = tk;

            $.ajax({
                "url" : '/api/minipro/member/activate',
                "data" : $("#form").serialize(),
                "dataType" : "json",
                "type" : "post",
                "headers" : {
                    "token" : "bearer " + token
                },
                "success" : function (ret) {
                    alert(ret.msg);
                },
                "error" : function (err) {
                    alert(err.body);
                }
            });

        });


    }

    function login(callBack){

        if(token){
            callBack(token);
            return true;
        }

        $.post('/api/mini_auth/login', {"name" : document.getElementById("name").value, "password" : document.getElementById("pwd").value},  function (ret, status, jqXHR) {
            if(ret.retcode == "success"){
                callBack(jqXHR.getResponseHeader('token'));
            }else{
                alert(ret.msg);
            }
        }, 'json', function (err) {
            alert(err.body);
        });
    }


</script>