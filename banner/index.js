// 简单说一下我是怎么想的：1.分步实现，先实现图片自己动，在加其它的功能
// 2.每实现一个功能要立马去测bug，因为放到后面就越难找了。
// 3.轮播向左，向右是两个互相联系的方法，需要找到彼此的关系
var imgae_div = document.getElementsByClassName('imgae_div')[0];
var imgae_div_child = imgae_div.children;
var btn = document.getElementsByClassName('btn')[0];
var block = document.getElementsByClassName('block')[0];
var new_point = document.getElementsByClassName("point")[0].children;
new_point[0].style.backgroundColor = "#000000";
// 利用函数的封装 ps：图片轮播离不开计时器，且个人觉得用setIntervar比较多
img_work();

function img_work() {
  time = setInterval(function () {
    img_workfirst('left', 0); //两个参数，判断向左还是向右轮播，索引
  }, 1500);
}
// console.log(point.child);
function img_workfirst(left_right, cindex) {
  // 这里面首先说一下css中写好的默认层关系：从第1张到第6张为别为 6 5 4 3 4 5，和页面的布局有关
  var firstpage = { //当前页的各种属性
    // getComputedStyle()获取css属性
    left: window.getComputedStyle(imgae_div_child[cindex]).left,
    top: window.getComputedStyle(imgae_div_child[cindex]).top,
    zIndex: window.getComputedStyle(imgae_div_child[cindex]).zIndex,
    backcolor: window.getComputedStyle(new_point[cindex]).backgroundColor
  };
  if (left_right == 'left') {
    // 向左轮播为默认轮播
    for (var i = 0; i < imgae_div_child.length; i++) {
      // for循环遍历所有元素
      if (i == imgae_div_child.length - 1) {
        // 当前页的下一张为 最后一张（位置都是动态切换的）
        imgae_div_child[i].style.left = firstpage.left;
        imgae_div_child[i].style.top = firstpage.top;
        imgae_div_child[i].style.zIndex = firstpage.zIndex;
        new_point[i].style.backgroundColor = firstpage.backcolor;
      } else {
        // 其它页对应为它前面元素的属性
        imgae_div_child[i].style.left = window.getComputedStyle(imgae_div_child[i + 1]).left;
        imgae_div_child[i].style.top = window.getComputedStyle(imgae_div_child[i + 1]).top;
        imgae_div_child[i].style.zIndex = window.getComputedStyle(imgae_div_child[i + 1]).zIndex;
        new_point[i].style.backgroundColor = window.getComputedStyle(new_point[i + 1]).backgroundColor;

      }
    }
  }
  // 向右轮播，借助向左轮播分析
  else {
    for (var i = imgae_div_child.length - 1; i >= 0; i--) {
      if (i == 0) {
        imgae_div_child[i].style.left = firstpage.left;
        imgae_div_child[i].style.top = firstpage.top;
        imgae_div_child[i].style.zIndex = firstpage.zIndex;
        new_point[i].style.backgroundColor = firstpage.backcolor;

      } else {
        imgae_div_child[i].style.left = window.getComputedStyle(imgae_div_child[i - 1]).left;
        imgae_div_child[i].style.top = window.getComputedStyle(imgae_div_child[i - 1]).top;
        imgae_div_child[i].style.zIndex = window.getComputedStyle(imgae_div_child[i - 1]).zIndex;
        new_point[i].style.backgroundColor = window.getComputedStyle(new_point[i - 1]).backgroundColor;


      }
    }
  }
  firstpage = null;
  // 将表示当前页的数据清空，防止bug（因为当前页也是动态变化的，需要动态创建）
}
// console.log(new_point);

// 消除一些bug
window.onblur = function () { //窗口失焦时，计时器停止
  clearInterval(time);
}
window.onfocus = function () {
  img_work(); //获焦时开启计时器
}
document.onselectstart = function () { //禁止用户复制
  return false;
}
block.οnmοuseenter = function () { //鼠标进入轮播图时，两个按钮滑动出来
  clearInterval(time);
  btn.children[1].className = 'marright';
  btn.children[0].className = 'marleft';
}
block.οnmοuseleave = function () { //离开时和平时隐藏
  img_work();
  btn.children[1].className = '';
  btn.children[0].className = '';
  for (var k = 0; k < imgae_div_child.length; k++) {
    imgae_div_child[k].style.transitionDuration = "0.5s";
  }
}
// 对应的左右按钮的点击事件
btn.children[0].οnclick = function (event) {
  if (event.detail == 1) { //用于控制鼠标的连击，但是效果对于故意测试来说还是有所缺陷 下同
    img_workfirst('left', 0);
  }
}
btn.children[1].οnclick = function (event) {
  if (event.detail == 1) {
    img_workfirst('right', imgae_div_child.length - 1);
  }
}


// point的事件
for (var i = 0; i < new_point.length; i++) {
  new_point[i].index = i;
  new_point[i].οnclick = function () {
    for (var k = 0; k < imgae_div_child.length; k++) {
      imgae_div_child[k].style.transitionDuration = '0.1s'; //动画完成
    }
    var oldindex = 0;
    for (var k = 0; k < new_point.length; k++) {
      if (new_point[k].style.backgroundColor == 'rgb(0, 0, 0)') { //格式必须统一
        oldindex = new_point[k].index;
      }
    }
    var num = 0; //判断计算转动次数
    if (this.index > oldindex) { //所需页在当前页的左（左右相对于下方点来说）
      num = this.index - oldindex;
      var timego = setInterval(function () {
        num--;
        if (num < 0) {
          clearInterval(timego);
        } else {
          img_workfirst('right', 5) //因为方向变了，所以下一页就为当前页的上一页，也就是cindex为5
        }
      }, 100); //动画时间缩短，优化体验
    } else { //所需页在当前页的左（左右相对于下方点来说）
      num = Math.abs(this.index - oldindex);
      var timego = setInterval(function () {
        num--;
        if (num < 0) {
          clearInterval(timego);
        } else {
          img_workfirst('left', 0)
        }
      }, 100);
    }
  }
}