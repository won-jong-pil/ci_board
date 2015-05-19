<?PHP if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
        <article class="content">
            <section class="contents_inner">
                <p class="page_location">
                    <img src="/images/common/img_location_bul1.jpg" alt="" />
                    <a href="/">HOME</a>
                    <img src="/images/common/img_location_bul2.jpg" alt="" />
                    <a href="/company/store">STORE</a>
                    <img src="/images/common/img_location_bul2.jpg" alt="" />
                    <a href="/company/store">매장 찾기</a>
                </p>

                <h3>매장 찾기</h3>

                <div class="contents_area store1">
                <form action="" method="post" name="search_store_form" id="search_store_form">
                    <div class="store_search">
                        <div id="s_map" class="store_search_map">
                            <div class="city01">
                                <a href="/board/listing/brd/store/sido/200" class="btn_map data01 ">경기도</a>
                                <a href="/board/listing/brd/store/sido/300" class="btn_map data02 ">강원도</a>
                                <a href="/board/listing/brd/store/sido/400" class="btn_map data03 ">충청남도</a>
                                <a href="/board/listing/brd/store/sido/500" class="btn_map data04 ">충청북도</a>
                                <a href="/board/listing/brd/store/sido/900" class="btn_map data05 ">전라북도</a>
                                <a href="/board/listing/brd/store/sido/800" class="btn_map data06 ">전라남도</a>
                                <a href="/board/listing/brd/store/sido/700" class="btn_map data07 ">경상북도</a>
                                <a href="/board/listing/brd/store/sido/600" class="btn_map data08 ">경상남도</a>
                                <a href="/board/listing/brd/store/sido/950" class="btn_map data09 ">제주도</a>
                            </div>
                            <div class="city02">
                                <a href="/board/listing/brd/store/sido/100" class="btn_map data01 ">서울</a>
                                <a href="/board/listing/brd/store/sido/130" class="btn_map data02 ">인천</a>
                                <a href="/board/listing/brd/store/sido/150" class="btn_map data03 ">대전</a>
                                <a href="/board/listing/brd/store/sido/120" class="btn_map data04 ">대구</a>
                                <a href="/board/listing/brd/store/sido/140" class="btn_map data05 ">울산</a>
                                <a href="/board/listing/brd/store/sido/150" class="btn_map data06 ">부산</a>
                                <a href="/board/listing/brd/store/sido/160" class="btn_map data07 ">광주</a>
                            </div>
                            <span><img src="/images/store/store_list_map_bg.jpg" class="map_img" alt=""></span>
                        </div>
						
                        <div class="store_input">
                            <dl>
                                <dt>원하는 매장을 검색하세요</dt>
                                <dd>
                                    <ul>
                                        <li>
                                            <span>지역 선택</span>
                                            <select title="시 또는 도를 선택하세요" name="sido" id="sido">
                                                <option value="">시/도</option>
	                                            <?php foreach($sido_array as $key=>$list):?>
	                                            <option value="<?php echo $list['CODE'];?>"><?php echo $list['NAME'];?></option>
	                                            <?php endforeach;?>
                                            </select>
                                            <select title="구 또는 군을 선택하세요" name="gugun" id="gugun">
                                                <option value="">구/군</option>
                                            </select>
                                        </li>
                                        <li>
                                            <label for="shop_name">매장명</label>
                                            <input type="text" name="shop_name" id="shop_name" placeholder="매장명을 입력해주세요." />
                                            <input type="submit" class="btn graybtn" value="검색">
                                        </li>
                                    </ul>
                                </dd>
                            </dl>
                            
                            <dl>
                                <dt>테마별로 매장을 찾아보세요.</dt>
                                <dd>
                                    <a href="/board/listing/brd/store/theme/6"><img src="/images/store/img_store_icon1_off.jpg"><span>테라스</span></a>
                                    <a href="/board/listing/brd/store/theme/5"><img src="/images/store/img_store_icon2_off.jpg"><span>미팅룸</span></a>
                                    <a href="/board/listing/brd/store/theme/7"><img src="/images/store/img_store_icon3_off.jpg"><span>상품권 판매</span></a>
                                    <a href="/board/listing/brd/store/theme/2"><img src="/images/store/img_store_icon4_off.jpg"><span>와이파이존</span></a>
                                    <a href="/board/listing/brd/store/theme/1"><img src="/images/store/img_store_icon5_off.jpg"><span>흡연석</span></a>
                                </dd>
                            </dl>
                        </div>
                        
                    </div>
					</form>
                    <div class="table_wrap">
                        <table class="list">
                            <caption>매장찾기 리스트</caption>
                            <colgroup>
                                <col width="18%" />
                                <col width="18%" />
                                <col width="38%" />
                                <col width="18%" />
                                <col width="8%" />
                            </colgroup>
                            <thead>
                                <tr class="center">
                                    <th>지역</th>
                                    <th>매장명</th>
                                    <th>주소</th>
                                    <th>매장 테마</th>
                                    <th>상세보기</th>
                                </tr>
                            </thead>
                            <tbody>
                            	<?php if(count($result['result']) > 0):?>
	                            	<?php foreach($result['result'] as $key=>$list):?>
	                                <tr>
	                                    <td><?php echo $list['sido'];?> <?php echo $list['gugun'];?></td>
	                                    <td><?php echo $list['NM_PARTNER'];?></td>
	                                    <td class="left"><?php echo mb_strimwidth($list['ADS'], 0, 45, '...', "utf-8");?></td>
	                                    <td>
	                                        <?php if($list['THEME6'] == 'Y'):?><img src="/images/store/img_store_icons1.jpg" alt="테라스" /><?php endif;?>
	                                        <?php if($list['THEME5'] == 'Y'):?><img src="/images/store/img_store_icons2.jpg" alt="미팅룸" /><?php endif;?>
	                                        <?php if($list['THEME7'] == 'Y'):?><img src="/images/store/img_store_icons3.jpg" alt="상품권 판매" /><?php endif;?>
	                                        <?php if($list['THEME2'] == 'Y'):?><img src="/images/store/img_store_icons4.jpg" alt="와이파이존" /><?php endif;?>
	                                        <?php if($list['THEME1'] == 'Y'):?><img src="/images/store/img_store_icons5.jpg" alt="흡연석" /><?php endif;?>
	                                    </td>
	                                    <td>
	                                        <a class="btn navybtn" href="/board/view/idx/<?php echo $list['CODE'];?>/<?php echo $url;?>">상세보기</a>
	                                    </td>
	                                </tr>
	                                <?php endforeach;?>
                                <?php else:?>
                                <tr>
                                	<td colspan="5">죄송합니다. 검색결과가 없습니다. <br />지역 또는 매장명을 다시 확인해 주세요.</td>
                                </tr>
                                <?php endif;?>
                            </tbody>
                        </table>
                    </div>
					<div class="paging"><?php echo $paging; ?></div>
					<?php if(isset($new_store['result']) === TRUE && count($new_store['result']) > 0):?>
                    <h4>신규 오픈 매장</h4>
                    <div class="storeslide">
                        <div>
                            <ul class="slides">
                            	<li class="clearfix">
                            	<?php foreach($new_store['result'] as $key=>$list):?>
									<?php $img_location = end(explode('\\', $list['IMAGE_LOCATION']));?>
		                			<?php $img_url_abs = $_SERVER['DOCUMENT_ROOT'].'/updata/store/'.$img_location.'/'.strtolower($list['FRONT_IMAGE']);?>
		                			<?php $img_url = '/updata/store/'.$img_location.'/'.strtolower($list['FRONT_IMAGE']);?>                            	
                                    <a href="/board/view/idx/<?php echo $list['CODE'];?>/<?php echo $url;?>">
                                        <?php if(file_exists($img_url_abs) === TRUE):?>
			                        		<img src="<?php echo $img_url;?>" alt="<?php echo $list['NM_PARTNER'];?>" width="230" height="190" />
				                        <?php else:?>
				                        	<img src="/images/store/store_thumb1.jpg" alt="<?php echo $list['NM_PARTNER'];?>" />
				                        <?php endif;?>
                                        <p><?php echo $list['NM_PARTNER'];?></p>
                                    </a>
                                    <?php if($key==2):?>
	                                </li>
	                                <li class="clearfix">    
                                    <?php endif;?>
                                <?php endforeach;?>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <?php endif;?>
                </div>
            </section>
        </article>
        <!-- //content -->
    </div>