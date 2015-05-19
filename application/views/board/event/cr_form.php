<?PHP if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>  
<form action="" method="post" name="cr_form" id="cr_form">
        <!-- content -->
        <article class="content">
            <section class="contents_inner">
                <p class="page_location">
                    <img src="/images/common/img_location_bul1.jpg" alt="" />
                    <a href="/">HOME</a>
                    <img src="/images/common/img_location_bul2.jpg" alt="" />
                    <a href="/board/listing/brd/notice">EVENT &amp; NEWS</a>
                    <img src="/images/common/img_location_bul2.jpg" alt="" />
                    <a href="/board/listing/brd/event/sel_type/now">이벤트</a>
                </p>

                <h3>이벤트</h3>

                <div class="contents_area event1_1">

                    <div class="tab_wrap tab_col4">
                        <a <?php if($sel_type=='now'):?> class="active"<?php endif;?> href="/board/listing/brd/event/sel_type/now/<?php $url?>">진행중인 이벤트</a>
                        <a <?php if($sel_type=='end'):?> class="active"<?php endif;?>href="/board/listing/brd/event/sel_type/end/<?php $url?>">종료된 이벤트</a>
                        <a <?php if($sel_type=='winner'):?> class="active"<?php endif;?>href="/board/listing/brd/event/sel_type/winner/<?php $url?>">당첨자 발표</a>
                        <hr/>
                    </div>

                    <div class="box">                        
                        <div class="contents">
                            <div class="view_box">
								<?php echo $result['result']['contents'];?>
								<ul class="clearfix">
									<?php echo $result['result']['etc'];?>	
								</ul>
								<input type="image" src="/images/event/btn_losting.png" alt="이벤트 응모하기" />
							</div>
                        </div>
                    </div>
                </div>
            </section>
        </article>
        <!-- //content -->
</form>                    


		<!-- 팝업시작 -->
        <div class="popup event_popup" id="sns_base" style="display:none;">
            <div class="contents">
				<p class="center bold">응모가 완료되었습니다. <br />이벤트에 참여해주셔서 감사합니다.</p>
				<p class="center">이디야커피 컬처로스팅 이벤트를 <br />SNS를 통해 친구들과 공유하세요.</p>
				<ul class="clearfix">
					<li><a href="#none"><img src="/images/event/icon_facebook.png" alt="페이스북" id="facebook_link_button" /></a></li>
					<li><a href="#none"><img src="/images/event/icon_twitter.png" alt="트위터" id="twitter_link_button" /></a></li>
					<li><a href="#none"><img src="/images/event/icon_kakao.png" alt="카카오톡" id="kakao_link_button" /></a></li>
					<li><a href="#none"><img src="/images/event/icon_story.png" alt="카카오스토리" id="kakao_story_link_button" /></a></li>
				</ul>
            </div>
        </div><!-- 팝업 -->