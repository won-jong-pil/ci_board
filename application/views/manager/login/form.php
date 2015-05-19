<?PHP if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<div class="container">	
<?php echo form_open('/manager', array('id'=>'logFrm', 'class'=>'form-horizontal'));?>
<input type="hidden" id="redirect_to" name="redirect_to">
<h2 class="form-signin-heading">Please sign in</h2>
  <div class="form-group">
    <label for="inputEmail3" class="col-sm-2 control-label">Email</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="id" name="id" placeholder="<?php echo $this->lang->line('member_placehold_id');?>" />
    </div>
  </div>
  <div class="form-group">
    <label for="inputPassword3" class="col-sm-2 control-label">Password</label>
    <div class="col-sm-10">
      <input type="password" class="form-control" id="password" name="passwd" placeholder="<?php echo $this->lang->line('member_placehold_password');?>" />
    </div>
  </div>
	
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" class="btn btn-default"><?php echo $this->lang->line('member_login_button');?></button>
    </div>
  </div>
<?php
 //폼검증 에러 표출
 if(validation_errors())
 {
  echo '<div id = "error"><h1>'.validation_errors().'</h1></div>';
 }
?>
</form>
</div>
