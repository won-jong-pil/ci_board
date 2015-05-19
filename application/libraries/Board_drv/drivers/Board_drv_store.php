<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @title 게시판 - 가맹점 관리 클래스
 * @author - 원종필(won0334@chol.com)
 * @history
 * */
class CI_Board_drv_store extends CI_Driver 
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
	 * @title 팦업 등록
	 * @author 원종필(won0334@chol.com)
	 * @history
	 * @param
	 * @return boolean $result['result'] : insert 결과 ( true: 성공 false : 실패)
	 */
	public function insert($config)
	{
    	return FALSE;
	}
	/**
	 * @title 팦업 - 수정
	 * @author 원종필(won0334@chol.com)
	 * @history $config는 controller 에서 보내는 설정 정보인데 아직 사용 목적은 없음
	 * @history 
	 * @param (int)$idx : 일련번호
	 * @param (array)$config : 게시판 설정값
	 * @return NULL
	 * */
	public function update($idx, $config)
	{
    	return FALSE;
	}
	/**
	 * @title 팦업 - 삭제
	 * @author 원종필(won0334@chol.com)
	 * @history
	 * @param (int)$idx : 일련번호
	 * @param (array)$config : 게시판 설정값
	 * @return NULL
	 * */
	public function delete($idx, $config)
	{
		return FALSE;	
	}
	/**
	 * @title 팦업 - 리스트
	 * @author 원종필(won0334@chol.com)
	 * @history
	 * @param (array)$parm : 리스트 설정값
	 * @return NULL
	 * */
	public function get_list($parm)
	{
		if( empty($this->board_table_name) === TRUE ) return FALSE;
	
		$sql = '(SELECT ROW_NUMBER() OVER(ORDER BY DT_OPEN DESC) AS NO, * FROM '.$this->board_table_name.' where 1=1 and CD_DIST <> \'\' and CD_CITY <> \'\'';
		$this->db->select('a.*, b.name sido, c.name gugun');
		$this->db->join('V_MA_CODEDTL_SA_F000019 b', 'a.CD_CITY = b.CODE');
		$this->db->join('V_MA_CODEDTL_SA_F000020 C', 'a.CD_DIST = c.CODE');
		//여기서부터 실제 데이터를 가져오는 처리
		if( isset($parm['search_key']) === TRUE && empty($parm['search_key']) === FALSE && isset($parm['search_val']) === TRUE && empty($parm['search_val']) === FALSE )
		{
			switch($parm['search_key'])
			{
				case 'all':
					$this->db->like('a.NM_PARTNER',  iconv('utf-8', 'euc-kr', $parm['search_val']));
					$this->db->or_like('a.ADS', iconv('utf-8', 'euc-kr', $parm['search_val']));
				break;
				
				case 'title':
					$this->db->like('a.title', iconv('utf-8', 'euc-kr', $parm['search_val']));
				break;
			}
		}
		
		if(isset($parm['per_page']) === TRUE && empty($parm['per_page']) === FALSE) $parm['off_set'] = ($parm['page']-1) * $parm['per_page'];
		if( isset($parm['status']) === TRUE && empty($parm['status']) === FALSE )
		{
			$this->db->where('USE_YN', $parm['status']);
			$sql = $sql .' and USE_YN = \''.$parm['status'].'\'';
		}
		if( isset($parm['sido']) === TRUE && empty($parm['sido']) === FALSE )
		{
			$this->db->where('CD_CITY', $parm['sido']);
			$sql = $sql .' and CD_CITY = '.$parm['sido'];
		}
		if( isset($parm['gugun']) === TRUE && empty($parm['gugun']) === FALSE )
		{
			$this->db->where('CD_DIST', $parm['gugun']);
			$sql = $sql .' and CD_DIST = '.$parm['gugun'];
		}
		if( isset($parm['shop_name']) === TRUE && empty($parm['shop_name']) === FALSE )
		{	
			$this->db->like('NM_PARTNER', iconv('utf-8', 'euc-kr', $parm['shop_name']));
			$sql = $sql .' and NM_PARTNER like \'%'.iconv('utf-8', 'euc-kr', $parm['shop_name']).'%\'';
		}
		if( isset($parm['theme']) === TRUE && empty($parm['theme']) === FALSE )
		{
			$this->db->where('theme'.$parm['theme'], 'Y');
			$sql = $sql .' and theme'.$parm['theme'].' = \'Y\'';
		}
		if( isset($parm['per_page']) === TRUE && empty($parm['per_page']) === FALSE && isset($parm['off_set']) === TRUE  ) $this->db->limit($parm['per_page']);
		
		$sql .= ') a';
		$this->db->from($sql);
	
		if( isset($parm['order_by']) === TRUE && empty($parm['order_by']) === FALSE)
		{
			if( isset($parm['order_type']) === FALSE OR empty($parm['order_type']) === TRUE ) $parm['order_type'] = 'desc';
			$this->db->order_by($parm['order_by'], $parm['order_type']);
		}
		else
		{
			$this->db->order_by('a.DT_OPEN', 'desc');
		$this->db->order_by('a.CODE', 'desc');
		}
		if(isset($parm['per_page']) === TRUE && empty($parm['per_page']) === FALSE) $this->db->where('NO > ', $parm['off_set']);
		$result['result'] = $this->db->get()->result_array();
log_message('error', $this->db->last_query());
		if($this->_debug == 'Y' ) $this->add_error($this->board_table_name.'_select_query', $this->db->last_query(), 'debug');
	
		//갯수 세기
		$this->db->select('count(*) cnt');
		if( isset($parm['search_key']) === TRUE && empty($parm['search_key']) === FALSE && isset($parm['search_val']) === TRUE && empty($parm['search_val']) === FALSE )
		{
			switch($parm['search_key'])
			{
				case 'all':
					$this->db->like('a.NM_PARTNER', iconv('utf-8', 'euc-kr', $parm['search_val']));
					$this->db->or_like('a.ADS', iconv('utf-8', 'euc-kr', $parm['search_val']));
				break;
		
				case 'title':
					$this->db->like('a.title', iconv('utf-8', 'euc-kr', $parm['search_val']));
				break;
			}
		}
	
		if( isset($parm['status']) === TRUE && empty($parm['status']) === FALSE ) $this->db->where('USE_YN', $parm['status']);
		if( isset($parm['YN_TA']) === TRUE && empty($parm['YN_TA']) === FALSE ) $this->db->where('YN_TA', $parm['YN_TA']);
		if( isset($parm['sido']) === TRUE && empty($parm['sido']) === FALSE ) $this->db->where('CD_CITY', $parm['sido']);
		if( isset($parm['gugun']) === TRUE && empty($parm['gugun']) === FALSE ) $this->db->where('CD_DIST', $parm['gugun']);
		if( isset($parm['shop_name']) === TRUE && empty($parm['shop_name']) === FALSE ) $this->db->like('NM_PARTNER', iconv('utf-8', 'euc-kr', $parm['shop_name']));
		if( isset($parm['theme']) === TRUE && empty($parm['theme']) === FALSE ) $this->db->where('theme'.$parm['theme'], 'Y');
		
		$this->db->from($this->board_table_name. ' a');
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
	
		if( $this->_debug == 'Y' )	$this->add_error($this->board_table_name.'_select_data', json_encode($parm), 'debug');
	
		return $result;
	}
	/**
	 * @title 게시판 - 단일 팦업 정보 기져오기
	 * @author 원종필(won0334@chol.com)
	 * @history
	 * @param (int)$idx : 일련번호
	 * @return NULL
	 * */
	public function get_data($idx)
	{
		$this->db->where('a.CODE', $idx);
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
	 * @history 메인에서 최근 사용자을 가져오고자 할때 사용
	 * @param (int)$count : 사용자 갯수
	 * @return NULL
	 * */
	public function get_top_data($parm = array())
	{
		$this->db->select('a.*');
		$this->db->select('\''.$this->board_code.'\' board_code, ');
		//$this->db->from($this->board_table_name.' a');//나중에 join을 할 수도 있으므로 별칭으로 관리한다.
		$this->db->from('(SELECT ROW_NUMBER() OVER(ORDER BY CODE DESC) AS NO, * FROM '.$this->board_table_name.') a');
		$this->db->join('V_MA_CODEDTL_SA_F000019 b', 'a.CD_CITY = b.CODE');
		$this->db->join('V_MA_CODEDTL_SA_F000020 C', 'a.CD_DIST = c.CODE');
		$this->db->where('USE_YN', 'Y');
		$this->db->where('YN_TA', 'N');
		
		$this->db->order_by('a.DT_OPEN', 'desc');
		if(isset($parm['count']) === TRUE && empty($parm['count']) === FALSE) $this->db->limit($parm['count']);
		$result = $this->db->get()->result_array();
		
		if( $this->_debug == 'Y' ) $this->add_error($this->board_table_name.'_gettopdata_query', $this->db->last_query(), 'debug');
		
		return $result;
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