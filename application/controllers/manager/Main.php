<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @title 관리자 페이지
 * @author 원종필(won0334@chol.com)
 * */
class Main extends CI_Controller 
{
	var $view_data = array();//view 출력용 데이터

	function __construct()
	{
		parent::__construct();

		$this->view_data['index_file_name'] = 'manager/index';
		$this->view_data['default_head_file_name'] = 'manager/default_head';
		$this->view_data['left_file_name'] = 'manager/left';
	}
	/**
	 * @title 관리자 로그인폼
	 * @author 원종필(won0334@chol.com)
	 * */	
	function login()
	{
		$this->load->library('form_validation');
		$this->lang->load('member', $this->session->userdata('language'));
		$this->form_validation->set_rules('id', 'lang:member_incorrect_id_rule', 'required|min_length[4]|max_length[20]');
		$this->form_validation->set_rules('passwd', 'lang:member_incorrect_password_rule', 'required|min_length[8]');
	
		if ($this->form_validation->run() === TRUE)
		{
			$id = $this->input->post('id');
			$passwd = $this->input->post('passwd');

			$this->db->from('manager');
			$this->db->where('id', $id);
			$result = $this->db->get();
		
			if( $result->num_rows() <= 0 )
			{
				alert($this->lang->line('member_incorrect_manager_info'), '/manager', TRUE);
			}
			else 
			{
				$mem = $result->row_array();
				//$input_pass = hash('sha256', $passwd);
	
				if($mem['attempts'] >= 5)
				{
					alert($this->lang->line('member_manager_lock'), '/manager', TRUE);
				}

				if(password_verify($passwd, $mem['passwd']) === FALSE)
				{
					$this->db->where('id', $id);
					$this->db->set('attempts', 'attempts + 1', FALSE);
					$this->db->update('manager');
					alert($this->lang->line('member_incorrect_password'), '/manager', TRUE);
				}
				
				$this->db->set('attempts', 0);
				$this->db->where('id', $id);
				$this->db->update('manager');			
	
				$this->session->set_userdata('admin_id', $mem['id']);
	
				if(date('Y-m-d') > date('Y-m-d', strtotime('+90 days', strtotime($mem['pass_update_date']))))
				{
					alert($this->lang->line('member_over_password_time'), '/manager/board/update_form/brd/manager/idx/'.$mem['idx'], TRUE);
				}			
			}
	
			$this->load->helper('url');
			redirect('/manager/board/listing/brd/notice');
			exit;
		}
		
		//레이아웃 처리(출력)
		$this->view_data['index_file_name'] = 'manager/login/index';
		$this->view_data['head_file_name'] = 'manager/login/head';
		$this->view_data['main_file_name'] = 'manager/login/form';
		$this->load->view($this->view_data['index_file_name'], $this->view_data);
	}
	/**
	 * @title 관리자 로그아웃 처리
	 * @author 원종필(won0334@chol.com)
	 * */	
	function log_out()
	{
		$this->session->sess_destroy();
		$this->load->helper('url');
		redirect('/manager');
	}
	/**
	 * @title 관리자 등록시 id 체크
	 * @author 원종필(won0334@chol.com)
	 * */
	function id_check()
	{
		$this->lang->load('member', $this->session->userdata('language'));
		$id = $this->input->post('id');
		
		$this->db->from('manager');
		$this->db->where('id', $id);
		$result = $this->db->get();
		
		if( $result->num_rows() <= 0 )
		{
			if(preg_match("/^[0-9A-Z][0-9A-Z_-]{4,20}[0-9A-Z]$/i", $id))
			{
				echo json_encode(array('result'=>TRUE, 'msg'=>$this->lang->line('member_check_id_success')));
			}
			else
			{
				echo json_encode(array('result'=>FALSE, 'msg'=>$this->lang->line('member_check_id_fail')));
			}
		}
		else
		{
			echo json_encode(array('result'=>FALSE, 'msg'=>$this->lang->line('member_check_id_exists')));
		}		
	}
}
/*관리자 페이지*/