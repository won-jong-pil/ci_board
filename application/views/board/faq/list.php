<?PHP if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>          
        <article class="content">
            <section class="contents_inner">
                <p class="page_location">
                    <img src="/images/common/img_location_bul1.jpg" alt="" />
                    <a href="/">HOME</a>
                    <img src="/images/common/img_location_bul2.jpg" alt="" />
                    <a href="/franchise/guide">FRANCHISE</a>
                    <img src="/images/common/img_location_bul2.jpg" alt="" />
                    <a href="/franchise/faq">FAQ</a>
                </p>

                <h3>FAQ</h3>

                <div class="contents_area mypage faq franchise_faq">
                    <h4>예비 창업자 여러분이 자주 하시는 질문입니다.</h4>
                    <div class="box">
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

                            <div class="paging">
                                <?php echo $paging; ?>
                            </div>
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
            </section>
        </article>