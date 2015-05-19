<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<p></p>
<h1><?php echo $board_name;?></h1>
<?php echo form_open('', array('id'=>'search_form', 'accept-charset'=>$this->config->item('site_encoding')));?>    
<div class="row">
    <select title="" name="search_key" id="search_key" >
	    <option value="title"<?php echo isset($search_key) == TRUE && $search_key == 'title' ? ' selected' : ''?>>제목</option>
	    <option value="contents"<?php echo isset($search_key) == TRUE && $search_key == 'contents' ? ' selected' : ''?>>내용</option>
	</select>							
	<input type="text" id="search_val" name="search_val" value="<?php echo isset($search_val) == TRUE ? $search_val : ''?>" />
	<button type="submit" ><span class="screen_out">검색</span></button>
</div>									
</form>

<div class="row">
    <div class="col-md-1">Check.<input type="checkbox" name="check_all" id="check_all" value="all" /></div>
    <div class="col-md-2">No.</div>
    <div class="col-md-3">제목</div>
    <div class="col-md-4">상태</div>
    <div class="col-md-5">등록일</div>
    <div class="col-md-6">관리</div>
</div>  
<?php echo form_open('/manager/board/del/<?php echo $url;?>', array('id'=>'del_form', 'accept-charset'=>$this->config->item('site_encoding')));?>
<div class="row">
<?php if(sizeof($result['result']) > 0):?>
    <?php foreach($result['result'] as $key=>$list): ?>  
            <div class="col-md-1"><input type="checkbox" name="del_list[]" id="del_list_<?php echo $list['idx'];?>" value="<?php echo $list['idx'];?>"></div>
            <div class="col-md-2"><?php echo $result['vnum']--;?></div>
            <div class="col-md-3"><?php echo $list['title'];?></div>
            <div class="col-md-4"><?php echo $list['status'];?></div>
            <div class="col-md-5"><?php echo $list['reg_date'];?></div>
            <div class="col-md-5">
                <a href="/manager/board/update/idx/<?php echo $list['idx'];?>/<?php echo $url;?>">수정</a>
		        <a href="/manager/board/del/idx/<?php echo $list['idx'];?>/<?php echo $url;?>" name="delbutton">삭제</a>          
			</div>
    <?php endforeach; ?>
<?php else:?>
      등록된 자료가 없습니다.
<?php endif;?>	
</div>
</form>
<div class="row"><?php echo $paging; ?></div>
<div class="row">
    <button type="button" id="all_del">선택 삭제</button>    
    <a href="/manager/board/write/<?php echo $url;?>">등록</a>
</div>
	