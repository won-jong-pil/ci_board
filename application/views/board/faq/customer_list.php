<?PHP if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>    
        <article class="content">
            <section class="contents_inner">
                <p class="page_location">
                    <img src="/images/common/img_location_bul1.jpg" alt="" />
                    <a href="/">HOME</a>
                    <img src="/images/common/img_location_bul2.jpg" alt="" />
                    <a href="/board/testimonials_sel">CUSTOMER</a>
                    <img src="/images/common/img_location_bul2.jpg" alt="" />
                    <a href="/board/listing/brd/faq_custom/faq_cate/1">FAQ</a>
                </p>

                <h3>FAQ</h3>

                <div class="contents_area mypage faq">
                    <div class="tab_wrap tab_col7">
                        <a href="/board/listing/brd/faq_custom/faq_cate/1"<?php if($faq_cate == 1) echo ' class="active"';?>>음료 &amp; 푸드</a>
                        <a href="/board/listing/brd/faq_custom/faq_cate/2"<?php if($faq_cate == 2) echo ' class="active"';?>>MD 상품</a>
                        <a href="/board/listing/brd/faq_custom/faq_cate/3"<?php if($faq_cate == 3) echo ' class="active"';?>>결제</a>
                        <a href="/board/listing/brd/faq_custom/faq_cate/4"<?php if($faq_cate == 4) echo ' class="active"';?>>서비스</a>
                        <a href="/board/listing/brd/faq_custom/faq_cate/5"<?php if($faq_cate == 5) echo ' class="active"';?>>회사, 채용</a>
                        <hr/>
                    </div>

                    <div class="box">

                        <p><img src="/images/mypage/img_bullet_small.png" /> 고객님께서 자주 하시는 질문입니다.</p>

                        <div class="tab_cont">
                            <div class="table_wrap">
                                <table class="list">
                                    <caption>list table</caption>
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
                                        <td class="left"><a href="#none"><?php echo $list['question'];?></a></td>
                                    </tr>
                                    <tr>
                                        <td class="even" colspan="2">
                                            <dl>
                                                <dt>A</dt>
                                                <dd>
                                                   	<?php echo $list['answer'];?>
                                                </dd>
                                            </dl>
                                        </td>
                                    </tr>
									<?php endforeach;?>
									<?php else:?>
										<tr><Td colspan="4">등록된 자료가 없습니다.</Td></tr>
									<?php endif;?>

                                    </tbody>
                                </table>
                            </div>

                            <div class="paging"><?php echo $paging; ?></div>

							<form action="" method="post">
                            <div class="searching">
                                <select name="search_key" id="search_key">
                                    <option value="all">전체</option>
                                    <option value="question">질문</option>
                                    <option value="answer">내용</option>
                                </select>
                                <input type="text" name="search_val" id="search_val" />
                                <input type="submit" class="btn graybtn" value="검색">
                            </div>
                            </form>
                        </div>
                     </div>
                </div>
            </section>
        </article>     