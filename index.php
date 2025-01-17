<?php
    phpinfo();

?>
<!DOCTYPE html>
<html>
<head>
    <title></title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta charset="utf-8"/>
    <link rel="stylesheet" href="http://cdn.bootcss.com/bootstrap/3.3.4/css/bootstrap.min.css">
    <link rel="stylesheet" href="http://cdn.bootcss.com/bootstrap/3.3.4/css/bootstrap-theme.min.css">
    <script src="http://cdn.bootcss.com/jquery/1.11.2/jquery.min.js"></script>
    <script src="http://cdn.bootcss.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
    <script src="http://cdn.bootcss.com/socket.io/1.3.5/socket.io.js"></script>
</head>
<body>
<div id="box" style="max-width:700px;margin:0 auto;">
    <div class="panel panel-default">
        <div class="panel-heading"><h2>聊天室</h2><span style="color:green;display:none;">(当前在线:<span id="length">0</span>人)</span></div>
        <div class="panel-body" id="body" style="height:400px;overflow-y:auto;">
        </div>
    </div>
    <div class="input-group">
        <input type="text" class="form-control" id="in" placeholder="您想说什么?" aria-describedby="basic-addon2">
        <span class="input-group-addon" id="basic-addon2" style="cursor:pointer;">发送</span>
    </div>
</div>
<div class="modal fade bs-example-modal-sm" data-backdrop="static" id="model" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
        <div class="input-group">
            <input type="text" class="form-control" id="name" placeholder="请输入您的昵称" aria-describedby="basic-addon3">
            <span class="input-group-addon" id="basic-addon3" style="cursor:pointer;">开始聊天</span>
        </div>
    </div>
  </div>
</div>
</body>
<script>
$(document).ready(function(){
    window.username = 'others';
    var wsServer = 'ws://192.168.151.125:9502';
    var websocket = new WebSocket(wsServer);
    
    websocket.onopen = function (evt) {
        console.log("Connected to WebSocket server.");
        $("#model").modal('show');
    };

    websocket.onmessage = function (evt) {
        console.log('Retrieved data from server: ' + evt.data);
        $(".panel-body").append(evt.data);
        //$(".panel-body").append('<p><span style="color:#177bbb">'+evt.data.username+'</span> <span style="color:#aaaaaa">('+evt.data.time+')</span>: '+evt.data.msg+'</p>');
        var body = document.getElementById("body");
        body.scrollTop = body.scrollHeight;
        $("#in").focus();        
        
    };
    
    $("#basic-addon2").click(function(){
        var msg = $("#in").val();
        websocket.send(msg);
        $("#in").val('');
    });
    
    $("#basic-addon3").click(function(){
        window.username = $("#name").val();
        websocket.send("login|@|"+window.username);        
        $("#model").modal('hide');
    });
    


});
</script>
</html>