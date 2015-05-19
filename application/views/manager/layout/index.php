<?PHP if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="<?php echo $this->config->item('site_encoding');?>">
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />	
	<meta name="description" content="<?php echo $this->config->item('site_desc');?>">
	<meta name="author" content="<?php echo $this->config->item('site_author');?>">

	<title>관리</title>

	<?php if(in_array($_SERVER['HTTP_HOST'], $this->config->item('test_domain')) === TRUE):?><meta name="robots" content="noindex"><?php endif;?>
	<?php if(isset($default_head_file_name) === TRUE && empty( $default_head_file_name ) === FALSE) $this->load->view($default_head_file_name);?>
	<?php if(isset($head_file_name) === TRUE && empty( $head_file_name ) === FALSE) $this->load->view($head_file_name);?>
</head>

<body>
	    <!-- Fixed navbar -->
    <nav class="navbar navbar-default navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#"><?php echo $this->config->item('site_name');?></a>
          <ul class="nav navbar-nav">
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Dropdown <span class="caret"></span></a>
              <ul class="dropdown-menu" role="menu">
                <li><a href="#">Action</a></li>
                <li><a href="#">Another action</a></li>
                <li><a href="#">Something else here</a></li>
                <li class="divider"></li>
                <li class="dropdown-header">Nav header</li>
                <li><a href="#">Separated link</a></li>
                <li><a href="#">One more separated link</a></li>
              </ul>
            </li>
          </ul>          
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav navbar-right">
            <li><a href="/manager/logout" class="line0">로그아웃</a></li>
            <li><a href="/manager/board/update_form/brd/manager/idx/<?php echo $this->session->userdata('admin_idx');?>" class="line0">정보변경</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>

    <div class="container">
		<!-- contents -->
		<?php if( isset( $main_file_name ) === TRUE && empty( $main_file_name ) === FALSE ) $this->load->view( $main_file_name );?>
    </div> <!-- /container -->
<div id="modalBase" name="modalBase" style="display:none;"></div>
</body>
</html>

