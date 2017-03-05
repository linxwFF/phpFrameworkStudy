$(function(){
	//tab动画
	var line = $(".m-tab .curBg");
	$(".m-tab span").click(function(){
		line.animate({"left":$(this).position().left}, 200);
		var type = $(this).attr("data-type");
		$(".m-"+type).fadeIn(200).siblings().hide();
	});
	//发表评论
	$(".jq-comment-submit").click(function(){
		var comment = $(".m-comment-send textarea").val();
		if($.trim(comment).length < 1){
			alert("请填写评论内容。");
			return false;
		}
		var url = $(this).attr("data-url");
		$.post(url, {"content":comment}, function(msg){
			if(msg.ok){
				addNewComment(msg.data);
			}else{
				alert(msg.data);
			}
		}, "json");
	});
	function addNewComment(data){
		var newComment = createComment(data);
		$(".m-comment-send").after(newComment);
		newComment.slideDown();
	}
	function addMoreComment(data){
		var newComments = [];
		$.each(data, function(name, value){
			newComments.push(createComment(value));
		});
		$(".m-comment-more").before(newComments);
		$(".m-comment dl").show();
	}
	function createComment(data){
		var newComment = $('<dl><dt><img alt="头像"></dt><dd><span><b></b><i></i></span><p></p><p></p></dd></dl>');
		newComment.find("dt img").attr("src", data.user_avatar);
		newComment.find("dd span b").text(data.user_name);
		newComment.find("dd span i").text("发表于 "+data.time);
		newComment.find("dd p:first").text(data.content);
        if(data.reply !== ""){
             newComment.find("dd p:last").text("管理员回复："+data.reply);
        }
		newComment.hide();
		return newComment;
	}
	
	//评论-加载更多
	var commentPage = 1;
	$(".m-comment-more span").click(function(){
		thisObj = $(this);
		var url = thisObj.attr("data-url");
		$.getJSON(url +"&page="+ (++commentPage), function(msg){
			addMoreComment(msg.data);
			if(msg.end){
				thisObj.text(msg.info);
			}
		});
	});
	
	//字数统计
	var txtCount = $(".m-comment-send i");
	var txtArea = $(".m-comment-send textarea");
	txtArea.keyup(function(){
		var str = $(this).val();
		txtCount.text(100-str.length);
	});
	//没有试题时隐藏提交按钮
	if($(".m-question-wrap").length < 1){
		$(".m-question-act").hide();
	}
	//阅卷
	$(".m-question-act button").click(function(){
		//判断题
		$(".jq-q-binary .m-question-each").each(function(){
			var input = $(this).find("input[type=radio]:checked").val();
			var answer = $(this).find("input[type=hidden]").val();
			$(this).find(".m-question-answer").html(showAnswer(input===answer, answer==='T'?'对':'错'));
		});
		//单选题
		$(".jq-q-single .m-question-each").each(function(){
			var input = $(this).find("input[type=radio]:checked").val();
			var answer = $(this).find("input[type=hidden]").val();
			$(this).find(".m-question-answer").html(showAnswer(input===answer, answer));
		});
		//多选题
		$(".jq-q-multiple .m-question-each").each(function(){
			var input = '';
			$(this).find("input[type=checkbox]:checked").each(function(){ 
				input += $(this).val();
			});
			var answer = $(this).find("input[type=hidden]").val();
			$(this).find(".m-question-answer").html(showAnswer(input===answer, answer));
		});
		//填空题
		$(".jq-q-fill .m-question-each").each(function(){
			var input = $.trim($(this).find("input[type=text]").val());
			var answer = $(this).find("input[type=hidden]").val();
			$(this).find(".m-question-answer").html(showAnswer(input===answer, answer));
		});
		//定位
		$("html,body").animate({scrollTop:$(".m-main-left").offset().top}, 500);
	});
	function showAnswer(flag, answer){
		return flag ? '<span>回答正确</span>' : '<span class="error">回答错误，答案是：'+ answer +'</span>';
	}
	//视频播放
	$(".m-video li").click(function(){
		var url = $(this).attr("data-url");
		if(url!==""){
			$("#jq_video").prop("src", url);
			$(this).addClass("curr").siblings().removeClass("curr");
		}
	});
	//自动播放第1个视频
	$(".m-video li:first").click();
	//播放上一个
	$(".m-top-vcon span:first").click(function(){
		$(".m-video li.curr").prev().click();
	});
	//播放下一个
	$(".m-top-vcon span:last").click(function(){
		$(".m-video li.curr").next().click();
	});
	//课程收藏
	$(".jq-fav").click(function(){
		$.post($(this).attr("href"), {"id": $(this).attr("data-id")}, function(msg){
			alert(msg.data);
			if(msg.ok){
				location.reload();
			}
		}, "json");
		return false;
	});
});