<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<!-- 배너 상세보기  -->
<?php echo form_open($next_url, array('id'=>'write_form', 'enctype'=>'multipart/form-data', 'accept-charset'=>$this->config->item('site_encoding')));?>
<?php if(isset($result['result']['idx'])===TRUE):?><input type="hidden" name="idx" id="idx" value="<?php echo $result['result']['idx'];?>" /><?php endif;?>
<h3>| <?php echo $board_name;?>  <?php if($mode == 'write'):?>쓰기<?php else:?>수정<?php endif;?></h3>
<div class="row">
    <div class="form-group">
        <label for="title">제목</label>
        <input type="text" name="title" id="title" class="form-control" value="<?php if(isset($result['result']['title'])===TRUE) echo $result['result']['title']; else echo set_value('title');?>" placeholder="제목을 입력하세요." />
    </div>		
    <div class="form-group">
        <label for="contents">내용</label>
        <textarea class="form-control" name="contents" id="contents"><?php if(isset($result['result']['contents'])===TRUE) echo $result['result']['contents']; else echo set_value('contents');?></textarea>
    </div>	
    <div class="radio">
        <label for="status">노출여부</label>
        <label class="checkbox-inline">
          <input type="radio" name="status" id="statusY" value="Y"<?php if(isset($result['result']['status'])===FALSE OR (isset($result['result']['status'])===TRUE && $result['result']['status'] =='Y')):?> checked="checked"<?php endif;?>> Y 
        </label>
        <label class="checkbox-inline">
            <input type="radio" name="status" id="statusN" value="N"<?php if(isset($result['result']['status'])===TRUE && $result['result']['status'] =='N'):?> checked="checked"<?php endif;?>> N
        </label>
    </div>
    <div class="form-group radio">
        <label for="top_notice">상단 노출여부</label>
        <label class="checkbox-inline">
            <input type="radio" name="top_notice" id="top_noticeY" value="Y"<?php if(isset($result['result']['top_notice'])===TRUE && $result['result']['top_notice'] =='Y'):?> checked="checked"<?php endif;?>> Y 
        </label>
        <label class="checkbox-inline">
            <input type="radio" name="top_notice" id="top_noticeN" value="N"<?php if(isset($result['result']['top_notice'])===FALSE OR (isset($result['result']['top_notice'])===TRUE && $result['result']['top_notice'] =='N')):?> checked="checked"<?php endif;?>> N
        </label>
    </div>	    
    <?php if((isset($file_use) === TRUE && $file_use == 'Y') && (isset($file_list) === TRUE && count($file_list) > 0)):?>
    <?php foreach($file_list as $key=>$list):?>
    <div class="form-group">
        <label for="contents"><?php echo $this->lang->line($list['title']);?></label>
        <input type="file" name="<?php echo $key;?>" id="<?php echo $key;?>" />
    </div>	
	<?php if(isset($result['file_list'][$key])===TRUE):?>
	<div class="form-group">
		<label for="contents"><?php echo $this->lang->line($list['title']);?></label>
		<?php echo $result['file_list'][$key]['org_name'];?>&nbsp;<input type="checkbox" name="check_del[]" id="check_del" value="<?php echo $result['file_list'][$key]['idx'];?>" />이미지 삭제
	</div>
	<?php endif;?>	
	<?php endforeach;?>
	<?php endif;?>
    <input type="submit" class="btn btn-default"value="확인" />
	<a href="/manager/board/listing/<?php echo $url;?>" class="btn btn-default">목록보기</a>
	<?php if(isset($result['result']['idx']) === TRUE):?>
	<a href="/manager/board/del/idx/<?php echo $result['result']['idx'];?>/<?php echo $url;?>" class="btn btn-default">삭제</a>
	<?php endif;?>	
</div>
</form>     

<?php
 //폼검증 에러 표출
 if(validation_errors())
 {
  echo '<div id="error"><h1>'.validation_errors().'</h1></div>';
 }
?>
<!-- //배너 상세보기  -->   