<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	http://codeigniter.com/user_guide/general/hooks.html
|
*/
/*$hook['pre_controller'] = array(
		'function' => 'check_mobile',
		'filename' => 'base_hooks.php',
		'filepath' => 'hooks'
);*/

$hook['post_controller_constructor'] = function(){
	$CI = &get_instance();
	$CI->load->library('user_agent');

	if($CI->uri->segment(4) == 'pc' && $CI->session->userdata('web_view') === FALSE)
	{
		$CI->session->set_userdata('web_view', 'Y');
	}
	
	//모바일 상태 처리
	if($CI->agent->is_mobile() === FALSE)
	{
		define('MOBILE_USE', 'N');
	}
	else
	{
		if($CI->session->userdata('web_view') == 'Y')
		{
			define('MOBILE_USE', 'N');
		}
		else
		{
			define('MOBILE_USE', 'Y');
		}
	}

	//현재 선택된 언어 정보 처리
	$language = $CI->input->post('language');
	if(isset($language) === TRUE && empty($language) === FALSE)
	{
		$CI->session->set_userdata('language', $language);
	}
	
	if($CI->session->userdata('language') != FALSE)
	{
		define('LANGUAGE', $CI->session->userdata('language'));
	}
	else
	{
		define('LANGUAGE', 'korean');
	}

	log_message('debug', 'hoook ip'.$_SERVER['REMOTE_ADDR']);
	log_message('debug', 'agent'.$_SERVER['HTTP_USER_AGENT']);
};