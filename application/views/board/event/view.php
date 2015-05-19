<?PHP if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>     
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
                            <?php echo $result['result']['contents'];?>
                        </div>
				        <?php if($result['result']['reply_use'] == 'Y'):?>
				        <div class="event_reply">
				        <div class="box">
				        <form action="/board/save/brd/comment" method="post" name="comment_form" id="comment_form">
				        	<?php if($this->session->userdata('idx') !== FALSE):?>
				        	<input type="hidden" name="comment_board_code" id="comment_board_code" value="<?php echo $brd;?>">
				        	<input type="hidden" name="board_idx" id="board_idx" value="<?php echo $result['result']['idx'];?>">
				        	<?php endif;?>
							<fieldset>
								<div class="comment"><textarea rows="4" cols="50" name="contents" id="contents"></textarea><input type="submit" value="댓글 남기기" name="btn_reply"/></div>
							</fieldset>        	
				        </form>
				        </div>
				        <div id="update_form_base"></div>
				        <div id="comment_list"></div>
				        </div>
				        <?php endif;?> 
				             
				        <?php if($result['result']['apply_use'] == 'Y'):?>                    
                        <div class="btnwrap">
                            <a class="btn navybtn" href="#none" id="apply_button">응모하기</a> 
                        </div>
                        <?php endif;?>
                    </div>
                </div>
            </section>
        </article>