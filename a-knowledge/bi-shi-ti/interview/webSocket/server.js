// 获得WebSocketServer类型
let WebSocketServer=require('ws').Server;
// 创建WebSocketServer对象实例，并监听指定端口
let wss=new WebSocketServer({port:8080});
// 创建保存所有已连接到服务器的客户端对象的数组
let clients=[];
// 为服务器添加connection事件监听，当有客户端连接到服务端
//时，立刻将客户端对象保存进数组中
wss.on('connection',function connection(ws){
  console.log("一个客户端连接到服务器");
  if(clients.indexOf(ws)===-1){  //如果是首次连接
    clients.push(ws);  //就将当前连接保存到数组备用
    console.log("有"+clients.length+"个客户端在线");
  }
  // 为每个ws对象绑定message事件，当某个客户端发来消息时，自动触发
  ws.on('message',function incoming(message){
    console.log("收到消息:"+message);
    // 遍历clients数组中每个其他客户端对象，并发送消息给其他客户端
    for(var c of clients){
      if(c!=ws){ 
        c.send(message);
      }
    }
  })
})