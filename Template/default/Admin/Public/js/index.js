/*严格模式 会检查对象中重复的键 未声明变量 重复的参数*/
'use strict';
$(function(){$("#nav-top .nt-nav li").click(function(n){
	var e=$(this).index();/*返回指定元素相对于其他指定元素的index位置*/
	$(this).addClass('ntn-active').siblings('li').removeClass('ntn-active'),$("#nav-left .nl-con").eq(e).addClass("nl-show").siblings(".nl-con").removeClass("nl-show"),$("#nav-left .nl-con").eq(e).find("dd").eq(0).click();/*切换tab栏时对应的内容框第一个dd被点击即去这个dd的框架页面*/
	var i = $("#nav-left .nl-con").eq(e).find("a").attr("href");/*获得对应的内容框第一个dd的href属性*/
	/*console.log(i);/index.php/Admin/Comment/index
index.js:7 /index.php/Admin/Recycle/article*/
              $("#content-iframe").attr("src", i) }),/*这样会动态的在点击导航栏时自动切换该栏目下的第一个dd对应的frame框架的内容页，默认是welcome页面*/
              $("#nav-left .nl-con dd").click(function(n) { $(".nl-checked").hide(), $(this).find(".nl-checked").show()
              /*关于蓝色箭头的显示与消失是通过$("#nav-left .nl-con dd").click(function(n) { $(".nl-checked").hide()将display改成none 每个li本身都有箭头CSS，dd哪个被点击哪个显示*/
})});


// tab栏切换
// <script>
// 	  //需求：鼠标放到上面的li上，li本身变色(添加类)，对应的span也显示出来(添加类);
//             //思路：1.点亮盒子。   2.利用索引值显示盒子。
//             var liArr = document.getElementsByTagName("li");
//             var spanArr = document.getElementsByTagName("span");
//             for(var i=0;i<liArr.length;i++){
//             	liArr[i].index=i;
//             	liArr[i].onmouseover=function(){
//             		for(var j=0;j<liArr.length;j++){
//             			liArr[j].className="";
//             			spanArr[j].className="";
//             		}
//             		this.className="current";
//             		spanArr[this.index].className="show";
//             	}
//             } 
// </script>



  // <script>
  //       jQuery(window).ready(function () {
  //           //需求:鼠标放到那个li上，让该li添加active类，下面的对应的.main的div添加selected
  //           $(".tab>li").mouseenter(function () {
  //               //不用判断，当前的li添加active类，其他的删除active类
  //               $(this).addClass("active").siblings("li").removeClass("active");
  //               //对应索引值的div添加selected类，其他的删除selected类
  //               $(".products>div").eq($(this).index()).addClass("selected").siblings("div").removeClass("selected");
  //           });
  //       });
  //   </script>