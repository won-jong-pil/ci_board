<?PHP if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<link rel="stylesheet" type="text/css" href="/static/css/flexslider.css"/>
<!-- bxSlider CSS file -->
<link href="/static/css/jquery.bxslider.css" rel="stylesheet" />
<script type="text/javascript" src="/static/js/jquery.placeholder.js"></script>
<script type="text/javascript" src="/static/js/jquery.flexslider.js"></script>
<script type="text/javascript" src="/static/js/jquery.cookie.js"></script>
<!-- bxSlider Javascript file -->
<script src="/static/js/jquery.bxslider.min.js"></script>
<script type="text/javascript">
$(window).ready(function(){
    $('.area1 .slide_wrap').flexslider({
        animation:"slide",
        useCSS:false,
        pausePlay:true,
        prevText:'<img src="/images/main/img_arrowbtn_left1.png" alt="" />',
        nextText:'<img src="/images/main/img_arrowbtn_right1.png" alt="" />'
    });

	/*$('.area2 .slide_wrap').flexslider({
		minItems:3,
		itemWidth:139,
		animation:"slide",
		controlNav:false,
		useCSS:false,
		pausePlay:false,
		slideshow: false,
		prevText:'<img src="/images/main/img_arrowbtn_left2.jpg" alt="" />',
		nextText:'<img src="/images/main/img_arrowbtn_right2.jpg" alt="" />'
	});*/

	  $('.area2 .slider1').bxSlider({
		slideWidth: 139,
		minSlides: 2,
		maxSlides: 3,
		moveSlides: 1,
		//slideMargin: 10
		pager:false
	  });

    $('.area3 .slide_wrap').flexslider({
        animation:"slide",
        minItems:3,
        itemWidth:320,
        controlNav:false,
        useCSS:false,
        pausePlay:true,
        prevText:'<img src="/images/main/img_arrowbtn_left1.png" alt="" />',
        nextText:'<img src="/images/main/img_arrowbtn_right1.png" alt="" />'
    });
	<?php if(isset($popup) === TRUE && sizeof($popup) > 0 ):?>
	var window_width = $(window).width();
	var window_height = $(window).height();
	<?php foreach($popup as $key=>$list):?>
	<?php 
		  switch($list['close_day'])
		  {
		  	case '1': $close_string = '오늘 하루 창 열지 않기'; break;
		  	case '3': $close_string = '3일간 열지 않기'; break;
		  	case '7': $close_string = '일주일간 열지 않기'; break;
		  	default: $close_string = '오늘 하루 창 열지 않기';
		  }
	?>
		if($.cookie("popup_today_<?php echo $list['idx'];?>") != "close")
		{
			var contents = '<?php echo addcslashes($list['contents'],"\\\'\"\n\r");?>';
			<?php if($list['popup_type'] == 'L'):?>
			var obj<?php echo $list['idx'];?> = $("#modalBase").clone();
			obj<?php echo $list['idx'];?>.html("<div class='popin' style='height:auto;'>"+contents+"</div><div id='today_close_<?php echo $list['idx'];?>' class='pop_today'><?php echo $close_string;?></div><div id='close_popup_<?php echo $list['idx'];?>' class='pop_btn_close'>X</div>");
			obj<?php echo $list['idx'];?>.dialog({
				resizable: true, 
				position:[<?php echo $list['pos_x'];?>, <?php echo $list['pos_y'];?>],
				width: <?php if($list['size_x']<= 100) echo 'window_width * '.round($list['size_x'] / 100, 1); else echo $list['size_x'];?>,
				height: <?php if($list['size_y']<= 100) echo 'window_height * '.round($list['size_y'] / 100, 1); else echo $list['size_y'];?>,
				dialogClass:"popups_main"
			});
			obj<?php echo $list['idx'];?>.find("#today_close_<?php echo $list['idx'];?>").click(function(){
				$.cookie("popup_today_<?php echo $list['idx'];?>", "close", {expires: <?php echo $list['close_day'];?>});
				obj<?php echo $list['idx'];?>.remove();
			});
			obj<?php echo $list['idx'];?>.find("#close_popup_<?php echo $list['idx'];?>").click(function(){
				obj<?php echo $list['idx'];?>.dialog("close");
			}).css("cursor", "pointer");
			<?php else:?>
				var popupWindow<?php echo $list['idx'];?> = window.open("", "popupWindow<?php echo $list['idx'];?>", "width=<?php echo $list['size_x'];?>, height=<?php echo $list['size_y'];?>");
				popupWindow<?php echo $list['idx'];?>.document.write("<div class='popin' style='overflow:auto;height:400px;'>"+contents+"</div><div id='today_close_<?php echo $list['idx'];?>' class='pop_today'><?php echo $close_string;?></div>");
				$("#today_close_<?php echo $list['idx'];?>").click(function(){
					$.cookie("popup_today_<?php echo $list['idx'];?>", "close", {expires: <?php echo $list['close_day'];?>});
					popupWindow<?php echo $list['idx'];?>.close();
				}).css("cursor", "pointer");				
				popupWindow<?php echo $list['idx'];?>.document.title = '<?php echo addcslashes($list['title'],"\\\'\"\n\r");?>';
			<?php endif;?>
		}	
		<?php endforeach;?>
	<?php endif;?>    
});
</script>