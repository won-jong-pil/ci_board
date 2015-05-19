<?PHP if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>   
        <div class="event_reply">
        <div class="box">     
        <form action="/board/update_save/brd/comment" method="post" name="comment_update_form" id="comment_update_form">
        <input type="hidden" name="idx" id="idx" value="<?php echo $result['result']['idx'];?>" />
			<fieldset>
				<div class="comment"><textarea rows="4" cols="50" name="contents" id="contents"><?php echo $result['result']['contents'];?></textarea><input type="submit" value="댓글 수정하기" name="btn_reply"/></div>
			</fieldset>     
        </form>
        </div>
        </div>