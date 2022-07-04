<?php
date_default_timezone_set('PRC');
$users = array();
//创建websocket服务器对象，监听0.0.0.0:9502端口
$ws = new swoole_websocket_server("0.0.0.0", 9502);

$ws->set(array(
    'worker_num' => 1,
));

//监听WebSocket连接打开事件
$ws->on('open', function ($ws, $request) {
    // var_dump($request->fd, $request->get, $request->server);
    //global $users;
    // var_dump($users);
    // $users[] = $request->fd;
    //var_dump($users);
    //$ws->push($request->fd, "hello, welcome\n");
});


//监听WebSocket消息事件
$ws->on('message', function ($ws, $frame) {
    global $users;
//      var_dump($frame);
    $data = $frame->data;
    $arr = explode('n|@|',$data);
    if(count($arr)>1){
        $users[$frame->fd] = $arr[1];
        foreach($users as $fd=>$name){
            $ws->push($fd,'<p><span style="color:#177bbb">系统通知</span><span style="color:#aaaaaa">('.date('H:i:s').')</span>:'.$arr[1].'加入聊天</p>');
        }
    }else{
        // var_dump($users);
        foreach($users as $fd=>$name){
            //$msg = 'from'.$name.":{$frame->data}\n";
            $msg = '<p><span style="color:#177bbb">'.$users[$frame->fd].'</span> <span style="color:#aaaaaa">('.date('H:i:s').')</span>: '.$frame->data.'</p>';
            $ws->push($fd,$msg);
        }
    }

});

//监听WebSocket连接关闭事件
$ws->on('close', function ($ws, $fd) {
    echo "client-{$fd} is closed\n";
});

$ws->start();