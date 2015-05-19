<?PHP if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<script type="text/javascript" src="/static/js/jquery.validate.min.js"></script>		
<script src="//cdn.ckeditor.com/4.4.5/full/ckeditor.js"></script>		
<script type="text/javascript">
$(document).ready(function(){
    //리스트 검색폼
    if($("#search_form").length > 0)
    {
    	$("#search_form").submit(function(){
    		if($("#search_val").val() == '')
    		{
    			alert("검색어를 입력하세요.");
    			$("#search_val").focus();
    			return false;
    		}
    	});
    }
    //선택 버튼 체크 on/off
    if($("#check_all").length > 0)
    {
    	$("#check_all").click(function(){
    		if($(this).prop("checked") === true)
    		{
    			$("input[name='del_list[]']").prop("checked", true);
    		}
    		else
    		{
    			$("input[name='del_list[]']").prop("checked", false);
    		}
    	});
    }
    //전체 선택 삭제 버튼
    if($("#all_del").length > 0)
    {
    	$("#all_del").click(function(){
    		if($("input[name='del_list[]']:checked").length < 1)
    		{
    			alert("한개 이상 선택되어야 합니다.");
    			return false;
    		}
    		else
    		{
    			$.post($("#del_form").attr("action"), $("#del_form").serialize(), function(response){
    				if(response.result == true)
    				{
    					alert("삭제 되었습니다.");
    					location.reload();
    				}
    				else
    				{
    					alert(response.msg);
    				}
    			}, "json");
    		}
    	});
    }
		
/*	$("#start_date, #end_date").datepicker({
		numberOfMonths: 1, showButtonPanel: true, changeMonth: true, changeYear: true, autoSize:true 
	});*/

	$("a[name='delbutton']").click(function(){
		var result = confirm("삭제 하시겠습니까?");
		if(result)
		{
			location.href = $(this).attr("href");
		}
		else
		{
			return false;
		}
	});
	
	<?php if(isset($mode) === TRUE):?>
	 CKEDITOR.replace( 'contents', {
		 filebrowserUploadUrl: '/manager/board/editor_upload',
		 toolbar:[
			['Source','-','Preview','-','Templates'],
			['Cut','Copy','Paste','PasteText','PasteFromWord','-','Print', 'SpellChecker', 'Scayt'],
			['Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat'],
			['BidiLtr', 'BidiRtl'],
			'/',
			['Bold','Italic','Underline','Strike','-','Subscript','Superscript'],
			['NumberedList','BulletedList','-','Outdent','Indent','Blockquote','CreateDiv'],
			['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
			['Link','Unlink','Anchor'],
			['Image','Flash','Table','HorizontalRule','Smiley','SpecialChar','PageBreak'],
			'/',
			['Styles','Format','Font','FontSize'],
			['TextColor','BGColor'],
			['Maximize', 'ShowBlocks','-','About']		 
		]
	 });	

	$("#write_form").validate({
		debug: false,
		onkeyup: false,
		onfocusout: false,
		showErrors:function(errorMap, errorList){
			if(errorList.length > 0)
			{
				alert(errorList[0].message);
				$(errorList[0].element).focus();
			}
		},
		rules: {
			title:{
				required: true,
				maxlength: 40
			}
		},
		messages: {
			title:{
				required: "제목을 입력하세요.",
				maxlength: $.format("비밀번호는 최대 {0}자 이하이어야 합니다.")
			}
		}
	});
	<?php endif;?>
});
</script>