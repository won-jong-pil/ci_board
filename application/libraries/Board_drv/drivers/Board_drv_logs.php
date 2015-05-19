<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @title 게시판 - 통계 클래스
 * @author - 원종필(won0334@chol.com)
 * @history
 * */
class CI_Board_drv_logs extends CI_Driver 
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
	public function insert()
	{
    	$data['title'] = $this->input->post('title');
    	$data['contents'] = $this->input->post('contents');
    	$data['link'] = $this->input->post('link');
    	$data['reg_date'] = $this->input->post('reg_date');

		$result['result'] = $this->db->insert($this->board_table_name, $data);
		$insert_idx = $this->db->insert_id();
		
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
    	$data['link'] = $this->input->post('link');
    	$data['reg_date'] = $this->input->post('reg_date');
    	
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
			$this->add_error('error', '일련번호가 전달 되지 않았습니다.');
		}
		else
		{		
			$this->db->where('idx', $idx);
			$result['result'] = $this->db->delete($this->board_table_name);

			if($result['result'] === FALSE)
			{
				$this->add_error('error', '게시판 정보 삭제에 실패 하였습니다.');
				log_message('error', $this->board_name.' 게시물 삭제에 실패하였습니다.\n'.$this->db->last_query().'\n');
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
		$last_query = '';//로그 데이터 기록을 위함.
		if( empty($this->board_table_name) === TRUE ) return FALSE;
		if(isset($parm['per_page']) === TRUE && empty($parm['per_page']) === FALSE) $parm['off_set'] = ($parm['page']-1) * $parm['per_page'];
		//여기서부터 실제 데이터를 가져오는 처리
		$this->db->select('SQL_CALC_FOUND_ROWS a.*, u.name, u.id, c.title, c.cate', FALSE);
		$this->db->from($this->board_table_name.' a');
		$this->db->join('users u', 'u.idx=a.user_idx', 'left');
		$this->db->join('board_movie c', 'c.idx=a.contents_idx', 'left');
		
		if( (isset($parm['start_date']) === TRUE && empty($parm['start_date']) === FALSE) && (isset($parm['end_date']) === TRUE && empty($parm['end_date']) === FALSE) )
		{
			$start_date = date('Y-m-d', strtotime($parm['start_date']));
			$end_date = date('Y-m-d H:i:s', strtotime($parm['end_date'])+86399);//해당일자까지만 하면 0시기준이라 저녁 24시까지로 하기위해
			$between_day = "a.reg_date between '".$start_date."' and '".$end_date."'";
			$this->db->where($between_day);
		}		
		
		if( isset($parm['contents_idx']) === TRUE && empty($parm['contents_idx']) === FALSE ) $this->db->where('a.contents_idx', $parm['contents_idx']);
		if( isset($parm['search_val']) === TRUE && empty($parm['search_val']) === FALSE ) $this->db->where('u.idx', $parm['search_val']);
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
			$this->db->order_by('a.reg_date', 'desc');
		}
		
		$result['result'] = $this->db->get()->result_array();
		if( isset($this->debug) === TRUE && $this->debug == 'Y' ) print_rr($this->db->last_query());
	
		$this->db->select("FOUND_ROWS() cnt", false);
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
		
		if( isset($parm['search_val']) === TRUE && empty($parm['search_val']) === FALSE )
		{
			//카테고리별 클리수
			$this->db->select('c.cate, count(c.cate) cnt', FALSE);
			$this->db->from($this->board_table_name.' a');
			$this->db->join('users u', 'u.idx=a.user_idx', 'left');
			$this->db->join('board_movie c', 'c.idx=a.contents_idx', 'left');
			$this->db->where('u.idx', $parm['search_val']);
			if( (isset($parm['start_date']) === TRUE && empty($parm['start_date']) === FALSE) && (isset($parm['end_date']) === TRUE && empty($parm['end_date']) === FALSE) )
			{
				$start_date = date("Y-m-d", strtotime($parm['start_date']));
				$end_date = date("Y-m-d H:i:s", strtotime($parm['end_date'])+86399);//해당일자까지만 하면 0시기준이라 저녁 24시까지로 하기위해
				$between_day = "a.reg_date between '".$start_date."' and '".$end_date."'";
				$this->db->where($between_day);
			}
			$this->db->group_by('c.cate');
			$result['cate_group'] = $this->db->get()->result_array();
	
			//일자별 컨텐츠 클릭수
			$this->db->select('substr(a.reg_date, 6, 5) reg_date, COUNT(a.reg_date) cnt', FALSE);
			$this->db->from($this->board_table_name.' a');
			$this->db->join('users u', 'u.idx=a.user_idx', 'left');
			$this->db->join('board_movie c', 'c.idx=a.contents_idx', 'left');
			$this->db->where('u.idx', $parm['search_val']);
			if( (isset($parm['start_date']) === TRUE && empty($parm['start_date']) === FALSE) && (isset($parm['end_date']) === TRUE && empty($parm['end_date']) === FALSE) )
			{
				$start_date = date("Y-m-d", strtotime($parm['start_date']));
				$end_date = date("Y-m-d H:i:s", strtotime($parm['end_date'])+86399);//해당일자까지만 하면 0시기준이라 저녁 24시까지로 하기위해
				$between_day = "a.reg_date between '".$start_date."' and '".$end_date."'";
				$this->db->where($between_day);
			}		
			$this->db->group_by('left(a.reg_date, 10)');
			$result['regdate_contents'] = $this->db->get()->result_array();		
		}
		
		if( isset($parm['contents_idx']) === TRUE && empty($parm['contents_idx']) === FALSE )
		{
			//컨텐츠별 클릭수
			$this->db->select(' count(*) cnt', FALSE);
			$this->db->from($this->board_table_name.' a');
			$this->db->where('a.contents_idx', $parm['contents_idx']);
			if( (isset($parm['start_date']) === TRUE && empty($parm['start_date']) === FALSE) && (isset($parm['end_date']) === TRUE && empty($parm['end_date']) === FALSE) )
			{
				$start_date = date("Y-m-d", strtotime($parm['start_date']));
				$end_date = date("Y-m-d H:i:s", strtotime($parm['end_date'])+86399);//해당일자까지만 하면 0시기준이라 저녁 24시까지로 하기위해
				$between_day = "a.reg_date between '".$start_date."' and '".$end_date."'";
				$this->db->where($between_day);
			}
			$result['contents_cnt'] = $this->db->get()->row_array();
			
			//컨텐츠별 회원수
			$this->db->select(' count(a.user_idx) cnt', FALSE);
			$this->db->from($this->board_table_name.' a');
			$this->db->where('a.contents_idx', $parm['contents_idx']);
			if( (isset($parm['start_date']) === TRUE && empty($parm['start_date']) === FALSE) && (isset($parm['end_date']) === TRUE && empty($parm['end_date']) === FALSE) )
			{
				$start_date = date("Y-m-d", strtotime($parm['start_date']));
				$end_date = date("Y-m-d H:i:s", strtotime($parm['end_date'])+86399);//해당일자까지만 하면 0시기준이라 저녁 24시까지로 하기위해
				$between_day = "a.reg_date between '".$start_date."' and '".$end_date."'";
				$this->db->where($between_day);
			}
			$this->db->group_by('a.user_idx');
			$result['user_cnt'] = $this->db->get()->row_array();	

			//일자별 컨텐츠 클릭수
			$this->db->select('substr(a.reg_date, 6, 5) reg_date, COUNT(a.contents_idx) cnt', FALSE);
			$this->db->from($this->board_table_name.' a');
			$this->db->where('a.contents_idx', $parm['contents_idx']);
			if( (isset($parm['start_date']) === TRUE && empty($parm['start_date']) === FALSE) && (isset($parm['end_date']) === TRUE && empty($parm['end_date']) === FALSE) )
			{
				$start_date = date("Y-m-d", strtotime($parm['start_date']));
				$end_date = date("Y-m-d H:i:s", strtotime($parm['end_date'])+86399);//해당일자까지만 하면 0시기준이라 저녁 24시까지로 하기위해
				$between_day = "a.reg_date between '".$start_date."' and '".$end_date."'";
				$this->db->where($between_day);
			}
			$this->db->group_by('left(a.reg_date, 10)');
			$result['regdate_contents'] = $this->db->get()->result_array();			
		}
		
		if( $this->_debug == 'Y' )
		{
			$this->add_error($this->board_table_name.'_select_data', json_encode($parm), 'debug');
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
		$this->db->where('a.idx', $idx);
		$this->db->from($this->board_table_name.' a');//나중에 join을 할 수도 있으므로 별칭으로 관리한다.
				
		$result['result'] = $this->db->get()->row_array();

		if( $this->debug == 'Y' )
		{
			print_rr($idx);
			print_rr($this->db->last_query());
			print_rr($result);
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
	public function get_top_data($count = 3)
	{
		$this->db->select('a.*');
		$this->db->from($this->board_table_name.' a');//나중에 join을 할 수도 있으므로 별칭으로 관리한다.

		$this->db->where('a.main_check', 1);
		$this->db->where('a.main_position', 0);
		$this->db->limit($count);
		
		$result = $this->db->get()->result_array();
		
		if( $this->debug == 'Y' )
		{
			print_rr($this->db->last_query());
			print_rr($result);
		}
		
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