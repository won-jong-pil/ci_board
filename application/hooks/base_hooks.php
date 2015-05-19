<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* @title 모바일 접근 여부를 상수에 기록
* @author 원종필(won0334@chol.com)
* @작업내역
* @return NULL
* */
function check_mobile()
{
	$CI =& get_instance();
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
		define('LANGUAGE', 'kr');
	}

	log_message('debug', 'hoook ip'.$_SERVER['REMOTE_ADDR']);
	log_message('debug', 'agent'.$_SERVER['HTTP_USER_AGENT']);
}
/*모바일 체크*/