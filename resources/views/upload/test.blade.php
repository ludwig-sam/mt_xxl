<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <script src="https://cdn.bootcss.com/jquery/3.3.1/jquery.js" type="text/javascript"></script>
</head>
<body>
    <h3 style="color: #8c8c8c;">view `uploadtest` it works!</h3>

    <input type="text" onblur="addFormActionToken(this)" style="outline: none;width: 600px; height: 30px; border: 1px solid red;" placeholder="">

    <form action="/testDoUpload" method="post" enctype="multipart/form-data">
        <input type="file" name="file">
        <input type="submit" value="form上传">
    </form>

    <form action="/testDoUpload" method="post">
        <input type="file"  onchange="change(this, 'file1')">
        <input type="text" id="file1" name="file" placeholder="">
        <input type="submit" value="base64上传">
    </form>

    <form action="/api/admin/wechat/upload_image" data-act="/api/admin/wechat/upload_image" method="post" enctype="multipart/form-data">
        <input type="file" name="file">
        <input type="submit" value="form上传到微信">
    </form>

    <form action="/api/admin/wechat/upload_image" data-act="/api/admin/wechat/upload_image" method="post">
        <input type="file"  onchange="change(this, 'file2')">
        <input type="text" id="file2" name="file" placeholder="">
        <input type="submit" value="base64上传到微信">
    </form>

    <form action="javascript:void(0)" method="post">
        <input type="file"  onchange="change(this, 'file3')">
        <input type="text" id="file3" name="file" placeholder="">
        <input type="submit" onclick="upload('http://mt.cn')" value="上传-dev">
        <input type="submit" onclick="upload('http://mt.0578app.com')" value="上传-test">
    </form>

    <pre id="response" style="width: 400px;height: 300px;color: red;">

    </pre>
    {{--<textarea class="form-control" name="response" id="response" cols="30" rows="10" placeholder="response"></textarea>--}}

</body>
</html>

<script>

    function upload(host) {
        $.post(host + '/api/upload', {"file" : document.getElementById("file3").value}, function (ret) {
            $("#response").html(JSON.stringify(ret));
        }, 'json', function (err) {
            alert(err.body);
        });
    }

    function addFormActionToken(theInput) {

        var forms = document.getElementsByTagName('form');
        for(var i =0;i<forms.length; i++){
            var attrAct = forms[i].getAttribute('data-act');
            if(attrAct){
                forms[i].setAttribute('action', attrAct + '?token=' + theInput.value);
            }
        }
    }

    function change(file, idName) {
        toUrlData(file.files[0], function (urlData) {
            document.getElementById(idName).value = urlData;
        })
    }

    function toUrlData(curFile, fun) {
        //读取加密的二进制文件流
        var reader = new FileReader();
        reader.readAsDataURL(curFile);
        reader.onload = function (e) {
            fun(this.result);
        };
    }
</script>