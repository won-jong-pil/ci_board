<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @title 게시판 - 포인트
 * @author - 원종필(won0334@chol.com)
 * @history
 * */
class CI_Board_drv_point extends CI_Driver 
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
	 * 
	 * @author 원종필(won0334@chol.com)
	 * @history
	 * @param
	 * @return boolean $result['result'] : insert 결과 ( true: 성공 false : 실패)
	 */
	public function insert()
	{
		return FALSE;
	}
	/**
	 * 
	 * @author 원종필(won0334@chol.com)
	 * @history $config는 controller 에서 보내는 설정 정보인데 아직 사용 목적은 없음
	 * @history 
	 * @param (int)$idx : 사용자 일련번호
	 * @param (array)$config : 게시판 설정값
	 * @return NULL
	 */	
	public function update($idx, $config = array())
	{
		return FALSE;
	}
	/**
	 * 
	 * @author 원종필(won0334@chol.com)
	 * @history
	 * @param (int)$idx : 사용자 일련번호
	 * @param (array)$config : 게시판 설정값
	 * @return NULL
	 */
	public function delete($idx, $config)
	{
		return FALSE;
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
		$this->db->where('user_idx', $parm['user_idx']);
		if( isset($parm['per_page']) === TRUE && empty($parm['per_page']) === FALSE && isset($parm['off_set']) === TRUE  ) $this->db->limit($parm['per_page'], $parm['off_set']);
		$this->db->order_by('a.idx', 'desc');
		
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
	 * 
	 * @author 원종필(won0334@chol.com)
	 * @history
	 * @param (int)$idx : 사용자 일련번호
	 * @return NULL
	 */
	public function get_data($idx)
	{
		return FALSE;
	}
	/**
	 * 
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
	 *
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
	 *
	 * @author - 원종필(won0334@chol.com)
	 * 작업내역 :
	 * @return NULL
	 * */
	public function get_next($idx)
	{
		return FALSE;
	}
	/**
	 * 
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