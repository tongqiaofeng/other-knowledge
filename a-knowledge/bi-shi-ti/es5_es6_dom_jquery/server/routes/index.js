var express = require('express');
var router = express.Router();

/* GET home page. */
let products=[
  {lid:1,pname:"台式机",price:3600},
  {lid:2,pname:"笔记本",price:6899},
  {lid:3,pname:"手机",price:2300}
]
router.get('/', function(req, res, next) {
  // res.send("欢迎访问首页");
  res.send(products);
});
router.get('/details',function(req,res,next){
  let lid=req.query.lid;
  res.send(products[lid-1]);
})
module.exports = router;
