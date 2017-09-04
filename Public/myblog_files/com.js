function VerifyMessage() {

	var strFormAction=$("#inpId").parent("form").attr("action");
	var strName=$("#inpName").val();
	var strEmail=$("#inpEmail").val();
	var strHomePage=$("#inpHomePage").val();
	var strVerify=$("#inpVerify").val();
	var strArticle=$("#txaArticle").val();
	var intReplyID=$("#inpRevID").val();
	var intPostID=$("#inpId").val();
	var intMaxLen=1000;

	if(strName==""){
		alert((typeof(lang_comment_name_error)=="undefined") ? "name error":lang_comment_name_error);
		return false;
	}
	else{
		re = new RegExp("^[\.\_A-Za-z0-9\u4e00-\u9fa5]+$");
		if (!re.test(strName)){
			alert((typeof(lang_comment_name_error)=="undefined") ? "name error":lang_comment_name_error);
			return false;
		}
	}

	if(strEmail==""){
		alert('邮箱格式不正确，可能过长或为空');
		return false;
	}
	else{
		re = new RegExp("^[\\w-]+(\\.[\\w-]+)*@[\\w-]+(\\.[\\w-]+)+$");
		if (!re.test(strEmail)){
			alert((typeof(lang_comment_email_error)=="undefined") ? "email error":lang_comment_email_error);
			return false;
		}
	}

	if(typeof(strArticle)=="string"){
		if(strArticle==""){
			alert((typeof(lang_comment_content_error)=="undefined") ? "content error":lang_comment_content_error);
			return false;
		}
		if(strArticle.length>intMaxLen)
		{
			alert((typeof(lang_comment_content_error)=="undefined") ? "content error":lang_comment_content_error);
			return false;
		}
	}

	// //ajax comment begin
	// var strSubmit=$("#inpId").parent("form").find(":submit").val();
	// $("#inpId").parent("form").find(":submit").val("Waiting...").attr("disabled","disabled").addClass("loading");

	// $.post(strFormAction,
	// 	{
	// 	"isajax":true,
	// 	"postid":intPostID,
	// 	"verify":strVerify,
	// 	"name":strName,		
	// 	"email":strEmail,
	// 	"content":strArticle,
	// 	"homepage":strHomePage,
	// 	"replyid":intReplyID
	// 	},
	// 	function(data){
		
	// 		$("#inpId").parent("form").find(":submit").removeClass("loading");
	// 		$("#inpId").parent("form").find(":submit").removeAttr("disabled");
	// 		$("#inpId").parent("form").find(":submit").val(strSubmit);
		
	// 		var s =data;
	// 		if((s.search("faultCode")>0)&&(s.search("faultString")>0))
	// 		{
	// 			alert(s.match("<string>.+?</string>")[0].replace("<string>","").replace("</string>",""))
	// 		}
	// 		else{
	// 			var s =data;
	// 			var cmt=s.match(/cmt\d+/);
	// 			if(intReplyID==0){
	// 				$(s).insertAfter("#AjaxCommentBegin");
	// 			}else{
	// 				//$(s).insertAfter("#comment-"+intReplyID+" .children");
					
	// 				$("#cmt-"+intReplyID).append(s);
					
	// 			}
	// 			window.location="#"+cmt;

	// 			$("#txaArticle").val("");
				
	// 			SaveRememberInfo();
	// 			CommentComplete();
	// 		}

	// 	}
	// );

	SetCookie('inpName', strName);
	SetCookie('inpEmail', strEmail);
	SetCookie('inpHomePage', strHomePage);

	return true;
	//ajax comment end

}






//重写了common.js里的同名函数
function RevertComment(i) {
	$("#inpRevID").val(i);
	var frm = $('#divCommentPost'),
		cancel = $("#cancel-reply"),
		temp = $('#temp-frm');
	
    var div = document.createElement('div');
	div.id = 'temp-frm';
	div.style.display = 'none';
	frm.before(div);

	$('#cmt-' + i).after(frm);
    $body = (window.opera) ? (document.compatMode == "CSS1Compat" ? $('html') : $('body')) : $('html,body');
	$body.animate({
		scrollTop: $('#cmt-' + i).offset().top - 200
	},800);
	
	frm.addClass("reply-frm");

	cancel.show();
	cancel.click(function() {
		$("#inpRevID").val(0);
		var temp = $('#temp-frm'),
			frm = $('#divCommentPost');
		if (!temp.length || !frm.length) return;
		temp.before(frm);
		temp.remove();
		$(this).hide();
		frm.removeClass("reply-frm");
		return false;
	});
	try {
		$('#txaArticle').focus();
	} catch (e) {}
	return false;
}

//重写GetComments，防止评论框消失
function GetComments(logid, page) {
	$('span.commentspage').html("Waiting...");
	$.get(bloghost + "zb_system/cmd.php?act=getcmt&postid=" + logid + "&page=" + page, function(data) {
		$('#AjaxCommentBegin').nextUntil('#AjaxCommentEnd').remove();
		$('#AjaxCommentEnd').before(data);
		$("#cancel-reply").click();
	});
}


function CommentComplete() {
	$("#cancel-reply").click();
}


