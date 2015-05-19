<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @title 게시판 - 복권 이벤트 게시판 클래스
 * @author - 원종필(won0334@chol.com)
 * @history
 * */
class CI_Board_drv_rbt extends CI_Driver 
{
	public $board_code = '';//게시판 구분 코드
	public $board_name = '';//게시판명
	public $board_type = '';//게시판 형식
	public $board_skin = 'default';//게시판 스킨
	public $board_table_name = '';//테이블명
	public $etc = '';//게시판 키타 설정
	public $product_info = array('꽝', '500COIN 적립', '100COIN 적립', '이디야 아메리카노 교환권');
	public $product_img = array('/images/event/fail.png', '/images/event/500coin.png', '/images/event/100coin.png', '/images/event/america.png');
	public $time_table = array(
			array(64, 64, 64, 64, 64, 64, 72),
			array(14, 14, 14, 14, 14, 14, 16),
			array(42, 42, 42, 42, 42, 42, 48),
			array(7, 7, 7, 7, 7, 7, 8)
	);
		
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
		if( empty($this->board_table_name) === TRUE ) return FALSE;
		//여기서부터 실제 데이터를 가져오는 처리
		$this->db->select('SQL_CALC_FOUND_ROWS a.*, b.name, b.id, b.tel', FALSE);
		$this->db->join('users b', 'a.mem_idx = b.idx');
		$this->db->from($this->board_table_name.' a');//나중에 join을 할 수도 있으므로 별칭으로 관리한다.
		if( isset($parm['search_key']) === TRUE && empty($parm['search_key']) === FALSE && isset($parm['search_val']) === TRUE && empty($parm['search_val']) === FALSE )
		{
			switch($parm['search_key'])
			{
				case 'title':
					$this->db->like('a.title', $parm['search_val']);
				break;
				
				case 'contents':
					$this->db->like('a.contents', $parm['search_val']);
				break;
				
				case 'all':
					$search_string = '(a.title like \'%'.$parm['search_val'].'%\' and a.contents like \'%'.$parm['search_val'].'%\')';
					$this->db->where($search_string);
				break;
			}
		}
		if( isset($parm['user_idx']) === TRUE && empty($parm['user_idx']) === FALSE ) $this->db->where('event_app.user_idx', $parm['user_idx']);
		if( isset($parm['per_page']) === TRUE && empty($parm['per_page']) === FALSE && isset($parm['off_set']) === TRUE  ) $this->db->limit($parm['per_page'], $parm['off_set']);
		
		if( isset($parm['start_date']) === TRUE && empty($parm['start_date']) === FALSE && sset($parm['end_date']) === TRUE && empty($parm['end_date']) === FALSE)
		{
			$in_date = 'date_format(reg_date, \'%Y-%m-%d\') >= \''.$parm['start_date'].'\' and date_format(reg_date, \'%Y-%m-%d\') <= \''.$parm['end_date'].'\'';
			$this->db->where($in_date);
		}
		
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
	
		if($this->_debug == 'Y' ) $this->add_error($this->board_table_name.$parm['type'].'_select_query', $this->db->last_query(), 'debug');
	
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
}
// End Class