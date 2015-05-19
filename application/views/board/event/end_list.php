<?PHP if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>  
        <article class="content">
            <section class="contents_inner">
                <p class="page_location">
                    <img src="/images/common/img_location_bul1.jpg" alt="" />
                    <a href="/">HOME</a>
                    <img src="/images/common/img_location_bul2.jpg" alt="" />
                    <a href="/board/listing/brd/notice">EVENT &amp; NEWS</a>
                    <img src="/images/common/img_location_bul2.jpg" alt="" />
                    <a href="/board/listing/brd/event/sel_type/end/">이벤트</a>
                </p>

                <h3>이벤트</h3>

                <div class="contents_area mypage faq">
                    <div class="tab_wrap tab_col4">
                        <a <?php if($sel_type=='now'):?> class="active"<?php endif;?> href="/board/listing/brd/event/sel_type/now/<?php $url?>">진행중인 이벤트</a>
                        <a <?php if($sel_type=='end'):?> class="active"<?php endif;?>href="/board/listing/brd/event/sel_type/end/<?php $url?>">종료된 이벤트</a>
                        <a <?php if($sel_type=='winner'):?> class="active"<?php endif;?>href="/board/listing/brd/event/sel_type/winner/<?php $url?>">당첨자 발표</a>
                        <hr/>
                    </div>

                    <div class="box">
                        <p><img src="/images/mypage/img_bullet_small.png" /> 종료된 이벤트입니다.</p>
                        <div class="table_wrap">
                            <table class="list">
                                <caption>종료된이벤트 리스트</caption>
                                <colgroup>
                                    <col width="91px" />
                                    <col width="629px" />
                                </colgroup>
                                <thead>
                                <tr class="center">
                                    <th>항목</th>
                                    <th>내용</th>
                                </tr>
                                </thead>
                                <tbody>
                            <?php if(count($result['result']) > 0):?>
                                <?php foreach($result['result'] as $key=>$list):?>                                  
                                <tr>
                                    <td><?php echo $result['vnum']--;?></td>
                                    <td class="left"><a href="#none"><?php echo $list['title'];?></a></td>
                                </tr>
	                            <tr>
	                                <td class="even" colspan="2"><?php echo $list['contents'];?></td>
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
                                <input type="submit" class="btn graybtn" value="검색">
                            </div>
                            </form>
                    </div>
                </div>
            </section>
        </article>      