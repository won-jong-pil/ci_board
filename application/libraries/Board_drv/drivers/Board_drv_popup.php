<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @title 게시판 - 팦업 관리 클래스
 * @author - 원종필(won0334@chol.com)
 * @history
 * */
class CI_Board_drv_popup extends CI_Driver 
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
    	$data['title'] = $this->input->post('title');
    	$data['popup_type'] = $this->input->post('popup_type');
    	$data['contents'] = $this->input->post('contents');
    	$data['status'] = $this->input->post('status');
    	$data['pos_x'] = $this->input->post('pos_x');
    	$data['pos_y'] = $this->input->post('pos_y');
    	$data['size_x'] = $this->input->post('size_x');
    	$data['size_y'] = $this->input->post('size_y');
    	$data['close_day'] = $this->input->post('close_day');
    	$data['reg_date'] = date('Y-m-d H:i:s');
    	
    	$date_type = $this->input->post('date_type');
    	if($date_type === FALSE) $data['date_type'] = 'N'; else $data['date_type'] = $date_type;

    	if($data['date_type'] != 'Y')
    	{
    		$data['start_date'] = $this->input->post('start_date');
    		$data['end_date'] = $this->input->post('end_date');
    	}
				
		$result['result'] = $this->db->insert($this->board_table_name, $data);
		$insert_idx = $this->db->insert_id();
		
		if($result['result'] === FALSE)
		{
			$this->add_error($this->board_table_name.'_regist_error', '팦업 등록이 실패 하였습니다.');
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
	    $data['title'] = $this->input->post('title');
    	$data['popup_type'] = $this->input->post('popup_type');
    	$data['contents'] = $this->input->post('contents');
    	$data['status'] = $this->input->post('status');
    	$data['pos_x'] = $this->input->post('pos_x');
    	$data['pos_y'] = $this->input->post('pos_y');
    	$data['size_x'] = $this->input->post('size_x');
    	$data['size_y'] = $this->input->post('size_y');
    	$data['close_day'] = $this->input->post('close_day');
    	
    	$date_type = $this->input->post('date_type');
    	if($date_type === FALSE) $data['date_type'] = 'N'; else $data['date_type'] = $date_type;

    	if($data['date_type'] != 'Y')
    	{
    		$data['start_date'] = $this->input->post('start_date');
    		$data['end_date'] = $this->input->post('end_date');
    	}
			
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
	 * @title 팦업 - 삭제
	 * @author 원종필(won0334@chol.com)
	 * @history
	 * @param (int)$idx : 일련번호
	 * @param (array)$config : 게시판 설정값
	 * @return NULL
	 * */
	public function delete($idx, $config)
	{
		if(empty($idx) === TRUE)
		{
			$result['result'] = FALSE;
			$this->add_error($this->board_table_name.'_delete_idx_error', '일련번호가 전달 되지 않았습니다.');
			return $result;
		}
		
		if(is_array($idx) === TRUE)	$this->db->where_in('idx', $idx);
		else $this->db->where('idx', $idx);
		
		$result['result'] = $this->db->delete($this->board_table_name);
		if($result['result'] === FALSE)
		{
			$this->add_error($this->board_table_name.'_delete_error', '팦업 삭제가 실패 하였습니다.');
			$this->add_error($this->board_table_name.'_delete_query', $this->db->last_query());
		}
			
		if($this->_debug == 'Y')
		{
			$this->add_error($this->board_table_name.'_delete_result', $result['result'], 'debug');
			$this->add_error($this->board_table_name.'_delete_query', $this->db->last_query(), 'debug');
		}
		
		return $result;			
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
		if(isset($parm['per_page']) === TRUE && empty($parm['per_page']) === FALSE) $parm['off_set'] = ($parm['page']-1) * $parm['per_page'];
		//여기서부터 실제 데이터를 가져오는 처리
		$this->db->select('SQL_CALC_FOUND_ROWS a.*', FALSE);
	
		if( isset($parm['search_key']) === TRUE && empty($parm['search_key']) === FALSE && isset($parm['search_val']) === TRUE && empty($parm['search_val']) === FALSE )
		{
			switch($parm['search_key'])
			{
				case "title":
					$this->db->like("a.title", $parm['search_val']);
				break;
			}
		}

		if( isset($parm['view_flag']) === TRUE && empty($parm['view_flag']) === FALSE ) $this->db->where("view_flag", $parm['view_flag']);
		if( isset($parm['per_page']) === TRUE && empty($parm['per_page']) === FALSE && isset($parm['off_set']) === TRUE  ) $this->db->limit($parm['per_page'], $parm['off_set']);
		if( isset($parm['status']) === TRUE && empty($parm['status']) === FALSE ) $this->db->where('a.status', $parm['status']);
		if( isset($parm['date_status']) === TRUE && empty($parm['date_status']) === FALSE ) $this->db->where('a.date_status', $parm['date_status']);
		
		$in_date = 'start_date <= \''.date('Y-m-d').'\' and end_date >= \''.date('Y-m-d').'\'';
		$out_date = 'end_date < \''.date('Y-m-d').'\'';
		$this->db->from('(select *, (case
									when '.$in_date.' then \'Y\' '.
				'when '.$out_date.' then \'N\' '.
				'end) as date_status from '.$this->board_table_name.' ) as a');
	
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
		}
	
		return $return_result;
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
		$this->db->where('a.idx', $idx);
		$this->db->from($this->board_table_name.' a');//나중에 join을 할 수도 있으므로 별칭으로 관리한다.
				
		$return_result['result'] = $this->db->get()->row_array();

		if( $this->_debug == 'Y' )
		{
			$this->add_error($this->board_table_name.'_getdata_idx', $idx, 'debug');
			$this->add_error($this->board_table_name.'_getdata_query', $this->db->last_query(), 'debug');
		}	
		
		return $return_result;
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
		$upload_url = $this->get_attr('etc.file_list.web_file.upload_url');
		$this->db->select('a.*');
		$in_date = 'start_date <= \''.date('Y-m-d').'\' and end_date >= \''.date('Y-m-d').'\'';
		$out_date = 'end_date < \''.date('Y-m-d').'\'';
		$this->db->from('(select *, (case
									when date_type = \'Y\' then \'Y\'  
									when '.$in_date.' then \'Y\' '.
								  'when '.$out_date.' then \'N\' '.
								  'end) as date_status from '.$this->board_table_name.' ) as a');
		if($this->get_attr('etc.board_file_use') == 'Y')
		{
			$db_alias = 98;//영문자 b부터 시작
			foreach($this->get_attr('etc.file_list') as $key=>$list)
			{
				$this->db->select(chr($db_alias).'.org_name as '.$key.'_org_name, '.chr($db_alias).'.file_name as '.$key.'_file_name, concat(\''.$upload_url.'\',' .chr($db_alias).'.file_name) as '.$key.'_file_name_url');
				$this->db->join('file_info '.chr($db_alias), chr($db_alias).'.field_name = \''.$key.'\' and a.idx='.chr($db_alias).'.board_idx and '.chr($db_alias++).'.board_id = \''.$this->board_code.'\'', 'left');
			}
		}
		$this->db->where('a.date_status', 'Y');
		$this->db->where('a.status', 'Y');
		$this->db->order_by('a.reg_date', 'desc');
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