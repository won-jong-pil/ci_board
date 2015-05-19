<?PHP if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
        <article class="content">
            <section class="contents_inner">
                <p class="page_location">
                    <img src="/images/common/img_location_bul1.jpg" alt="" />
                    <a href="/">HOME</a>
                    <img src="/images/common/img_location_bul2.jpg" alt="" />
                    <a href="/board/listing/brd/notice">EVENT &amp; NEWS</a>
                    <img src="/images/common/img_location_bul2.jpg" alt="" />
                    <a href="/board/listing/brd/notice">이디야 소식</a>
                </p>

                <h3><?php echo $this->board_drv->get_attr('board_name');?></h3>

                <div class="contents_area mypage faq news">

                    <div class="box">

                        <div class="tab_cont">
                            <p><img src="/images/mypage/img_bullet_small.png" /> 이디야에서 알려드립니다.</p>
                            <div class="table_wrap">
                                <table class="list">
                                    <caption>list table</caption>
                                    <colgroup>
                                        <col width="10%" />
                                        <col width="75%" />
                                        <col width="15%" />
                                    </colgroup>
                                    <thead>
                                    <tr class="center">
                                        <th>번호</th>
                                        <th>내용</th>
                                        <th>작성일</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php if(isset($top_result['result']) === TRUE && count($top_result['result']) > 0):?>
                                    <?php foreach($top_result['result'] as $key=>$list):?>
                                        <tr>
                                            <td>*</td>
                                            <td class="left"><a href="#none"><?php echo $list['title'];?></a></td>
                                            <td><?php echo substr($list['reg_date'], 0, 10);?></td>
                                        </tr>
                                        <tr>
                                            <td class="even" colspan="3">
                                                <div class="lhediter">
                                                    <?php echo $list['contents'];?>
                                                </div>
                                            </td>
                                        </tr>
									<?php endforeach;?>
									<?php endif;?>
                                    
                                    <?php if(count($result['result']) > 0):?>
                                    <?php foreach($result['result'] as $key=>$list):?>
                                        <tr>
                                            <td><?php echo $result['vnum']--;?></td>
                                            <td class="left"><a href="#none"<?php if(isset($idx) === TRUE && $idx == $list['idx']) echo ' class="active"';?>><?php echo $list['title'];?></a></td>
                                            <td><?php echo substr($list['reg_date'], 0, 10);?></td>
                                        </tr>
                                        <tr>
                                            <td class="even" colspan="3" <?php if(isset($idx) === TRUE && $idx == $list['idx']) echo ' style="display:table-cell;"';?>>
                                                <div class="lhediter">
                                                    <?php echo $list['contents'];?>
                                                </div>
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
                                    <option value="title">제목</option>
                                    <option value="contents">내용</option>
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