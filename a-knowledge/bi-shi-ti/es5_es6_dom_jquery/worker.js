// 在任务函数中要执行的命令
onmessage=function(e){
  for(let i=0;i<1000000000000;i++){
    this.postMessage(i);
  }
}