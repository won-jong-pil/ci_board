<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
        <article class="content">
            <section class="contents_inner">
                <p class="page_location">
                    <img src="/images/common/img_location_bul1.jpg" alt="" />
                    <a href="/">HOME</a>
                    <img src="/images/common/img_location_bul2.jpg" alt="" />
                    <a href="/users/check_pass_form">MYPAGE</a>
                    <img src="/images/common/img_location_bul2.jpg" alt="" />
                    <a href="/board/listing/brd/point_history">코인 적립 내역</a>
                </p>

                <h3>코인 적립 내역</h3>

                <div class="contents_area mypage">
                    <div class="box">
                        <div class="graybox">
                            <div>
                            	<img src="/images/mypage/img_point_bullet1.jpg" alt="" /> 적립코인 : <span><?php echo number_format(get_userpoint($this->session->userdata('idx')));?> COIN</span>
                                <img src="/images/mypage/img_point_bullet2.jpg" alt="" /> 이벤트 참여 내역 : <span><?php echo get_eventapp($this->session->userdata('idx'));?>개</span>
                            </div>
                        </div>
                                            
                        <div class="tab_cont">
                            <div class="table_wrap">
                                <table class="list">
                                    <caption>list table</caption>
                                    <colgroup>
                                        <col width="15%" />
                                        <col width="55%" />
                                        <col width="15%" />
                                        <col width="15%" />
                                    </colgroup>
                                    <thead>
                                    <tr class="center">
                                        <th>번호</th>
                                        <th>적립내용</th>
                                        <th>적립코인</th>
                                        <th>적립일</th>
                                    </tr>
                                    </thead>
                                    <tbody>
		                            <?php if(count($result['result']) > 0):?>
		                                <?php foreach($result['result'] as $key=>$list):?>                                       
		                                    <tr>
		                                        <td><?php echo $result['vnum']--;?></td>
		                                        <td><?php echo $list['point_contents'];?></td>
		                                        <td><?php echo $list['point_type'].$list['point'];?></td>
		                                        <td><?php echo substr($list['reg_date'],0, 10);?></td>
		                                    </tr>
		                                <?php endforeach;?>
									<?php else:?>
										<tr><td colspan="3">등록된 자료가 없습니다.</td></tr>
									<?php endif;?>    
                                    </tbody>
                                </table>
                            </div>

                            <div class="paging"><?php echo $paging; ?></div>

                        </div>
                    </div>
                </div>
            </section>
        </article>