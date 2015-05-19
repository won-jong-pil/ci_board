<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
        <article class="content">
            <section class="contents_inner">
                <p class="page_location">
                    <img src="/images/common/img_location_bul1.jpg" alt="" />
                    <a href="/">HOME</a>
                    <img src="/images/common/img_location_bul2.jpg" alt="" />
                    <a href="/users/check_pass_form">MYPAGE</a>
                    <img src="/images/common/img_location_bul2.jpg" alt="" />
                    <a href="/board/listing/brd/event/sel_type/user_list_now">이벤트 참여 내역</a>
                </p>

                <h3>이벤트 참여 내역</h3>

                <div class="contents_area mypage">
                    <div class="tab_wrap tab_col4">
                        <a href="/board/listing/brd/event/sel_type/user_list_now"<?php if($sel_type == 'user_list_now') echo ' class="active"';?>>진행중인 이벤트</a>
                        <a href="/board/listing/brd/event/sel_type/user_list_end"<?php if($sel_type == 'user_list_end') echo ' class="active"';?>>종료된 이벤트</a>
                        <hr/>
                    </div>

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
                                        <col width="91px" />
                                        <col width="498px" />
                                        <col width="131px" />
                                    </colgroup>
                                    <thead>
                                    <tr class="center">
                                        <th>항목</th>
                                        <th>내용</th>
                                        <th>결과</th>
                                    </tr>
                                    </thead>
                                    <tbody>
		                            <?php if(count($result['result']) > 0):?>
		                                <?php foreach($result['result'] as $key=>$list):?>                                       
		                                    <tr>
		                                        <td><?php echo $result['vnum']--;?></td>
		                                        <td class="left"><a href="#none"><?php echo $list['title'];?></a></td>
		                                        <?php if(isset($list['winner_idx']) === TRUE && empty($list['winner_idx']) === FALSE):?>
		                                        <td><a href="/board/listing/brd/event/sel_type/winner">당첨자확인</a></td>
		                                        <?php else:?>
		                                        <td>진행중</td>
		                                        <?php endif;?>
		                                    </tr>
		                                    <tr>
		                                        <td class="even" colspan="3">
		                                            <?php echo $list['contents'];?>
		                                        </td>
		                                    </tr>
		                                <?php endforeach;?>
									<?php else:?>
										<tr><td colspan="3">등록된 자료가 없습니다.</td></tr>
									<?php endif;?>    
                                    </tbody>
                                </table>
                            </div>

                            <div class="paging"><?php echo $paging; ?></div>

							<form action="" method="post">
                            <div class="searching">
                                <select name="search_key" id="search_key">
                                    <option value="all">전체</option>
                                    <option value="title">제목</option>
                                    <option value="contents">내용</option>
                                </select>
                                <input type="text" name="search_val" id="search_val" />
                                <input type="submit" class="btn graybtn2" value="검색">
                            </div>
                            </form>
                        </div>
                    </div>
                </div>
            </section>
        </article>