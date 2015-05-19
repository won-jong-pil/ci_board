<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @title 게시판 - 문의하기 게시판 클래스
 * @author - 원종필(won0334@chol.com)
 * @history
 * */
class CI_Board_drv_contact extends CI_Driver 
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
    	$data['user_idx'] = $this->CI->session->userdata('idx');
    	$data['title'] = $this->input->post('title');
    	$data['contents'] = $this->input->post('contents');
    	$data['reg_date'] = date('Y-m-d H:i:s');
				
		$result['result'] = $this->db->insert($this->board_table_name, $data);
		$insert_idx = $this->db->insert_id();
		
		if($this->get_attr('etc.board_file_use') == 'Y')
		{
			$this->CI->load->library('upload');
			$this->CI->load->library('image_lib');
			
			foreach($this->get_attr('etc.file_list') as $key=>$list)
			{
				$upload_config['upload_path'] = $list['upload_path'];
				$upload_config['allowed_types'] = $list['board_file_allow'];
				$upload_config['encrypt_name'] = TRUE;
				$this->CI->upload->initialize($upload_config);

				if($this->_debug == 'Y') $this->add_error($this->board_table_name.'_upload_config', json_encode($upload_config), 'debug');
								
				if(isset($_FILES[$key]) === TRUE && empty($_FILES[$key]['tmp_name']) === FALSE)
				{
					if ( ! $this->CI->upload->do_upload($key))
					{
						$this->add_error($key.'_error', json_encode($this->CI->upload->display_errors()));
					}
					else
					{
						$upload_data = $this->CI->upload->data();
						$file_info = array(
								'file_name'=>$upload_data['file_name'],
								'org_name'=>$upload_data['orig_name'],
								'file_size'=>$upload_data['file_size'],
								'file_type'=>$upload_data['file_type'],
								'board_id'=>$this->board_code,
								'board_idx'=>$insert_idx,
								'field_name'=>$key,
								'reg_date'=>date('Y-m-d H:i:s')
						);
						
						if($list['thumb_use'] == 'Y')
						{
							$thumb_config['source_image'] = $upload_config['upload_path'].$upload_data['file_name'];
							$thumb_config['create_thumb'] = TRUE;

							$this->CI->image_lib->initialize($thumb_config);
							$this->CI->image_lib->resize();
						
							if ( $this->CI->image_lib->resize() === FALSE)
							{
								$this->add_error($this->board_table_name.'_thumb_fail', $this->image_lib->display_errors()); 
							}							
							else
							{
								$thumb_file_name = "";
								$temp = explode(".", $upload_data['file_name']);
								for($i=0;$i<sizeof($temp)-1;$i++) $thumb_file_name = $thumb_file_name.$temp[$i].".";
								$thumb_file_name = substr($thumb_file_name, 0, -1);
								$thumb_file_name .= "_thumb.".$temp[sizeof($temp)-1];
								$file_info['thumb_name'] = $thumb_file_name;
							} 
							
							$this->CI->image_lib->clear();
						}						
						
						$result['file_result'][$key] = $this->db->insert('file_info', $file_info);
						if($this->_debug == 'Y') 
						{
							$this->add_error($this->board_table_name.'_upload_data', @json_encode($file_info), 'debug');
							$this->add_error($this->board_table_name.'_upload_data_query', $this->db->last_query(), 'debug');
							$this->add_error($this->board_table_name.'_upload_thumb_data', @$thumb_file_name, 'debug');
						}
					}
				}		
			}
		}
		
		if($result['result'] === FALSE)
		{
			$this->add_error('error', '게시판 정보 등록에 실패하였습니다.');
			log_message('error', '게시판('.$this->board_name.') 정보등록에 실패하였습니다.\n'.$this->db->last_query().'\n');
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
		$this->db->set('reply_title', $this->CI->input->post('reply_title'));	
		$this->db->set('reply_contents', $this->CI->input->post('reply_contents'));
		$this->db->set('reply_reg_date', $this->CI->input->post('reply_reg_date'));
		
		$this->db->where('idx', $idx);
		$result['result'] = $this->db->update($this->board_table_name);

		if($result['result'] === FALSE)
		{
			$this->add_error('error', '게시판 정보 변경에 실패 하였습니다.');
			log_message('error', $this->board_name.' 정보 변경에 실패하였습니다.\n'.$this->db->last_query().'\n');
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
			$old_data = $this->get_data($idx);
			$upload_path = $this->get_attr('etc.upload_path');
			//환경에 따라 각각 다른 이미지를 사용하고 있음.
			foreach(array('file') as $key=>$list)
			{
				if(isset($old_data['file_info'][$key]['file_name']) === TRUE)// 등록시 파일 첨부를 하지 않았을 경우에는 file에 대해서는 insert를 수행해주어야 한다.
				{
					if(file_exists($upload_path.$old_data['file_info'][$key]['file_name']) === TRUE)
					{
						$result[$list.'_del_result'] = @unlink($upload_path.$old_data['file_info'][$key]['file_name']);
					}
			
					$this->db->where('board_id', $this->board_code);
					$this->db->where('board_idx', $idx);
					$result[$list.'_db_result'] = $this->db->delete('file_info');
				}
			}
						
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
		$this->db->select('SQL_CALC_FOUND_ROWS a.*, users.name',	FALSE);
		$this->db->join('users', 'users.idx = a.user_idx');
		if($this->get_attr('etc.board_file_use') == 'Y')
		{
			$db_alias = 98;//영문자 b부터 시작
			foreach($this->get_attr('etc.file_list') as $key=>$list)
			{
				$this->db->select(chr($db_alias).'.org_name as '.$key.'_org_name, '.chr($db_alias).'.file_name as '.$key.'_file_name');
				$this->db->join('file_info '.chr($db_alias), chr($db_alias).'.field_name = \''.$key.'\' and a.idx='.chr($db_alias).'.board_idx and '.chr($db_alias++).'.board_id = \''.$this->board_code.'\'', 'left');
			}
		}
		if( isset($parm['search_key']) === TRUE && empty($parm['search_key']) === FALSE && isset($parm['search_val']) === TRUE && empty($parm['search_val']) === FALSE )
		{
			switch($parm['search_key'])
			{
				case "title":
					$this->db->like("a.title", $parm['search_val']);
				break;
				
				case "writer":
					$this->db->like("a.writer", $parm['search_val']);
				break;

				case "email":
					$this->db->like("a.email", $parm['search_val']);
				break;

				case "tel":
					$this->db->like("a.tel", $parm['search_val']);
				break;						
				
				case "contents":
					$this->db->like("a.contents", $parm['search_val']);
				break;				
			}
		}

		if( isset($parm['view_flag']) === TRUE && empty($parm['view_flag']) === FALSE ) $this->db->where('view_flag', $parm['view_flag']);
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
	
		if( isset($this->debug) === TRUE && $this->debug == 'Y' )
		{
			print_rr($parm);
			print_rr($this->db->last_query());
			print_rr($return_result);
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
		$this->db->select('a.*, users.name');
		$this->db->where('a.idx', $idx);
		$this->db->from($this->board_table_name.' a');//나중에 join을 할 수도 있으므로 별칭으로 관리한다.
		$this->db->join('users', 'users.idx = a.user_idx');
		$result['result'] = $this->db->get()->row_array();
		
		$this->db->from('file_info');
		$this->db->where('board_id', $this->board_code);
		$this->db->where('board_idx', $idx);
		$file_info = $this->db->get()->result_array();
		
		foreach($file_info as $key=>$list)
		{
			$result['file_info'][$list['field_name']] = $list;
		}		

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
		$this->db->from($this->board_table_name.' a');//나중에 join을 할 수도 있으므로 별칭으로 관리한다.
		$this->db->select('b.org_name, b.file_name');
		$this->db->join('file_info b', 'a.idx=b.board_idx and b.board_id = \''.$this->board_code.'\'', 'left');
		$this->db->order_by('reg_date', 'desc');
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
		return FALSE;
	}	
	/**
	 * 게시판 - 다음 게시물 조회
	 * @author - 원종필(won0334@chol.com)
	 * 작업내역 :
	 * @return NULL
	 * */
	public function get_next($idx)
	{
		return FALSE;
	}
	/**
	 * 게시판 - 이전 게시물 조회
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