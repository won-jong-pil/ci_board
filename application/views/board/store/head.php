<?PHP if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp"></script>
<script type="text/javascript" >
<?php if(isset($result['result']['ADS']) === TRUE):?>
var geocoder, map;
var image = '/images/ediya/img_map_flag.png';
geocoder = new google.maps.Geocoder();

var address = "<?php echo $result['result']['ADS'];?>";
geocoder.geocode( { 'address': address}, function(results, status) {
  if (status == google.maps.GeocoderStatus.OK) {
	 map = new google.maps.Map(document.getElementById('map-canvas'), {
		    zoom: 16,
		    center: results[0].geometry.location
	});
			  
    map.setCenter(results[0].geometry.location);
    var marker = new google.maps.Marker({
        map: map,
        position: results[0].geometry.location,
        icon: image
    });
  } else {
    alert("해당 주소의 지도 정보를 불러 올수 없습니다.");
  }
});
<?php endif;?>

                    $(document).ready(function(){
                        var prevthumb;
                        $('.borderbox .clearfix dl dd a').on('click',function(){
                            thumbClick(this);
                        });

                        function thumbClick(tg)
                        {
                            if(prevthumb)
                            {
                                $('.borderbox .clearfix dl dt img').eq($(prevthumb).index()).css('display','none');
                            }
                            $('.borderbox .clearfix dl dt img').eq($(tg).index()).css('display','inline');
                            prevthumb=tg;
                        }
                        thumbClick($('.borderbox .clearfix dl dd a').eq(0));
                        
                        $("#sido").change(function(){
							$.post("/board/gugun", "sido="+$(this).val(), function(response){
								$("#gugun").empty().append("<option value=''>구/군</option>");

								$(response.data).each(function(key, value){
									$("#gugun").append("<option value='"+value.CODE+"'>"+value.NAME+"</option>");
								});
								
							}, "json");
                        });


                        $('.storeslide').children().flexslider({
                            animation:"slide",
                            directionNav:false,
                            useCSS:false,
                            pausePlay:true
                        });
            				
            				
            				var map01 = -1;
                            var map02 = -1;

                            /************************
                             @ 매장 마우스 오버
                             ************************/
                            function mapOver(){
                                var mIndex = 0;
                                $('#s_map .city02').find('a').mouseover(function(){
                                    mIndex = $(this).index();
                                    mapAlign(mIndex);
                                });
                                $('#s_map .city02').find('a').mouseout(function(){
                                    mapAlign(map01);
                                });
                                $('#s_map .city01').find('a').mouseover(function(){
                                    mIndex = ($(this).index() == 9)?13:$(this).index()+7;
                                    mapAlign(mIndex);
                                });
                                $('#s_map .city01').find('a').mouseout(function(){
                                    mapAlign(map01);
                                });

                                $('#s_map').mouseleave(function(){
                                    //mapAlign(map01);
                                });
                            }

                            //매장 마우스 오버시 정렬
                            function mapAlign(_index){
                                var mtarget = $('.store_search_map');
                                var _src	= (_index == -1)?"store_list_map_bg":"store_list_map_bg"+_index;
                                mtarget.find('.map_img').attr('src',"/images/store/"+_src+".jpg");
                            }

                            /************************
                             @ 매장 오버 디폴트
                             ************************/
                            function mapDefault(){
                                $('#s_map .city01').find('a').each(function(){
                                    if($(this).hasClass('on')){
                                        mIndex = ($(this).index() == 9)?13:$(this).index()+7;
                                        mapAlign(mIndex);
                                        $(this).addClass('actived');
                                        map01 = mIndex;
                                    }
                                });
                                $('#s_map .city02').find('a').each(function(){
                                    if($(this).hasClass('on')){
                                        mIndex = $(this).index();
                                        mapAlign(mIndex);
                                        $(this).addClass('actived');
                                        map01 = mIndex;
                                    }
                                });
                            }

                            mapOver();
                            mapDefault();                        
                    });
</script>