<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @title 게시판
 * @author - 원종필(won0334@chol.com)
 * */
class File_model extends CI_Model 
{
	var $CI = NULL;
    function __construct()
    {
        parent::__construct();
        $this->CI = &get_instance();
    }
	/**
	 * @title 다중 업로드 처리
	 * @author 원종필(won0334@chol.com)
	 * @history
	 * @param Array $parm['file_list'] : 업로드 하고자 하는 파일 필드 배열
	 * @return NULL
	 */ 
    function file_upload($parm = array())
    {
			$this->CI->load->library('upload');
			
			
			foreach($parm['file_list'] as $key=>$list)
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
								'board_id'=>$parm['board_code'],
								'board_idx'=>$parm['insert_idx'],
								'field_name'=>$pamr['key'],
								'reg_date'=>date('Y-m-d H:i:s')
						);
						
						if($list['thumb_use'] == 'Y')
						{
							$this->CI->load->library('image_lib');
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
								$file_info['thumb_name'] = $this->get_thumb_name($upload_data['file_name']);    	
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
	/**
	 * @title 섬네일 이미지 파일명 추출 - 섬네일 생성시 파일명 앞에 thumb_ 가 추가된다. 이부분은 차후 설정 가능하게 바꾸자
	 * @author 원종필(won0334@chol.com)
	 * @history
	 * @param String $file_name : 추출하고자 하는 원본 파일명
	 * @return NULL
	 */ 
    function get_thumb_name($file_name = '')
    {
    	if(empty($file_name) === TRUE) return '';
		$thumb_file_name = "";
		$temp = explode(".", $file_name);
		for($i=0;$i<sizeof($temp)-1;$i++) $thumb_file_name = $thumb_file_name.$temp[$i].".";
		$thumb_file_name = substr($thumb_file_name, 0, -1);
		$thumb_file_name .= "_thumb.".$temp[sizeof($temp)-1];
		
		return $thumb_file_name;
    }
}