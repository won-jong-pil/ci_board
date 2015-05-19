<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @title 게시판 - 제품 관리 클래스
 * @author - 원종필(won0334@chol.com)
 * @history
 * */
class CI_Board_drv_product extends CI_Driver 
{
	public $board_code = '';//게시판 구분 코드
	public $board_name = '';//게시판명
	public $board_type = '';//게시판 형식
	public $board_skin = 'default';//게시판 스킨
	public $board_table_name = '';//테이블명
	public $etc = '';//게시판 키타 설정
	private $cate = array(
		'beverage' => array(1=>'BEVERAGE', 2=>'FLATCCINO', 3=>'TEA'),
		'dessert' => array(1=>'BREAD & WAFFLE', 2=>'PRETZEL & BAGEL', 3=>'BUN & MUFFIN', 4=>'CAKE', 5=>'COOKIE')
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
		$calories = $this->input->post('calories');
		$sugars = $this->input->post('sugars');
		$protein = $this->input->post('protein');
		$saturated = $this->input->post('saturated');
		$sodium = $this->input->post('sodium');
		$caffeine = $this->input->post('caffeine');
		$data['calories'] = $calories == ''?0:$calories ;
		$data['sugars'] =  $sugars == ''?0:$sugars ;
    	$data['protein'] = $protein == ''?0:$protein ;
    	$data['saturated'] = $saturated == ''?0:$saturated ;
    	$data['sodium'] = $sodium == ''?0:$sodium ;
    	$data['caffeine'] = $caffeine == ''?0:$caffeine ;
				
    	$data['kr_title'] = $this->input->post('kr_title');
    	$data['kr_contents'] = $this->input->post('kr_contents');
    	$data['en_title'] = $this->input->post('en_title');
    	$data['en_contents'] = $this->input->post('en_contents');
    	$data['ch_title'] = $this->input->post('ch_title');
    	$data['ch_contents'] = $this->input->post('ch_contents');
    	$data['calories'] = $this->input->post('calories');

    	$data['status'] = $this->input->post('status');
    	$data['main_dp'] = $this->input->post('main_dp');
    	$data['hot_icon'] = $this->input->post('hot_icon');
    	$data['new_icon'] = $this->input->post('new_icon');
    	$pconfig = $this->input->post('pconfig');
    	if($pconfig !== FALSE)
    	{
	    	if(is_array($pconfig) === FALSE) $pconfig = array($pconfig);
	    	if(count($pconfig) > 0) $data['pconfig'] = json_encode($pconfig);
    	}
    	
    	$cate = $this->input->post('cate');
    	if($cate !== FALSE)
    	{
    		$data['cate'] = $cate;    		
    	}
    	
    	if(empty($data['hot_icon']) === TRUE) $data['hot_icon'] = 'N';
    	if(empty($data['new_icon']) === TRUE) $data['new_icon'] = 'N';
    	    	
    	$data['reg_date'] = date('Y-m-d H:i:s');
    	
		$result['result'] = $this->db->insert($this->board_table_name, $data);
		$insert_idx = $this->db->insert_id();
		
		$this->db->set('seq', $insert_idx);
		$this->db->where('idx', $insert_idx);
		$this->db->update($this->board_table_name);
		
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
		
				if($this->_debug == 'Y') $this->add_error($this->board_table_name.'_upload_config', @json_encode($upload_config), 'debug');
		
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
			$this->add_error($this->board_table_name.'_regist_error', '등록이 실패 하였습니다.');
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
		$calories = $this->input->post('calories');
		$sugars = $this->input->post('sugars');
		$protein = $this->input->post('protein');
		$saturated = $this->input->post('saturated');
		$sodium = $this->input->post('sodium');
		$caffeine = $this->input->post('caffeine');
		$data['calories'] = $calories == ''?0:$calories ;
		$data['sugars'] =  $sugars == ''?0:$sugars ;
    	$data['protein'] = $protein == ''?0:$protein ;
    	$data['saturated'] = $saturated == ''?0:$saturated ;
    	$data['sodium'] = $sodium == ''?0:$sodium ;
    	$data['caffeine'] = $caffeine == ''?0:$caffeine ;
				
    	$data['kr_title'] = $this->input->post('kr_title');
    	$data['kr_contents'] = $this->input->post('kr_contents');
    	$data['en_title'] = $this->input->post('en_title');
    	$data['en_contents'] = $this->input->post('en_contents');
    	$data['ch_title'] = $this->input->post('ch_title');
    	$data['ch_contents'] = $this->input->post('ch_contents');

    	$data['status'] = $this->input->post('status');
    	$data['main_dp'] = $this->input->post('main_dp');
    	$data['hot_icon'] = $this->input->post('hot_icon');
    	$data['new_icon'] = $this->input->post('new_icon');
    	$data['seq'] = $this->input->post('seq');
    	$pconfig = $this->input->post('pconfig');
	    if($pconfig !== FALSE)
    	{
	    	if(is_array($pconfig) === FALSE) $pconfig = array($pconfig);
	    	if(count($pconfig) > 0) $data['pconfig'] = json_encode($pconfig);
    	}

    	$cate = $this->input->post('cate');
    	if($cate !== FALSE)
    	{
    		$data['cate'] = $cate;
    	}    	
    	
    	if(empty($data['hot_icon']) === TRUE) $data['hot_icon'] = 'N';
    	if(empty($data['new_icon']) === TRUE) $data['new_icon'] = 'N';    	
		
		$this->db->where('idx', $idx);
		$result['result'] = $this->db->update($this->board_table_name, $data);
		
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
				$upload_config['max_size'] = $list['board_file_size'];
				$upload_config['encrypt_name'] = TRUE;
		
				$this->CI->upload->initialize($upload_config);
		
				if(isset($_FILES[$key]) === TRUE && empty($_FILES[$key]['tmp_name']) === FALSE)
				{
					if ( ! $this->CI->upload->do_upload($key))
					{
						$this->add_error($key.'_error',  strip_tags($this->CI->upload->display_errors()));
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
								$this->add_error($this->board_table_name.'_upload_data', @json_encode($file_info), 'debug');
								$this->add_error($this->board_table_name.'_upload_data_query', $this->db->last_query(), 'debug');
								if($list['thumb_use'] == 'Y') $this->add_error($this->board_table_name.'_upload_thumb_data', @$thumb_file_name, 'debug');
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
								$this->add_error($this->board_table_name.'_upload_data', @json_encode($file_info), 'debug');
								$this->add_error($this->board_table_name.'_upload_data_query', $this->db->last_query(), 'debug');
								if($list['thumb_use'] == 'Y') $this->add_error($this->board_table_name.'_upload_thumb_data', $thumb_file_name, 'debug');
							}
						}
					}
				}
			}
		}		

		if($result['result'] === FALSE)
		{
			$this->add_error($this->board_table_name.'_update_error', '수정이 실패 하였습니다.');
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
							
						$this->db->where('board_id', $this->board_code);
						$this->db->where('board_idx', $idx_list);
						$result[$key.'_db_result'] = $this->db->delete('file_info');
						$this->add_error($key.'_db_result', $result[$key.'_db_result'], 'debug');
					}
				}
			}	
		}
		
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
					$this->db->like("a.kr_title", $parm['search_val']);
					$this->db->or_like("a.en_title", $parm['search_val']);
					$this->db->or_like("a.ch_title", $parm['search_val']);
				break;
				
				case "contents":
					$this->db->like("a.kr_contents", $parm['search_val']);
					$this->db->or_like("a.en_contents", $parm['search_val']);
					$this->db->or_like("a.ch_contents", $parm['search_val']);
				break;
			}
		}

		if( isset($parm['view_flag']) === TRUE && empty($parm['view_flag']) === FALSE ) $this->db->where('view_flag', $parm['view_flag']);
		if( isset($parm['per_page']) === TRUE && empty($parm['per_page']) === FALSE && isset($parm['off_set']) === TRUE  ) $this->db->limit($parm['per_page'], $parm['off_set']);
		if( isset($parm['status']) === TRUE && empty($parm['status']) === FALSE ) $this->db->where('a.status', $parm['status']);
		if( isset($parm['sel_cate']) === TRUE && empty($parm['sel_cate']) === FALSE ) $this->db->where('a.cate', $parm['sel_cate']);
		
		$this->db->from($this->board_table_name.' a');
	
		if( isset($parm['order_by']) === TRUE && empty($parm['order_by']) === FALSE)
		{
			if( isset($parm['order_type']) === FALSE OR empty($parm['order_type']) === TRUE ) $parm['order_type'] = 'desc';
			$this->db->order_by($parm['order_by'], $parm['order_type']);
		}
		else
		{
			$this->db->order_by('a.seq', 'desc');
			$this->db->order_by('a.idx', 'desc');
		}
		$result['result'] = $this->db->get()->result_array();
	
		if($this->_debug == 'Y' ) $this->add_error($this->board_table_name.'_select_query', $this->db->last_query(), 'debug');
	
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
	
		if( $this->_debug == 'Y' )
		{
			$this->add_error($this->board_table_name.'_select_data', json_encode($parm), 'debug');
		}
	
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
		$this->db->where('a.idx', $idx);
		$this->db->from($this->board_table_name.' a');//나중에 join을 할 수도 있으므로 별칭으로 관리한다.
				
		$result['result'] = $this->db->get()->row_array();
		if(isset($result['result']['pconfig']) === TRUE) $result['result']['pconfig'] = json_decode($result['result']['pconfig']); 
		else $result['result']['pconfig'] = array();
		
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
		
			if( $this->_debug == 'Y' )
			{
				$this->add_error($this->board_table_name.'_getdata_file', @json_encode($result['file_info']), 'debug');
			}
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
		$upload_url = $this->get_attr('etc.file_list.web_file.upload_url');
		$this->db->select('a.*');
		$this->db->select('\''.$this->board_code.'\' board_code, ');
		$this->db->from($this->board_table_name.' a');//나중에 join을 할 수도 있으므로 별칭으로 관리한다.
		if($this->get_attr('etc.board_file_use') == 'Y')
		{
			$db_alias = 98;//영문자 b부터 시작
			foreach($this->get_attr('etc.file_list') as $key=>$list)
			{
				
				$this->db->select(chr($db_alias).'.org_name as '.$key.'_org_name, '.chr($db_alias).'.file_name as '.$key.'_file_name, concat(\''.$upload_url.'\',' .chr($db_alias).'.file_name) as '.$key.'_file_name_url');
				$this->db->join('file_info '.chr($db_alias), chr($db_alias).'.field_name = \''.$key.'\' and a.idx='.chr($db_alias).'.board_idx and '.chr($db_alias++).'.board_id = \''.$this->board_code.'\'', 'left');
			}
		}
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