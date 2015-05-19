<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @title 게시판 - 공지사항 게시판 클래스
 * @author - 원종필(won0334@chol.com)
 * @history
 * */
class Board_drv_notice extends CI_Driver 
{
	private $board_code = '';//게시판 구분 코드
	private $board_name = '';//게시판명
	private $board_type = 'notice';//게시판 형식
	private $board_skin = 'default';//게시판 스킨
	private $board_table_name = '';//테이블명
	private $etc = '';//게시판 기타 설정
	private $per_page = 10;//한페이지에 노출될 게시물 수
	private $page_size = 10;//한페이지에 노출될 페이지 리스트의 수
	
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
	 * @title 게시판
	 * @author 원종필(won0334@chol.com)
	 * @history
	 * @param (array)$config : 게시판 설정값
	 * @return boolean $result['result'] : insert 결과 ( true: 성공 false : 실패)
	 * @return string $result['error'] : 에러 메세지 
	 */
	public function insert()
	{
    	$data['title'] = $this->CI->input->post('title');
    	$data['contents'] = $this->CI->input->post('contents');
    	$data['status'] = $this->CI->input->post('status');
    	if($this->board_code == 'notice') $data['top_notice'] = $this->CI->input->post('top_notice');
    	$data['reg_date'] = date('Y-m-d H:i:s');

		$result['result'] = $this->CI->db->insert($this->board_table_name, $data);
		$insert_idx = $this->CI->db->insert_id();
		
		if($result['result'] === FALSE)
		{
			$this->add_debug($this->board_table_name.'_regist_error', '게시물 등록이 실패 하였습니다.');
			$this->add_debug($this->board_table_name.'_regist_query', $this->CI->db->last_query());
		}	
		
		if($this->debug == 'Y')
		{
			$this->add_debug($this->board_table_name.'_insert_result', $result['result'], 'debug');
			$this->add_debug($this->board_table_name.'_insert_data', json_encode($data), 'debug');
			$this->add_debug($this->board_table_name.'_insert_query', $this->CI->db->last_query(), 'debug');
		}		
		
		if($this->get_attr('etc.board_file_use') == 'Y')
		{
			$this->CI->load->library('upload');
			$this->CI->load->library('image_lib');
			
			foreach($this->get_attr('etc.file_list') as $key=>$list)
			{
				$upload_config['upload_path'] = $list['upload_path'];
				$upload_config['allowed_types'] = $list['board_file_allow'];
				$upload_config['max_size'] = $list['board_file_size'];
				$upload_config['encrypt_name'] = TRUE;
				$this->CI->upload->initialize($upload_config);
				
				if($this->debug == 'Y') $this->add_debug($this->board_table_name.'_upload_config', @json_encode($upload_config), 'debug');
								
				if(isset($_FILES[$key]) === TRUE && empty($_FILES[$key]['tmp_name']) === FALSE)
				{
					if ( ! $this->CI->upload->do_upload($key))
					{
						$this->add_debug($list.'_error', implode(' : ', $this->CI->upload->display_errors()));
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
								$this->add_debug($this->board_table_name.'_thumb_fail', $this->image_lib->display_errors()); 
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
						
						$result['file_result'][$key] = $this->CI->db->insert('file_info', $file_info);
						if($this->debug == 'Y') 
						{
							$this->add_debug($this->board_table_name.'_upload_data', @json_encode($file_info), 'debug');
							$this->add_debug($this->board_table_name.'_upload_data_query', $this->CI->db->last_query(), 'debug');
							$this->add_debug($this->board_table_name.'_upload_thumb_data', @$thumb_file_name, 'debug');
						}
					}
				}		
			}
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
    	$data['title'] = $this->CI->input->post('title');
    	$data['contents'] = $this->CI->input->post('contents');
    	$data['status'] = $this->CI->input->post('status');
    	if($this->board_code == 'notice')$data['top_notice'] = $this->CI->input->post('top_notice');
    	
		$this->CI->db->where('idx', $idx);
		$result['result'] = $this->CI->db->update($this->board_table_name, $data);
		
		if($result['result'] === FALSE)
		{
			$this->add_debug($this->board_table_name.'_update_error', '게시물 수정이 실패 하였습니다.');
			$this->add_debug($this->board_table_name.'_update_query', $this->CI->db->last_query());
		}		
		
		if($this->debug == 'Y') 
		{
			$this->add_debug($this->board_table_name.'_update_result', $result['result'], 'debug');
			$this->add_debug($this->board_table_name.'_update_query', $this->CI->db->last_query(), 'debug');
		}

		if($this->get_attr('etc.board_file_use') == 'Y')
		{		
			$this->CI->load->library('upload');
			$this->CI->load->library('image_lib');
						
			$old_data = $this->get_data($idx);
			
			$check_del = $this->CI->input->post('check_del');
			if($check_del != FALSE && sizeof($check_del) > 0)
			{
				foreach($check_del as $del_key=>$del_list)
				{
					foreach($old_data['file_info'] as $f_key=>$f_list)
					{
						if((int)$f_list['idx'] == (int)$del_list)
						{
							@unlink($upload_config['upload_path'].$f_list['file_name']);
							$this->CI->db->where('idx', $del_list);
							$this->CI->db->delete('file_info');
							break;
						}
					}
				}
			}			

			//환경에 따라 각각 다른 이미지를 사용하고 있음.
			foreach($this->get_attr('etc.file_list') as $key=>$list)
			{
				$upload_config['upload_path'] = $list['upload_path'];
				$upload_config['allowed_types'] = $list['board_file_allow'];
				$upload_config['max_size'] = $list['board_file_size'];
				$upload_config['encrypt_name'] = TRUE;

				$this->CI->upload->initialize($upload_config);
				
				if(isset($_FILES[$key]) === TRUE && empty($_FILES[$key]['tmp_name']) === FALSE)
				{
					if ( ! $this->CI->upload->do_upload($key))
					{
						$this->add_debug($key.'_error',  strip_tags($this->CI->upload->display_errors()));
					}
					else
					{
						$upload_data = $this->CI->upload->data();
						 
						if(isset($old_data['file_info'][$key]['file_name']) === TRUE)// 등록시 파일 첨부를 하지 않았을 경우에는 file에 대해서는 insert를 수행해주어야 한다.
						{
							if(file_exists($upload_config['upload_path'].$old_data['file_info'][$key]['file_name']) === TRUE)
							{
								@unlink($upload_config['upload_path'].$old_data['file_info'][$key]['file_name']);
								@unlink($upload_config['upload_path'].$old_data['file_info'][$key]['thumb_name']);
							}
							
							$file_info = array(
									'file_name'=>$upload_data['file_name'],
									'org_name'=>$upload_data['orig_name'],
									'file_size'=>$upload_data['file_size'],
									'file_type'=>$upload_data['file_type']
							);							
							
							if($list['thumb_use'] == 'Y')
							{
								$thumb_config['source_image'] = $upload_config['upload_path'].$upload_data['file_name'];
								$thumb_config['create_thumb'] = TRUE;

								$this->CI->image_lib->initialize($thumb_config);
								if ( $this->CI->image_lib->resize() === FALSE)
								{
									$this->add_debug($this->board_table_name.'_thumb_fail', $this->image_lib->display_errors());
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

							$this->CI->db->where('board_id', $this->board_code);
							$this->CI->db->where('board_idx', $idx);
							$this->CI->db->where('field_name', $key);

							$result['file_result'][$key] = $this->CI->db->update('file_info', $file_info);
							if($this->debug == 'Y')
							{
								$this->add_debug($this->board_table_name.'_upload_data', @json_encode($file_info), 'debug');
								$this->add_debug($this->board_table_name.'_upload_data_query', $this->CI->db->last_query(), 'debug');
								if($list['thumb_use'] == 'Y') $this->add_debug($this->board_table_name.'_upload_thumb_data', @$thumb_file_name, 'debug');
							}							
						}
						else
						{
							$file_info = array(
									'file_name'=>$upload_data['file_name'],
									'org_name'=>$upload_data['orig_name'],
									'file_size'=>$upload_data['file_size'],
									'file_type'=>$upload_data['file_type'],
									'board_id'=>$this->board_code,
									'board_idx'=>$idx,
									'field_name'=>$key,
									'reg_date'=>date('Y-m-d H:i:s')
							);
														
							if($list['thumb_use'] == 'Y')
							{
								$thumb_config['source_image'] = $upload_config['upload_path'].$upload_data['file_name'];
								$thumb_config['create_thumb'] = TRUE;
							
								$this->CI->image_lib->initialize($thumb_config);
								if ( $this->CI->image_lib->resize() === FALSE)
								{
									$this->add_debug($this->board_table_name.'_thumb_fail', $this->image_lib->display_errors());
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

							$result['file_result'][$key] = $this->CI->db->insert('file_info', $file_info);
							if($this->debug == 'Y')
							{
								$this->add_debug($this->board_table_name.'_upload_data', @json_encode($file_info), 'debug');
								$this->add_debug($this->board_table_name.'_upload_data_query', $this->CI->db->last_query(), 'debug');
								if($list['thumb_use'] == 'Y') $this->add_debug($this->board_table_name.'_upload_thumb_data', $thumb_file_name, 'debug');
							}							
						}
					}
				}
			}
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
			$this->add_debug($this->board_table_name.'_delete_idx_error', '일련번호가 전달 되지 않았습니다.');
			return $result;
		}
		
		if(is_array($idx) === FALSE) $idx = array($idx);
		foreach($idx as $idx_key=>$idx_list)
		{		
			$old_data = $this->get_data($idx_list);
			if($this->get_attr('etc.board_file_use') == 'Y')
			{			
				//환경에 따라 각각 다른 이미지를 사용하고 있음.
				foreach($this->get_attr('etc.file_list') as $key=>$list)
				{
					$upload_path = $list['upload_path'];
					if(isset($old_data['file_info'][$key]['file_name']) === TRUE)// 등록시 파일 첨부를 하지 않았을 경우에는 file에 대해서는 insert를 수행해주어야 한다.
					{
						if(file_exists($upload_path.$old_data['file_info'][$key]['file_name']) === TRUE)
						{
							$result[$key.'_del_result'] = @unlink($upload_path.$old_data['file_info'][$key]['file_name']);
						}
							
						$this->CI->db->where('board_id', $this->board_code);
						$this->CI->db->where('board_idx', $idx_list);
						$result[$key.'_db_result'] = $this->CI->db->delete('file_info');
						$this->add_debug($key.'_db_result', $result[$key.'_db_result']);
					}
				}
			}	
		}
		
		$this->CI->db->where_in('idx', $idx);
		$result['result'] = $this->CI->db->delete($this->board_table_name);
		
		if($result['result'] === FALSE)
		{
			$this->add_debug('error', '게시판 정보 삭제에 실패 하였습니다.');
			log_message('error', $this->board_name.' 게시물 삭제에 실패하였습니다.\n'.$this->CI->db->last_query());
			throw new Exception('board_delete_fail');
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
	public function get_list(array $parm = array()) 
	{
		if($this->debug != 'N') $this->set_debug('get list config', json_encode($parm));
		if(count($parm) > 0) $this->initialize($parm);//외부 설정값이 있으면 먼저 설정
		if( empty($this->board_table_name) === TRUE ) throw new Exception('board_no_table_name');
		$parm['off_set'] = ($parm['page']-1) * $this->per_page;
		//여기서부터 실제 데이터를 가져오는 처리
		$this->CI->db->select('SQL_CALC_FOUND_ROWS a.*', FALSE);
		if($this->get_attr('etc.board_file_use') == 'Y')
		{
			$db_alias = 98;//영문자 b부터 시작
			foreach($this->get_attr('etc.file_list') as $key=>$list)
			{
				$this->CI->db->select(chr($db_alias).'.org_name as '.$key.'_org_name, '.chr($db_alias).'.file_name as '.$key.'_file_name');
				$this->CI->db->join('file_info '.chr($db_alias), chr($db_alias).'.field_name = \''.$key.'\' and a.idx='.chr($db_alias).'.board_idx and '.chr($db_alias++).'.board_id = \''.$this->board_code.'\'', 'left');
			}
		}		
	
		if( isset($parm['search_val']) === TRUE && empty($parm['search_val']) === FALSE )
		{
			switch($parm['search_key'])
			{
				case 'title':
				case 'contents':
					$this->CI->db->like('a.'.$parm['search_key'], $parm['search_val']);
				break;

				case 'all':
					$this->CI->db->like('a.title', $parm['search_val']);
					$this->CI->db->or_like('a.contents', $parm['search_val']);
				break;
			}
		}

		if( isset($parm['view_flag']) === TRUE && empty($parm['view_flag']) === FALSE ) $this->CI->db->where('view_flag', $parm['view_flag']);//화면 노출 여부
		if( isset($parm['top_notice']) === TRUE && empty($parm['top_notice']) === FALSE ) $this->CI->db->where('top_notice', $parm['top_notice']);//상단 공지 처리 여부
		if( isset($parm['status']) === TRUE && empty($parm['status']) === FALSE ) $this->CI->db->where('a.status', $parm['status']);//상태값에 따른 리스트 대상 처리
		if( $this->per_page != 0 ) $this->CI->db->limit($this->per_page, $parm['off_set']);//per_page가 0일 경우에는 전체 노출처리됨.
	
		$this->CI->db->from($this->board_table_name.' a');
		
		if( isset($parm['order_by']) === TRUE && empty($parm['order_by']) === FALSE)
		{
			if( isset($parm['order_type']) === FALSE OR empty($parm['order_type']) === TRUE ) $parm['order_type'] = 'desc';
			$this->CI->db->order_by($parm['order_by'], $parm['order_type']);
		}
		else
		{
			$this->CI->db->order_by('a.idx', 'desc');
		}
		
		$return_result['result'] = $this->CI->db->get()->result_array();
		if($this->debug != 'N') $this->set_debug('get list query', $this->CI->db->last_query());

		$this->CI->db->select("FOUND_ROWS() cnt", false);
		$return_result['record_count'] = $this->CI->db->get()->row(1)->cnt;
	
		//게시판 기본 정보 리턴
		if( isset($parm['vnum_use']) === TRUE && $parm['vnum_use'] == 'Y' && $this->per_page != 0 )
		{
			if($return_result['record_count'] > $this->per_page)
			{//출력시 가상번호로
				$return_result['vnum'] = $return_result['record_count'] - ($parm['page']-1) * $this->per_page;
			}else{
				$return_result['vnum'] = $return_result['record_count'];
			}
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
		$this->CI->db->where('a.idx', $idx);
		$this->CI->db->from($this->board_table_name.' a');//나중에 join을 할 수도 있으므로 별칭으로 관리한다.
		$result['result'] = $this->CI->db->get()->row_array();
		
		if( $this->debug == 'Y' )
		{
			$this->add_debug($this->board_table_name.'_getdata_idx', $idx);
			$this->add_debug($this->board_table_name.'_getdata_query', $this->CI->db->last_query());
		}		

		if($this->get_attr('etc.board_file_use') == 'Y')
		{		
			$this->CI->db->from('file_info');
			$this->CI->db->where('board_id', $this->board_code);
			$this->CI->db->where('board_idx', $idx);
			$file_info = $this->CI->db->get()->result_array();
			
			foreach($file_info as $key=>$list)
			{
				$result['file_info'][$list['field_name']] = $list;
			}		
	
			if( $this->debug == 'Y' ) $this->add_debug($this->board_table_name.'_getdata_file', @json_encode($result['file_info']));
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
		$upload_url = $this->get_attr('etc.file_list.web_file.upload_url');
		$this->CI->db->select('a.*');
		$this->CI->db->select('\''.$this->board_code.'\' board_code, ');
		$this->CI->db->from($this->board_table_name.' a');//나중에 join을 할 수도 있으므로 별칭으로 관리한다.
		if($this->get_attr('etc.board_file_use') == 'Y')
		{
			$db_alias = 98;//영문자 b부터 시작
			foreach($this->get_attr('etc.file_list') as $key=>$list)
			{
				
				$this->CI->db->select(chr($db_alias).'.org_name as '.$key.'_org_name, '.chr($db_alias).'.file_name as '.$key.'_file_name, concat(\''.$upload_url.'\',' .chr($db_alias).'.file_name) as '.$key.'_file_name_url');
				$this->CI->db->join('file_info '.chr($db_alias), chr($db_alias).'.field_name = \''.$key.'\' and a.idx='.chr($db_alias).'.board_idx and '.chr($db_alias++).'.board_id = \''.$this->board_code.'\'', 'left');
			}
		}
		$this->CI->db->where('a.status', 'Y');
		$this->CI->db->order_by('a.reg_date', 'desc');
		if(isset($parm['count']) === TRUE && empty($parm['count']) === FALSE) $this->CI->db->limit($parm['count']);
		$result = $this->CI->db->get()->result_array();
		
		if( $this->debug == 'Y' ) $this->add_debug($this->board_table_name.'_gettopdata_query', $this->CI->db->last_query());
		
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
		$this->CI->db->set('hit', 'hit+1', FALSE);
		$this->CI->db->where('idx', $idx);
		$return_result['result'] = $this->CI->db->update($this->board_table_name);

		return $return_result;
	}	
	/**
	 * 게시판 - 다음 게시물 조회
	 * @author - 원종필(won0334@chol.com)
	 * 작업내역 :
	 * @return NULL
	 * */
	public function get_next($idx)
	{
		$this->CI->db->from($this->board_table_name);
		$this->CI->db->where('idx >', $idx);
		$this->CI->db->limit(1);
		$this->CI->db->order_by('idx', 'desc');
		$result = $this->CI->db->get()->row_array();
		
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
		$this->CI->db->from($this->board_table_name.' a');
		$this->CI->db->where('a.idx <', $idx);
		$this->CI->db->limit(1);
		$this->CI->db->order_by('a.idx', 'desc');
		$result = $this->CI->db->get()->row_array();
		
		return $result;
	}	
}
// End Class