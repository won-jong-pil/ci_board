<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @title 게시판
 * @author - 원종필(won0334@chol.com)
 * */
class Board extends CI_Controller 
{
	var $view_data = array();//뷰에 전달할 값 저장 배열
	function __construct()
	{
		parent::__construct();
		$this->output->enable_profiler(FALSE);
		
		$id = $this->session->userdata('admin_id');
		if(empty($id) === TRUE)
		{
			$this->lang->load('member', $this->session->userdata('language'));	
			alert($this->lang->line('member_no_login'), '/manager', TRUE);
		}
		
		$this->view_data['index_file_name'] = 'manager/layout/index';
		$this->view_data['default_head_file_name'] = 'manager/layout/default_head';
		$this->view_data['brd'] = '';
	}
	/**
	 * @title 게시판 에디터 이미지 업로드 처리를 위함.
	 * @author - 원종필(won0334@chol.com)
	 * */	
	function editor_upload()
	{
		if(isset($_FILES['upload']) === TRUE && empty($_FILES['upload']['tmp_name']) === FALSE)
		{
			$upload_config['upload_url'] = '/updata/editor';
			$upload_config['upload_path'] = $_SERVER['DOCUMENT_ROOT'].'/updata/editor';
			$upload_config['allowed_types'] = 'jpg|gif|jpeg|png';
			$upload_config['encrypt_name'] = TRUE;
		
			$this->load->library('upload', $upload_config);
		
			if ( ! $this->upload->do_upload('upload'))
			{
				log_message('debug', '업로드가 실패하였습니다. \n'.strip_tags($this->upload->display_errors()));
			}
			else
			{
				$upload_data = $this->upload->data();
			}
		}

		$CKEditorFuncNum = $this->input->get('CKEditorFuncNum');
		if($upload_data)
		{
			$url = $upload_config['upload_url'].'/'.$upload_data['file_name'];
			echo "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction('".$CKEditorFuncNum."', '".$url."', '전송에 성공 했습니다')</script>";
		}
		else 
		{
			echo "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction('".$CKEditorFuncNum."', '', strip_tags($this->upload->display_errors()))</script>";
		}
	}
	/**
	 * @title 게시판 에디터 이미지 삭제 처리를 위함.
	 * @author - 원종필(won0334@chol.com)
	 * */
	function editor_upload_delete()
	{
		$upload_config['upload_path'] = $_SERVER['DOCUMENT_ROOT'].'/updata/editor';
		$delete = $this->input->get('img');
		$pos = strrpos($delete, '/');
		
		$delete = substr($delete, $pos+1);
		$filepath = sprintf("%s/%s", $upload_config['upload_path'], $delete);
		log_message('debug', $filepath);
		$r = unlink($filepath);
		
		echo $r ? true : false;
	}	
	/**
	 * @title 게시판 리스트
	 * @author - 원종필(won0334@chol.com)
	 * */	
	public function listing()
	{
		$this->lang->load('board', $this->session->userdata('language'));			
		$url_array = $this->uri->uri_to_assoc(4);
		if(isset($url_array['brd']) === FALSE OR empty($url_array['brd']) === TRUE) alert($this->lang->line('board_no_code'), '', TRUE );
		$this->load->helper(array('form'));
		
		//검색 파라메터 처리
		$search_key_array = array('search_key', 'search_val', 'brd', 'page', 'search_email', 'group_name', 'cate', 'contents_idx', 'start_date', 'end_date', 'type');
		$search_array = convert_search($search_key_array, $url_array);//검색 항목을 포스트와 segment를 검색하여 존재하는 정보를 리턴
		if(isset($search_array['search_val']) === TRUE && empty($search_array['search_val']) === FALSE)  
		{
			$search_array['search_val'] = urldecode($search_array['search_val']);//검색 항목중 검색키값은 segment로 전달시 한글값을 인코딩되므로 view출력시 디코딩 처리
			if (detectEncoding($search_array['search_val'], array('UTF-8'))!='UTF-8') $search_array['search_val'] = iconv('EUC-KR', 'UTF-8', $search_array['search_val']);
		}
		$this->view_data = $config =  array_merge($this->view_data, $search_array);//검색 항목을 view로 전달		
		//데이터 가져오기
		$config['debug'] = 'Y';
		$config['vnum_use'] = 'Y';
		$config['board_code'] = $url_array['brd'];
		$config['page'] = (isset($url_array['page']) === TRUE && empty($url_array['page']) === FALSE)?$url_array['page']:1;
		try{
			$this->load->driver('board_drv',  $config);
		}catch(Exception $e)
		{
			echo $this->lang->line($e->getMessage()).$e->getMessage();
			exit;
		}
		
		try{
			$result = $this->board_drv->get_list($config);	
		}catch(Exception $e)
		{
			echo $this->lang->line($e->getMessage()).$e->getMessage();
			exit;		
		}
		$this->view_data['result'] = $result;
		$this->view_data['board_name'] = $this->board_drv->get_attr('board_name');

		unset($search_array['page']);
		$this->view_data['url'] = $this->uri->assoc_to_uri($search_array);
		if(substr($this->view_data['url'], 0, 1) == '/') $this->view_data['url'] = substr($this->view_data['url'], 1);

		//페이징 - 이부분에서 설정하지 않은 기본 설정값은 appication/config/pagination.php에 설정
		$this->load->library('pagination');
		$page_config['base_url'] = '/board/listing/'.$this->view_data['url'].'/page/';
		$page_config['total_rows'] = $result['record_count'];
		$page_config['per_page'] = $this->board_drv->get_attr('per_page');
		$page_config['page_size'] = $this->board_drv->get_attr('page_size');
		$this->pagination->initialize($page_config);
		$this->view_data['paging'] = $this->pagination->create_links();
		$skin = $this->board_drv->get_attr('board_skin');

		$this->view_data['head_file_name'] = 'manager/board/'.$skin.'/head';
		$this->view_data['main_file_name'] = 'manager/board/'.$skin.'/list';

		if(isset($url_array['excel']) === TRUE && $url_array['excel'] == 'Y')
		{
			ob_start();
			$this->load->view('manager/board/'.$skin.'/excel_list', $this->view_data);
			$data = ob_get_contents();
			ob_end_clean();
			$file_name = "member_". date("YmdHis",time()). ".xls";

			print("<meta http-equiv=\"Content-Type\" content=\"application/vnd.ms-excel; charset=utf-8\">");			
			$this->load->helper('download');
			force_download($file_name, $data);
		}
		else
		{
			//레이아웃 처리(출력)
			$this->load->view($this->view_data['index_file_name'], $this->view_data);
		}
		
		if($config['debug'] != 'N')
		{
			echo '<div>';
			if($config['debug'] == 'T') $this->board_drv->show_debug();
			if($config['debug'] == 'R') $this->board_drv->write_debug();
			echo '</div>';
		}
	}
	/**
	 * @title 게시물 등록 폼
	 * @author - 원종필(won0334@chol.com)
	 * */	
	function write()
	{
		$this->lang->load('board', $this->session->userdata('language'));
		$url_array = $this->uri->uri_to_assoc(4);	
		if(isset($url_array['brd']) === FALSE OR empty($url_array['brd']) === TRUE) alert($this->lang->line('board_no_code'), '', TRUE );

		$this->load->helper(array('form'));
        $this->load->library('form_validation');
        
		$this->form_validation->set_rules('title', 'lang:board_rule_title', 'required');
		$this->form_validation->set_rules('contents', 'lang:board_rule_contents', 'required');
	
		//게시판 정보 가져오기
		$config['debug'] = 'N';
		$config['board_code'] = $url_array['brd'];
		try{
			$this->load->driver('board_drv',  $config);
		}catch(Exception $e)
		{
			echo $this->lang->line($e->getMessage()).$e->getMessage();
			exit;
		}

		if ($this->form_validation->run() === FALSE)
		{ 
			$this->view_data['brd'] = $url_array['brd'];
			$this->view_data['url'] = $this->uri->assoc_to_uri($url_array);
			$this->view_data['next_url'] = '/manager/board/write/'.$this->view_data['url'];
			$this->view_data['mode'] = 'write';
			$this->view_data['board_name'] = $this->board_drv->get_attr('board_name');
			$this->view_data['file_use'] = $this->board_drv->get_attr('etc.board_file_use');//파일 사용 여부
			$this->view_data['file_list'] = $this->board_drv->get_attr('etc.file_list');

			//레이아웃 처리(출력)
			$skin = $this->board_drv->get_attr('board_skin');
			$this->view_data['head_file_name'] = 'manager/board/'.$skin.'/head';
			$this->view_data['main_file_name'] = 'manager/board/'.$skin.'/write';
			$this->load->view($this->view_data['index_file_name'], $this->view_data);
		}
		else
		{
			try
			{
				$result = $this->board_drv->insert($config);
				$file_use = $this->board_drv->get_attr('etc.board_file_use');
				if($file_use == 'Y')
				{
					$this->load->model('file_model');
					$file_config['file_list'] = $this->board_drv->get_attr('etc.file_list');
					$file_config['board_code'] = $url_array['brd'];
					$file_config['insert_idx'] = $result['idx'];

					$this->file_model->file_upload($file_config);
				}

				$is_ajax = $this->input->is_ajax_request();
		
				if($is_ajax === TRUE)
				{
					if($result['result'] === TRUE) echo json_encode(array('result'=>TRUE, 'data'=>$result['result']));
					else  echo json_encode(array('result'=>FALSE, 'data'=>'', 'msg'=>$this->lang->line('board_reg_fail')));
				}
				else
				{		
					if($result['result'] === TRUE)
					{
						alert($this->lang->line('board_reg_complete'), '/manager/board/listing/'.$url, TRUE);
					}
					else
					{
						alert($this->lang->line('board_reg_fail'), '', TRUE);
					}
				}
			}
			catch(Exception $e)
			{
				alert($this->lang->line('board_reg_fail'), '', TRUE);
			}
		}
	}
	/**
	 * @title 게시물 수정폼 출력
	 * @author - 원종필(won0334@chol.com)
	 * */	
	function update()
	{
		$this->lang->load('board', $this->session->userdata('language'));
		$url_array = $this->uri->uri_to_assoc(4);	
		if(isset($url_array['brd']) === FALSE OR empty($url_array['brd']) === TRUE) alert($this->lang->line('board_no_code'), '', TRUE );
		if( isset($url_array['idx']) === FALSE OR empty($url_array['idx']) === TRUE) alert($this->lang->line('board_no_idx'), '', TRUE );

		$this->load->helper(array('form'));
        $this->load->library('form_validation');
        
		$this->form_validation->set_rules('title', 'lang:board_rule_title', 'required');
		$this->form_validation->set_rules('contents', 'lang:board_rule_contents', 'required');
	
		//게시판 정보 가져오기
		$config['debug'] = 'N';
		$config['board_code'] = $url_array['brd'];
		$this->load->driver('board_drv',  $config);

		if ($this->form_validation->run() === FALSE)
		{ 		
			$result = $this->board_drv->get_data($url_array['idx']);
			if( $result === FALSE ) alert('게시판 정보를 가져오는데 실패하였습니다.', '', TRUE);
	
			$this->view_data['result'] = $result;
			$this->view_data['board_name'] = $this->board_drv->get_attr('board_name');
			$this->view_data['brd'] = $url_array['brd'];
			$this->view_data['mode'] = 'update';
			if(isset($url_array['type']) === TRUE) $this->view_data['type'] = $url_array['type'];
	
			$idx = $url_array['idx'];
			unset($url_array['idx']);
			$this->view_data['url'] = $this->uri->assoc_to_uri($url_array);
			$this->view_data['search_string'] = '/manager/board/listing/'.$this->view_data['url'];
			$this->view_data['next_url'] = '/manager/board/update/'.$this->view_data['url'];
			$skin = $this->board_drv->get_attr('board_skin');
			$this->view_data['file_use'] = $this->board_drv->get_attr('etc.board_file_use');//파일 사용 여부

			//레이아웃 처리(출력)
			$this->view_data['head_file_name'] = 'manager/board/'.$skin.'/head';
			$this->view_data['main_file_name'] = 'manager/board/'.$skin.'/write';
			$this->load->view($this->view_data['index_file_name'], $this->view_data);
	
			if($config['debug'] == 'Y')
			{
				print_rr($this->view_data, FALSE, 'controll view data');
				$this->board_drv->write_error(TRUE, 'debug');
			}	
		}
		else
		{
			$result = $this->board_drv->update($idx, $config);
			$error_str = '';
			foreach($this->board_drv->get_attr('etc.file_list') as $key=>$list)
			{
				$file_error = $this->board_drv->get_error($key.'_error');
				if(isset($file_error) === TRUE && $file_error !== FALSE)
				{
					$error_str .= $file_error.'\n';
				}
			}
			
			$url = $this->uri->assoc_to_uri($url_array);
			$is_ajax = $this->input->is_ajax_request();
	
			if($is_ajax === TRUE)
			{
				if($result['result'] === TRUE) echo json_encode(array('result'=>TRUE, 'data'=>$result['result']));
				else  echo json_encode(array('result'=>FALSE, 'data'=>'', 'msg'=>'등록이 실패 하였습니다.'));
			}
			else
			{		
				if($result['result']  === TRUE)
				{
					alert($error_str.'수정 되었습니다.', '/manager/board/listing/'.$url, TRUE);
				}
				else
				{
					alert($result['error'], '', TRUE);
				}
			}
		}
	}
	/**
	 * @title 게시물 삭제
	 * @author - 원종필(won0334@chol.com)
	 * */	
	function del()
	{
		$url_array = $this->uri->uri_to_assoc(4);
		if(isset($url_array['brd']) === FALSE OR empty($url_array['brd']) === TRUE) alert($this->lang->line('board_no_code'), '', TRUE );
		if( isset($url_array['idx']) === FALSE OR empty($url_array['idx']) === TRUE ) alert($this->lang->line('board_no_idx'), '', TRUE );
		
		//게시판 정보 가져오기
		$config['debug'] = 'N';
		$config['board_code'] = $url_array['brd'];
		$config['idx'] = $url_array['idx'];
		
		$this->load->driver('board_drv', $config);
		$result = $this->board_drv->delete($url_array['idx'], $config);
		$is_ajax = $this->input->is_ajax_request();
		
		if($is_ajax === TRUE)
		{
			if($result['result'] === TRUE) echo json_encode(array('result'=>TRUE, 'data'=>$result['result']));
			else  echo json_encode(array('result'=>FALSE, 'data'=>'', 'msg'=>'삭제에 실패하였습니다.'));
		}
		else
		{		
			if( $result['result'] === FALSE ) alert('삭제에 실패하였습니다.', '', TRUE);
			unset($url_array['idx']);
			$url = $this->uri->assoc_to_uri($url_array);
			alert("삭제 되었습니다. ", '/manager/board/listing/'.$url);			
		}
	}	
	/**
	 * @title 게시물 삭제 - 다중 선택 삭제
	 * @author - 원종필(won0334@chol.com)
	 * */
	function del_array()
	{
		$url_array = $this->uri->uri_to_assoc(4);
		if(isset($url_array['brd']) === FALSE OR empty($url_array['brd']) === TRUE) alert($this->lang->line('board_no_code'), '', TRUE );
	
		//게시판 정보 가져오기
		$config['debug'] = 'N';
		$config['board_code'] = $url_array['brd'];
	
		$this->load->driver('board_drv', $config);
		$result = $this->board_drv->delete($this->input->post('del_list'), $config);
		$is_ajax = $this->input->is_ajax_request();
	
		if($config['debug'] == 'Y')
		{
			print_rr($this->input->post('del_list'), FALSE, 'controll del post_data');
			print_rr($url_array, FALSE, 'controll del url_array');
			print_rr($this->view_data, FALSE, 'controll del view data');
			$this->board_drv->write_error(FALSE, 'debug');
		}
				
		if($is_ajax === TRUE)
		{
			if($result['result'] === TRUE) echo json_encode(array('result'=>TRUE, 'data'=>$result['result']));
			else  echo json_encode(array('result'=>FALSE, 'data'=>'', 'msg'=>'삭제에 실패하였습니다.'));
		}
		else
		{
			if( $result['result'] === FALSE ) alert('삭제에 실패하였습니다.', '', TRUE);
			unset($url_array['idx']);
			$url = $this->uri->assoc_to_uri($url_array);
			alert("삭제 되었습니다. ", '/manager/board/listing/'.$url);
		}
	}	
	/**
	 * @title 첨부 파일 다운로드
	 * @author - 원종필(won0334@chol.com)
	 * */	
	function file_down()
	{
		$url_array = $this->uri->uri_to_assoc(4);
		if(isset($url_array['brd']) === FALSE OR empty($url_array['brd']) === TRUE) alert($this->lang->line('board_no_code'), '', TRUE );
		if( isset($url_array['idx']) === FALSE OR empty($url_array['idx']) === TRUE ) alert($this->lang->line('board_no_idx'), '', TRUE );
		
		//게시판 정보 가져오기
		$config['debug'] = 'N';
		$config['board_code'] = $url_array['brd'];
		$config['idx'] = $url_array['idx'];
				
		$this->load->driver('board_drv',  $config);
		$result = $this->board_drv->get_data($url_array['idx']);
		if( $result === FALSE ) alert('게시판 정보를 가져오는데 실패하였습니다.', '', TRUE);
		
		$upload_path = $this->board_drv->get_attr('etc.upload_path');
		$file_name = $upload_path.$result['file']['file_name'];
		$convert_file_name = iconv("UTF-8", "EUC-KR", $result['file']['org_name']);
		
		if(file_exists($file_name) === TRUE)
		{
			$data = file_get_contents($file_name);
			
			$this->load->helper('download');
			force_download($convert_file_name, $data);
		}
		else
		{
			alert('파일이 존재하지 않습니다.', '', TRUE);
		}
	}
}
/* End of file admin.php */