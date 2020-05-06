<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<form action="/api/mini_auth/login" method="post">
    mobile : <input type="text" name="name" value="blue12856278582">
    password : <input type="text" name="password" value="123456">
    <input type="submit" value="submit">
</form>

<br>注册: <br>
<form action="/api/mini_auth/register" method="post">
    name : <input type="text" name="name" id="name" value="blue">
    password : <input type="text" name="password" value="123456">
    password_confirmation : <input type="text" name="password_confirmation" value="123456">
    <input type="submit" value="submit">
</form>
</body>
</html>

<script>
    window.onload = function () {
        var oEmail = document.getElementById("name");
        var numbers =  Array(10);
        for(var i =0;i<numbers.length;i++){
            numbers[i] = Math.floor(Math.random() * 9);
        }
        oEmail.value = 'blue1' + numbers.join('');
    };

</script>