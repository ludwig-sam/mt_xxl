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

    <form action="javascript:void(0)" method="post">
        <label for="">图片素材</label><br>
        <input type="file"  onchange="change(this, 'image_file')">
        <input type="text" id="image_file" name="image_file" placeholder="">
        <input type="submit" onclick="upload('http://mt.cn')" value="上传-dev">
        <input type="submit" onclick="upload('http://mt.0578app.com')" value="上传-test">
    </form>

    <br>

    <form action="javascript:void(0)" method="post">
        <label for="">图文素材</label><br>
        <input type="file"  onchange="changeThumb(this, 'thumb_file')">
        <input type="text" id="thumb_file" name="image_file" placeholder="">
        <input type="submit" onclick="uploadArticle('http://mt.cn')" value="上传-dev">
        <input type="submit" onclick="uploadArticle('http://mt.0578app.com')" value="上传-test">
    </form>

    <pre id="response" style="width: 400px;height: 300px;color: red;">

    </pre>

</body>
</html>

<script>

    var token = '';

    var articles = [{
        "title": "测试title",
        "thumb_media_id": '',
        "thumb_media_url": '',
        "author": 'blue',
        "digest": 'DIGEST',
        "show_cover_pic": 1,
        "content": 'content',
        "content_source_url": 'http://www.baidu.com'
    },
    ];

    function changeThumb(file, idName) {

        if(!file.files || !file.files[0])return;

        toUrlData(file.files[0], function (urlData) {
            login(function (token) {
                $.post('http://mt.cn/api/admin/material/upload_thumb?token=' + token, {"thumb_file" : urlData}, function (ret) {
                    alert(ret.msg);

                    articles[0].thumb_media_id = ret.data.thumb_media_id;
                    articles[0].thumb_media_url = ret.data.thumb_media_url;
                }, 'json', function (err) {
                    alert(err.body);
                });
            });
        });
    }

    function uploadArticle(host) {
        login(function (token) {
            $.post(host + '/api/admin/material/create/article?token=' + token, {articles}, function (ret) {
                $("#response").html(JSON.stringify(ret));
            }, 'json', function (err) {
                alert(err.body);
            });
        });
    }

    function upload(host) {
        login(function (token) {
            $.post(host + '/api/admin/material/create/image?token=' + token, {"image_file" : document.getElementById("image_file").value}, function (ret) {
                $("#response").html(JSON.stringify(ret));
            }, 'json', function (err) {
                alert(err.body);
            });
        });
    }

    function change(file, idName) {
        toUrlData(file.files[0], function (urlData) {
            document.getElementById(idName).value = urlData;
        })
    }

    function login(callBack){

        if(token){
            callBack(token);
            return true;
        }

        $.post('/api/adauth/login', {"user_name" : 'blue1', "password" : '123456'},  function (ret, status, jqXHR) {
            if(ret.retcode == "success"){
                callBack(jqXHR.getResponseHeader('token'));
            }else{
                alert(ret.msg);
            }
        }, 'json', function (err) {
            alert(err.body);
        });
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