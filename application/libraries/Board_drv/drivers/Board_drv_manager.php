<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @title 게시판 - 관리자 관리 클래스
 * @author - 원종필(won0334@chol.com)
 * @history
 * */
class CI_Board_drv_manager extends CI_Driver 
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
		$this->CI->load->library('form_validation');
		$this->CI->form_validation->set_rules('id', '아이디', 'required|min_length[4]|max_length[30]');
		$this->CI->form_validation->set_rules('passwd', '패스워드', 'required|min_length[8]|max_length[30]');
		$this->CI->form_validation->set_rules('name', '이름', 'required');
		
		if ($this->CI->form_validation->run() === FALSE)
		{
			alert('입력정보가 올바르지 않습니다.', '', TRUE);
		}
		$tel = $this->input->post('tel1').'-'.$this->input->post('tel2').'-'.$this->input->post('tel3');
		$email = $this->input->post('email1').'@'.$this->input->post('email2');
				
		$data['id'] = $this->input->post('id');
		//$data['passwd'] = hash('sha256', $this->input->post('passwd'));
		$data['passwd'] = password_hash($this->input->post('passwd'), PASSWORD_BCRYPT);
		$data['name'] = $this->input->post('name');
		$data['tel'] = $tel;
		$data['email'] = $email;
		$data['reg_date'] = date('Y-m-d H:i:s');
		$data['pass_update_date'] = $data['reg_date'];
		
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
						$this->add_error($list.'_error', $this->CI->upload->display_errors());
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
							$this->add_error($this->board_table_name.'_upload_data', json_encode($file_info), 'debug');
							$this->add_error($this->board_table_name.'_upload_data_query', $this->db->last_query(), 'debug');
							$this->add_error($this->board_table_name.'_upload_thumb_data', $thumb_file_name, 'debug');
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
	 * @history $config는 controller 에서 보내는 설정 정보인데 아직 사용 목적은 없음
	 * @history 2014-06-15 관리자 패스워드 변경시 기존 패스워드를 입력 받아서 진행하도록 변경 
	 * @param (int)$idx : 게시물 일련번호
	 * @param (array)$config : 게시판 설정값
	 * @return NULL
	 */	
	public function update($idx, $config = array())
	{
		if(empty($idx) === TRUE) return FALSE;
		$tel = $this->input->post('tel1').'-'.$this->input->post('tel2').'-'.$this->input->post('tel3');
		$email = $this->input->post('email1').'@'.$this->input->post('email2');
		
		$old_passwd = hash('sha256', $this->input->post('old_passwd'));
		$data['passwd'] = hash('sha256', $this->input->post('passwd'));
		$data['name'] = $this->input->post('name');
		$data['tel'] = $tel;
		$data['email'] = $email;
		$data['pass_update_date'] = date('Y-m-d H:i:s');

		$this->db->where('passwd', $old_passwd);
		$this->db->where('idx', $idx);
		$this->db->from($this->board_table_name);
		$old_data = $this->db->get()->row_array();

		if(sizeof($old_data) <= 0)
		{
			$result['error'] = '기존 패스워드가 올바르지 않습니다.';
			$result['result'] = FALSE;
			$this->add_error('pass error', '기존 패스워드가 올바르지 않습니다.');
			
			if($this->_debug == 'Y')
			{
				$this->add_error('pass error', '기존 패스워드가 올바르지 않습니다.', 'debug');
				$this->add_error('pass data', $old_data['passwd'].json_encode($data), 'debug');
			}			
			return $result;
		}

		//기존 패스워드와 새로 입력된 패스워드가 다를 경우 패스워드 업데이트 시간을 변경한다.
		if($old_passwd != $data['passwd'])
		{
			//패스워드 변경 시간이 24시간 이내일 경우에는 에러를 출력한다.
			if(date('Y-m-d H:i:s') < date('Y-m-d H:i:s', strtotime('+1 days', strtotime($old_data['pass_update_date']))))
			{
				log_message('debug', '비교 시간값 : '.strtotime('+1 days', strtotime($old_data['pass_update_date'])));
				$result['result'] = FALSE;
				$result['error'] = '패스워드는 24시간 이내 변경이 불가합니다.';
				
				$this->add_error('pass24 error', $result['error']);
					
				if($this->_debug == 'Y')
				{
					$this->add_error('pass24 error', $result['error'], 'debug');
					$this->add_error('pass24 data', '비교 시간값 : '.strtotime('+1 days', strtotime($old_data['pass_update_date'])), 'debug');
				}				
				
				return $result;
			}
			
			$data['attempts'] = 0;
		}
		
		$data['pass_update_date'] = date('Y-m-d H:i:s');
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
		
		if($this->get_attr('etc.board_file_use') == 'Y')
		{		
			$this->CI->load->library('upload');
			$this->CI->load->library('image_lib');
						
			$old_data = $this->get_data($idx);
			
			//환경에 따라 각각 다른 이미지를 사용하고 있음.
			foreach($this->get_attr('etc.file_list') as $key=>$list)
			{
				$upload_config['upload_path'] = $list['upload_path'];
				$upload_config['allowed_types'] = $list['board_file_allow'];
				$upload_config['encrypt_name'] = TRUE;

				$this->CI->upload->initialize($upload_config);
				
				if(isset($_FILES[$key]) === TRUE && empty($_FILES[$key]['tmp_name']) === FALSE)
				{
					if ( ! $this->CI->upload->do_upload($key))
					{
						$this->add_error($key.'_error', $this->CI->upload->display_errors());
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

							$this->db->where('board_id', $this->board_code);
							$this->db->where('board_idx', $idx);
							$this->db->where('field_name', $key);

							$result['file_result'][$key] = $this->db->update('file_info', $file_info);
							if($this->_debug == 'Y')
							{
								$this->add_error($this->board_table_name.'_upload_data', json_encode($file_info), 'debug');
								$this->add_error($this->board_table_name.'_upload_data_query', $this->db->last_query(), 'debug');
								$this->add_error($this->board_table_name.'_upload_thumb_data', $thumb_file_name, 'debug');
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
								$this->add_error($this->board_table_name.'_upload_data', json_encode($file_info), 'debug');
								$this->add_error($this->board_table_name.'_upload_data_query', $this->db->last_query(), 'debug');
								$this->add_error($this->board_table_name.'_upload_thumb_data', $thumb_file_name, 'debug');
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
			$this->add_error($this->board_table_name.'_delete_idx_error', '일련번호가 전달 되지 않았습니다.');
			return $result;
		}
		else
		{
			if($this->get_attr('etc.board_file_use') == 'Y')
			{
				$old_data = $this->get_data($idx);
				//환경에 따라 각각 다른 이미지를 사용하고 있음.
				foreach($this->get_attr('etc.file_list') as $key=>$list)
				{			
					$upload_path = $list['upload_path'];
					if(isset($old_data['file_info'][$key]['file_name']) === TRUE)// 등록시 파일 첨부를 하지 않았을 경우에는 file에 대해서는 insert를 수행해주어야 한다.
					{
						if(file_exists($upload_path.$old_data['file_info'][$key]['file_name']) === TRUE)
						{
							$del_result = @unlink($upload_path.$old_data['file_info'][$key]['file_name']);
							$del_thumb_result = @unlink($upload_path.$old_data['file_info'][$key]['thumb_name']);
							
							$this->add_error('delete_file_result', $del_result, 'debug');
							$this->add_error('delete_thumbfile_result', $del_thumb_result, 'debug');							
						}
							
						$this->db->where('board_id', $this->board_code);
						$this->db->where('board_idx', $idx);
						$db_file_result = $this->db->delete('file_info');
						$this->add_error('delete_db_file_result', $db_file_result, 'debug');
					}
				}
			}		
			
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
		$this->db->select('SQL_CALC_FOUND_ROWS a.*', FALSE);
		$this->db->from($this->board_table_name.' a');
		
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
				case "id": 
					$this->db->like("a.id", $parm['search_val']);  
				break;

				case "name":
					$this->db->like("a.name", $parm['search_val']);
				break;
			}
		}
		
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
		
		$this->db->select('FOUND_ROWS() cnt', FALSE);
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
		
		if(isset($result['result']['tel']) === TRUE) $result['result']['stel'] = explode('-', $result['result']['tel']); else $result['result']['stel'] = array('', '', '');
		if(isset($result['result']['email']) === TRUE) $result['result']['semail'] = explode('@', $result['result']['email']);  else $result['result']['semail'] = array('', '', '');
		
		if( $this->_debug == 'Y' )
		{
			$this->add_error($this->board_table_name.'_getdata_idx', $idx, 'debug');
			$this->add_error($this->board_table_name.'_getdata_query', $this->db->last_query(), 'debug');
		}		
		
		if($this->get_attr('etc.board_file_use') == 'Y')
		{		
			$this->db->from('file_info');
			$this->db->where('board_id', $this->board_code);
			$this->db->where('board_idx', $idx);
			$file_info = $this->db->get()->result_array();
			
			foreach($file_info as $key=>$list)
			{
				$result['file_info'][$list['field_name']] = $list;
			}		
	
			if( $this->_debug == 'Y' ) $this->add_error($this->board_table_name.'_get_top_query', $this->db->last_query(), 'debug');
		}
		
		return $result;
	}
	/**
	 * @title 인터페이스 
	 * @author 원종필(won0334@chol.com)
	 * @history 
	 * @param 
	 * @return 
	 */
	public function get_top_data($parm = array())
	{
		return FALSE;
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