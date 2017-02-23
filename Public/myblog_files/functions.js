jQuery(function(){

function autFun2(){
	var mW2=$(".slider").width();
	var mBL2=640/173;
	$(".slider .slideBox .bd").css("height",mW2/mBL2);
	$(".slider .slideBox .bd img").css("width",mW2);
	$(".slider .slideBox .bd img").css("height",mW2/mBL2);
}

setInterval(autFun2,1);



})
$(document).ready(function(){
    $("#btn-bars").click(function(){
	$(".header menu").slideToggle(500);
    });
});
$(function(){
    $('#monavber li').hover(function(){
       $(this).addClass('on');  
    },function(){
       $(this).removeClass('on'); 
    });
});
//导航高亮
jQuery(document).ready(function($){ 
var datatype=$("#monavber").attr("data-type");
    $(".navbar>li ").each(function(){
        try{
            var myid=$(this).attr("id");
            if("index"==datatype){
                if(myid=="nvabar-item-index"){
                    $("#nvabar-item-index").addClass("active");
                }
            }else if("category"==datatype){
                var infoid=$("#monavber").attr("data-infoid");
                if(infoid!=null){
                    var b=infoid.split(' ');
                    for(var i=0;i<b.length;i++){
                        if(myid=="navbar-category-"+b[i]){
                            $("#navbar-category-"+b[i]+"").addClass("active");
                        }
                    }
                }
            }else if("article"==datatype){
                var infoid=$("#monavber").attr("data-infoid");
                if(infoid!=null){
                    var b=infoid.split(' ');
                    for(var i=0;i<b.length;i++){
                        if(myid=="navbar-category-"+b[i]){
                            $("#navbar-category-"+b[i]+"").addClass("active");
                        }
                    }
                }
            }else if("page"==datatype){
                var infoid=$("#monavber").attr("data-infoid");
                if(infoid!=null){
                    if(myid=="navbar-page-"+infoid){
                        $("#navbar-page-"+infoid+"").addClass("active");
                    }
                }
            }else if("tag"==datatype){
                var infoid=$("#monavber").attr("data-infoid");
                if(infoid!=null){
                    if(myid=="navbar-tag-"+infoid){
                        $("#navbar-tag-"+infoid+"").addClass("active");
                    }
                }
            }
        }catch(E){}
    });
	$("#monavber").delegate("a","click",function(){
		$(".navbar>li").each(function(){
			$(this).removeClass("active");
		});
		if($(this).closest("ul")!=null && $(this).closest("ul").length!=0){
			if($(this).closest("ul").attr("id")=="munavber"){
				$(this).addClass("active");
			}else{
				$(this).closest("ul").closest("li").addClass("active");
			}
		}
	});
});
//
$(function() {
	    $(window).scroll(function(){		
		    if($(window).scrollTop()>500){		
			    $("#gttop").show();
		    }else{
			    $("#gttop").hide();
		    }
	    });		
	    $("#gttop").click(function(){
		    $("body,html").animate({scrollTop:0},1500);
		    return false;
	    });		
    });
	
//	
$(document).ready(function() {
   
    $('#content-index-togglelink').on('click',
    function() {
        if ($('#content-index-togglelink').html() == '显示') {
            $('#content-index-togglelink').html('隐藏');
        } else {
            $('#content-index-togglelink').html('显示');
        }
        $('#content-index-contents').toggle();
        
    }); 
});	
	

//

$(function(){$(".tooltip-trigger").each(function(b){if(this.title){var c=this.title;var a=0;$(this).mouseover(function(d){this.title="";$(this).append('<div class="tooltip top" role="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner">'+c+'</div></div>');$(".tooltip").css({left:(($(this).width()-$('.tooltip').width())/2)+"px",bottom:($(this).height()+a)+"px"}).addClass('in').fadeIn(250)}).mouseout(function(){this.title=c;$(".tooltip").remove()});}})});	
	
	
	
	