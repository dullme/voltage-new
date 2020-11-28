<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    <link href="/vendor/laravel-admin/font-awesome/css/font-awesome.min.css" rel="stylesheet">

    <script src="{{ asset('/js/jquery.min.js') }}"></script>
    <script src="{{ asset('/js/axios.min.js') }}"></script>

    <!-- Styles -->
    <style>
        html, body {
            background-color: #fff;
            color: #636b6f;
            font-family: 'Nunito', sans-serif;
            font-weight: 200;
            height: 100vh;
            margin: 0;
        }

        .full-height {
            height: 100vh;
        }

        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center;
        }

        .position-ref {
            position: relative;
        }

        .top-right {
            position: absolute;
            right: 10px;
            top: 18px;
        }

        .content {
            text-align: center;
        }

        .title {
            font-size: 84px;
        }

        .links > a {
            color: #636b6f;
            padding: 0 25px;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: .1rem;
            text-decoration: none;
            text-transform: uppercase;
        }

        .m-b-md {
            margin-bottom: 30px;
        }
    </style>
</head>
<body style="padding: 30px">
<div class="m-b-md">
    <input type="text" placeholder="按票刷箱" id="id" style="width: 400px; height: 20px;margin-bottom: 10px" autocomplete="on">
    <input type="text" placeholder="按箱刷箱" id="code" style="width: 400px; height: 20px" autocomplete="on">
    <input type="button" class="start" value="开始" onclick="change()">
</div>

<div class="loading" style="font-weight: bold"></div>
<span id="yanchi"></span>
<div class="message" style="font-weight: bold"></div>
<script>
    var i = 0;
    var qingqiucishu = 1;
    var is_start = false

    function change(){
        if($('#code').val() == '' && $('#id').val() == ''){
            alert('请输入代码')
            return false
        }

        if(is_start == false){
            start();
            $('.start').val('结束')
            is_start = true
        }else{
            location.reload()
            $('.start').val('开始')
            is_start = false
            $('.loading').html('暂停中...')
        }
    }

    function start(){
        let code = $('#code').val()
        let id = $('#id').val()


        $('.loading').html('第'+qingqiucishu+'次请求中 <i class="fa fa-refresh fa-spin fa-fw"></i>')
        qingqiucishu ++
        axios({
            method: 'post',
            url: '/test',
            data: {
                code:code,
                id:id,
            },
            timeout: 5000,
        }).then(response => {
            console.log(response.data)
            if(response.data){
                if(response.data.oneway=="1" || response.data.oneway == 1){
                    $('.message').css('color','green')
                    $('.message').html('刷箱成功啦！！！！！！！！！！！！！！！！！！！')
                    $('.start').val('还想抢')
                    is_start = false
                    $('.loading').html('成功！！！')
                }else{
                    if(response.data.message == "操作频繁，请过几秒钟后再次尝试" || response.data.message == '一分钟内只能操作50次' || response.data.msg == '请求页面返回请重新登录并且不要频繁发送打印请求!'){

                    }else{
                        i++
                        $('.message').html("<p>第 <span style='font-size: 40px;color: #cb2027'>" + i + "</span> 次请求"+response.data.message+"</p>")
                    }
                    let rrrrr = Math.floor(Math.random()*5+1)
                    $('#yanchi').html('随机延迟了 '+ rrrrr + ' 秒')
                    if(is_start){
                        setTimeout(function (){
                            start()
                        }, rrrrr * 1000)
                    }
                }
                console.log(response.data)
            }else{
                console.log('请求超时了')
                let rrrrr = Math.floor(Math.random()*5+1)
                $('#yanchi').html('随机延迟了 '+ rrrrr + ' 秒')
                if(is_start){
                    setTimeout(function (){
                        start()
                    }, rrrrr * 1000)
                }
            }
        }).catch(error => {
            console.log('出错了')
            let rrrrr = Math.floor(Math.random()*5+1)
            $('#yanchi').html('随机延迟了 '+ rrrrr + ' 秒')
            setTimeout(function (){
                start()
            }, rrrrr * 1000)
        });
    }
</script>

</body>
</html>
