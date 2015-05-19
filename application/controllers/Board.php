<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 /**
 * @title 게시판
 * @author 원종필(won0334@chol.com)
 */
class Board extends CI_Controller 
{
	var $view_data = array();//뷰에 전달할 값 저장 배열
	
	function __construct()
	{
		parent::__construct();
		$this->output->enable_profiler(TRUE);
		$this->lang->load('board', 'korean');
		
	}
	/**
	 * @title 게시판 리스트
	 * @author 원종필(won0334@chol.com)
	 */ 
	public function listing()
	{
		$url_array = $this->uri->uri_to_assoc(3);
		if(isset($url_array['brd']) === FALSE OR empty($url_array['brd']) === TRUE) alert($this->lang->line('board_no_code'), '', TRUE );
		//검색 파라메터 처리
		$search_key_array = array(
			'search_key', 'search_val', 'brd', 'status', 'date_status', 'sel_type', 'faq_cate', 'idx', 'comment_board_code', 'board_idx', 
			'sel_cate', 'theme', 'shop_name', 'sido', 'gugun'
		);
		$search_array = convert_search($search_key_array, $url_array);//검색 항목을 포스트와 segment를 검색하여 존재하는 정보를 리턴
		//검색 항목중 검색키값은 segment로 전달시 한글값을 인코딩되므로 view출력시 디코딩 처리
		if(isset($search_array['search_val']) === TRUE && empty($search_array['search_val']) === FALSE)  $search_array['search_val'] = urldecode($search_array['search_val']);
		$this->view_data = $config =  array_merge($this->view_data, $search_array);//검색 항목을 view로 전달		
		//게시판 driver 설정값
		$config['debug'] = 'N';
		$config['vnum_use'] = 'Y';
		$config['board_code'] = $url_array['brd'];
		$config['page'] = (isset($url_array['page']) === TRUE && empty($url_array['page']) === FALSE)?$url_array['page']:1;		
		$this->load->driver('board_drv',  $config);

		if($this->board_drv->error_flag === TRUE)
		{
			alert($this->lang->line('board_no_code'));
		}

		//게시판별 디비 설정값
		$result = $this->board_drv->get_list($config);
		//게시판 view 노출 데이터
		$this->view_data['result'] = $result;
		$this->view_data['board_name'] = $this->board_drv->get_attr('board_name');
		$this->view_data['record_count'] = $result['record_count'];
		//페이지 링크
		if(isset($search_array['search_val']) === TRUE && empty($search_array['search_val']) === FALSE)  $search_array['search_val'] = urlencode($search_array['search_val']);//한글 검색 문제
		$this->view_data['url'] = $this->uri->assoc_to_uri($search_array);
		//페이징 - 이부분에서 설정하지 않은 기본 설정값은 appication/config/pagination.php에 설정
		$this->load->library('pagination');
		$page_config['base_url'] = '/board/listing/'.$this->view_data['url'].'/page/';
		$page_config['total_rows'] = $result['record_count'];
		$page_config['per_page'] = $this->board_drv->get_attr('per_page');
		$page_config['page_size'] = $this->board_drv->get_attr('page_size');
		$this->pagination->initialize($page_config);
		$this->view_data['paging'] = $this->pagination->create_links();
		//view 설정값 처리
		$skin = $this->board_drv->get_attr('board_skin');
		$this->view_data['head_file_name'] = 'board/'.$skin.'/head';
		$this->view_data['main_file_name'] = 'board/'.$skin.'/list';
		$this->view_data['index_file_name'] = 'index/index';
		$this->view_data['default_head_file_name'] = 'index/default_head';
		//view 처리(출력)
		$this->load->library('user_agent');
		$is_ajax = $this->input->is_ajax_request();
		if($is_ajax === TRUE)
		{
			$this->load->view('board/'.$skin.'/list', $this->view_data);
		}
		else
		{
			$this->load->view($this->view_data['index_file_name'], $this->view_data);
		}
		
		if($config['debug'] == 'Y')
		{ 
			print_rr($this->view_data);
			$this->board_drv->write_error(TRUE, 'debug');
		}
	}
	/**
	 * @title 조회수 증가
	 * @author 원종필(won0334@chol.com)
	 */ 	 
	public function hit()
	{
		$url_array = $this->uri->uri_to_assoc(3);
		if( isset($url_array['brd']) === FALSE OR empty($url_array['brd']) === TRUE ) alert('게시판 코드가 전달되지 않았습니다.', '', TRUE );
		if( isset($url_array['idx']) === FALSE OR empty($url_array['idx']) === TRUE) alert('게시판 일련번호는 필수입니다.', '', TRUE );
	
		//게시판 정보 가져오기
		$config['debug'] = 'N';
		$config['board_code'] = $url_array['brd'];
		
		$this->load->driver('board_drv',  $config);
		if($url_array['brd'] == 'store') $this->board_drv->set_db_instance('erp');
		$result = $this->board_drv->hit($url_array['idx']);
		if( $result['result'] === FALSE ) alert('게시판 정보를 가져오는데 실패하였습니다.', '', TRUE);
		
		$data = array(
				'user_idx'=>$this->session->userdata('idx'),
				'contents_idx'=>$url_array['idx'],
				'reg_date'=>date('Y-m-d H:i:s')
		);
		$result = $this->db->insert('view_log', $data);		

		$this->load->helper('url');
		redirect('/board/view/'.$this->uri->assoc_to_uri($url_array));
	}	
	/**
	 * @title 게시물 보기
	 * @author 원종필(won0334@chol.com)
	 */ 	 
	public function view()
	{
		$url_array = $this->uri->uri_to_assoc(3);
		if( isset($url_array['brd']) === FALSE OR empty($url_array['brd']) === TRUE ) alert('게시판 코드가 전달되지 않았습니다.', '', TRUE );
		if( isset($url_array['idx']) === FALSE OR empty($url_array['idx']) === TRUE) alert('게시판 일련번호는 필수입니다.', '', TRUE );		

		//게시판 정보 가져오기
		$config['debug'] = 'N';
		$config['board_code'] = $url_array['brd'];
		
		$this->load->driver('board_drv',  $config);
		if($url_array['brd'] == 'store') $this->board_drv->set_db_instance('erp');
		$result = $this->board_drv->get_data($url_array['idx']);
		if( $result === FALSE ) alert('게시판 정보를 가져오는데 실패하였습니다.', '', TRUE);
		
		$this->view_data['result'] = $result;

		$this->view_data['board_name'] = $this->board_drv->get_attr('board_name');
		$this->view_data['brd'] = $url_array['brd'];
		$this->view_data['idx'] = $url_array['idx'];
		if(isset($url_array['sel_type']) === TRUE) $this->view_data['sel_type'] = $url_array['sel_type'];

		//레이아웃 처리(출력)
		$skin = $this->board_drv->get_attr('board_skin');
		if(MOBILE_USE == 'Y') $base_skin = 'mobile/'; else $base_skin = 'web/';
		if(defined('LANGUAGE') === TRUE) $base_skin .=  LANGUAGE.'/';

		$this->view_data['head_file_name'] = 'board/'.$skin.'/head';
		$this->view_data['subviisual_file_name'] = 'common/sub';
		$this->view_data['main_file_name'] = 'board/'.$skin.'/view';
		$this->view_data['index_file_name'] = 'index/index';
		$this->view_data['default_head_file_name'] = 'index/default_head';
		if( ($file_use = $this->board_drv->get_attr('etc.board_file_use')) === 'Y') $this->view_data['file_use'] = $file_use;//파일 사용 여부
				
		switch($url_array['brd'])	
		{
			case 'event':
				$this->view_data['page_name'] = 'event';
				$this->view_data['subviisual_file_name'] = 'common/event_sub';
				$this->view_data['left_file_name'] = 'common/event_left';

				if($result['result']['event_division'] == 'CR')
				{
					$this->view_data['head_file_name'] = 'board/'.$skin.'/cr_head';
					$this->view_data['main_file_name'] = 'board/'.$skin.'/cr_form';
				}
			break;		

			case 'store':
				$this->view_data['page_name'] = 'store';
				$this->view_data['subviisual_file_name'] = 'common/store_sub';
				$this->view_data['left_file_name'] = 'common/store_left';
				
				$img_location = end(explode('\\', $result['result']['IMAGE_LOCATION']));
				if(isset($result['result']['FRONT_IMAGE']) === TRUE) $this->view_data['img_url1'] = '/updata/store/'.$img_location.'/'.strtolower($result['result']['FRONT_IMAGE']);
				if(isset($result['result']['FRONT_IMAGE2']) === TRUE)  $this->view_data['img_url2'] = '/updata/store/'.$img_location.'/'.strtolower($result['result']['FRONT_IMAGE2']);
				if(isset($result['result']['FRONT_IMAGE3']) === TRUE)  $this->view_data['img_url3'] = '/updata/store/'.$img_location.'/'.strtolower($result['result']['FRONT_IMAGE3']);				
				if(isset($result['result']['INNER_IMAGE1']) === TRUE)  $this->view_data['img_url4'] = '/updata/store/'.$img_location.'/'.strtolower($result['result']['INNER_IMAGE1']);				
				if(isset($result['result']['FRONT_IMAGE5']) === TRUE)  $this->view_data['img_url5'] = '/updata/store/'.$img_location.'/'.strtolower($result['result']['FRONT_IMAGE5']);				

			break;			
		}

		unset($url_array['idx']);
		$this->view_data['url'] = $this->uri->assoc_to_uri($url_array);
		
		$is_ajax = $this->input->is_ajax_request();
		if($is_ajax === TRUE)
		{
			$this->load->view($this->view_data['main_file_name'], $this->view_data);
		}
		else
		{
			$this->load->view($this->view_data['index_file_name'], $this->view_data);
		}

		if($config['debug'] == 'Y')
		{ 
			print_rr($this->view_data);
			$this->board_drv->write_error(TRUE, 'debug');
		}
	}
	/**
	 * @title 게시물 저장
	 * @author 원종필(won0334@chol.com)
	 */ 	 
	function save()
	{
		$url_array = $this->uri->uri_to_assoc(3);	
		if( isset($url_array['brd']) === FALSE OR empty($url_array['brd']) === TRUE ) alert('게시판 코드가 전달되지 않았습니다.', '', TRUE );

		//게시판 정보 가져오기
		$config['debug'] = 'N';
		$config['board_code'] = $url_array['brd'];
		
		$this->load->driver('board_drv',  $config);
		$result = $this->board_drv->insert($config);
		
		$url = $this->uri->assoc_to_uri($url_array);
		$is_ajax = $this->input->is_ajax_request();

		if($is_ajax === TRUE)
		{
			echo json_encode(array('result'=>true, 'data'=>$result['result'], 'msg'=>'등록되었습니다.'));
		}
		else
		{		
			if($result['result'] === TRUE)
			{
				alert('등록되었습니다.', '/board/listing/'.$url, TRUE);
			}
			else
			{
				alert('등록이 실패하였습니다.', '', TRUE);
			}
		}
	}
	/**
	 * @title 수정폼 출력
	 * @author 원종필(won0334@chol.com)
	 */	 
	function update_form()
	{
		$url_array = $this->uri->uri_to_assoc(3);

		//게시판 정보 가져오기
		$config['debug'] = 'N';
		$config['board_code'] = 'comment';
	
		$this->load->driver('board_drv',  $config);
		$result = $this->board_drv->get_data($url_array['idx']);
		if( $result === FALSE ) alert('게시판 정보를 가져오는데 실패하였습니다.', '', TRUE);
		$this->view_data['result'] = $result;
		//레이아웃 처리(출력)
		$skin = $this->board_drv->get_attr('board_skin');
		if(MOBILE_USE == 'Y') $base_skin = 'mobile/'; else $base_skin = 'web/';
		if(defined('LANGUAGE') === TRUE) $base_skin .=  LANGUAGE.'/';
		$this->load->view('board/'.$skin.'/update_form', $this->view_data);
	
		if($config['debug'] == 'Y')
		{
			print_rr($this->view_data, FALSE, 'controll update data');
			$this->board_drv->write_error(TRUE, 'debug');
		}
	}	
	/**
	 * @title 게시물 수정 저장 - comment 수정 전용
	 * @author - 원종필(won0334@chol.com)
	 * */
	function update_save()
	{
		$url_array = $this->uri->uri_to_assoc(3);
		if( ($idx = $this->input->post('idx')) === FALSE) 
		{
			echo json_encode(array('result'=>FALSE,  'msg'=>'게시판 일련번호는 필수입니다.'));
			exit;
		}
	
		//게시판 정보 가져오기
		$config['debug'] = 'N';
		$config['board_code'] = 'comment';
	
		$this->load->driver('board_drv',  $config);
		$result = $this->board_drv->update($idx, $config);
	
		if($result['result'] === TRUE) echo json_encode(array('result'=>TRUE, 'data'=>$result['result'], 'msg'=>'수정되었습니다.'));
		else  echo json_encode(array('result'=>FALSE, 'data'=>'', 'msg'=>'수정이 실패 하였습니다.'));
	}	
	/**
	 * @title 게시물 삭제 - comment 삭제 전용
	 * @author - 원종필(won0334@chol.com)
	 * */
	function del()
	{
		$url_array = $this->uri->uri_to_assoc(3);
		if( isset($url_array['idx']) === FALSE OR empty($url_array['idx']) === TRUE )
		{
			echo json_encode(array('result'=>FALSE, 'msg'=>'일련번호가 전달되지 않았습니다.'));
			exit;
		}
	
		//게시판 정보 가져오기
		$config['debug'] = 'N';
		$config['board_code'] = 'comment';
		$config['idx'] = $url_array['idx'];
	
		$this->load->driver('board_drv', $config);
		$result = $this->board_drv->delete($url_array['idx'], $config);
		$is_ajax = $this->input->is_ajax_request();
	
		if($is_ajax === TRUE)
		{
			if($result['result'] === TRUE) echo json_encode(array('result'=>TRUE, 'data'=>$result['result'], 'msg'=>'삭제 되었습니다.'));
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
		$url_array = $this->uri->uri_to_assoc(3);
		if( isset($url_array['brd']) === FALSE OR empty($url_array['brd']) === TRUE ) alert('게시판 코드가 전달되지 않았습니다.', '', TRUE );
		if( isset($url_array['idx']) === FALSE OR empty($url_array['idx']) === TRUE) alert('게시판 일련번호는 필수입니다.', '', TRUE );		

		//게시판 정보 가져오기
		$config['debug'] = 'N';
		$config['board_code'] = $url_array['brd'];
		
		$this->load->driver('board_drv',  $config);
		$result = $this->board_drv->get_data($url_array['idx']);
		if( $result === FALSE ) alert('게시판 정보를 가져오는데 실패하였습니다.', '', TRUE);

		$file_name = $this->board_drv->get_attr('etc.file_list.pdf_file.upload_path').$result['file_info']['pdf_file']['file_name'];

		$convert_file_name = iconv("UTF-8", "EUC-KR", $result['file_info']['pdf_file']['org_name']);
		
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
	/**
	 * @title 고객의 소리 구분 선택 폼
	 * @author - 원종필(won0334@chol.com)
	 * */
	function testimonials_sel()
	{
		//레이아웃 처리(출력)
		if(MOBILE_USE == 'Y') $base_skin = 'mobile/'; else $base_skin = 'web/';
		if(defined('LANGUAGE') === TRUE) $base_skin .=  LANGUAGE.'/';
		$this->view_data['subviisual_file_name'] = 'common/customer_sub';
		$this->view_data['head_file_name'] = 'index/head';
		$this->view_data['left_file_name'] = 'common/customer_left';
		$this->view_data['main_file_name'] = 'page/testimonials_sel';
		$this->view_data['index_file_name'] = 'index/index';
		$this->view_data['default_head_file_name'] = 'index/default_head';		
		$this->view_data['page_name'] = 'testimonials_sel';
		$this->load->view($this->view_data['index_file_name'], $this->view_data);
	}	
	/**
	 * @title 고객의 소리 인증 선택화면
	 * @author - 원종필(won0334@chol.com)
	 * */
	function testimonials_check()
	{
		$enc_data	= get_sms_code($this->config->item('base_url').'board/check_sms', $this->config->item('base_url').'board/check_sms_fail', MOBILE_USE == 'Y'?'Mobile':'');
		
		if( $enc_data == -1 )
		{
			$returnMsg = "암/복호화 시스템 오류입니다.";
			script_exec('alert("'.$returnMsg.'");');
			$enc_data = "";
		}
		else if( $enc_data== -2 )
		{
			$returnMsg = "암호화 처리 오류입니다.";
			script_exec('alert("'.$returnMsg.'");');
			$enc_data = "";
		}
		else if( $enc_data== -3 )
		{
			$returnMsg = "암호화 데이터 오류 입니다.";
			script_exec('alert("'.$returnMsg.'");');
			$enc_data = "";
		}
		else if( $enc_data== -9 )
		{
			$returnMsg = "입력값 오류 입니다.";
			script_exec('alert("'.$returnMsg.'");');
			$enc_data = "";
		}
		
		$this->view_data['enc_data'] = $enc_data;
		
		$sEncData	= get_ipin_code($this->config->item('base_url').'board/check_ipin');
		// 리턴 결과값에 따른 처리사항
		if ($sEncData == -9)
		{
			$sRtnMsg = "IPIN사용을 위한 암호화 처리에 문제가 있습니다. 관리자에게 문의 하세요.";
			script_exec('alert(\"'.$sRtnMsg.'\");');
		}
		else
		{
			$this->view_data['sEncData'] = $sEncData;
		}
		//레이아웃 처리(출력)
		if(MOBILE_USE == 'Y') $base_skin = 'mobile/'; else $base_skin = 'web/';
		if(defined('LANGUAGE') === TRUE) $base_skin .=  LANGUAGE.'/';
		$this->view_data['subviisual_file_name'] = 'common/customer_sub';
		$this->view_data['left_file_name'] = 'common/customer_left';
		$this->view_data['main_file_name'] = 'page/testimonials_check';
		$this->view_data['index_file_name'] = 'index/index';
		$this->view_data['default_head_file_name'] = 'index/default_head';
		$this->view_data['page_name'] = 'testimonials_check';
		$this->load->view($this->view_data['index_file_name'], $this->view_data);
	}
	/**
	 * @title sms 결과 리턴 확인 - 성공시
	 * @author - 원종필(won0334@chol.com)
	 * */
	function check_sms()
	{
		$error_msg = '';
		$sitecode = $this->config->item('sms_site_code');					// NICE로부터 부여받은 사이트 코드
		$sitepasswd = $this->config->item('sms_site_pass');				// NICE로부터 부여받은 사이트 패스워드
		$cb_encode_path = $this->config->item('sms_module_pass');		// NICE로부터 받은 암호화 프로그램의 위치 (절대경로+모듈명)
	
		$enc_data = $this->input->post('EncodeData');		// 암호화된 결과 데이타
		//////////////////////////////////////////////// 문자열 점검///////////////////////////////////////////////
		if(preg_match('~[^0-9a-zA-Z+/=]~', $enc_data, $match)) {$error_msg = '입력 값 확인이 필요합니다 : '.$match[0]; } // 문자열 점검 추가.
		if(base64_encode(base64_decode($enc_data))!=$enc_data) {$error_msg = '입력 값 확인이 필요합니다'; }
	
		if ($enc_data != '' && $error_msg == '')
		{
			$plaindata = `$cb_encode_path DEC $sitecode $sitepasswd $enc_data`;		// 암호화된 결과 데이터의 복호화
	
			if ($plaindata == -1){
				$error_msg  = '암/복호화 시스템 오류';
			}else if ($plaindata == -4){
				$error_msg  = '복호화 처리 오류';
			}else if ($plaindata == -5){
				$error_msg  = 'HASH값 불일치 - 복호화 데이터는 리턴됨';
			}else if ($plaindata == -6){
				$error_msg  = '복호화 데이터 오류';
			}else if ($plaindata == -9){
				$error_msg  = '입력값 오류';
			}else if ($plaindata == -12){
				$error_msg  = '사이트 비밀번호 오류';
			}else{
				// 복호화가 정상적일 경우 데이터를 파싱합니다.
				$requestnumber = GetValue($plaindata , "REQ_SEQ");
				$responsenumber = GetValue($plaindata , "RES_SEQ");
				$authtype = GetValue($plaindata , "AUTH_TYPE");
				$name = GetValue($plaindata , "NAME");
				$birthdate = GetValue($plaindata , "BIRTHDATE");
				$gender = GetValue($plaindata , "GENDER");
				$dupinfo = GetValue($plaindata , "DI");
				$mobileinfo = GetValue($plaindata , "MOBILE_NO");
				 
				$this->session->set_flashdata('enc_code', $dupinfo);
				$this->session->set_flashdata('check_name', iconv('euc-kr', 'utf-8', $name));
				$this->session->set_userdata('mobile_info', $mobileinfo);
	
				if(strcmp($this->session->userdata('REQ_SEQ'), $requestnumber) != 0)
				{
					$error_msg = "세션값이 다릅니다. 올바른 경로로 접근하시기 바랍니다.<br>";
					$requestnumber = "";
					$responsenumber = "";
					$authtype = "";
					$name = "";
					$birthdate = "";
					$gender = "";
					$dupinfo = "";
				}
			}
		}
		 
		$this->view_data['error_msg'] = $error_msg;
		//레이아웃 처리(출력)
		if(MOBILE_USE == 'Y') $base_skin = 'mobile/'; else $base_skin = 'web/';
		if(defined('LANGUAGE') === TRUE) $base_skin .=  LANGUAGE.'/';
		$this->load->view('page/check_sms', $this->view_data);
	}
	/**
	 * @title sms 결과 리턴 확인 - 실패시
	 * @author - 원종필(won0334@chol.com)
	 * */
	function check_sms_fail()
	{
		$error_msg = 'sms 인증이 실패하였습니다.';
		$sitecode = $this->config->item('sms_site_code');					// NICE로부터 부여받은 사이트 코드
		$sitepasswd = $this->config->item('sms_site_pass');				// NICE로부터 부여받은 사이트 패스워드
		$cb_encode_path = $this->config->item('sms_module_pass');		// NICE로부터 받은 암호화 프로그램의 위치 (절대경로+모듈명)
	
		$enc_data = $this->input->post('EncodeData');		// 암호화된 결과 데이타
		//////////////////////////////////////////////// 문자열 점검///////////////////////////////////////////////
		if(preg_match('~[^0-9a-zA-Z+/=]~', $enc_data, $match)) {$error_msg = '입력 값 확인이 필요합니다 : '.$match[0]; } // 문자열 점검 추가.
		if(base64_encode(base64_decode($enc_data))!=$enc_data) {$error_msg = '입력 값 확인이 필요합니다'; }
	
		if ($enc_data != "")
		{
			$plaindata = `$cb_encode_path DEC $sitecode $sitepasswd $enc_data`;		// 암호화된 결과 데이터의 복호화
	
			if ($plaindata == -1){
				$error_msg  = '암/복호화 시스템 오류';
			}else if ($plaindata == -4){
				$error_msg  = '복호화 처리 오류';
			}else if ($plaindata == -5){
				$error_msg  = 'HASH값 불일치 - 복호화 데이터는 리턴됨';
			}else if ($plaindata == -6){
				$error_msg  = '복호화 데이터 오류';
			}else if ($plaindata == -9){
				$error_msg  = '입력값 오류';
			}else if ($plaindata == -12){
				$error_msg  = '사이트 비밀번호 오류';
			}else{
				$requestnumber = GetValue($plaindata , "REQ_SEQ");
				$errcode = GetValue($plaindata , "ERR_CODE");
				$authtype = GetValue($plaindata , "AUTH_TYPE");
			}
		}
		 
		$this->view_data['error_msg'] = $error_msg.'['.$errcode.']';
		//레이아웃 처리(출력)
		if(MOBILE_USE == 'Y') $base_skin = 'mobile/'; else $base_skin = 'web/';
		if(defined('LANGUAGE') === TRUE) $base_skin .=  LANGUAGE.'/';
		$this->load->view('page/check_sms', $this->view_data);
	}	
	/**
	 * @title 아이핀 결과 리턴 확인
	 * @author - 원종필(won0334@chol.com)
	 * */
	function check_ipin()
	{
		$sSiteCode					= $this->config->item('site_code');			// IPIN 서비스 사이트 코드		(NICE평가정보에서 발급한 사이트코드)
		$sSitePw					= $this->config->item('site_pass');			// IPIN 서비스 사이트 패스워드	(NICE평가정보에서 발급한 사이트패스워드)
		$sModulePath				= $this->config->item('module_pass');			// 하단내용 참조
		
		$sEncData					= "";			// 암호화 된 사용자 인증 정보
		$sDecData					= "";			// 복호화 된 사용자 인증 정보
		$sRtnMsg					= "";			// 처리결과 메세지
		
		$sEncData = $this->input->post('enc_data');	// ipin_process.php 에서 리턴받은 암호화 된 사용자 인증 정보
		// 데이타 위변조 방지를 위해 확인하기 위함이므로, 필수사항은 아니며 보안을 위한 권고사항입니다.
		$sCPRequest = $this->session->userdata('CPREQUEST');
		
		if ($sEncData != "") {
			// 사용자 정보를 복호화 합니다.
			$sDecData = `$sModulePath RES $sSiteCode $sSitePw $sEncData`;
		
			if ($sDecData == -9) {
				$sRtnMsg = "입력값 오류 : 복호화 처리시, 필요한 파라미터값의 정보를 정확하게 입력해 주시기 바랍니다.";
			} else if ($sDecData == -12) {
				$sRtnMsg = "NICE평가정보에서 발급한 개발정보가 정확한지 확인해 보세요.";
			} else {
				// 복호화된 데이타 구분자는 ^ 이며, 구분자로 데이타를 파싱합니다.
				/*
				 - 복호화된 데이타 구성
				가상주민번호확인처리결과코드^가상주민번호^성명^중복확인값(DupInfo)^연령정보^성별정보^생년월일(YYYYMMDD)^내외국인정보^고객사 요청 Sequence
				*/
				$arrData = explode("^", $sDecData);
				$iCount = count($arrData);
		
				if ($iCount >= 5) {
					$strResultCode	= $arrData[0];			// 결과코드
					if ($strResultCode == 1) {
						$strCPRequest	= $arrData[8];			// CP 요청번호
							
						if ($sCPRequest == $strCPRequest) {
							$strUserName		= $arrData[2];	// 이름
							$strDupInfo = $arrData[3];// 중복가입 확인값 (64Byte 고유값)
							
							$redirect_url = '/board/testimonials_form';
							$this->session->set_flashdata('enc_code', $strDupInfo);
							$this->session->set_flashdata('check_name', iconv('euc-kr', 'utf-8', $strUserName));
						} else {
							$sRtnMsg = "CP 요청번호 불일치 : 세션에 넣은 $sCPRequest 데이타를 확인해 주시기 바랍니다.";
						}
					} else {
						$sRtnMsg = "리턴값 확인 후, NICE평가정보 개발 담당자에게 문의해 주세요. [$strResultCode]";
					}
		
				} else {
					$sRtnMsg = "리턴값 확인 후, NICE평가정보 개발 담당자에게 문의해 주세요.";
				}
			}
		} else {
			$sRtnMsg = "처리할 암호화 데이타가 없습니다.";
		}
		
		$this->view_data['sRtnMsg'] = $sRtnMsg;
		$this->view_data['redirect_url'] = $redirect_url;
				
		//레이아웃 처리(출력)
		if(MOBILE_USE == 'Y') $base_skin = 'mobile/'; else $base_skin = 'web/';
		if(defined('LANGUAGE') === TRUE) $base_skin .=  LANGUAGE.'/';
		$this->load->view('page/check_ipin', $this->view_data);
	}
	/**
	 * @title 고객의 소리 폼
	 * @author - 원종필(won0334@chol.com)
	 * */
	function testimonials_form()
	{
		$enc_code = $this->session->flashdata('enc_code');
		if($enc_code === FALSE OR empty($enc_code) === TRUE) alert('암호화 정보가 전달되지 않았습니다.', '/board/testimonials_check' ,TRUE);

		$this->view_data['check_name'] = $this->session->flashdata('check_name');
		$this->session->set_flashdata('check_name', $this->view_data['check_name']);

		$this->set_db_instance();
		$this->db->from('V_MA_CODEDTL_SA_F000019');
		$this->view_data['sido'] = $this->db->get()->result_array();
		
		$mobile_info = $this->session->userdata('mobile_info');
		if($mobile_info !== FALSE)
		{
			$this->view_data['session_tel1'] = substr($mobile_info, 0, 3);
			$this->view_data['session_tel2'] = substr($mobile_info, 3, 4);
			$this->view_data['session_tel3'] = substr($mobile_info, 7, 4);
		}
		else
		{
			$this->view_data['session_tel1'] = '';
			$this->view_data['session_tel2'] = '';
			$this->view_data['session_tel3'] = '';
		}
				
		//레이아웃 처리(출력)
		if(MOBILE_USE == 'Y') $base_skin = 'mobile/'; else $base_skin = 'web/';
		if(defined('LANGUAGE') === TRUE) $base_skin .=  LANGUAGE.'/';
		$this->view_data['subviisual_file_name'] = 'common/customer_sub';
		$this->view_data['head_file_name'] = 'page/testimonials_form_head';
		$this->view_data['left_file_name'] = 'common/customer_left';
		$this->view_data['main_file_name'] = 'page/testimonials_form';
		$this->view_data['index_file_name'] = 'index/index';
		$this->view_data['default_head_file_name'] = 'index/default_head';
		$this->view_data['page_name'] = 'testimonials_form';
		$this->load->view($this->view_data['index_file_name'], $this->view_data);
	}	
	/**
	 * @title 고객의 소리 폼에서 시도 선택시 하위 구/군 정보 가져오기 - ajax 전용
	 * @author - 원종필(won0334@chol.com)
	 * */	
	function gugun()
	{
		$this->set_db_instance();
		$this->db->from('V_MA_CODEDTL_SA_F000020');
		$this->db->where('left(CODE, 2) = ', substr($this->input->post('sido'), 0, 2), FALSE);
		$result = $this->db->get()->result_array();
		
		foreach($result as $key=>$list)
		{
			$result[$key]['NAME'] = iconv('euc-kr', 'utf-8', $result[$key]['NAME']);
		}

		if(count($result) > 0) echo json_encode(array('result'=>TRUE, 'data'=>$result));
		else  echo json_encode(array('result'=>FALSE, 'data'=>''));
	}
	/**
	 * @title 고객의 소리 폼에서  구/군 정보 선택시 하위 매장 정보 가져오기 - ajax 전용
	 * @author - 원종필(won0334@chol.com)
	 * */
	function get_store()
	{
		$sido = $this->input->post('sido');
		$gugun = $this->input->post('gugun');
		
		$this->set_db_instance();
		$this->db->from('V_MA_PARTNER_LH');
		$this->db->where('CD_CITY', $sido);
		$this->db->where('CD_DIST', $gugun);
		$result = $this->db->get()->result_array();
	
		if(count($result) > 0) echo json_encode(array('result'=>TRUE, 'data'=>$result));
		else  echo json_encode(array('result'=>FALSE, 'data'=>''));
	}	
	/**
	 * @title 고객의 소리 저장 처리
	 * @author - 원종필(won0334@chol.com)
	 * */
	function testimonials_save()
	{
		$name = $this->input->post('name');
		if($name === FALSE OR empty($name) === TRUE) alert('사용자 정보가  전달되지 않았습니다.', '/board/testimonials_check' ,TRUE);
		
		$this->set_db_instance();
		$tel = $this->input->post('tel1').'-'.$this->input->post('tel2').'-'.$this->input->post('tel3');
		$email = $this->input->post('email1').'@'.$this->input->post('email2');

		$council_result = $this->db->query('declare @P_NO nvarchar(20); declare @DATE nvarchar(6); SET @DATE = CONVERT(NVARCHAR(6),GETDATE(),112);   
		EXEC CP_GETNO \'1000\', \'SA\', \'34\', @DATE, @P_NO output; select @P_NO NO_CUS; ');
		$council = $council_result->row(0);

		$data = array(
			'CD_COMPANY'=>'1000',//회사코드
			'CD_PLANT'=>'1000',//공장코드
			'NO_VOC'=>$council->NO_CUS,//접수번호
			'DT_VOC'=>date('Ymd'),//접수일자,
			'CUS_NAME'=>$name,//고객명
			'CUS_PHONE'=>$tel,//연락처
			'CUS_EMAIL'=>$email,//이메일
			'DT_RECEIPT'=>str_replace('-', '', $this->input->post('visitday')),//방문일자 /시
			'WAY_RECEIPT'=>'100',//접수경로
			'TP_RECEIPT'=>$this->input->post('cate'),//접수유형
			'FG_QSC'=>'100',//QSC구분
			'CD_PARTNER'=>$this->input->post('store'),//가맹점코드
			'RECEIPT_DETAIL'=>$this->input->post('contents'),//접수상세내용
			'ID_INSERT'=>'HOMEPAGE',//입력자
			'DTS_INSERT'=>date('Ymdhis')//입력일
		);
		$result = $this->db->insert('SA_Z_FRAN_VOICE_CUS', $data);
		
		if($result === TRUE)
		{
			alert('등록되었습니다.', '/board/testimonials_check', TRUE);
		}
		else
		{
			alert('등록이 실패하였습니다.', '/board/testimonials_check', TRUE);
		}

	}	
	/**
	 * @title 가맹 상담 신청 폼
	 * @author - 원종필(won0334@chol.com)
	 * */
	function signatories_form()
	{
		$this->set_db_instance();
		$this->db->from('V_MA_CODEDTL_SA_F000019');
		$this->view_data['sido'] = $this->db->get()->result_array();
				
		//레이아웃 처리(출력)
		if(MOBILE_USE == 'Y') $base_skin = 'mobile/'; else $base_skin = 'web/';
		if(defined('LANGUAGE') === TRUE) $base_skin .=  LANGUAGE.'/';
		$this->view_data['subviisual_file_name'] = 'common/fr_sub';
		$this->view_data['head_file_name'] = 'index/head';
		$this->view_data['left_file_name'] = 'common/fr_left';
		$this->view_data['head_file_name'] = 'page/signatories_form_head';
		$this->view_data['main_file_name'] = 'page/signatories_form';
		$this->view_data['index_file_name'] = 'index/index';
		$this->view_data['default_head_file_name'] = 'index/default_head';		
		$this->view_data['page_name'] = 'signatories_form';
		$this->load->view($this->view_data['index_file_name'], $this->view_data);
	}
	/**
	 * @title 상담신청 저장 처리
	 * @author - 원종필(won0334@chol.com)
	 * */
	function signatories_save()
	{
		$name = $this->input->post('name');
		if($name === FALSE OR empty($name) === TRUE) alert('사용자 정보가  전달되지 않았습니다.', '/board/testimonials_check' ,TRUE);
	
		$tel = $this->input->post('tel1').$this->input->post('tel2').$this->input->post('tel3');
		$email = $this->input->post('email1').'@'.$this->input->post('email2');

		$this->set_db_instance();
		$council_result = $this->db->query('declare @P_NO nvarchar(20); declare @DATE nvarchar(6); SET @DATE = CONVERT(NVARCHAR(6),GETDATE(),112);   
		EXEC CP_GETNO \'1000\', \'SA\', \'55\', @DATE, @P_NO output; select @P_NO NO_CUS; ');
		$council = $council_result->row(0);

		$data = array(
				'CD_COMPANY'=>'1000',//회사코드
				'NO_CUST'=>$council->NO_CUS,//모객번호
				'CHL_RECP'=>'HMP',//접수채널 
				'HOMEPAGE_CHK'=>'Y',//홈페이지체크값 
				'NM_CUSTOMER'=>$this->input->post('name'),//고객이름 
				'TEL_CUSTOMER'=>$tel,//연락처
				'EMAIL'=>$email,//이메일
				'DC_ADS_CUST_1'=>$this->input->post('residence'),//거주지
				'HOPE_AREACITY1'=>$this->input->post('sido'),//희망지1(시도)
				'HOPE_AREADIST1'=>$this->input->post('gugun'),//희망지1(구군)
				'HOPE_AREACITY2'=>$this->input->post('sido2'),//희망자2(시도)
				'HOPE_AREADIST2'=>$this->input->post('gugun2'),//희망지2(구군)
				'YN_SHOP'=>$this->input->post('jumpo'),//점포유무
				'HOPE_M2'=>$this->input->post('hopearea'),//매장 희망 평수 
				'CUSTOMER_AGE'=>$this->input->post('age'),//연령대
				'HOPE_AM'=>$this->input->post('price1'),//창업희망비용
				'DC_RMK'=>$this->input->post('contents'),//비고
		);
		$this->db->set('DT_RECP', 'CONVERT(NVARCHAR(20),GETDATE(),112) ', FALSE);//접수일자
		$result = $this->db->insert('SA_Z_FRAN_CUSTOMER_REG', $data);
	
		if($result === TRUE)
		{
			alert('등록되었습니다.', '/board/signatories_form', TRUE);
		}
		else
		{
			alert('등록이 실패하였습니다.', '/board/signatories_form', TRUE);
		}
	
	}	
	/**
	 * @title 블루 코인 적립 이벤트
	 * @author - 원종필(won0334@chol.com)
	 * */
	function blue_coin()
	{
		//레이아웃 처리(출력)
		if(MOBILE_USE == 'Y') $base_skin = 'mobile/'; else $base_skin = 'web/';
		if(defined('LANGUAGE') === TRUE) $base_skin .=  LANGUAGE.'/';
		$this->view_data['subviisual_file_name'] = 'common/event_sub';
		$this->view_data['head_file_name'] = 'index/head';
		$this->view_data['left_file_name'] = 'common/event_left';
		$this->view_data['main_file_name'] = 'page/blue_coin';
		$this->view_data['index_file_name'] = 'index/index';
		$this->view_data['default_head_file_name'] = 'index/default_head';
		$this->view_data['page_name'] = 'blue_coin';
		$this->load->view($this->view_data['index_file_name'], $this->view_data);
	}
	/**
	 * @title 블루 코인 적립하기 달력 폼
	 * @author - 원종필(won0334@chol.com)
	 * */
	function earn()
	{
		$prefs['template'] = '
		   {table_open}
				<table>
                                    <caption>블루 코인 적립 이벤트 테이블</caption>
                                    <colgroup>
                                        <col width="95px" />
                                        <col width="95px" />
                                        <col width="95px" />
                                        <col width="95px" />
                                        <col width="95px" />
                                        <col width="95px" />
                                        <col width="95px" />
                                    </colgroup>
           {/table_open}	
		   {week_row_start}<thead><tr class="center">{/week_row_start}
		   {week_day_cell}<th>{week_day}</th>{/week_day_cell}
		   {week_row_end}</tr></thead>{/week_row_end}
				
		   {cal_row_start}<tr>{/cal_row_start}
		   {cal_cell_start}<td class="{class}">{/cal_cell_start}
		   {cal_cell_start_today}<td class="{class}">{/cal_cell_start_today}

		   {cal_cell_content}<span>{day}</span>{/cal_cell_content}
		   {cal_cell_content_today}<span>{day}</span><div class="btnwrap"><a class="btn navybtn" href="{content}">출석하기</a></div>{/cal_cell_content_today}

		   {cal_cell_blank}&nbsp;{/cal_cell_blank}
				
		   {cal_cell_end}</td>{/cal_cell_end}
		   {cal_row_end}</tr>{/cal_row_end}
		   {table_close}</table>{/table_close}	
		';
		
		$prefs['day_type'] = 'short';
		$this->load->library('calendar', $prefs);
		$mon_day = array();
		
		$this->db->from('blue_coin_history');
		$this->db->where('user_idx', $this->session->userdata('idx'));
		$this->db->where('left(p_date, 7)', '= \''.date('Y').'-'.date('m').'\'', FALSE);
		$this->db->order_by('p_date');
		$month_data = $this->db->get()->result_array();
		
		foreach($month_data as $key=>$list)
		{
			$temp = explode('-', $list['p_date']);
			$mon_day[] = $temp[2]; 
		}

		$today = date('d');
		for($i=1; $i<=31; $i++)
		{
			if($i < $today  && (in_array($i, $mon_day) === TRUE))   
				$this->view_data['data'][$i] = array('class' => 'on', 'content'=>'');
			else 
				$this->view_data['data'][$i] = array('class' => 'off', 'content'=>'');
			
			if($today == $i)
			{
				if( in_array($i, $mon_day) === TRUE) $class = 'on'; else $class='ready';
				if($this->session->userdata('idx') !== FALSE)
					$this->view_data['data'][$i] = array('class' => $class, 'content'=>'/board/earn_check/'.date('m').'/'.$today);
				else 
					$this->view_data['data'][$i] = array('class' => $class, 'content'=>'');
			}
			
			if($i> $today) $this->view_data['data'][$i] = array('class' => 'off', 'content'=>'');
		}
		
		$this->db->select('IF(ISNULL(SUM(POINT)), 0, SUM(POINT)) sum_point');
		$this->db->from('point_history');
		$this->db->where('point_contents', '블루코인 이벤트 참여');
		$this->db->where('point_type', '+');
		$this->db->where('month(reg_date)', date('m'), FALSE);
		$this->db->where('user_idx', $this->session->userdata('idx'));
		$this->view_data['cum_point'] = $this->db->get()->row_array();

		//레이아웃 처리(출력)
		if(MOBILE_USE == 'Y') $base_skin = 'mobile/'; else $base_skin = 'web/';
		if(defined('LANGUAGE') === TRUE) $base_skin .=  LANGUAGE.'/';
		$this->view_data['subviisual_file_name'] = 'common/event_sub';
		$this->view_data['head_file_name'] = 'page/earn_head';
		$this->view_data['left_file_name'] = 'common/event_left';
		$this->view_data['main_file_name'] = 'page/earn';
		$this->view_data['index_file_name'] = 'index/index';
		$this->view_data['default_head_file_name'] = 'index/default_head';
		$this->view_data['page_name'] = 'earn';
		$this->load->view($this->view_data['index_file_name'], $this->view_data);
	}/**
	 * @title 블루 코인 이벤트 참가 처리
	 * @author - 원종필(won0334@chol.com)
	 * */
	function earn_check()
	{
		$id = $this->session->userdata('id');
		if(empty($id) === TRUE)
		{
			echo json_encode(array('result'=>false, 'msg'=>'로그인 후에 참여 가능합니다.'));
			exit;
		}
				
		$month = $this->uri->segment(3);
		$day = $this->uri->segment(4);
		$idx = $this->session->userdata('idx');
		$date = date('Y-m-d H:i:s');
		$p_date = date('Y').'-'.$month.'-'.$day;
		
		$this->db->from('blue_coin_history');
		$this->db->where('p_date', $p_date);
		$this->db->where('user_idx', $this->session->userdata('idx'));
		$old_history = $this->db->get();
		if($old_history->num_rows() > 0)
		{
			echo json_encode(array('result'=>false, 'msg'=>'이미 이벤트에 응모 하셨습니다.'));
			exit;
		}
		$data = array(
			'user_idx'=>$idx,
			'p_date'=>$p_date,
			'reg_date'=>$date
		);
		$result = $this->db->insert('blue_coin_history', $data);
		log_message('debug', 'blue coin history query'.$this->db->last_query());
		
		$data = array(
				'user_idx'=>$idx,
				'point'=>100,
				'point_type'=>'+',
				'point_contents'=>'블루코인 이벤트 참여',
				'reg_date'=>$date
		);
		$result = $this->db->insert('point_history', $data);
		log_message('debug', 'point_history query'.$this->db->last_query());
		
		$this->db->set('point', 'point + 100', FALSE);
		$this->db->where('idx', $idx);
		$result = $this->db->update('users');
		log_message('debug', 'users query'.$this->db->last_query());
		
		if($result == TRUE)
		{
			echo json_encode(array('result'=>true, 'msg'=>'출석 체크 이벤트 응모 성공!!“'.$month.'월 '.$day.'일” 출석 체크 하셨습니다.“100”COIN이 적립 되었습니다.'));
		}
		else
		{
			echo json_encode(array('result'=>false, 'msg'=>'적립이 실패 하였습니다. 관리자에게 문의 하세요.'));
		}		
	}
	/**
	 * @title 코인  이벤트
	 * @author - 원종필(won0334@chol.com)
	 * */
	function coin_event()
	{
		$user_idx = $this->session->userdata('idx');
		$week_num = date('N');echo $week_num;
		if($user_idx !== FALSE)
		{
			if($week_num == 2)//수요일일 경우
			{
				$this->db->from('vote_info');
				$this->db->where('mem_idx', $user_idx);
				$this->db->where('date_format(reg_date, \'%Y-%m-%d\') = ', date('Y-m-d'));
				$mem_vote_info = $this->db->get()->row_array();
				
				$this->view_data['mem_vote_info'] = $mem_vote_info;
				$this->view_data['user_idx'] = $user_idx;
			}
		}
		
		$this->view_data['week_num'] = $week_num;
		$this->load->driver('board_drv', array('board_code'=>'rbt'));
		$this->view_data['product_img'] = $this->board_drv->get_attr('product_img');
		//레이아웃 처리(출력)
		if(MOBILE_USE == 'Y') $base_skin = 'mobile/'; else $base_skin = 'web/';
		if(defined('LANGUAGE') === TRUE) $base_skin .=  LANGUAGE.'/';
		$this->view_data['subviisual_file_name'] = 'common/event_sub';
		$this->view_data['head_file_name'] = 'page/coin_head';
		$this->view_data['left_file_name'] = 'common/event_left';
		$this->view_data['main_file_name'] = 'page/coin_event';
		$this->view_data['index_file_name'] = 'index/index';
		$this->view_data['default_head_file_name'] = 'index/default_head';
		$this->view_data['page_name'] = 'coin_event';
		$this->load->view($this->view_data['index_file_name'], $this->view_data);
	}	
	/**
	 * @title 코인  이벤트 당첨 정보 저장 및 결과 화면 처리(ajax)
	 * @author - 원종필(won0334@chol.com)
	 * */
	function coin_event_save()
	{
		$user_idx = $this->session->userdata('idx');
		if($user_idx !== FALSE)
		{
			$this->db->where('idx', $user_idx);
			$mem_info = $this->db->get('users')->row_array();
			if($mem_info['point'] < 100)
			{
				echo json_encode(array('result'=>false, 'msg'=>'코인이 부족합니다. 이벤트 참여를 위해서는 100코인이 필요합니다.'));
				exit;
			}
			
			$week_num = date('N');
			if($week_num != 2)
			{
				echo json_encode(array('result'=>false, 'msg'=>'수요일만 참여 가능합니다.'));
				exit;
			}

			$this->db->from('vote_info');
			$this->db->where('mem_idx', $user_idx);
			$this->db->where('DATE_FORMAT(reg_date,\'%Y-%m-%d\')', '\''.date('Y-m-d').'\'', FALSE);
			$mem_vote_info = $this->db->get()->row_array();

			if(count($mem_vote_info) > 0)
			{
				echo json_encode(array('result'=>false, 'msg'=>'이미 참여한 내역이 있습니다.'));
				exit;
			}
			else
			{
				$this->load->driver('board_drv', array('board_code'=>'rbt'));
				$product_info = $this->board_drv->get_attr('product_info');
				$product_img = $this->board_drv->get_attr('product_img');
				$time_table = $this->board_drv->get_attr('time_table');
				
				$this->db->select('sweep_stakes, count(sweep_stakes) as sweep_stakes_count, reg_date');
				$this->db->from('vote_info');
				$this->db->where('DATE_FORMAT(reg_date,\'%Y-%m-%d\')', '\''.date('Y-m-d').'\'', FALSE);
				$this->db->group_by('sweep_stakes');
				$this->db->group_by('date_format(reg_date, \'%Y-%m-%d\')');
				$remind_result = $this->db->get()->result_array();

				foreach($product_info as $key=>$list) $remind[$key] = $time_table[$key][$week_num];//남은 갯수 초기화 - 상품별 당첨 가능 개수
				foreach($remind_result as $key=>$list) $remind[$list['sweep_stakes']] -= $list['sweep_stakes_count']; //남은 갯수에서 디비상에 기당첨된 상품 갯수를 차감해서 남은 갯수를 구하기
				 
				$weights = array($remind[0], $remind[1], $remind[2], $remind[3]);
				$index = weighted_random($weights);

				$data = array(
						'mem_idx'=>$user_idx,
						'reg_date'=>date('Y-m-d H:i:s'),
						'sweep_stakes'=>$index
				);
				$result = $this->db->insert('vote_info', $data);		
				
				if($result == TRUE)
				{
					$data = array(
							'user_idx'=>$user_idx,
							'point'=>100,
							'point_type'=>'-',
							'point_contents'=>'즉석 복권 이벤트 참여',
							'reg_date'=>date('Y-m-d H:i:s')
					);
					$result = $this->db->insert('point_history', $data);
					
					$this->db->set('point', 'point - 100', FALSE);
					$this->db->where('idx', $user_idx);
					$result = $this->db->update('users');	

					if($index == 1 OR $index == 2)
					{
						if($index == 1) $point = 500;
						if($index == 2) $point = 100;
						$data = array(
								'user_idx'=>$user_idx,
								'point'=>$point,
								'point_type'=>'+',
								'point_contents'=>'즉석 복권 이벤트 참여 당첨',
								'reg_date'=>date('Y-m-d H:i:s')
						);
						$result = $this->db->insert('point_history', $data);
						log_message('debug', 'point_history query'.$this->db->last_query());	

						$this->db->set('point', 'point + '.$point, FALSE);
						$this->db->where('idx', $user_idx);
						$result = $this->db->update('users');
						log_message('debug', 'users query'.$this->db->last_query());						
					}
					echo json_encode(array('result'=>true, 'msg'=>'이벤트 응모 완료', 'product'=>$product_info[$index], 'product_img'=>$product_img[$index], 'product_idx'=>$index));
				}
				else
				{
					echo json_encode(array('result'=>false, 'msg'=>'이벤트 응모 실패. 관리자에게 문의 하세요.'));
				}
			}
		}
		else
		{
			echo json_encode(array('result'=>false, 'msg'=>'로그인해 주세요.'));
		}
	}	
	/**
	 * @title 이메일 보내기 폼
	 * @author - 원종필(won0334@chol.com)
	 * */
	function email_send()
	{	
		$email_config['wordwrap'] = TRUE;
		$email_config['mailtype'] = 'html';
		$email_config['useragent'] = 'Digital library';
		
		$this->load->library('email');
		$this->email->initialize($email_config);
		
		$subject =  '[ETHICON]'.$this->input->post('title');
		$contents = $this->load->view('movie_email', '', TRUE);
		$contents = str_replace('{{title}}', $subject, $contents);
		$contents = str_replace('{{domain}}', $this->config->item('base_url'), $contents);
		$contents = str_replace('{{send_name}}', urldecode($this->session->userdata('name')), $contents);
		$contents = str_replace('{{coment}}', nl2br($this->input->post('contents')), $contents);
		
		$this->email->from($this->session->userdata('id'), $this->input->post('send_email'));
		$this->email->to($this->input->post('receive_email'));
		$this->email->subject($subject);
		$this->email->message($contents);
		$email_result = $this->email->send();
		log_message('debug', '이메일 전송 결과 '. $email_result);
		
		if($email_result == TRUE)
		{
			echo json_encode(array('result'=>true, 'msg'=>'이메일이 발송되었습니다.'));
		}
		else
		{
			echo json_encode(array('result'=>false, 'msg'=>'이메일 발송에 실패하였습니다.'));
		}		
	}
	/**
	 * 이벤��� 응모 폼
	 * @author - 원종필(won0334@chol.com)
	 * @history
	 * @return NULL
	 * */
	function app_form()
	{
		//레이아웃 처리(출력)
		if(MOBILE_USE == 'Y') $base_skin = 'mobile/'; else $base_skin = 'web/';
		if(defined('LANGUAGE') === TRUE) $base_skin .=  LANGUAGE.'/';
		$this->load->view('page/popup_event');
	}	
	/**
	 * 이벤트 응모하기
	 * @author - 원종필(won0334@chol.com)
	 * @history
	 * @return NULL
	 * */
	function app_regist()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('event_id', '이벤트아이디', 'required');
		$this->form_validation->set_rules('tel1', '전화번호', 'required|min_length[2]');
		$this->form_validation->set_rules('tel2', '전화번호', 'required|min_length[3]');
		$this->form_validation->set_rules('tel3', '전화번호', 'required|min_length[3]');
		$this->form_validation->set_rules('addr1', '주소', 'required');
		$this->form_validation->set_rules('addr2', '주소', 'required');
		$this->form_validation->set_rules('email1', '이메일', 'required');
		$this->form_validation->set_rules('email2', '이메일', 'required');
		$this->form_validation->set_rules('postcode1', '우편번호', 'required');
		$this->form_validation->set_rules('postcode1', '우편번호', 'required');		
		
		if ($this->form_validation->run() === FALSE)
		{
			echo json_encode(array('result'=>false, 'msg'=>'입력값이 올바르지 않습니다.')); 
			exit;
		}
		
		$email = $this->input->post('email1').'@'.$this->input->post('email2');
		$event_idx = $this->input->post('event_id');
		$this->db->select('count(*) cnt');
		$this->db->from('event_app');
		if(is_login() === TRUE) $this->db->where('user_idx', $this->session->userdata('idx'));
		else $this->db->where('email', $email);
		$this->db->where('event_idx', $event_idx);
		$app_result = $this->db->get()->row_array();
		
		if($app_result['cnt'] != 0)
		{
			echo json_encode(array('result'=>false, 'msg'=>'이미 응모 하셨습니다.')); 
			exit;
		}
		
		$tel = $this->input->post('tel1').'-'.$this->input->post('tel2').'-'.$this->input->post('tel3');
		$post_num = $this->input->post('postcode1').$this->input->post('postcode2');
		$name = is_login() === TRUE?urldecode($this->session->userdata('name')):$this->input->post('name');
		$etc = $this->input->post('etc');
		$data = array(
			'user_idx'=>$this->session->userdata('idx'),
			'tel'=>$tel,
			'addr1'=>$this->input->post('addr1'),
			'addr2'=>$this->input->post('addr2'),
			'email'=>$email,
			'post_num'=>$post_num,
			'event_idx'=>$event_idx,
			'reg_date'=>date('Y-m-d H:i:s'),
			'device_type'=>(MOBILE_USE == 'Y')?'m':'w',
			'name'=>$name,
			'etc'=>$etc
		);
		$result = $this->db->insert('event_app', $data);
		
		if($result == TRUE)
		{
			echo json_encode(array('result'=>true, 'msg'=>'이벤트에 응모 되었습니다.'));
		}
		else
		{
			echo json_encode(array('result'=>false, 'msg'=>'응모가 실패하였습니다. \n잠시후 다시 진행해 주세요.'));
		}
	}	
}
/* End of file admin.php */