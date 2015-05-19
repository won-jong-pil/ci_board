<?PHP if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<?php if(isset($mode) === TRUE && ($mode == 'write' OR $mode =='update')):?>	
<script type="text/javascript">
$(document).ready(function(){
		$("#write_form").validate({
		debug: false,
		showErrors:function(errorMap, errorList){
			if(errorList.length > 0)
			{
				alert(errorList[0].message);
				$(errorList[0].element).focus();
			}
		},
		rules: {
			id:{
				required: true,
				email: true
			},
			passwd: {
				required: true
			},			
			name:{
				required: true
			}
		},
		messages: {
			id:{
				required: "이름을 입력하세요.\n",
				email: "이메일 형식이 올바르지 않습니다."
			},
			passwd:{ 
				required: "패스워드를 입력하세요.\n"
			},			
			name:{ 
				required: "관리자명을 입력하세요.\n"
			}
		}
	});

	$('#cancel').click(function() {
		if(confirm("모든 내용이 삭제 됩니다.\n그래도 취소하시겠습니까?") == true) {
			if($('#write_form').attr('class') == 'write') {
				history.go(-1);
			} else {
				history.go(-2);
			}
		}
		return false;
	});

	
	$("#grade").change(function(){
		var brand;
		switch($(this).val())
		{
			case '2': brand = 'nex'; break;
			case '3': brand = 'kim'; break;
			case '4': brand = 'kix'; break;
		}
		
		$("input[name='menu_id[]']").prop("checked", false).each(function(){
			if($(this).val().substr(0, 3) == brand)
			{
				$(this).prop("checked", true);
			}
		});
	});
});
</script>
<?php endif;?>