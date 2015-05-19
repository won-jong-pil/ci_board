<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @title 게시판 - 회원 관리 클래스
 * @author - 원종필(won0334@chol.com)
 * @history
 * */
class CI_Board_drv_users extends CI_Driver 
{
	public $board_code = '';//게시판 구분 코드
	public $board_name = '';//게시판명
	public $board_type = '';//게시판 형식
	public $board_skin = 'default';//게시판 스킨
	public $board_table_name = '';//테이블명
	public $etc = '';//게시판 기타 설정
	
	public function __construct()
	{
	}
	/**
	 * @title 게시판 - 게시판 초기화 처리
	 * @author 원종필(won0334@chol.com)
	 * @history
	 * @param (array)$config : 게시판 설정값
	 * @return NULL
	 */	
	public function initialize($config)
	{
		foreach ($config as $key=>$value)
		{
			if (isset($config[$key]) === TRUE && empty($config[$key]) === FALSE && property_exists($this, $key) === TRUE)
			{
				$this->{$key} = $config[$key];
			}
		}
	}
	/**
	 * @title 게시판 기본 속성 정보 가져오기
	 * @author 원종필(won0334@chol.com)
	 * @history
	 * @param (string)$attr_name : 속성명 - 다중 속성 설정시에는 '.'로 구분
	 * @return NULL
	 */
	public function get_attr($attr_name)
	{
		$attr = FALSE;//속성값이 없을 경우 FALSE를 리턴하기 위함.
		$temp = explode('.', $attr_name);
		if(sizeof($temp) > 1)
		{
			foreach($temp as $key=>$list)
			{
				if($key == 0) $attr = $this->$list;
				elseif(isset($attr[$list]) === TRUE) $attr = $attr[$list];
			}
			
			return $attr;
		}
		else
		{
			if(isset($this->{$attr_name}) === TRUE)
				return $this->{$attr_name};
			else return FALSE;
		}
	}	
	/**
	 * @title 게시판 기본 속성 정보 설정하기
	 * @author 원종필(won0334@chol.com)
	 * @history
	 * @param (string)$attr_name : 속성명
	 * @param (복합)$attr_value : 속성값
	 * @return NULL
	 */
	public function set_attr($attr_name, $attr_value)
	{
		$this->{$attr_name} = $attr_value;
	}	
	/**
	 * @title 사용자 삽입
	 * @author 원종필(won0334@chol.com)
	 * @history
	 * @param
	 * @return boolean $result['result'] : insert 결과 ( true: 성공 false : 실패)
	 */
	public function insert()
	{
		$this->CI->load->library('form_validation');
		$this->CI->form_validation->set_rules('id', '아이디', 'required|min_length[6]|max_length[20]');
		$this->CI->form_validation->set_rules('passwd', '패스워드', 'required|min_length[8]|max_length[30]');
		$this->CI->form_validation->set_rules('name', '이름', 'required');
		
		if ($this->CI->form_validation->run() === FALSE)
		{
			alert('입력정보가 올바르지 않습니다.', '', TRUE);
		}
		
		$tel = $this->input->post('tel1').'-'.$this->input->post('tel2').'-'.$this->input->post('tel3');
		$email = $this->input->post('email1').'@'.$this->input->post('email2');
		$email_check = $this->input->post('email_check');
		$sms_check = $this->input->post('sms_check');
		if($email_check == FALSE) $email_check = 'N';
		if($sms_check == FALSE) $sms_check = 'N';
		
		$data['email_check'] = $email_check;
		$data['sms_check'] = $sms_check;
		$data['name'] = $this->input->post('name');
		$data['id'] = $this->input->post('id');
		$data['passwd'] = hash('sha256', $this->input->post('passwd'));
		$data['tel'] = $tel;
		$data['email'] = $email;
		$data['reg_date'] = date('Y-m-d H:i:s');
		$data['pass_update_date'] = $data['reg_date'];
		if($this->CI->session->userdata('ipin_dup_data') != FALSE) $data['dup_data'] = $this->CI->session->userdata('ipin_dup_data');
		if($this->CI->session->userdata('sms_dup_data') != FALSE) $data['dup_data'] = $this->CI->session->userdata('sms_dup_data');
		
		$this->db->from($this->board_table_name);
		$this->db->where('id', $data['id']);
		$user_old = $this->db->get();
		if($user_old->num_rows() > 0) alert('이미 등록된 아이디 입니다.', '', TRUE);
		
		$this->db->from($this->board_table_name);
		$this->db->where('email', $data['email']);
		$user_old = $this->db->get();
		if($user_old->num_rows() > 0) alert('이미 등록된 이메일 입니다.', '', TRUE);		
		
		$result['result'] = $this->db->insert($this->board_table_name, $data);
		$insert_idx = $this->db->insert_id();
		
		if($result['result'] === FALSE)
		{
			$this->add_error($this->board_table_name.'_regist_error', '사용자 등록이 실패 하였습니다.');
			$this->add_error($this->board_table_name.'_regist_query', $this->db->last_query());
		}

		if($this->_debug == 'Y')
		{
			$this->add_error($this->board_table_name.'_insert_result', $result['result'], 'debug');
			$this->add_error($this->board_table_name.'_insert_data', json_encode($data), 'debug');
			$this->add_error($this->board_table_name.'_insert_query', $this->db->last_query(), 'debug');
		}		
		
		$result['email'] = $data['email'];
		return $result;
	}
	/**
	 * @title 게시판 - 수정
	 * @author 원종필(won0334@chol.com)
	 * @history $config는 controller 에서 보내는 설정 정보인데 아직 사용 목적은 없음
	 * @history 
	 * @param (int)$idx : 사용자 일련번호
	 * @param (array)$config : 게시판 설정값
	 * @return NULL
	 */	
	public function update($idx, $config = array())
	{
		if(empty($idx) === TRUE) return FALSE;
		$tel = $this->input->post('tel1').'-'.$this->input->post('tel2').'-'.$this->input->post('tel3');
		$email = $this->input->post('email1').'@'.$this->input->post('email2');
		$id = $this->input->post('id');
		$passwd = $this->input->post('passwd');		
		$email_check = $this->input->post('email_check');
		$sms_check = $this->input->post('sms_check');
		if($email_check == FALSE) $email_check = 'N';
		if($sms_check == FALSE) $sms_check = 'N';
		
		$data['email_check'] = $email_check;
		$data['sms_check'] = $sms_check;	
		$data['tel'] = $tel;
		$data['email'] = $email;
		$data['pass_update_date'] = date('Y-m-d H:i:s');
		if(isset($config['edit_mode']) === TRUE && $config['edit_mode'] == 'Y')
		{
			$data['attempts'] = $this->input->post('attempts');
			$data['point'] = $this->input->post('point');
		}
		else
		{
			$data['attempts'] = 0;
		}
		
		if(isset($id) === TRUE && empty($id) === FALSE)  $data['id'] = $id;
		if(isset($passwd) === TRUE && empty($passwd) === FALSE) $data['passwd'] = hash('sha256', $passwd);
		
		$this->db->where('idx', $idx);
		$result['result'] = $this->db->update($this->board_table_name, $data);
		
		if($result['result'] === FALSE)
		{
			$this->add_error($this->board_table_name.'_update_error', '사용자 수정이 실패 하였습니다.');
			$this->add_error($this->board_table_name.'_update_query', $this->db->last_query());
		}		
		
		if($this->_debug == 'Y') 
		{
			$this->add_error($this->board_table_name.'_update_result', $result['result'], 'debug');
			$this->add_error($this->board_table_name.'_update_query', $this->db->last_query(), 'debug');
		}
		
		return $result;
	}
	/**
	 * @title 게시판 - 삭제
	 * @author 원종필(won0334@chol.com)
	 * @history
	 * @param (int)$idx : 사용자 일련번호
	 * @param (array)$config : 게시판 설정값
	 * @return NULL
	 */
	public function delete($idx, $config)
	{
		if(empty($idx) === TRUE)
		{
			$result['result'] = FALSE;
			$this->add_error($this->board_table_name.'_delete_idx_error', '일련번호가 전달 되지 않았습니다.');
		}
		else
		{			
			$this->db->where('idx', $idx);
			$result['result'] = $this->db->delete($this->board_table_name);
			
			$this->db->from('point_history');
			$this->db->where('user_idx', $idx);
			$this->db->delete();
			
			$this->db->from('event_app');
			$this->db->where('user_idx', $idx);
			$this->db->delete();
			
			$this->db->from('blue_coin_history');
			$this->db->where('user_idx', $idx);
			$this->db->delete();
			
			$this->db->from('comment');
			$this->db->where('writer', $idx);
			$this->db->delete();
		
			if($result['result'] === FALSE)
			{
				$this->add_error($this->board_table_name.'_delete_error', '사용자 삭제가 실패 하였습니다.');
				$this->add_error($this->board_table_name.'_delete_query', $this->db->last_query());
			}		
			
			if($this->_debug == 'Y') 
			{
				$this->add_error($this->board_table_name.'_delete_result', $result['result'], 'debug');
				$this->add_error($this->board_table_name.'_delete_query', $this->db->last_query(), 'debug');
			}
		}
		
		return $result;
	}
	/**
	 * @title 게시판 - 리스트
	 * @author 원종필(won0334@chol.com)
	 * @history
	 * @param (array)$parm : 리스트 설정값
	 * @return NULL
	 */
	public function get_list($parm)
	{
		if( empty($this->board_table_name) === TRUE ) return FALSE;
		if(isset($parm['per_page']) === TRUE && empty($parm['per_page']) === FALSE) $parm['off_set'] = ($parm['page']-1) * $parm['per_page'];
		//여기서부터 실제 데이터를 가져오는 처리
		$this->db->select('SQL_CALC_FOUND_ROWS a.*',	FALSE);
		$this->db->from($this->board_table_name.' a');
		if( isset($parm['search_key']) === TRUE && empty($parm['search_key']) === FALSE && isset($parm['search_val']) === TRUE && empty($parm['search_val']) === FALSE )
		{
			switch($parm['search_key'])
			{
				case "id":
					$this->db->like("a.id", $parm['search_val']);
					break;
		
				case "name":
					$this->db->like("a.name", $parm['search_val']);
					break;
			}
		}
		if( isset($parm['search_email']) === TRUE && empty($parm['search_email']) === FALSE) $this->db->where('id', $parm['search_email']);
		if( isset($parm['per_page']) === TRUE && empty($parm['per_page']) === FALSE && isset($parm['off_set']) === TRUE  ) $this->db->limit($parm['per_page'], $parm['off_set']);
		if( isset($parm['order_by']) === TRUE && empty($parm['order_by']) === FALSE)
		{
			if(is_array($parm['order_by']) === TRUE)
			{
				foreach($parm['order_by'] as $key=>$list)
				{
					$this->db->order_by($key, $list);
				}
			}
			else
			{
				if( isset($parm['order_type']) === FALSE OR empty($parm['order_type']) === TRUE ) $parm['order_type'] = 'desc';
				$this->db->order_by($parm['order_by'], $parm['order_type']);
			}
		}
		else
		{
			$this->db->order_by('a.idx', 'desc');
		}
		
		$return_result['result'] = $this->db->get()->result_array();
		
		if($this->_debug == 'Y' ) $this->add_error($this->board_table_name.'_select_query', $this->db->last_query(), 'debug');
		
		$this->db->select("FOUND_ROWS() cnt", false);
		$return_result['record_count'] = $this->db->get()->row(1)->cnt;
		
		//게시판 기본 정보 리턴
		if( isset($parm['vnum_use']) === TRUE && $parm['vnum_use'] == 'Y' && isset($parm['per_page']) === TRUE && empty($parm['per_page']) === FALSE )
		{
			if($return_result['record_count'] > $parm['per_page'])
			{//출력시 가상번호로
				$return_result['vnum'] = $return_result['record_count'] - ($parm['page']-1) * $parm['per_page'];
			}else{
				$return_result['vnum'] = $return_result['record_count'];
			}
		}

		if( $this->_debug == 'Y' )
		{
			$this->add_error($this->board_table_name.'_select_data', json_encode($parm), 'debug');
			$this->add_error($this->board_table_name.'_select_query', $this->db->last_query(), 'debug');
		}

		return $return_result;
	}
	/**
	 * @title 게시판 - 단일 사용자 정보 기져오기
	 * @author 원종필(won0334@chol.com)
	 * @history
	 * @param (int)$idx : 사용자 일련번호
	 * @return NULL
	 */
	public function get_data($idx)
	{
		$this->db->where('a.idx', $idx);
		$this->db->from($this->board_table_name.' a');//나중에 join을 할 수도 있으므로 별칭으로 관리한다.
		$result['result'] = $this->db->get()->row_array();
		if(isset($result['result']['tel']) === TRUE) $result['result']['stel'] = explode('-', $result['result']['tel']); else $result['result']['stel'] = array('', '', '');
		if(isset($result['result']['email']) === TRUE) $result['result']['semail'] = explode('@', $result['result']['email']);  else $result['result']['semail'] = array('', '', '');
		
		if( $this->_debug == 'Y' )
		{
			$this->add_error($this->board_table_name.'_getdata_idx', $idx, 'debug');
			$this->add_error($this->board_table_name.'_getdata_query', $this->db->last_query(), 'debug');
		}		
		
		return $result;
	}
	/**
	 * @title 게시판 최근 데이터 가져오기
	 * @author 원종필(won0334@chol.com)
	 * @history 메인에서 최근 사용자을 가져오고자 할때 사용
	 * @param (int)$count : 사용자 갯수
	 * @return NULL
	 */
	public function get_top_data($parm = array())
	{
		return FALSE;
	}	
	/**
	 * @title 게시판 - 조회수 증가
	 * @author 원종필(won0334@chol.com)
	 * @history
	 * @param (int)$idx : 사용자 일련번호
	 * @return NULL
	 */
	public function hit($idx)
	{
		return FALSE;
	}	
	/**
	 * 게시판 - 다음 사용자 조회
	 * @author - 원종필(won0334@chol.com)
	 * 작업내역 :
	 * @return NULL
	 * */
	public function get_next($idx)
	{
		return FALSE;
	}
	/**
	 * 게시판 - 이전 사용자 조회
	 * @author - 원종필(won0334@chol.com)
	 * 작업내역 :
	 * @return NULL
	 * */
	public function get_prev($idx)
	{
		return FALSE;
	}	
}
// End Class