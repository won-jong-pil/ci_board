<?PHP if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php if(in_array($_SERVER['HTTP_HOST'], $this->config->item('test_domain')) === TRUE):?>
    <meta name="robots" content="noindex">
    <?php endif;?>
	<title><?php echo $this->lang->line('member_manager_login_form_title');?></title>
	<?php if(isset($head_file_name) === TRUE && empty( $head_file_name ) === FALSE) $this->load->view($head_file_name);?>
</head>

<body>
<?php if( isset( $main_file_name ) === TRUE && empty( $main_file_name ) === FALSE ) $this->load->view( $main_file_name );?>
<div id="modalBase" name="modalBase" style="display:none;"></div>
</body>
</html>