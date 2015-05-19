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

                <div class="contents_area event1">
                    <div class="tab_wrap tab_col4">
                        <a <?php if($sel_type=='now'):?> class="active"<?php endif;?> href="/board/listing/brd/event/sel_type/now/<?php $url?>">진행중인 이벤트</a>
                        <a <?php if($sel_type=='end'):?> class="active"<?php endif;?>href="/board/listing/brd/event/sel_type/end/<?php $url?>">종료된 이벤트</a>
                        <a <?php if($sel_type=='end'):?> class="active"<?php endif;?>href="/board/listing/brd/event/sel_type/winner/<?php $url?>">당첨자 발표</a>
                        <hr/>
                    </div>

                    <div class="box">                        
                        <p><img src="/images/mypage/img_bullet_small.png" /> 상시 이벤트</p>                        
                        <div class="eventslide">
                            <div>
                                <ul class="slides item">
                                <?php $always_count = count($always_result['result']);?>
                       		    <?php if(isset($always_result) === TRUE && $always_count > 0):?>
                                    <li class="clearfix">
                                    <?php foreach($always_result['result'] as $key=>$list):?>
                                        <div>
                                            <a href="/board/view/idx/<?php echo $list['idx'];?>/<?php echo $url;?>">
                                                <img src="<?php echo $img_url.$list['web_file_file_name'];?>" alt="" />
                                                <p><?php echo $list['title'];?></p>
                                                <ul>
                                                <?php if($list['date_type'] == 'N'):?>
                                                    <li>기간 : <span><?php echo $list['start_date'];?> ~ <?php echo $list['end_date'];?></span></li>
                                                <?php else:?>
                                                    <li>기간 : <span>상시진행</span></li>
                                                <?php endif;?>
                                                    <li>당첨자발표 : <span><?php echo $list['anno_date'];?></span></li>
                                                </ul>
                                            </a>
                                        </div>
                                    	<?php if(($key+1) % 2 == 0 && ($key+1) < $always_count) echo '</li><li>';?>
									<?php endforeach;?>
									</li>
								<?php else:?>
										<li>등록된 자료가 없습니다.</li>
								<?php endif;?>
                                </ul>
                            </div>
                        </div>
                        
                        <p><img src="/images/mypage/img_bullet_small.png" /> 기획 이벤트</p>
                        <div class="planevent">
                            <ul>
                            <?php if(count($result['result']) > 0):?>
                                <?php foreach($result['result'] as $key=>$list):?>                            
                                <li>
                                	<a href="/board/view/idx/<?php echo $list['idx'];?>/<?php echo $url;?>">
                                	<img src="<?php echo $img_url.$list['web_file_file_name'];?>" alt="<?php echo $list['title'];?>" />
                                	<!--<p class="clearfix">
                                		<span class="leftbox"><?php echo $list['title'];?></span>
                                		<span class="rightbox">기간 : <?php echo $list['start_date'];?>~<?php echo $list['end_date'];?> &nbsp;&nbsp;&nbsp; 당첨자발표 : <?php echo $list['anno_date'];?></span>
                                	</p>-->
                                	</a>
                                </li>
								<?php endforeach;?>
							<?php else:?>
								<li>등록된 자료가 없습니다.</li>
							<?php endif;?>
                            </ul>
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