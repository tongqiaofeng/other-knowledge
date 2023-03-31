var express = require('express');
var router = express.Router();

/* GET users listing. */
router.post('/', function(req, res, next) {
  let uname=req.body.uname;
  let upwd=req.body.upwd;
  if(uname==="lucky" & upwd==="123456"){
    res.send("登陆成功!");
  }else{
    res.send("用户名或密码错误!");
  }
});

module.exports = router;
