<?PHP if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
         <article class="content">
            <section class="contents_inner">
                <p class="page_location">
                    <img src="/images/common/img_location_bul1.jpg" alt="" />
                    <a href="/">HOME</a>
                    <img src="/images/common/img_location_bul2.jpg" alt="" />
                    <a href="/company/career">채용 안내</a>
                </p>

                <h3>채용 안내</h3>

                <div class="contents_area careers2">
                    <div class="tab_wrap tab_col4">
                        <a href="/board/listing/brd/head_career"<?php if($page_name=='head_career') echo 'class="active"';?>>본사 채용</a>
                        <a href="/company/career"<?php if($page_name=='career') echo 'class="active"';?>>채용 정보</a>
                        <hr/>
                    </div>

                    <div class="borderbox">
                        <h4>2014 이디야 공개 채용</h4>
                        <p>대한 민국 대표 커피 브랜드 이디야커피가 열정과 도전의 인재를 모집합니다.</p>
                        <a class="btn navybtn" href="https://ediya.saramin.co.kr" target="_blank">입사지원 바로가기</a>
                    </div>

                    <div class="table_wrap">
                        <table class="list">
                            <caption>list table</caption>
                            <colgroup>
                                <col width="10%" />
                                <col width="53%" />
                                <col width="22%" />
                                <col width="15%" />
                            </colgroup>
                            <thead>
                            <tr class="center">
                                <th>번호</th>
                                <th>내용</th>
                                <th>채용 기간</th>
                                <th>진행여부</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if(count($result['result']) > 0):?>
                            <?php foreach($result['result'] as $key=>$list):?>                            
                            <tr>
                                <td><?php echo $result['vnum']--;?></td>
                                <td class="left line_high"><a href="#none"><?php echo $list['title'];?></a></td>
                                <td>
                               		 <?php if(isset($list['start_date']) === TRUE && empty($list['start_date']) === FALSE && $list['start_date'] != '0000-00-00') echo date('Y.m.d', strtotime($list['start_date']));?>
                               		 <?php if(isset($list['end_date']) === TRUE && empty($list['end_date']) === FALSE && $list['end_date'] != '0000-00-00') echo ' ~ '.date('Y.m.d', strtotime($list['end_date']));?>
                                </td>
                                <td>
									<?php 
		                            		switch($list['progress_status'])
		                            		{
		                            			case 'R': echo '접수중'; break;
		                            			case 'D': echo '서류전형'; break;
		                            			case 'I': echo '면접전형'; break;
		                            			case 'T': echo '전형완료'; break;
		                            		} 
		                            ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="even" colspan="4"><?php echo $list['contents'];?></td>
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
                                    <option value="title">제목</option>
                                    <option value="contents">내용</option>
                                </select>
                                <input type="text" name="search_val" id="search_val" />
                                <input type="submit" class="btn graybtn" value="검색">
                            </div>
                            </form>
                </div>
            </section>
        </article>