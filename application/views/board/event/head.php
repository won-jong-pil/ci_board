<?PHP if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>     
<script type="text/javascript" src="/static/js/jquery.validate.min.js"></script>	
<script type="text/javascript" src="/static/js/jquery.form.min.js"></script>
<script src="http://dmaps.daum.net/map_js_init/postcode.js"></script>
<script type="text/javascript">
<?php if(isset($idx) === TRUE):?>
var idx = <?php echo $idx;?>;
<?php endif;?>

$(document).ready(function(){
	 $('.eventslide').children().flexslider({animation:"slide", directionNav:false, useCSS:false,pausePlay:true});
	 $(".planevent ul li a").on({
			"mouseover" : function() {
			$(this).children('p').show();
		},
			"mouseout" : function() {
			$(this).children('p').hide();
		}
	});
	<?php if(isset($idx) === FALSE):?>listInit(0);<?php endif;?>
	$("#apply_button").click(function(){
		<?php if($this->session->userdata('idx') === FALSE):?>
		alert('로그인 해주세요.');
		location.href = "/users/login_form";
		<?php else:?>
		$.post("/board/app_form", "", function(response){
			$("#modalBase").html(response).dialog({
				width:400, 
				height:473, 
				resizable:true, 
				draggable: true,
				modal:true, 
				dialogClass:"popups",
				title:"이벤트 응모하기"	
			});

			$("#email3").change(function(){
				  var selValue = $("#email3 option:selected").val();
				  if(selValue!=""){
					  $("#email2").attr("readonly", true).val(selValue);
				  }else{
					  $("#email2").attr("readonly", false).val("").focus();
				  }
			});

			// 우편번호 찾기 iframe을 넣을 element
			var element = document.getElementById('addr_layer');
			$("#btnCloseLayer").click(function(){
			    // iframe을 넣은 element를 안보이게 한다.
			    element.style.display = 'none';
			});

		    $(".lightnavybtn").click(function(){
		        new daum.Postcode({
		            oncomplete: function(data) {
		                // 검색결과 항목을 클릭했을때 실행할 코드를 작성하는 부분.
		                // 우편번호와 주소 및 영문주소 정보를 해당 필드에 넣는다.
		                document.getElementById('postcode1').value = data.postcode1;
		                document.getElementById('postcode2').value = data.postcode2;
		                document.getElementById('addr1').value = data.address;
		                // iframe을 넣은 element를 안보이게 한다.
		                element.style.display = 'none';
		            },
		            width : '100%',
		            height : '100%'
		        }).embed(element);

		        // iframe을 넣은 element를 보이게 한다.
		        element.style.display = 'block';
			});

			$("#event_id").val(idx);

			$("#app_form").validate({
				debug: false,
				onfocusout: false,
				onkeyup: false,
				showErrors:function(errorMap, errorList){
					if(this.numberOfInvalids())
					{
						alert(errorList[0].message);
						$(errorList[0].element).focus();
					}
				},				
				submitHandler : function(form){
					$("#app_form").ajaxSubmit({
					   	  type: "POST",
					   	  resetForm: true,
					      data:$(this).serialize(),
					      dataType:'json',
					   	  success: function(response){
					   	  	  alert(response.msg);
					   	  	  $("#modalBase").dialog("close");
						  }
					});

					return false;
				},
				rules: {
					tel1: {
						number: true,
						minlength: 3,
						maxlength: 4,
						required: true
					},
					tel2: {
						number: true,
						minlength: 3,
						maxlength: 4,
						required: true
					},
					tel3: {
						number: true,
						minlength: 3,
						maxlength: 4,
						required: true
					},
					email1:{
						required: true
					},
					email2:{
						required: true
					},
					addr1:{
						required: true
					},
					addr2:{
						required: true
					},
					policy1:{
						required: true
					},
					policy2:{
						required: true
					}
				},
				messages: {
					tel1:{ 
						number: "숫자로 입력해주세요.",
						minlength: $.format("휴대폰번호는 최소 {0}자 이상이어야 합니다."),
						maxlength: $.format("휴대폰번호는 최대 {0}자 이상이어야 합니다."),
						required: "휴대폰 번호를 입력해주세요."
					},
					tel2:{ 
						number: "숫자로 입력해주세요.",
						minlength: $.format("휴대폰번호는 최소 {0}자 이상이어야 합니다."),
						maxlength: $.format("휴대폰번호는 최대 {0}자 이상이어야 합니다."),
						required: "휴대폰 번호를 입력해주세요."
					},
					tel3:{ 
						number: "숫자로 입력해주세요.",
						minlength: $.format("휴대폰번호는 최소 {0}자 이상이어야 합니다."),
						maxlength: $.format("휴대폰번호는 최대 {0}자 이상이어야 합니다."),
						required: "휴대폰 번호를 입력해주세요."
					},
					email1:{
						required: "이메일을 입력하세요."
					},
					email2:{
						required: "이메일을 입력하세요."
					},
					addr1:{
						required: "주소를 검색하세요."
					},
					addr2:{
						required: "주소를 입력하세요."
					},
					policy1:{
						required: "정보 전송에 동의 하셔야 합니다."
					},
					policy2:{
						required: "메일(SMS) 수신에 동의 하셔야 합니다."
					}
				}
			});			
		});
		<?php endif;?>
	});
});
</script>