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
    <h3 style="color: #8c8c8c;">view `upload` it works!</h3>
    <table class="table table-bordered">
        <tr>
            <th>img</th>
            <th>action</th>
        </tr>
        <tr>
            <td><img src="/{{$file}}" width="100" height="100" alt=""></td>
            <td><a href="{{$download}}">download</a></td>
        </tr>
    </table>
</body>
</html>