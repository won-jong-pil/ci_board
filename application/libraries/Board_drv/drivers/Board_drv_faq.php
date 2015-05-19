<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @title 게시판 - 배너 게시판 클래스
 * @author - 원종필(won0334@chol.com)
 * @history
 * */
class CI_Board_drv_faq extends CI_Driver 
{
	public $board_code = '';//게시판 구분 코드
	public $board_name = '';//게시판명
	public $board_type = '';//게시판 형식
	public $board_skin = 'default';//게시판 스킨
	public $board_table_name = '';//테이블명
	public $etc = '';//게시판 키타 설정
	
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
	 * @param (string)$attr_name : 속성명 - 다중 속성 설정시에는 ','로 구분
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
	 * @title 게시판
	 * @author 원종필(won0334@chol.com)
	 * @history
	 * @param (array)$config : 게시판 설정값
	 * @return boolean $result['result'] : insert 결과 ( true: 성공 false : 실패)
	 * @return string $result['error'] : 에러 메세지 
	 */
	public function insert($config)
	{
    	$data['question'] = $this->input->post('question');
    	$data['faq_cate'] = $this->input->post('faq_cate');
    	$data['answer'] = $this->input->post('answer');
    	$data['status'] = $this->input->post('status');
    	$data['reg_date'] = date('Y-m-d H:i:s');

		$result['result'] = $this->db->insert($this->board_table_name, $data);
		$insert_idx = $this->db->insert_id();
		
		if($result['result'] === FALSE)
		{
			$this->add_error($this->board_table_name.'_regist_error', 'faq 등록이 실패 하였습니다.');
			$this->add_error($this->board_table_name.'_regist_query', $this->db->last_query());
		}

		if($this->_debug == 'Y')
		{
			$this->add_error($this->board_table_name.'_insert_result', @json_encode($result), 'debug');
			$this->add_error($this->board_table_name.'_insert_data', @json_encode($data), 'debug');
			$this->add_error($this->board_table_name.'_insert_query', $this->db->last_query(), 'debug');
		}	
			
		return $result;
	}
	/**
	 * @title 게시판 - 수정
	 * @author 원종필(won0334@chol.com)
	 * @history
	 * @param (int)$idx : 게시물 일련번호
	 * @param (array)$config : 게시판 설정값
	 * @return NULL
	 */
	public function update($idx, $config)
	{
    	$data['question'] = $this->input->post('question');
    	$data['faq_cate'] = $this->input->post('faq_cate');
    	$data['answer'] = $this->input->post('answer');
    	$data['status'] = $this->input->post('status');
				
		$this->db->where('idx', $idx);
		$result['result'] = $this->db->update($this->board_table_name, $data);
		
		if($result['result'] === FALSE)
		{
			$this->add_error($this->board_table_name.'_update_error', '팦업 수정이 실패 하였습니다.');
			$this->add_error($this->board_table_name.'_update_query', $this->db->last_query());
		}
		
		if($this->_debug == 'Y')
		{
			$this->add_error($this->board_table_name.'_update_result', @json_encode($result['result']), 'debug');
			$this->add_error($this->board_table_name.'_update_query', $this->db->last_query(), 'debug');
		}
		
		return $result;
	}
	/**
	 * @title 게시판 - 삭제
	 * @author 원종필(won0334@chol.com)
	 * @history
	 * @param (int)$idx : 게시물 일련번호
	 * @param (array)$config : 게시판 설정값
	 * @return NULL
	 */
	public function delete($idx, $config)
	{
		if(empty($idx) === TRUE)
		{
			$result['result'] = FALSE;
			$this->add_error($this->board_table_name.'_delete_idx_error', '일련번호가 전달 되지 않았습니다.');
			return $result;
		}
		
		if(is_array($idx) === FALSE) $idx = array($idx);
		$this->db->where_in('idx', $idx);
		$result['result'] = $this->db->delete($this->board_table_name);
		
		if($result['result'] === FALSE)
		{
			$this->add_error('error', '게시판 정보 삭제에 실패 하였습니다.');
			log_message('error', $this->board_name.' 게시물 삭제에 실패하였습니다.\n'.$this->db->last_query().'\n');
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
		$last_query = '';//로그 데이터 기록을 위함.
		if( empty($this->board_table_name) === TRUE ) return FALSE;
		if(isset($parm['per_page']) === TRUE && empty($parm['per_page']) === FALSE) $parm['off_set'] = ($parm['page']-1) * $parm['per_page'];
		//여기서부터 실제 데이터를 가져오는 처리
		$this->db->select('SQL_CALC_FOUND_ROWS a.*',	FALSE);
		if( isset($parm['search_key']) === TRUE && empty($parm['search_key']) === FALSE && isset($parm['search_val']) === TRUE && empty($parm['search_val']) === FALSE )
		{
			switch($parm['search_key'])
			{
				case 'question':
					$this->db->like('a.question', $parm['search_val']);
				break;
				
				case 'answer':
					$this->db->like('a.answer', $parm['search_val']);
				break;
				
				case 'all':
					$this->db->like('a.answer', $parm['search_val']);
					$this->db->or_like('a.question', $parm['search_val']);
				break;
			}
		}

		if( isset($parm['view_flag']) === TRUE && empty($parm['view_flag']) === FALSE ) $this->db->where('view_flag', $parm['view_flag']);
		if( isset($parm['faq_cate']) === TRUE && empty($parm['faq_cate']) === FALSE ) $this->db->where('faq_cate', $parm['faq_cate']);
		if( isset($parm['per_page']) === TRUE && empty($parm['per_page']) === FALSE && isset($parm['off_set']) === TRUE  ) $this->db->limit($parm['per_page'], $parm['off_set']);
		if( isset($parm['status']) === TRUE && empty($parm['status']) === FALSE ) $this->db->where('a.status', $parm['status']);
		
		$this->db->from($this->board_table_name.' a');
	
		if( isset($parm['order_by']) === TRUE && empty($parm['order_by']) === FALSE)
		{
			if( isset($parm['order_type']) === FALSE OR empty($parm['order_type']) === TRUE ) $parm['order_type'] = 'desc';
			$this->db->order_by($parm['order_by'], $parm['order_type']);
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
	 * @title 게시판 - 단일 게시물 정보 기져오기
	 * @author 원종필(won0334@chol.com)
	 * @history
	 * @param (int)$idx : 게시물 일련번호
	 * @return NULL
	 */
	public function get_data($idx)
	{
		$this->db->where('a.idx', $idx);
		$this->db->from($this->board_table_name.' a');//나중에 join을 할 수도 있으므로 별칭으로 관리한다.
		$result['result'] = $this->db->get()->row_array();
		
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
	 * @history 메인에서 최근 게시물을 가져오고자 할때 사용
	 * @param (int)$count : 게시물 갯수
	 * @return NULL
	 */
	public function get_top_data($parm = array())
	{
		$this->db->select('a.*');
		$this->db->from($this->board_table_name.' a');//나중에 join을 할 수도 있으므로 별칭으로 관리한다.
		$this->db->where('a.status', 'Y');
		$this->db->order_by('a.reg_date', 'desc');
		if(isset($parm['count']) === TRUE && empty($parm['count']) === FALSE) $this->db->limit($parm['count']);
		$result = $this->db->get()->result_array();
		
		if( $this->_debug == 'Y' ) $this->add_error($this->board_table_name.'_gettopdata_query', $this->db->last_query(), 'debug');
		
		return $result;
	}	
	/**
	 * @title 게시판 - 조회수 증가
	 * @author 원종필(won0334@chol.com)
	 * @history
	 * @param (int)$idx : 게시물 일련번호
	 * @return NULL
	 */
	public function hit($idx)
	{
		return FALSE;
	}	
	/**
	 * @title 다음 게시물 조회
	 * @author - 원종필(won0334@chol.com)
	 * @history
	 * @return NULL
	 * */
	public function get_next($idx)
	{
		return FALSE;
	}
	/**
	 * @title 이전 게시물 조회
	 * @author - 원종필(won0334@chol.com)
	 * @history
	 * @return NULL
	 * */
	public function get_prev($idx)
	{
		return FALSE;
	}
}
// End Class