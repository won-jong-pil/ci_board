<?PHP if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
        <article class="content">
            <section class="contents_inner">
                <p class="page_location">
                    <img src="/images/common/img_location_bul1.jpg" alt="" />
                    <a href="/">HOME</a>
                    <img src="/images/common/img_location_bul2.jpg" alt="" />
                    <a href="/product/new">PRODUCT</a>
                    <img src="/images/common/img_location_bul2.jpg" alt="" />
                    <a href="/board/listing/brd/<?php echo $brd;?>/sel_cate/1"><?php echo $board_name;?></a>
                </p>

                <h3><?php echo $board_name;?></h3>
				
                <div class="contents_area coffee">
                	<?php if(isset($cate) === TRUE && count($cate) > 0):?>
                	<?php if(isset($sel_cate) === FALSE) $sel_cate = 1;?>
                    <div class="tab_wrap tab_col4 product_tab clearfix">
                    	<?php foreach($cate as $key=>$list):?>
                        <a <?php if($key == $sel_cate):?> class="active"<?php endif;?> href="/board/listing/sel_cate/<?php echo $key;?>/brd/<?php echo $brd;?>"><?php echo $list;?><img src="/images/ediya/bg_campaign_tabarr.png" alt="하단방향화살표" class="tablt" /></a>
                        <?php endforeach;?>
                    </div> 
                    <?php endif;?>                
                	<!-- 제품 상세 이미지 영역 시작 -->
                	<!-- .flag와 .syrup, .powder, .topping은 .visib를 이용하여 페이지에 표시 여부를 정합니다.-->
                	<?php if(isset($view_result) === TRUE):?>
                	<div class="pdview clearfix">
                		<?php if(isset($view_result['file_info']['web_view_file']) === TRUE):?>
                		<div class="imgzone">
                			<img src="<?php echo $img_url.$view_result['file_info']['web_view_file']['file_name'];?>" alt="제품 이미지 입니다." />
                		</div>
                		<?php endif;?>
                		<div class="textzone">
              				<p class="pdname"><?php echo $view_result['result']['kr_title'];?></span></p>
            				<p class="pddscpt"><?php echo nl2br($view_result['result']['kr_contents']);?></p>
            				<?php if(isset($view_result['result']['pconfig']) === TRUE && ($pconfig_size = count($view_result['result']['pconfig'])) > 0):?>
                			<div class="source">
                			<?php 
                				$pconfig_array = array(
                					array('/images/product/icon_coffee_espresso.png', '재료아이콘'),
									array('/images/product/icon_coffee_milk.png', '재료아이콘'),
               						array('/images/product/icon_coffee_homemilk.png', '재료아이콘'),
									array('/images/product/icon_coffee_cream.png', '재료아이콘'),
									array('/images/product/icon_coffee_syrupmocha.png', '모카시럽아이콘'),
									array('/images/product/icon_coffee_syrupcaramel.png', '카라멜시럽아이콘'),
									array('/images/product/icon_coffee_syrupwchoco.png', '화이트초코시럽아이콘'),
									array('/images/product/icon_coffee_pwdcinnamon.png', '시나몬파우더아이콘'),
									array('/images/product/icon_coffee_pwdchoco.png', '초코파우더아이콘'),
									array('/images/product/icon_coffee_pwdmint.png', '민트파우더아이콘'),
									array('/images/product/icon_coffee_pwdvanilla.png', '바닐라파우더아이콘'),
									array('/images/product/icon_coffee_toppingmint.png', '민트토핑아이콘'),
									array('/images/product/icon_coffee_toppingmocha.png', '모카토핑아이콘'),
									array('/images/product/icon_coffee_toppingcinnamon.png', '시나몬토핑아이콘'),
									array('/images/product/icon_coffee_toppingcaramel.png', '카라멜토핑아이콘')
                				);
							?>
                			<?php foreach($view_result['result']['pconfig'] as $key=>$list):?>
                				<span>
	                				<img src="<?php echo $pconfig_array[$list-1][0];?>" alt="<?php echo $pconfig_array[$list-1][1];?>" />
	                				<?php if(($key+1) != $pconfig_size):?><img src="/images/product/icon_coffee_plus.png" alt="플러스아이콘" class="plusimg" /><?php endif;?>
                				</span>
                			<?php endforeach;?>	
                			</div>
                			<?php endif;?>
                		</div>
                		<!-- 상단 음료의 신제품과, 베스트상품의 표시 시작 -->
                		<div class="flag">
							<?php if(isset($view_result['result']['hot_icon']) === TRUE && $view_result['result']['hot_icon'] == 'Y'):?><p class="visib">BEST</p><?php endif;?>
							<?php if(isset($view_result['result']['new_icon']) === TRUE && $view_result['result']['new_icon'] == 'Y'):?><p class="visib">NEW</p><?php endif;?>
	                	</div>
	                	<!-- 상단 음료의 신제품과, 베스트상품의 표시 끝 -->
                	</div>
                	<?php if($brd != 'product_md'):?>
                	<div class="grayborder clearfix">
                			<p class="nutritit leftbox">제품영양정보<br /><span>(1회 제공분 기준)</span></p>
                			<ul class="nutri right box clearfix">
                				<li class="mr38">
                					<dl class="clearfix">
                						<dt>칼로리 <span>Calorie</span></dt>
                						<dd><?php echo $view_result['result']['calories'];?>kcal</dd>
                					</dl>
                				</li>
                				<li>
                					<dl class="clearfix">
                						<dt>포화지방 <span>Saturated fat</span></dt>
                						<dd><?php echo $view_result['result']['saturated'];?>g</dd>
                					</dl>
                				</li>
                				<li class="mr38">
                					<dl class="clearfix">
                						<dt>당류  <span>Saccharide</span></dt>
                						<dd><?php echo $view_result['result']['sugars'];?>g</dd>
                					</dl>
                				</li>
                				<li>
                					<dl class="clearfix">
                						<dt>나트륨  <span>Natrium</span></dt>
                						<dd><?php echo $view_result['result']['sodium'];?>mg</dd>
                					</dl>
                				</li>
                				<li class="mr38">
                					<dl class="clearfix">
                						<dt>단백질  <span>Protein</span></dt>
                						<dd><?php echo $view_result['result']['protein'];?>g</dd>
                					</dl>
                				</li>
                				<li>
                					<dl class="clearfix">
                						<dt>카페인  <span>Caffeine</span></dt>
                						<dd><?php echo $view_result['result']['caffeine'];?>mg</dd>
                					</dl>
                				</li>
                			</ul>
                	</div>
                	<?php endif;?>
                	<!-- 제품 상세 이미지 영역 시작 -->
                	<?php endif;?>
                	<!-- 제품이미지 위 NEW말풍선 역시 .visib를 이용하여 페이지에 표시 여부를 정합니다.-->
                	<div class="coffee">
                		<ul class="pdlist clearfix">
                		<?php if(count($result['result']) > 0):?>
                            <?php foreach($result['result'] as $key=>$list):?>
                			<li><a href="/board/listing/idx/<?php echo $list['idx'];?>/page/<?php echo $page;?>/<?php echo $url;?>">
                				<dl>
                					<dt class="minipd">
                						<img src="<?php echo $img_url.$list['web_file_file_name'];?>" alt="제품 미리보기 이미지" /><span>
                						<img src="/images/product/icon_coffee_new.png" alt="신상품" /></span>
                					</dt>
                					<dd><?php echo $list['kr_title'];?></dd>
                				</dl>
                				</a>
                			</li>
							<?php endforeach;?>
						<?php else:?>
							<li>등록된 자료가 없습니다.</li>
						<?php endif;?>                			
                		</ul>
                	</div>
                	
                	<div class="paging"><?php echo $paging; ?></div>
                </div>
            </section>
        </article>