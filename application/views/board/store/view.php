<?PHP if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
        <article class="content">
            <section class="contents_inner">
                <p class="page_location">
                    <img src="/images/common/img_location_bul1.jpg" alt="" />
                    <a href="#none">HOME</a>
                    <img src="/images/common/img_location_bul2.jpg" alt="" />
                    <a href="#none">STORE</a>
                    <img src="/images/common/img_location_bul2.jpg" alt="" />
                    <a href="#none">매장 찾기</a>
                </p>

                <h3>매장 찾기</h3>

                <div class="contents_area store2">
                    <div class="borderbox">
                        <div class="clearfix">
                            <dl>
                                <dt>
                                    <?php if(file_exists($_SERVER['DOCUMENT_ROOT'].$img_url1) === TRUE):?><img src="<?php echo $img_url1;?>" alt="" width="290" height="200"><?php else:?><img src="/images/store/store_img.jpg" /><?php endif;?>
                                    <?php if(file_exists($_SERVER['DOCUMENT_ROOT'].$img_url2) === TRUE):?><img src="<?php echo $img_url2;?>" alt="" width="290" height="200"><?php else:?><img src="/images/store/store_img.jpg" /><?php endif;?>
                                    <?php if(file_exists($_SERVER['DOCUMENT_ROOT'].$img_url3) === TRUE):?><img src="<?php echo $img_url3;?>" alt="" width="290" height="200"><?php else:?><img src="/images/store/store_img.jpg" /><?php endif;?>
                                </dt>
                                <dd>
                                    <a href="#none"><?php if(file_exists($_SERVER['DOCUMENT_ROOT'].$img_url1) === TRUE):?><img src="<?php echo $img_url1;?>" alt="" width="90" height="68" /><?php else:?><img src="/images/store/store_thumb.jpg" alt="" /><?php endif;?></a>
                                    <a href="#none"><?php if(file_exists($_SERVER['DOCUMENT_ROOT'].$img_url2) === TRUE):?><img src="<?php echo $img_url2;?>" alt="" width="90" height="68" /><?php else:?><img src="/images/store/store_thumb.jpg" alt="" /><?php endif;?></a>
                                    <a href="#none"><?php if(file_exists($_SERVER['DOCUMENT_ROOT'].$img_url3) === TRUE):?><img src="<?php echo $img_url3;?>" alt="" width="90" height="68" /><?php else:?><img src="/images/store/store_thumb.jpg" alt="" /><?php endif;?></a>
                                </dd>
                            </dl>
                            <div>
                                <h4><?php echo $result['result']['NM_PARTNER'];?></h4>
                                <p><?php echo $result['result']['DC_RMK'];?></p>
                                <div class="table_wrap">
                                    <table class="form">
                                        <caption>매장소개 테이블</caption>
                                        <colgroup>
                                            <col width="30%" />
                                            <col width="*" />
                                        </colgroup>
                                        <tbody>
                                            <tr>
                                                <th>주소</th>
                                                <td class="td_address"><?php echo $result['result']['ADS'];?></td>
                                            </tr>
			                                <tr>
												<th>전화번호</th>
												<td><?php echo $result['result']['NO_TEL'];?></td>
											</tr>                                            
                                            <tr>
                                                <th>영업시간</th>
                                                <td><?php echo $result['result']['OPEN_TIME'];?></td>
                                            </tr>
                                            <tr>
                                                <th>찾아오시는길</th>
                                                <td><?php echo $result['result']['DC_RMK2'];?></td>
                                            </tr>
                                            <tr>
                                                <th>테마</th>
                                                <td>
                                                    <?php if($result['result']['THEME6'] == 'Y'):?><img src="/images/store/img_store_icons1.jpg" alt="테라스" /><?php endif;?>
			                                        <?php if($result['result']['THEME5'] == 'Y'):?><img src="/images/store/img_store_icons2.jpg" alt="미팅룸" /><?php endif;?>
			                                        <?php if($result['result']['THEME7'] == 'Y'):?><img src="/images/store/img_store_icons3.jpg" alt="상품권 판매" /><?php endif;?>
			                                        <?php if($result['result']['THEME2'] == 'Y'):?><img src="/images/store/img_store_icons4.jpg" alt="와이파이존" /><?php endif;?>
			                                        <?php if($result['result']['THEME1'] == 'Y'):?><img src="/images/store/img_store_icons5.jpg" alt="흡연석" /><?php endif;?>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="map" id="map-canvas"></div>
                    </div>
                </div>
            </section>
        </article>
        <!-- //content -->
    </div>