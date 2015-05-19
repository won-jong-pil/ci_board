<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 메인 페이지 및 순수 안내 페이지 처리
 * @author - 원종필(won0334@chol.com)
 * */
class Users extends CI_Controller 
{
	var $view_data = array();

	function __construct()
	{
		parent::__construct();
		$this->output->enable_profiler(FALSE);
	}
	/**
	 * 메인 페이지 첫 화면
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
		$this->load->view($this->view_data['index_file_name'], $this->view_data);		
	}	
	/**
	 * 사용자 로그인폼
	 * @author - 원종필(won0334@chol.com)
	 * */
	function login_form()
	{
		//레이아웃 처리(출력)
		if(MOBILE_USE == 'Y') $base_skin = 'mobile/'; else $base_skin = 'web/';
		if(defined('LANGUAGE') === TRUE) $base_skin .=  LANGUAGE.'/';
		$this->view_data['head_file_name'] = $base_skin.'users/login_head';
		$this->view_data['subviisual_file_name'] = $base_skin.'common/regist_sub';
		$this->view_data['left_file_name'] = $base_skin.'common/regist_left';
		$this->view_data['main_file_name'] = $base_skin.'users/login_form';
		$this->view_data['index_file_name'] = $base_skin.'index/index';
		$this->view_data['default_head_file_name'] = $base_skin.'index/default_head';	
		$this->view_data['page_name'] = 'log_in_form';	
		$this->load->view($this->view_data['index_file_name'], $this->view_data);	
	}
	/**
	 * 사용자 로그인 처리
	 * @author - 원종필(won0334@chol.com)
	 * */
	function log_in()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('id', '아이디', 'required|min_length[6]|max_length[20]');
		$this->form_validation->set_rules('passwd', '패스워드', 'required|min_length[8]|max_length[30]');
	
		if ($this->form_validation->run() === FALSE)
		{
			alert('입력정보가 올바르지 않습니다.', '/users/login_form', TRUE);
		}
	
		$id = $this->input->post('id');
		$passwd = $this->input->post('passwd');
	
		$this->db->from('users');
		$this->db->where('id', $id);
		$result = $this->db->get();
	
		if( $result->num_rows() <= 0 )
		{
			alert('아이디와 비밀번호가 일치하지 않습니다', '/users/login_form', TRUE);
		}
		else
		{
			$mem = $result->row_array();
			$input_pass = hash('sha256', $passwd);
	
			if($mem['attempts'] >= 5)
			{
				alert('회원 계정이 잠금처리 되었습니다.관리자에게 문의 하세요.', '/users/login_form', TRUE);
			}
				
			if($mem['passwd'] != $input_pass)
			{
				$this->db->where('id', $id);
				$this->db->set('attempts', 'attempts+1', FALSE);
				$this->db->update('users');
				alert('아이디와 비밀번호가 일치하지 않습니다', '/users/login_form', TRUE);
			}
				
			$this->db->set('attempts', 0);
			$this->db->where('id', $id);
			$this->db->update('users');
	
			$this->session->set_userdata('idx', $mem['idx']);
			$this->session->set_userdata('id', $mem['id']);
			$this->session->set_userdata('pass_update_date', $mem['pass_update_date']);
			$this->session->set_userdata('name', urlencode($mem['name']));
			$this->session->set_userdata('level', $mem['point']);
	
			if(date('Y-m-d') > date('Y-m-d', strtotime('+90 days', strtotime($this->session->userdata('pass_update_date')))))
			{
				alert('패스워드를 변경한지 90일이 지났습니다. 패스워드를 변경해 주세요.', '/users/pass_form/idx/'.$mem['idx'], TRUE);
			}
		}
	
		$this->load->helper('url');
		$return_url = $this->input->post('return_url');
		
		if($return_url != FALSE)
		{
			$temp = explode('/', $return_url);
			foreach(array('users', 'testimonials_check', 'check_ipin', 'testimonials_form', 'user_list_now', 'user_list_end') as $key=>$list)
			{
				if(in_array($list, $temp) === TRUE)
				{
					redirect('/');
				}	
			}
			 
			redirect($return_url);
		}
		else
		{
			redirect('/');
		}
	}
	/**
	 * 사용자 로그아웃 처리
	 * @author - 원종필(won0334@chol.com)
	 * */
	function log_out()
	{
		$this->session->sess_destroy();
		$this->load->helper('url');
		redirect('/users/login_form');
	}
	/**
	 * 사용자 패스워드 변경 폼 
	 * @author - 원종필(won0334@chol.com)
	 * */	
	function pass_form()
	{
		$id = $this->session->userdata('id');
		if(empty($id) === TRUE) alert('로그인 하여 주세요.', '/users/login_form', TRUE);
		$url_array = $this->uri->uri_to_assoc(3);
		
		//레이아웃 처리(출력)
		if(MOBILE_USE == 'Y') $base_skin = 'mobile/'; else $base_skin = 'web/';
		if(defined('LANGUAGE') === TRUE) $base_skin .=  LANGUAGE.'/';
		$this->view_data['subviisual_file_name'] = $base_skin.'common/mypage_sub';
		$this->view_data['left_file_name'] = $base_skin.'common/mypage_left';		
		$this->view_data['head_file_name'] = $base_skin.'users/pass_form_head';
		$this->view_data['main_file_name'] = $base_skin.'users/pass_form';
		$this->view_data['index_file_name'] = $base_skin.'index/index';
		$this->view_data['default_head_file_name'] = $base_skin.'index/default_head';	
		$this->view_data['page_name'] = 'pass_form';
		$this->load->view($this->view_data['index_file_name'], $this->view_data);
	}
	/**
	 * 사용자 패스워드 변경 저장
	 * @author - 원종필(won0334@chol.com)
	 * */	
	function pass_update()
	{
		$id = $this->session->userdata('id');
		if(empty($id) === TRUE) alert('로그인 하여 주세요.', '/users/login_form', TRUE);
		$this->load->library('form_validation');

		$this->form_validation->set_rules('old_passwd', 'old_passwd', 'required|min_length[8]');
		$this->form_validation->set_rules('passwd', 'passwd', 'required|min_length[8]');
		
		if ($this->form_validation->run() === FALSE)
		{
			alert('입력정보가 올바르지 않습니다.', 'pass_form', TRUE);
		}
		
		$id = $this->session->userdata('id');
		$old_passwd = $this->input->post('old_passwd');
		$passwd = $this->input->post('passwd');
		
		$this->db->from('users');
		$this->db->where('id', $id);
		$result = $this->db->get();
		
		if( $result->num_rows() <= 0 )
		{
			alert('사용자 정보가 올바르지 않습니다.', '/users/pass_form', TRUE);
		}
		else
		{
			$mem = $result->row_array();
			$input_old_pass = hash('sha256', $old_passwd);
			$input_pass = hash('sha256', $passwd);
		
			if($mem['passwd'] != $input_old_pass)
			{
				alert('회원정보가 올바르지 않습니다.','/users/pass_form', TRUE);
			}
			else
			{
				$this->db->where('id', $id);
				$this->db->set('passwd', $input_pass);
				$this->db->set('attempts', 0);
				$this->db->set('pass_update_date', date('Y-m-d H:i:s'));
				$this->db->update('users');
				alert('변경되었습니다.','/users/pass_form', TRUE);
			}
		}
	}
	/**
	 * 사용자 패스워드 확인 폼
	 * @author - 원종필(won0334@chol.com)
	 * */
	function check_pass_form()
	{
		$id = $this->session->userdata('id');
		if(empty($id) === TRUE) alert('로그인 하여 주세요.', '/users/login_form', TRUE);
		$url_array = $this->uri->uri_to_assoc(3);
	
		//레이아웃 처리(출력)
		if(MOBILE_USE == 'Y') $base_skin = 'mobile/'; else $base_skin = 'web/';
		if(defined('LANGUAGE') === TRUE) $base_skin .=  LANGUAGE.'/';
		$this->view_data['subviisual_file_name'] = $base_skin.'common/mypage_sub';
		$this->view_data['left_file_name'] = $base_skin.'common/mypage_left';
		$this->view_data['head_file_name'] = $base_skin.'users/mypage_head';
		$this->view_data['main_file_name'] = $base_skin.'users/check_pass_form';
		$this->view_data['index_file_name'] = $base_skin.'index/index';
		$this->view_data['default_head_file_name'] = $base_skin.'index/default_head';
		$this->view_data['page_name'] = 'check_pass_form';
		$this->load->view($this->view_data['index_file_name'], $this->view_data);
	}	
	/**
	 * 사용자 등록시 id 체크
	 * @author - 원종필(won0334@chol.com)
	 * */
	function id_check()
	{
		$id = $this->input->post('id');
	
		$this->db->from('users');
		$this->db->where('id', $id);
		$result = $this->db->get();
	
		if( $result->num_rows() <= 0 )
		{
			if(preg_match("/^[a-zA-Z]{1}[a-zA-Z0-9_]{5,20}+$/", $id))
			{
				echo 'true';
			}
			else
			{
				echo 'false';
			}
		}
		else
		{
			echo 'false';
		}
	}	
	/**
	 * 회원 확인
	 * @author - 원종필(won0334@chol.com)
	 * @history
	 * @return NULL
	 * */
	function step1()
	{
		if($this->session->userdata('id') !== FALSE) alert('이미 로그인되어 있습니다.', '/', TRUE);

		$enc_data	= get_sms_code($this->config->item('base_url').'users/check_sms', $this->config->item('base_url').'users/check_sms_fail', MOBILE_USE == 'Y'?'Mobile':'');
		
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
	
	    $sEncData	= get_ipin_code($this->config->item('base_url').'users/check_ipin');

	    // 리턴 결과값에 따른 처리사항
	    if ($sEncData == -9)
	    {
	    	$sRtnMsg = "IPIN사용을 위한 암호화 처리에 문제가 있습니다. 관리자에게 문의 하세요.";
	    	script_exec('alert("'.$sRtnMsg.'");');
	    }		
	    else
	    {
	    	$this->view_data['sEncData'] = $sEncData;
	    }
	    
		//레이아웃 처리(출력)
	    if(MOBILE_USE == 'Y') $base_skin = 'mobile/'; else $base_skin = 'web/';
	    if(defined('LANGUAGE') === TRUE) $base_skin .=  LANGUAGE.'/';
		$this->view_data['subviisual_file_name'] = $base_skin.'common/regist_sub';
		$this->view_data['left_file_name'] = $base_skin.'common/regist_left';
		$this->view_data['head_file_name'] = $base_skin.'users/agree_head';
		$this->view_data['main_file_name'] = $base_skin.'users/step1';
		$this->view_data['index_file_name'] = $base_skin.'index/index';
		$this->view_data['default_head_file_name'] = $base_skin.'index/default_head';
		$this->view_data['page_name'] = 'reg_step';
		$this->load->view($this->view_data['index_file_name'], $this->view_data);
	}	
	/**
	 * sms 결과 리턴 확인 - 성공시
	 * @author - 원종필(won0334@chol.com)
	 * @history
	 * @return NULL
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
	        }else{echo $plaindata.'<br>';;
	            // 복호화가 정상적일 경우 데이터를 파싱합니다.
	            $requestnumber = GetValue($plaindata , "REQ_SEQ");
	            $responsenumber = GetValue($plaindata , "RES_SEQ");
	            $authtype = GetValue($plaindata , "AUTH_TYPE");
	            $name = GetValue($plaindata , "NAME");
	            $birthdate = GetValue($plaindata , "BIRTHDATE");
	            $gender = GetValue($plaindata , "GENDER");	
	            $dupinfo = GetValue($plaindata , "DI");
	            $mobileinfo = GetValue($plaindata , "MOBILE_NO");
	            
	            $this->db->select('count(*) cnt');
	            $this->db->from('users');
	            $this->db->where('dup_data', $dupinfo);
	            $result = $this->db->get()->row_array();
	            if($result['cnt'] > 0)
	            {
	            	$error_msg = '이미 가입된 회원입니다.';
	            }
	            else
	            {
	            	$this->session->set_userdata('sms_dup_data', $dupinfo);
	            	$this->session->set_userdata('check_name', iconv('euc-kr', 'utf-8', $name));
	            	$this->session->set_userdata('mobile_info', $mobileinfo);
	            }	            
	
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
    	$this->load->view($base_skin.'users/check_sms', $this->view_data);    	
	}	
	/**
	 * sms 결과 리턴 확인 - 실패시
	 * @author - 원종필(won0334@chol.com)
	 * @history
	 * @return NULL
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
   		$this->load->view($base_skin.'users/check_sms', $this->view_data);   		
	}	
	/**
	 * 아이핀 결과 리턴 확인
	 * @author - 원종필(won0334@chol.com)
	 * @history
	 * @return NULL
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
		$sCPRequest = $this->session->userdata('CPREQUEST');// 데이타 위변조 방지를 위해 확인하기 위함이므로, 필수사항은 아니며 보안을 위한 권고사항입니다.
		
		if ($sEncData != "") 
		{
			$sDecData = `$sModulePath RES $sSiteCode $sSitePw $sEncData`;// 사용자 정보를 복호화 합니다.
				
			if ($sDecData == -9) 
			{
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
		
				if ($iCount >= 5) 
				{
					$strResultCode	= $arrData[0];			// 결과코드
					if ($strResultCode == 1) 
					{
						$strCPRequest	= $arrData[8];			// CP 요청번호
						if ($sCPRequest == $strCPRequest)
						{
							$strUserName		= $arrData[2];	// 이름
							$strDupInfo = $arrData[3];// 중복가입 확인값 (64Byte 고유값)
								
							$this->db->select('count(*) cnt');
							$this->db->from('users');
							$this->db->where('dup_data', $strDupInfo);
							$result = $this->db->get()->row_array();
							if($result['cnt'] > 0)
							{
								$sRtnMsg = '이미 가입된 회원입니다.';
							}
							else
							{
								$this->session->set_userdata('ipin_dup_data', $strDupInfo);
								$this->session->set_userdata('check_name', iconv('euc-kr', 'utf-8', $strUserName));
							}
						} else {
							$sRtnMsg = "CP 요청번호 불일치 : 데이타를 확인해 주시기 바랍니다.";
						}
					} else {
						$sRtnMsg = "리턴값 확인 후, NICE평가정보 개발 담당자에게 문의해 주세요. [$strResultCode]";
					}
				} else {
					$sRtnMsg = "리턴값 확인 후, NICE평가정보 개발 담당자에게 문의해 주세요.";
				}
			}
		} 
		else 
		{
			$sRtnMsg = "처리할 암호화 데이타가 없습니다.";
		}
		// 암호화된 사용자 정보가 존재하는 경우
		log_message('debug', $sRtnMsg);
		$this->view_data['action_url'] = '/users/agree';
		$this->view_data['sResponseData'] = $sEncData;
		$this->view_data['msg'] = $sRtnMsg;

		//레이아웃 처리(출력)
		if(MOBILE_USE == 'Y') $base_skin = 'mobile/'; else $base_skin = 'web/';
		if(defined('LANGUAGE') === TRUE) $base_skin .=  LANGUAGE.'/';			
		$this->load->view($base_skin.'users/check_ipin', $this->view_data);
	}
	/**
	 * 회원 약관
	 * @author - 원종필(won0334@chol.com)
	 * @history
	 * @return NULL
	 * */
	function agree()
	{
		if($this->session->userdata('id') !== FALSE) alert('이미 로그인되어 있습니다.', '/', TRUE);
		if($this->session->userdata('ipin_dup_data') === FALSE && $this->session->userdata('sms_dup_data') === FALSE)
		{
			alert('사용자 인증 정보 처리에 문제가 있습니다. 창을 닫은 후 다시 실행 해 주세요.', '/users/step1', TRUE);
		}
				
		//레이아웃 처리(출력)
		if(MOBILE_USE == 'Y') $base_skin = 'mobile/'; else $base_skin = 'web/';
		if(defined('LANGUAGE') === TRUE) $base_skin .=  LANGUAGE.'/';
		$this->view_data['subviisual_file_name'] = $base_skin.'common/regist_sub';
		$this->view_data['left_file_name'] = $base_skin.'common/regist_left';
		$this->view_data['head_file_name'] = $base_skin.'users/agree_head';
		$this->view_data['main_file_name'] = $base_skin.'users/agree';
		$this->view_data['index_file_name'] = $base_skin.'index/index';
		$this->view_data['default_head_file_name'] = $base_skin.'index/default_head';
		$this->view_data['page_name'] = 'reg_step';
		$this->load->view($this->view_data['index_file_name'], $this->view_data);
	}	
	/**
	 * 회원 가입폼
	 * @author - 원종필(won0334@chol.com)
	 * @history
	 * @return NULL
	 * */
	function reg_form()
	{
 		$this->load->library('form_validation');
		$this->form_validation->set_rules('agree1', 'agree1', 'required');
		$this->form_validation->set_rules('agree2', 'agree2', 'required');
		
		if ($this->form_validation->run() == FALSE) alert('약관에 동의해 주세요.', '', TRUE);
		if($this->session->userdata('ipin_dup_data') === FALSE && $this->session->userdata('sms_dup_data') === FALSE)
		{
			alert('사용자 인증 정보 처리에 문제가 있습니다. 창을 닫은 후 다시 실행 해 주세요.', '/users/step1', TRUE);
		}
		
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
		
		$this->view_data['next_url'] = '/users/reg_save';
		$this->view_data['mode'] = 'write';
		$this->view_data['subviisual_file_name'] = $base_skin.'common/regist_sub';
		$this->view_data['left_file_name'] = $base_skin.'common/regist_left';
		$this->view_data['head_file_name'] = $base_skin.'users/reg_head';
		$this->view_data['main_file_name'] = $base_skin.'users/reg_form';
		$this->view_data['index_file_name'] = $base_skin.'index/index';
		$this->view_data['default_head_file_name'] = $base_skin.'index/default_head';
		$this->view_data['page_name'] = 'reg_step';
		$this->load->view($this->view_data['index_file_name'], $this->view_data);
	}
	/**
	 * 회원정보 저장
	 * @author - 원종필(won0334@chol.com)
	 * @history
	 * @return NULL
	 * */
	function reg_save()
	{
		$url_array = $this->uri->uri_to_assoc(3);
	
		//게시판 정보 가져오기
		$config['debug'] = 'N';
		$config['board_code'] = 'users';
		$this->load->driver('board_drv',  $config);
		$result = $this->board_drv->insert($config);
		
		$url = $this->uri->assoc_to_uri($url_array);

		if($result['result'] === TRUE)
		{
			$email_config['wordwrap'] = TRUE;
			$email_config['mailtype'] = 'html';
			$email_config['useragent'] = 'EDIYA';
				
			$this->load->library('email');
			$this->email->initialize($email_config);
				
			$base_skin = 'web/';
			if(defined('LANGUAGE') === TRUE) $base_skin .=  LANGUAGE.'/';
			$admin_email = json_decode(get_sitemeta('admin_email'));
			$admin_name = json_decode(get_sitemeta('admin_name'));
				
			$subject = '['.$email_config['useragent'].'] 회원이 되신 것을 진심으로 환영합니다.';
			$contents = $this->load->view($base_skin.'common/email_join', '', TRUE);
			$contents = str_replace('{{home_url}}', $this->config->item('base_url'), $contents);	
			$this->email->from($admin_email[0], $admin_name[0]);
			$this->email->to($result['email']);
			$this->email->subject($subject);
			$this->email->message($contents);
			$email_result = $this->email->send();
			log_message('debug', '가입 이메일 전송 이메일 '. $result['email']);
			log_message('debug', '가입 이메일 전송 결과 '. $email_result);
			
			$this->load->helper('url');
			redirect('/users/reg_complete/');
		}
		else
		{
			alert('등록이 실패하였습니다.', '', TRUE);
		}
	}	
	/**
	 * 회원 가입 완료 폼
	 * @author - 원종필(won0334@chol.com)
	 * @history
	 * @return NULL
	 * */
	function reg_complete()
	{
		$url_array = $this->uri->uri_to_assoc(3);
		
		//레이아웃 처리(출력)
		if(MOBILE_USE == 'Y') $base_skin = 'mobile/'; else $base_skin = 'web/';
		if(defined('LANGUAGE') === TRUE) $base_skin .=  LANGUAGE.'/';
		$this->view_data['subviisual_file_name'] = $base_skin.'common/regist_sub';
		$this->view_data['left_file_name'] = $base_skin.'common/regist_left';
		$this->view_data['head_file_name'] = $base_skin.'users/reg_head';
		$this->view_data['main_file_name'] = $base_skin.'users/reg_complete';
		$this->view_data['index_file_name'] = $base_skin.'index/index';
		$this->view_data['default_head_file_name'] = $base_skin.'index/default_head';
		$this->view_data['page_name'] = 'reg_step';
		$this->load->view($this->view_data['index_file_name'], $this->view_data);
	}	
	/**
	 * 회원 정보 수정폼
	 * @author - 원종필(won0334@chol.com)
	 * @history
	 * @return NULL
	 * */
	function update_form()
	{
		$id = $this->session->userdata('id');
		if(empty($id) === TRUE) alert('로그인 하여 주세요.', '/users/login_form', TRUE);
		
		$pass = hash('sha256', $this->input->post('passwd'));
		//사용자 정보 가져오기
 		$config['board_code'] = 'users';
		$this->load->driver('board_drv',  $config);
		$this->view_data['result'] = $this->board_drv->get_data($this->session->userdata('idx'), $config); 
		$this->view_data['next_url'] = '/users/user_update';
		
		if(count($this->view_data['result']['result']) == 0) alert('사용자 정보가 올바르지 않습니다.', '', TRUE);
		if($this->uri->segment(3) != 'complete' && (isset($this->view_data['result']['result']['passwd']) === FALSE OR $pass != $this->view_data['result']['result']['passwd'])) alert('사용자 정보가 올바르지 않습니다.', '', TRUE);
		
		//레이아웃 처리(출력)
		if(MOBILE_USE == 'Y') $base_skin = 'mobile/'; else $base_skin = 'web/';
		if(defined('LANGUAGE') === TRUE) $base_skin .=  LANGUAGE.'/';
		$this->view_data['mode'] = 'update';
		$this->view_data['subviisual_file_name'] = $base_skin.'common/mypage_sub';
		$this->view_data['left_file_name'] = $base_skin.'common/mypage_left';
		$this->view_data['head_file_name'] = $base_skin.'users/reg_head';
		$this->view_data['main_file_name'] = $base_skin.'users/update_form';
		$this->view_data['index_file_name'] = $base_skin.'index/index';
		$this->view_data['default_head_file_name'] = $base_skin.'index/default_head';	
		$this->view_data['page_name'] = 'update_form';
		$this->load->view($this->view_data['index_file_name'], $this->view_data);
	}	
	/**
	 * 회원 정보 수정 저장
	 * @author - 원종필(won0334@chol.com)
	 * @history
	 * @return NULL
	 * */
	function user_update()
	{
		$id = $this->session->userdata('id');
		if(empty($id) === TRUE) alert('로그인 하여 주세요.', '/users/login_form', TRUE);
		//게시판 정보 가져오기
		$config['board_code'] = 'users';
		
		$this->load->driver('board_drv',  $config);
		$result = $this->board_drv->update($this->session->userdata('idx'), $config);
		
		if($result['result']  === TRUE)
		{
			alert('수정 되었습니다.', '/users/update_form/complete', TRUE);
		}
		else
		{
			alert($result['error'], '', TRUE);
		}		
	}
	/**
	 * 회원 탈퇴 안내 페이지
	 * @author - 원종필(won0334@chol.com)
	 * @history
	 * @return NULL
	 * */
	function withdrawal()
	{
		$id = $this->session->userdata('id');
		if(empty($id) === TRUE) alert('로그인 하여 주세요.', '/users/login_form', TRUE);
		$url_array = $this->uri->uri_to_assoc(3);
	
		$config['board_code'] = 'event';
		
		//레이아웃 처리(출력)
		if(MOBILE_USE == 'Y') $base_skin = 'mobile/'; else $base_skin = 'web/';
		if(defined('LANGUAGE') === TRUE) $base_skin .=  LANGUAGE.'/';
		$this->view_data['subviisual_file_name'] = $base_skin.'common/mypage_sub';
		$this->view_data['left_file_name'] = $base_skin.'common/mypage_left';
		$this->view_data['head_file_name'] = $base_skin.'users/pass_form_head';
		$this->view_data['main_file_name'] = $base_skin.'users/withdrawal';
		$this->view_data['index_file_name'] = $base_skin.'index/index';
		$this->view_data['default_head_file_name'] = $base_skin.'index/default_head';
		$this->view_data['page_name'] = 'withdrawal';
		$this->load->view($this->view_data['index_file_name'], $this->view_data);
	}	
	/**
	 * 회원 탈퇴 정보 입력 폼 페이지
	 * @author - 원종필(won0334@chol.com)
	 * @history
	 * @return NULL
	 * */
	function withdrawal_info_form()
	{
		$id = $this->session->userdata('id');
		if(empty($id) === TRUE) alert('로그인 하여 주세요.', '/users/login_form', TRUE);
		$url_array = $this->uri->uri_to_assoc(3);
	
		//레이아웃 처리(출력)
		if(MOBILE_USE == 'Y') $base_skin = 'mobile/'; else $base_skin = 'web/';
		if(defined('LANGUAGE') === TRUE) $base_skin .=  LANGUAGE.'/';
		$this->view_data['subviisual_file_name'] = $base_skin.'common/mypage_sub';
		$this->view_data['left_file_name'] = $base_skin.'common/mypage_left';
		$this->view_data['head_file_name'] = $base_skin.'users/withdrawal_head';
		$this->view_data['main_file_name'] = $base_skin.'users/withdrawal_info';
		$this->view_data['index_file_name'] = $base_skin.'index/index';
		$this->view_data['default_head_file_name'] = $base_skin.'index/default_head';
		$this->view_data['page_name'] = 'withdrawal';
		$this->load->view($this->view_data['index_file_name'], $this->view_data);
	}	
	/**
	 * 회원 탈퇴 정보 저장 처리
	 * @author - 원종필(won0334@chol.com)
	 * @history
	 * @return NULL
	 * */
	function withdrawal_save()
	{
		$id = $this->session->userdata('id');
		if(empty($id) === TRUE) alert('로그인 하여 주세요.', '/users/login_form', TRUE);
		$url_array = $this->uri->uri_to_assoc(3);
	
		$pass = hash('sha256', $this->input->post('passwd'));
		//사용자 정보 가져오기
		$config['board_code'] = 'users';
		$this->load->driver('board_drv',  $config);
		$result = $this->board_drv->get_data($this->session->userdata('idx'), $config);
		$this->view_data['next_url'] = '/users/user_update';
		
		if(count($result['result']) == 0) alert('사용자 정보가 올바르지 않습니다.', '', TRUE);
		if($pass != $result['result']['passwd']) alert('사용자 정보가 올바르지 않습니다.', '', TRUE);
				
		$data = array(
			'id'=>$this->session->userdata('id'),
			'name'=>urldecode($this->session->userdata('name')),
			'secession'=>$this->input->post('secession'),
			'reg_date'=>date('Y-m-d H:i:s'),
			'tel'=>$result['result']['tel'],
			'email'=>$result['result']['email']
		);
		$result = $this->db->insert('withdrawal_mem', $data);
		
		$result = $this->board_drv->delete($this->session->userdata('idx'), $config);
		if($result['result'] === FALSE)
		{
			alert('탈퇴에 문제가 있습니다. 관리자에게 문의 하세요.', '', TRUE);
		}
		$this->session->sess_destroy();
		
		$email_config['wordwrap'] = TRUE;
		$email_config['mailtype'] = 'html';
		$email_config['useragent'] = 'EDIYA';
		
		$this->load->library('email');
		$this->email->initialize($email_config);
		
		$base_skin = 'web/';
		if(defined('LANGUAGE') === TRUE) $base_skin .=  LANGUAGE.'/';
		$admin_email = json_decode(get_sitemeta('admin_email'));
		$admin_name = json_decode(get_sitemeta('admin_name'));
		
		$subject = '['.$email_config['useragent'].'] 회원 탈퇴 안내입니다.';
		$contents = $this->load->view($base_skin.'common/email_widthdrawal', '', TRUE);
		$contents = str_replace('{{home_url}}', $this->config->item('base_url'), $contents);
		$this->email->from($admin_email[0], $admin_name[0]);
		$this->email->to($result['result']['email']);
		$this->email->subject($subject);
		$this->email->message($contents);
		$email_result = $this->email->send();
		log_message('debug', '탈퇴 이메일 전송 결과 '. $email_result);		
		
		$this->load->helper('url');
		redirect('/users/withdrawal_complete');
	}	
	/**
	 * 회원 탈퇴 완료 페이지
	 * @author - 원종필(won0334@chol.com)
	 * @history
	 * @return NULL
	 * */
	function withdrawal_complete()
	{
		$url_array = $this->uri->uri_to_assoc(3);
	
		//레이아웃 처리(출력)
		if(MOBILE_USE == 'Y') $base_skin = 'mobile/'; else $base_skin = 'web/';
		if(defined('LANGUAGE') === TRUE) $base_skin .=  LANGUAGE.'/';
		$this->view_data['subviisual_file_name'] = $base_skin.'common/mypage_sub';
		$this->view_data['left_file_name'] = $base_skin.'common/mypage_left';
		$this->view_data['head_file_name'] = $base_skin.'users/pass_form_head';
		$this->view_data['main_file_name'] = $base_skin.'users/withdrawal_complete';
		$this->view_data['index_file_name'] = $base_skin.'index/index';
		$this->view_data['default_head_file_name'] = $base_skin.'index/default_head';
		$this->view_data['page_name'] = 'withdrawal_complete';
		$this->load->view($this->view_data['index_file_name'], $this->view_data);
	}	
	/**
	 * 아이디 비번 찾기 메인
	 * @author - 원종필(won0334@chol.com)
	 * @history
	 * @return NULL
	 * */
	function find_main()
	{
		if($this->session->userdata('id') !== FALSE) alert('이미 로그인되어 있습니다.', '/', TRUE);
		//레이아웃 처리(출력)
		if(MOBILE_USE == 'Y') $base_skin = 'mobile/'; else $base_skin = 'web/';
		if(defined('LANGUAGE') === TRUE) $base_skin .=  LANGUAGE.'/';
		$this->view_data['subviisual_file_name'] = $base_skin.'common/regist_sub';
		$this->view_data['left_file_name'] = $base_skin.'common/regist_left';
		$this->view_data['head_file_name'] = $base_skin.'users/find_head';
		$this->view_data['main_file_name'] = $base_skin.'users/find_main';
		$this->view_data['index_file_name'] = $base_skin.'index/index';
		$this->view_data['default_head_file_name'] = $base_skin.'index/default_head';
		$this->view_data['page_name'] = 'find_page';
		$this->load->view($this->view_data['index_file_name'], $this->view_data);
	}	
	/**
	 * 아이디 찾기
	 * @author - 원종필(won0334@chol.com)
	 * @history
	 * @return NULL
	 * */
	function find_id()
	{
		$enc_data	= get_sms_code($this->config->item('base_url').'users/find_id_sms', $this->config->item('base_url').'users/find_id_sms_fail', MOBILE_USE == 'Y'?'Mobile':'');
		
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
				
		$sEncData	= get_ipin_code($this->config->item('base_url').'users/find_id_result');
		
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
		$this->view_data['subviisual_file_name'] = $base_skin.'common/regist_sub';
		$this->view_data['left_file_name'] = $base_skin.'common/regist_left';
		$this->view_data['head_file_name'] = $base_skin.'users/find_head';
		$this->view_data['main_file_name'] = $base_skin.'users/find_id';
		$this->view_data['index_file_name'] = $base_skin.'index/index';
		$this->view_data['default_head_file_name'] = $base_skin.'index/default_head';
		$this->view_data['page_name'] = 'find_page';
		$this->load->view($this->view_data['index_file_name'], $this->view_data);
	}	
	/**
	 * sms 결과 리턴 확인 - 성공시
	 * @author - 원종필(won0334@chol.com)
	 * @history
	 * @return NULL
	 * */
	function find_id_sms()
	{
		$sRtnMsg = '';
		$sitecode = $this->config->item('sms_site_code');					// NICE로부터 부여받은 사이트 코드
		$sitepasswd = $this->config->item('sms_site_pass');				// NICE로부터 부여받은 사이트 패스워드
		$cb_encode_path = $this->config->item('sms_module_pass');		// NICE로부터 받은 암호화 프로그램의 위치 (절대경로+모듈명)
	
		$enc_data = $this->input->post('EncodeData');		// 암호화된 결과 데이타
		//////////////////////////////////////////////// 문자열 점검///////////////////////////////////////////////
		if(preg_match('~[^0-9a-zA-Z+/=]~', $enc_data, $match)) {$sRtnMsg = '입력 값 확인이 필요합니다 : '.$match[0]; } // 문자열 점검 추가.
		if(base64_encode(base64_decode($enc_data))!=$enc_data) {$sRtnMsg = '입력 값 확인이 필요합니다'; }
	
		if ($enc_data != '' && $sRtnMsg == '')
		{
			$plaindata = `$cb_encode_path DEC $sitecode $sitepasswd $enc_data`;		// 암호화된 결과 데이터의 복호화
	
			if ($plaindata == -1){
				$sRtnMsg  = '암/복호화 시스템 오류';
			}else if ($plaindata == -4){
				$sRtnMsg  = '복호화 처리 오류';
			}else if ($plaindata == -5){
				$sRtnMsg  = 'HASH값 불일치 - 복호화 데이터는 리턴됨';
			}else if ($plaindata == -6){
				$sRtnMsg  = '복호화 데이터 오류';
			}else if ($plaindata == -9){
				$sRtnMsg  = '입력값 오류';
			}else if ($plaindata == -12){
				$sRtnMsg  = '사이트 비밀번호 오류';
			}else{
				// 복호화가 정상적일 경우 데이터를 파싱합니다.
				$dupinfo = GetValue($plaindata , "DI");
				 
				$this->db->from('users');
				$this->db->where('dup_data', $dupinfo);
				$result = $this->db->get();
				if($result->num_rows() > 0)
				{
					$mem_info = $result->row_array();
					$sRtnMsg = '<img src="/images/member/img_findid_icon.jpg" alt="" />';
					$sRtnMsg .= '회원님의 아이디는 <span class="red">'.$mem_info['id'].'</span> 입니다.';
				}
				else
				{
					$sRtnMsg = '<img src="/images/member/img_findid_icon.jpg" alt="" />';
					$sRtnMsg .= '일치하는 회원 정보가 없습니다.';
				}
			}
		}
		 
		$this->view_data['sRtnMsg'] = $sRtnMsg;
		//레이아웃 처리(출력)
		if(MOBILE_USE == 'Y') $base_skin = 'mobile/'; else $base_skin = 'web/';
		if(defined('LANGUAGE') === TRUE) $base_skin .=  LANGUAGE.'/';
		$this->load->view($base_skin.'users/find_sms_result', $this->view_data);
	}
	/**
	 * sms 결과 리턴 확인 - 실패시
	 * @author - 원종필(won0334@chol.com)
	 * @history
	 * @return NULL
	 * */
	function find_id_sms_fail()
	{
		$sRtnMsg = 'sms 인증이 실패하였습니다.';
		$sitecode = $this->config->item('sms_site_code');					// NICE로부터 부여받은 사이트 코드
		$sitepasswd = $this->config->item('sms_site_pass');				// NICE로부터 부여받은 사이트 패스워드
		$cb_encode_path = $this->config->item('sms_module_pass');		// NICE로부터 받은 암호화 프로그램의 위치 (절대경로+모듈명)
	
		$enc_data = $this->input->post('EncodeData');		// 암호화된 결과 데이타
		//////////////////////////////////////////////// 문자열 점검///////////////////////////////////////////////
		if(preg_match('~[^0-9a-zA-Z+/=]~', $enc_data, $match)) {$sRtnMsg = '입력 값 확인이 필요합니다 : '.$match[0]; } // 문자열 점검 추가.
		if(base64_encode(base64_decode($enc_data))!=$enc_data) {$sRtnMsg = '입력 값 확인이 필요합니다'; }
	
		if ($enc_data != "")
		{
			$plaindata = `$cb_encode_path DEC $sitecode $sitepasswd $enc_data`;		// 암호화된 결과 데이터의 복호화
	
			if ($plaindata == -1){
				$sRtnMsg  = '암/복호화 시스템 오류';
			}else if ($plaindata == -4){
				$sRtnMsg  = '복호화 처리 오류';
			}else if ($plaindata == -5){
				$sRtnMsg  = 'HASH값 불일치 - 복호화 데이터는 리턴됨';
			}else if ($plaindata == -6){
				$sRtnMsg  = '복호화 데이터 오류';
			}else if ($plaindata == -9){
				$sRtnMsg  = '입력값 오류';
			}else if ($plaindata == -12){
				$sRtnMsg  = '사이트 비밀번호 오류';
			}else{
				$requestnumber = GetValue($plaindata , "REQ_SEQ");
				$errcode = GetValue($plaindata , "ERR_CODE");
				$authtype = GetValue($plaindata , "AUTH_TYPE");
			}
		}
		 
		$this->view_data['sRtnMsg'] = $sRtnMsg.'['.$errcode.']';
		//레이아웃 처리(출력)
		if(MOBILE_USE == 'Y') $base_skin = 'mobile/'; else $base_skin = 'web/';
		if(defined('LANGUAGE') === TRUE) $base_skin .=  LANGUAGE.'/';
		$this->load->view($base_skin.'users/find_sms_result', $this->view_data);
	}
	/**
	 * 아이핀 결과 리턴 확인2
	 * @author - 원종필(won0334@chol.com)
	 * @history
	 * @return NULL
	 * */
	function find_id_result()
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
							
						if ($sCPRequest == $strCPRequest) 
						{
							$strUserName		= $arrData[2];	// 이름
							$strDupInfo = $arrData[3];// 중복가입 확인값 (64Byte 고유값)
							
							$this->db->from('users');
							$this->db->where('dup_data', $strDupInfo);
							$result = $this->db->get();
							if($result->num_rows() > 0)
							{
								$mem_info = $result->row_array();
								$sRtnMsg = '<img src="/images/member/img_findid_icon.jpg" alt="" />';
								$sRtnMsg .= '회원님의 아이디는 <span class="red">'.$mem_info['id'].'</span> 입니다.';
							}
							else
							{
								$sRtnMsg = '<img src="/images/member/img_findid_icon.jpg" alt="" />';
								$sRtnMsg .= '일치하는 회원 정보가 없습니다.';
							}
						} else {
							$sRtnMsg = "CP 요청번호 불일치 : 세션에 넣은 데이타를 확인해 주시기 바랍니다.";
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
		//레이아웃 처리(출력)
		if(MOBILE_USE == 'Y') $base_skin = 'mobile/'; else $base_skin = 'web/';
		if(defined('LANGUAGE') === TRUE) $base_skin .=  LANGUAGE.'/';		
		$this->load->view($base_skin.'users/find_result', $this->view_data);
	}	
	/**
	 * 이메일 찾기 결과 
	 * @author - 원종필(won0334@chol.com)
	 * @history
	 * @return NULL
	 * */
	function find_email_result()
	{	
		$name = $this->input->post('name');
		$email = $this->input->post('email1').'@'.$this->input->post('email2');
		
		$this->db->from('users');
		$this->db->where('name', $name);
		$this->db->where('email', $email);
		$result = $this->db->get();
		
		if($result->num_rows() > 0)
		{
			$mem_info = $result->row_array();
			$sRtnMsg = '<img src="/images/member/img_findid_icon.jpg" alt="" />';
			$sRtnMsg .= '회원님의 아이디는 <span class="red">'.$mem_info['id'].'</span> 입니다.';			
			echo json_encode(array('result'=>true, 'msg'=>$sRtnMsg));
		}
		else
		{
			$sRtnMsg = '<img src="/images/member/img_findid_icon.jpg" alt="" />';
			$sRtnMsg .= '일치하는 회원 정보가 없습니다.';
			echo json_encode(array('result'=>true, 'msg'=>$sRtnMsg));
		}
	}
	/**
	 * 패스워드 찾기
	 * @author - 원종필(won0334@chol.com)
	 * @history
	 * @return NULL
	 * */
	function find_pass()
	{
		$enc_data	= get_sms_code($this->config->item('base_url').'users/find_pass_sms', $this->config->item('base_url').'users/find_pass_sms_fail', MOBILE_USE == 'Y'?'Mobile':'');
		
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
				
		$sEncData	= get_ipin_code($this->config->item('base_url').'users/find_pass_ipin');
		
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
		$this->view_data['subviisual_file_name'] = $base_skin.'common/regist_sub';
		$this->view_data['left_file_name'] = $base_skin.'common/regist_left';
		$this->view_data['head_file_name'] = $base_skin.'users/find_head';
		$this->view_data['main_file_name'] = $base_skin.'users/find_pass';
		$this->view_data['index_file_name'] = $base_skin.'index/index';
		$this->view_data['default_head_file_name'] = $base_skin.'index/default_head';
		$this->view_data['page_name'] = 'find_page';
		$this->load->view($this->view_data['index_file_name'], $this->view_data);
	}	
	/**
	 * sms 결과 리턴 확인 - 성공시
	 * @author - 원종필(won0334@chol.com)
	 * @history
	 * @return NULL
	 * */
	function find_pass_sms()
	{
		$sRtnMsg = '';
		$redirect_url = '';
		$sitecode = $this->config->item('sms_site_code');					// NICE로부터 부여받은 사이트 코드
		$sitepasswd = $this->config->item('sms_site_pass');				// NICE로부터 부여받은 사이트 패스워드
		$cb_encode_path = $this->config->item('sms_module_pass');		// NICE로부터 받은 암호화 프로그램의 위치 (절대경로+모듈명)
	
		$enc_data = $this->input->post('EncodeData');		// 암호화된 결과 데이타
		//////////////////////////////////////////////// 문자열 점검///////////////////////////////////////////////
		if(preg_match('~[^0-9a-zA-Z+/=]~', $enc_data, $match)) {$sRtnMsg = '입력 값 확인이 필요합니다 : '.$match[0]; } // 문자열 점검 추가.
		if(base64_encode(base64_decode($enc_data))!=$enc_data) {$sRtnMsg = '입력 값 확인이 필요합니다'; }
	
		if ($enc_data != '' && $sRtnMsg == '')
		{
			$plaindata = `$cb_encode_path DEC $sitecode $sitepasswd $enc_data`;		// 암호화된 결과 데이터의 복호화
	
			if ($plaindata == -1){
				$sRtnMsg  = '암/복호화 시스템 오류';
			}else if ($plaindata == -4){
				$sRtnMsg  = '복호화 처리 오류';
			}else if ($plaindata == -5){
				$sRtnMsg  = 'HASH값 불일치 - 복호화 데이터는 리턴됨';
			}else if ($plaindata == -6){
				$sRtnMsg  = '복호화 데이터 오류';
			}else if ($plaindata == -9){
				$sRtnMsg  = '입력값 오류';
			}else if ($plaindata == -12){
				$sRtnMsg  = '사이트 비밀번호 오류';
			}else{
				// 복호화가 정상적일 경우 데이터를 파싱합니다.
				$dupinfo = GetValue($plaindata , "DI");
					
				$this->db->from('users');
				$this->db->where('dup_data', $dupinfo);
				$result = $this->db->get();
				if($result->num_rows() > 0)
				{
					$redirect_url = '/users/ipin_pass_form';
					$this->session->set_flashdata('enc_code', $dupinfo);
				}
				else
				{
					$sRtnMsg = '일치하는 회원이 없습니다.';
					$redirect_url = '';
				}
			}
		}

		$this->view_data['redirect_url'] = $redirect_url;
		$this->view_data['sRtnMsg'] = $sRtnMsg;
		//레이아웃 처리(출력)
		if(MOBILE_USE == 'Y') $base_skin = 'mobile/'; else $base_skin = 'web/';
		if(defined('LANGUAGE') === TRUE) $base_skin .=  LANGUAGE.'/';
		$this->load->view($base_skin.'users/find_pass_sms_result', $this->view_data);
	}
	/**
	 * sms 결과 리턴 확인 - 실패시
	 * @author - 원종필(won0334@chol.com)
	 * @history
	 * @return NULL
	 * */
	function find_pass_sms_fail()
	{
		$sRtnMsg = 'sms 인증이 실패하였습니다.';
		$sitecode = $this->config->item('sms_site_code');					// NICE로부터 부여받은 사이트 코드
		$sitepasswd = $this->config->item('sms_site_pass');				// NICE로부터 부여받은 사이트 패스워드
		$cb_encode_path = $this->config->item('sms_module_pass');		// NICE로부터 받은 암호화 프로그램의 위치 (절대경로+모듈명)
	
		$enc_data = $this->input->post('EncodeData');		// 암호화된 결과 데이타
		//////////////////////////////////////////////// 문자열 점검///////////////////////////////////////////////
		if(preg_match('~[^0-9a-zA-Z+/=]~', $enc_data, $match)) {$sRtnMsg = '입력 값 확인이 필요합니다 : '.$match[0]; } // 문자열 점검 추가.
		if(base64_encode(base64_decode($enc_data))!=$enc_data) {$sRtnMsg = '입력 값 확인이 필요합니다'; }
	
		if ($enc_data != "")
		{
			$plaindata = `$cb_encode_path DEC $sitecode $sitepasswd $enc_data`;		// 암호화된 결과 데이터의 복호화
	
			if ($plaindata == -1){
				$sRtnMsg  = '암/복호화 시스템 오류';
			}else if ($plaindata == -4){
				$sRtnMsg  = '복호화 처리 오류';
			}else if ($plaindata == -5){
				$sRtnMsg  = 'HASH값 불일치 - 복호화 데이터는 리턴됨';
			}else if ($plaindata == -6){
				$sRtnMsg  = '복호화 데이터 오류';
			}else if ($plaindata == -9){
				$sRtnMsg  = '입력값 오류';
			}else if ($plaindata == -12){
				$sRtnMsg  = '사이트 비밀번호 오류';
			}else{
				$requestnumber = GetValue($plaindata , "REQ_SEQ");
				$errcode = GetValue($plaindata , "ERR_CODE");
				$authtype = GetValue($plaindata , "AUTH_TYPE");
			}
		}
			
		$this->view_data['sRtnMsg'] = $sRtnMsg.'['.$errcode.']';
		//레이아웃 처리(출력)
		if(MOBILE_USE == 'Y') $base_skin = 'mobile/'; else $base_skin = 'web/';
		if(defined('LANGUAGE') === TRUE) $base_skin .=  LANGUAGE.'/';
		$this->load->view($base_skin.'users/find_pass_sms_result', $this->view_data);
	}
	/**
	 * 패스워드 아이핀 결과 리턴 확인
	 * @author - 원종필(won0334@chol.com)
	 * @history
	 * @return NULL
	 * */
	function find_pass_ipin()
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
							
						if ($sCPRequest == $strCPRequest) 
						{
							$strUserName		= $arrData[2];	// 이름
							$strDupInfo = $arrData[3];// 중복가입 확인값 (64Byte 고유값)
							
							$this->db->from('users');
							$this->db->where('dup_data', $strDupInfo);
							$result = $this->db->get();
							if($result->num_rows() > 0)
							{
								$redirect_url = '/users/ipin_pass_form';
								$this->session->set_flashdata('enc_code', $strDupInfo);
							}
							else
							{
								$sRtnMsg = '일치하는 회원이 없습니다.';
								$redirect_url = '';
							}
						} else {
							$sRtnMsg = "CP 요청번호 불일치 : 세션에 넣은 데이타를 확인해 주시기 바랍니다.";
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
		$this->load->view($base_skin.'users/find_pass_ipin', $this->view_data);
	}	
	/**
	 * 이메일로 패스워드 찾기 결과 처리
	 * @author - 원종필(won0334@chol.com)
	 * @history
	 * @return NULL
	 * */
	function find_pass_email_result()
	{
		$name = $this->input->post('name');
		$email = $this->input->post('email1').'@'.$this->input->post('email2');
		
		$this->db->from('users');
		$this->db->where('name', $name);
		$this->db->where('email', $email);
		$result = $this->db->get();
		
		if($result->num_rows() > 0)
		{
			$mem_info = $result->row_array();
			$email_config['wordwrap'] = TRUE;
			$email_config['mailtype'] = 'html';
			$email_config['useragent'] = 'EDIYA';
			
			$this->load->library('email');
			$this->email->initialize($email_config);
			
			if(MOBILE_USE == 'Y') $base_skin = 'mobile/'; else $base_skin = 'web/';
			if(defined('LANGUAGE') === TRUE) $base_skin .=  LANGUAGE.'/';	
			$admin_email = json_decode(get_sitemeta('admin_email'));
			$admin_name = json_decode(get_sitemeta('admin_name'));
			
			$enc_code = hash('sha256', $mem_info['id'].time());
			set_sitemeta('email_pass_enc_code_'.$mem_info['id'], $enc_code);
			$subject = '['.$email_config['useragent'].'] 패스워드 찾기 결과입니다.';
			$contents = $this->load->view($base_skin.'common/email', '', TRUE);
			$contents = str_replace('{{url}}', $this->config->item('base_url').'users/email_pass_form/'.$enc_code, $contents);
			$contents = str_replace('{{home_url}}', $this->config->item('base_url'), $contents);
			
			$this->email->from($admin_email[0], $admin_name[0]);
			$this->email->to($email);
			$this->email->subject($subject);
			$this->email->message($contents);
			$email_result = $this->email->send();
			log_message('debug', '패스워드 찾기 이메일 전송 결과 '. $email_result);
						
			if($email_result === TRUE)
			{
				alert('메일이 발송되었습니다.', '/users/find_pass', TRUE);
			}
			else
			{
				alert('메일이 발송이 실패하였습니다. 관리자에게 문의 하세요.', '/users/find_pass', TRUE);
			}
		}
		else
		{
			alert('일치하는 회원 정보가 없습니다.', '', TRUE);
		}
		
	}
	/**
	 * 이메일을 통한 패스워드 변경 폼
	 * @author - 원종필(won0334@chol.com)
	 * @history
	 * @return NULL
	 * */	
	function email_pass_form()
	{
		$enc_code = $this->uri->segment(3);
		$this->db->from('site_meta');
		$this->db->where('meta_value', $enc_code);
		$result = $this->db->get();
		
		if($result->num_rows() > 0)
		{
			//레이아웃 처리(출력)
			if(MOBILE_USE == 'Y') $base_skin = 'mobile/'; else $base_skin = 'web/';
			if(defined('LANGUAGE') === TRUE) $base_skin .=  LANGUAGE.'/';
			$this->view_data['subviisual_file_name'] = $base_skin.'common/regist_sub';
			$this->view_data['left_file_name'] = $base_skin.'common/regist_left';
			$this->view_data['head_file_name'] = $base_skin.'users/pass_change_pass_head';
			$this->view_data['main_file_name'] = $base_skin.'users/update_pass_form';
			$this->view_data['index_file_name'] = $base_skin.'index/index';
			$this->view_data['default_head_file_name'] = $base_skin.'index/default_head';
			$this->view_data['enc_code'] = $enc_code;
			$this->view_data['next_url'] = 'email_pass_update';
			$this->view_data['page_name'] = 'find_page';
			$this->load->view($this->view_data['index_file_name'], $this->view_data);
		}
		else
		{
			alert('인증 정보가 잘못되었습니다.', '/', TRUE);
		}
	}
	/**
	 * 이메일을 통한 패스워드 최종 변경
	 * @author - 원종필(won0334@chol.com)
	 * @history
	 * @return NULL
	 * */	
	function email_pass_update()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('passwd', '패스워드', 'required|min_length[8]');
		$this->form_validation->set_rules('enc_code', '암호화값', 'required');
		
		if ($this->form_validation->run() === FALSE)
		{
			alert('입력정보가 올바르지 않습니다.', '', TRUE);
		}
				
		$enc_code = $this->input->post('enc_code');
		$passwd = $this->input->post('passwd');
		$input_pass = hash('sha256', $passwd);
		
		$this->db->from('site_meta');
		$this->db->where('meta_value', $enc_code);
		$result = $this->db->get()->row_array();
		$id = end(explode('_', $result['meta_key']));
		
		$this->db->from('users');
		$this->db->where('id', $id);
		$this->db->set('passwd', $input_pass);
		$result = $this->db->update();
		
		$this->db->from('site_meta');
		$this->db->where('meta_value', $enc_code);
		$this->db->delete();

		if($result === TRUE)
		{
			alert('변경되었습니다.', '/');
		}
		else 
		{
		alert('변경에 문제가 있습니다. 관리자에게 문의 하세요', '/');
		}
	}
	/**
	 * 아이핀을 통한 패스워드 변경 폼
	 * @author - 원종필(won0334@chol.com)
	 * @history
	 * @return NULL
	 * */
	function ipin_pass_form()
	{
		$enc_code = $this->session->flashdata('enc_code');
		if($enc_code === FALSE OR empty($enc_code) === TRUE)
		{
			alert('암호화 정보가 전달되지 않았습니다.', '/users/find_pass' ,TRUE);
		}
		$this->session->set_flashdata('enc_code', $enc_code);
		//레이아웃 처리(출력)
		if(MOBILE_USE == 'Y') $base_skin = 'mobile/'; else $base_skin = 'web/';
		if(defined('LANGUAGE') === TRUE) $base_skin .=  LANGUAGE.'/';
		$this->view_data['subviisual_file_name'] = $base_skin.'common/regist_sub';
		$this->view_data['left_file_name'] = $base_skin.'common/regist_left';
		$this->view_data['head_file_name'] = $base_skin.'users/pass_change_pass_head';
		$this->view_data['main_file_name'] = $base_skin.'users/update_pass_form';
		$this->view_data['index_file_name'] = $base_skin.'index/index';
		$this->view_data['default_head_file_name'] = $base_skin.'index/default_head';
		$this->view_data['next_url'] = 'ipin_pass_update';
		$this->view_data['page_name'] = 'find_page';
		$this->load->view($this->view_data['index_file_name'], $this->view_data);
	}
	/**
	 * 이메일을 통한 패스워드 최종 변경
	 * @author - 원종필(won0334@chol.com)
	 * @history
	 * @return NULL
	 * */
	function ipin_pass_update()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('passwd', '패스워드', 'required|min_length[8]');
	
		if ($this->form_validation->run() === FALSE)
		{
			alert('입력정보가 올바르지 않습니다.', '', TRUE);
		}
	
		$enc_code = $this->session->flashdata('enc_code');
		if($enc_code === FALSE OR empty($enc_code) === TRUE)
		{
			alert('암호화 정보가 전달되지 않았습니다.', '/users/find_pass' ,TRUE);
		}
		$passwd = $this->input->post('passwd');
		$input_pass = hash('sha256', $passwd);
	
		$this->db->from('users');
		$this->db->where('dup_data', $enc_code);
		$this->db->set('passwd', $input_pass);
		$result = $this->db->update();
		
		if($result === TRUE)
		{
			alert('변경되었습니다.', '/');
		}
		else
		{
			alert('변경에 문제가 있습니다. 관리자에게 문의 하세요', '/');
		}
	}	
}