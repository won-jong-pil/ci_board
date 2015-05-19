<?PHP if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<div class="box">
							<table class="view">
								<caption class="ir_pm">댓글 현황판</caption>
								<colgroup>
									<col width="9%" />
									<col width="*" />
									<col width="11%" />
									<col width="15%" />
								</colgroup>
								<tbody>
									<?php if(count($result['result']) > 0):?>
                                    <?php foreach($result['result'] as $key=>$list):?>
									<tr>
										<td><?php echo $list['name'];?></td>
										<td><?php echo nl2br($list['contents']);?></td>
										<td class="redate"><?php echo substr($list['reg_date'], 0, 10);?></td>
                                        <td>
                                            <?php if($this->session->userdata('idx') == $list['writer']):?>
                                            	<a href="/board/del/brd/comment/idx/<?php echo $list['idx'];?>" class="del_button">삭제</a>
                                            <?php endif;?>
                                        </td>										
									</tr>
									<?php endforeach;?>
									<?php else:?>
										<tr><Td colspan="4">등록된 자료가 없습니다.</Td></tr>
									<?php endif;?>
								</tbody>
							</table>
							<div class="paging"><?php echo $paging; ?></div>
</div>