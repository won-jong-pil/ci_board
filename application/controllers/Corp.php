<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @title 메인 페이지 및 순수 안내 페이지 처리
 * @author - 원종필(won0334@chol.com)
 * */
class Corp extends CI_Controller 
{
	var $view_data = array();

	function __construct()
	{
		parent::__construct();
		$this->output->enable_profiler(FALSE);
	}
	/**
	 * @title 메인 페이지 첫 화면
	 * @author - 원종필(won0334@chol.com)
	 * */
	function index()
	{
		//레이아웃 처리(출력)
		if(MOBILE_USE == 'Y') $base_skin = 'mobile/'; else $base_skin = 'web/';
		if(defined('LANGUAGE') === TRUE) $base_skin .=  LANGUAGE.'/';
		$this->view_data['head_file_name'] = $base_skin.'index/head';
		$this->view_data['main_file_name'] = $base_skin.'index/main';
		$this->view_data['index_file_name'] = $base_skin.'index/index';
		$this->view_data['default_head_file_name'] = $base_skin.'index/default_head';		
		$this->view_data['main_class'] = ' main';
		$this->load->view($this->view_data['index_file_name'], $this->view_data);
		
		if($config['debug'] == 'Y') $this->board_drv->write_error(TRUE, 'debug');		
	}	
	/**
	 * @title 페이지 뷰어
	 * @author - 원종필(won0334@chol.com)
	 * */
	function page_view()
	{
		$page_name = $this->uri->segment(2);
		if( isset($page_name) === FALSE OR empty($page_name) === TRUE ) alert('페이지 정보가 올바르지 않습니다.', '', TRUE );
		$this->view_data['page_name'] = $page_name;
		
		//레이아웃 처리(출력)
		if(MOBILE_USE == 'Y') $base_skin = 'mobile/'; else $base_skin = 'web/';
		if(defined('LANGUAGE') === TRUE) $base_skin .=  LANGUAGE.'/';
		$this->view_data['main_file_name'] = $base_skin.'page/'.$page_name;
		$this->view_data['index_file_name'] = $base_skin.'index/index';
		$this->view_data['default_head_file_name'] = $base_skin.'index/default_head';

		$is_ajax = $this->input->is_ajax_request();
		if($is_ajax === TRUE)
		{
			$this->load->view($this->view_data['main_file_name'], $this->view_data);
		}
		else
		{
			$this->load->view($this->view_data['index_file_name'], $this->view_data);
		}		
	}	
}