<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
        <!-- visual area -->
        <section class="area1 clearfix">
            <div class="leftbox">
                <div class="slide_wrap">
                    <ul class="slides">
                    <?php if(count($banner) > 0):?>
                    	<?php foreach($banner as $key=>$list):?>       
                    	<?php if($list['banner_type'] == 'P'):?>             
                        	<li><a href="<?php echo $list['org_url'];?>"><img src="<?php echo $list['web_file_file_name_url'];?>" alt="<?php echo $list['title'];?>" /></a></li>
                        <?php else:?>
                        	<li><a href="#none"><img src="<?php echo $list['web_file_file_name_url'];?>" alt="<?php echo $list['title'];?>" /></a></li>
                        <?php endif;?>
                        <?php endforeach;?>
                    <?php endif;?>                        
                    </ul>
                </div>
            </div>
            <ul class="rightbox">
                <li class="top">
                    <dl>
                        <dt>커피 한잔의<br/>소중함을 위해...</dt>
                        <dd>
			                            최상급 생두의 선택부터 로스팅까지,<br/>
			                            오직 커피만을 향한 열정으로<br/>
			                            커피 연구소를 설립했습니다.
                        </dd>
                    </dl>
                    <a class="mainbtn" href="/coffee/institute">커피연구소</a>
                    <a class="mainbtn" href="/coffee/academy">커피아카데미</a>
                </li>
                <li class="bottom">
                    <a href="/franchise/guide">
                        <dl>
                            <dt>가맹 안내</dt>
                            <dd>당신의 성공 창업 스토리,<br/><span>이디야커피</span>가 함께 합니다.</dd>
                        </dl>
                        <span>가맹안내 바로가기</span>
                    </a>
                </li>
            </ul>
        </section>
        <!-- visual area //-->

        <!-- product / news area -->
        <section class="area2 clearfix">
            <div class="leftbox">
                <h2>EDIYA PRODUCTS</h2>
                <div class="slide_wrap">
                    <ul class="slides slider1">
                    <?php if(count($product) > 0):?>
                    	<?php foreach($product as $key=>$list):?>
                    	<?php
                    		$icon_type = '';
                    		if($list['hot_icon'] == 'Y') $icon_type = 'best';
                    		if($list['new_icon'] == 'Y') $icon_type = 'new';
                    	?>
                        <li class="<?php echo $icon_type;?> slide">
                            <a href="/board/listing/brd/<?php echo $list['board_code']?>/idx/<?php echo $list['idx'];?>">
                                <img src="<?php echo '/updata/'.$list['web_file_file_name'];?>" alt="<?php echo $list['kr_title'];?>" />
                                <p><?php echo $list['kr_title'];?></p>
                                <span>
                                    <img src="/images/main/img_product_best.png" class="best_icon" alt="best" />
                                    <img src="/images/main/img_product_new.png" class="new_icon" alt="new" />
                                </span>
                            </a>
                        </li>
                        <?php endforeach;?>
                    <?php endif;?>
                    </ul>
                </div>
            </div>
            <div class="rightbox">
                <h2>NEWS & NOTICE</h2>
                <?php if(isset($main_img_news) === TRUE && count($main_img_news) > 0):?>
                <a class="clearfix" href="/board/listing/brd/<?php echo $main_img_news['board_code']?>/idx/<?php echo $main_img_news['idx'];?>">
                    <img src="<?php echo $main_img_news['web_file_file_name_url'];?>" class="" />
                    <dl>
                        <dt><?php echo $main_img_news['title'];?></dt>
                        <dd><?php echo cut_string_utf8(strip_tags($main_img_news['contents']), 280);?></dd>
                    </dl>
                </a>
                <?php endif;?>
                
                <?php if(count($board) > 0):?>
                <ul>
                	<?php foreach($board as $key=>$list):?>
                    <li>
	                    <a href="/board/listing/brd/<?php echo $list['board_code']?>/idx/<?php echo $list['idx'];?>">
		                    <span><?php echo $list['title'];?></span>
		                    <span class="date"><?php echo date('Y. m. d' , strtotime($list['reg_date']));?></span>
	                    </a>
                    </li>
                    <?php endforeach;?>
                </ul>
                <?php endif;?>
            </div>
        </section>
        <!-- product / news area //-->

        <!-- banner area -->
        <section class="area3">
            <div class="slide_wrap">
                <ul class="slides">
                    <?php if(count($banner_bottom) > 0):?>
                    	<?php foreach($banner_bottom as $key=>$list):?>       
                    	<?php if($list['banner_type'] == 'P'):?>             
                        	<li><a href="<?php echo $list['org_url'];?>"><img src="<?php echo $list['web_file_file_name_url'];?>" alt="<?php echo $list['title'];?>" /></a></li>
                        <?php else:?>
                        	<li><a href="#none"><img src="<?php echo $list['web_file_file_name_url'];?>" alt="<?php echo $list['title'];?>" /></a></li>
                        <?php endif;?>
                        <?php endforeach;?>
                    <?php endif;?>  
                </ul>
            </div>
        </section>
        <!-- banner area //-->

        <!-- campain / store area -->
        <section class="area4 clearfix">
            <div class="leftbox">
                <h2>B’WATER 캠페인</h2>
                <div>
	                <dl>
	                    <dt><img src="/images/main/img_campain_title.png" alt="b’water 캠페인"></dt>
	                    <dd>
	                    	<p>오염된 물로 인해 질병과 죽음에 이르고 있는 아프리카 아동들을 위한 식수 정화 캠페인을 진행하고 있습니다.</p>	                    	
	                		<a class="btn" href="/company/campaign">캠페인 바로가기<span>&gt;</span></a>	                		    	
                    	</dd>
	                </dl>	                
                </div>
            </div>
            <form action="/board/listing/brd/store" method="post">
            <div class="rightbox">
                <h2>STORE</h2>
                <div class="clearfix">
                    <input type="text" name="shop_name" id="shop_name" class="searchtext" placeholder="매장명을 검색하시면 이디야 매장을 찾아드립니다." />
                    <input type="image" src="/images/main/img_searchbtn.jpg" name="searchbtn" id="searchbtn" class="searchbtn" />
                </div>
                <div class="iconwrap">
	                <a class="" href="/board/listing/brd/store/theme/6"><img class="off" src="/images/main/img_icon1_off.jpg" alt="" /><img class="on" src="/images/main/img_icon1_on.jpg" alt="" /><br/><span>테라스</span></a>
	                <a href="/board/listing/brd/store/theme/5"><img class="off" src="/images/main/img_icon2_off.jpg" alt="" /><img class="on" src="/images/main/img_icon2_on.jpg" alt="" /><br/><span>미팅룸</span></a>
	                <a href="/board/listing/brd/store/theme/7"><img class="off" src="/images/main/img_icon3_off.jpg" alt="" /><img class="on" src="/images/main/img_icon3_on.jpg" alt="" /><br/><span>상품권 판매</span></a>
	                <a href="/board/listing/brd/store/theme/2"><img class="off" src="/images/main/img_icon4_off.jpg" alt="" /><img class="on" src="/images/main/img_icon4_on.jpg" alt="" /><br/><span>와이파이존</span></a>
	                <a href="/board/listing/brd/store/theme/1"><img class="off" src="/images/main/img_icon5_off.jpg" alt="" /><img class="on" src="/images/main/img_icon5_on.jpg" alt="" /><br/><span>흡연석</span></a>
                </div>
                
                <div class="bottom">
	                <h2>신규 오픈 매장</h2>
	                <a class="btn" href="/board/listing/brd/store"><img src="/images/main/img_store_more.jpg" alt="더보기" /></a>
	                <ul class="clearfix">
	                <?php if(count($store) > 0):?>
	                	<?php foreach($store as $key=>$list):?>
	                	<?php $img_location = end(explode('\\', $list['IMAGE_LOCATION']));?>
	                	<?php $img_url_abs = $_SERVER['DOCUMENT_ROOT'].'/updata/store/'.$img_location.'/'.strtolower($list['FRONT_IMAGE']);?>
	                	<?php $img_url = '/updata/store/'.$img_location.'/'.strtolower($list['FRONT_IMAGE']);?>
	                    <li>
	                        <a href="/board/view/brd/store/idx/<?php echo $list['CODE'];?>">
	                        <?php if(file_exists($img_url_abs) === TRUE):?>
	                        	<span><img src="<?php echo $img_url;?>" alt="<?php echo $list['NM_PARTNER'];?>" width="153" height="80" /></span><br/>
	                        <?php else:?>
	                        	<span><img src="/images/main/img_store_thumb1.jpg" alt="<?php echo $list['NM_PARTNER'];?>" /></span><br/>
	                        <?php endif;?>
		                        <span><?php echo $list['NM_PARTNER'];?></span>
	                        </a>
	                    </li>
	                    <?php endforeach;?>
	                <?php endif;?>
	                </ul>
                </div>
            </div>
            </form>
        </section>
        <!-- campain / store area //-->

        <!-- overview area -->
        <section class="area5">
            <a href="/company/company">
				<dl>
					<dt><img src="/images/main/img_ad_title.png" alt="신뢰와 상생" /></dt>
					<dd>
						이디야커피는 신뢰를 최고로 여기며, 상생협력을 실천하고 있습니다.
					</dd>
				</dl>
			</a>
        </section>
        <!-- overview area //-->
