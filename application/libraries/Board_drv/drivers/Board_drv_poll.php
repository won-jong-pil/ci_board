<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @title 게시판 - 설문조사 관리
 * @author - 원종필(won0334@chol.com)
 * @history
 * */
class CI_Board_drv_poll extends CI_Driver 
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
	 * @param 
	 * @return boolean $result['result'] : insert 결과 ( true: 성공 false : 실패)
	 */
	public function insert()
	{
    	$data['title'] = $this->input->post('title');
    	$data['contents'] = $this->input->post('contents');
    	$data['poll_use'] = $this->input->post('poll_use');
    	$data['service_type'] = $this->input->post('service_type');
    	if($data['service_type'] == 'P') $data['contents_idx'] = $this->input->post('contents_idx');
    	$data['reg_date'] = date('Y-m-d H:i:s');

		$result['result'] = $this->db->insert($this->board_table_name, $data);
		$result['insert_idx'] = $this->db->insert_id();
		
		if($result['result'] === FALSE)
		{
			$this->add_error($this->board_table_name.'_regist_error', '게시물 등록이 실패 하였습니다.');
			$this->add_error($this->board_table_name.'_regist_query', $this->db->last_query());
		}	
		
		if($this->_debug == 'Y')
		{
			$this->add_error($this->board_table_name.'_insert_result', $result['result'], 'debug');
			$this->add_error($this->board_table_name.'_insert_data', json_encode($data), 'debug');
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
    	$data['title'] = $this->input->post('title');
    	$data['contents'] = $this->input->post('contents');
    	$data['poll_use'] = $this->input->post('poll_use');
    	$data['service_type'] = $this->input->post('service_type');
    	if($data['service_type'] == 'P') $data['contents_idx'] = $this->input->post('contents_idx');
    	$data['reg_date'] = date('Y-m-d H:i:s');
    	
		$this->db->where('idx', $idx);
		$result['result'] = $this->db->update($this->board_table_name, $data);
		
		if($result['result'] === FALSE)
		{
			$this->add_error($this->board_table_name.'_update_error', '게시물 수정이 실패 하였습니다.');
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
		}
		else
		{
			$this->db->where('idx', $idx);
			$result['result'] = $this->db->delete($this->board_table_name);

			if($result['result'] === FALSE)
			{
				$this->add_error($this->board_table_name.'_delete_error', '게시물 삭제가 실패 하였습니다.');
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
		$this->db->select('SQL_CALC_FOUND_ROWS a.*, b.cate', FALSE);
		$this->db->from($this->board_table_name.' a');
		$this->db->join('board_contents b', 'b.idx = a.contents_idx', 'left');
		
		if( isset($parm['search_val']) === TRUE && empty($parm['search_val']) === FALSE ) $this->db->like('a.title', $parm['search_val']);
		if( isset($parm['poll_use']) === TRUE && empty($parm['poll_use']) === FALSE) $this->db->where('a.poll_use', $parm['poll_use']);
		if( isset($parm['per_page']) === TRUE && empty($parm['per_page']) === FALSE && isset($parm['off_set']) === TRUE  ) $this->db->limit($parm['per_page'], $parm['off_set']);
		if( isset($parm['cate']) === TRUE && empty($parm['cate']) === FALSE ) $this->db->where('cate', $parm['cate']);
		if( isset($parm['order_by']) === TRUE && empty($parm['order_by']) === FALSE)
		{
			if( isset($parm['order_type']) === FALSE OR empty($parm['order_type']) === TRUE ) $parm['order_type'] = 'desc';
			$this->db->order_by($parm['order_by'], $parm['order_type']);
		}
		else
		{
			$this->db->order_by('a.reg_date', 'desc');
		}
		
		if( isset($parm['relation']) === TRUE && empty($parm['relation']) === FALSE)
		{
			$this->db->join('movie_relation', 'a.idx = movie_relation.movie_idx');
			$this->db->join('users', 'users.idx = movie_relation.user_idx');
		}		
		
		$result['result'] = $this->db->get()->result_array();

		if($this->_debug == 'Y' ) $this->add_error($this->board_table_name.'_select_query', $this->db->last_query(), 'debug');
	
		$this->db->select('FOUND_ROWS() cnt', false);
		$result['record_count'] = $this->db->get()->row(1)->cnt;
	
		//게시판 기본 정보 리턴
		if( isset($parm['vnum_use']) === TRUE && $parm['vnum_use'] == 'Y' && isset($parm['per_page']) === TRUE && empty($parm['per_page']) === FALSE )
		{
			if($result['record_count'] > $parm['per_page'])
			{//출력시 가상번호로
				$result['vnum'] = $result['record_count'] - ($parm['page']-1) * $parm['per_page'];
			}else{
				$result['vnum'] = $result['record_count'];
			}
		}

		if( $this->_debug == 'Y' )
		{
			$this->add_error($this->board_table_name.'_select_data', @json_encode($parm), 'debug');
			$this->add_error($this->board_table_name.'_select_query', $this->db->last_query(), 'debug');
		}
	
		return $result;
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
		$this->db->select('a.*, b.cate', FALSE);
		$this->db->where('a.idx', $idx);
		$this->db->from($this->board_table_name.' a');//나중에 join을 할 수도 있으므로 별칭으로 관리한다.
		$this->db->join('board_contents b', 'a.contents_idx = b.idx', 'left');		
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
		$this->db->order_by('a.reg_date', 'desc');
		$this->db->limit($count);
		
		$result = $this->db->get()->result_array();
		
		if( $this->_debug == 'Y' ) $this->add_error($this->board_table_name.'_get_top_query', $this->db->last_query(), 'debug');
		
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
		$this->db->set('hit', 'hit+1', FALSE);
		$this->db->where('idx', $idx);
		$result['result'] = $this->db->update($this->board_table_name);

		return $result;
	}	
	/**
	 * 게시판 - 다음 게시물 조회
	 * @author - 원종필(won0334@chol.com)
	 * 작업내역 :
	 * @return NULL
	 * */
	public function get_next($idx)
	{
		$this->db->from($this->board_table_name);
		$this->db->where('idx >', $idx);
		$this->db->limit(1);
		$this->db->order_by('idx', 'desc');
		$result = $this->db->get()->row_array();
		
		return $result;
	}
	/**
	 * 게시판 - 이전 게시물 조회
	 * @author - 원종필(won0334@chol.com)
	 * 작업내역 :
	 * @return NULL
	 * */
	public function get_prev($idx)
	{
		$this->db->from($this->board_table_name.' a');
		$this->db->where('a.idx <', $idx);
		$this->db->limit(1);
		$this->db->order_by('a.idx', 'desc');
		$result = $this->db->get()->row_array();
		
		return $result;
	}	
}
// End Class