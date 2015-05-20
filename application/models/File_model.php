<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @title 파일 처리
 * @author - 원종필(won0334@chol.com)
 * */
class File_model extends CI_Model 
{
	var $CI = NULL;
	public $debug = 'N';//디버깅 여부	N:디버그 안함, Y:로그파일에 디버깅용 데이터를 기록
	/**
	 * @title 파일 처리
	 * @author 원종필(won0334@chol.com)
	 */	
    public function __construct()
    {
        parent::__construct();
        $this->CI = &get_instance();
    }
	/**
	 * @title 파일 업로드 초기화 처리
	 * @author 원종필(won0334@chol.com)
	 * @param Array $parm : 게시판 설정값
	 */	
	public function initialize($parm = array())
	{
		if($this->debug == 'Y')
		{
			$log_data = $parm;
			$log_data['log_title'] = 'file_model init cofig';
			Log_message('tdebug', $log_data);
		}

		foreach ($parm as $key=>$value)
		{
			if (isset($parm[$key]) === TRUE && empty($parm[$key]) === FALSE && property_exists($this, $key) === TRUE)
			{
				$this->{$key} = $parm[$key];
			}
		}
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
		if($this->debug == 'Y')
		{
			$log_data = $parm;
			$log_data['log_title'] = 'file_upload cofig';
			Log_message('tdebug', $log_data);
		}    	
		
		if(isset($parm['file_list']) === TRUE && count($parm['file_list']) > 0)
		{
			$this->CI->load->library('upload');
			foreach($parm['file_list'] as $key=>$list)
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
						if($this->debug == 'Y')
						{
							$log_data = $upload_config;
							$log_data['log_title'] = 'file_upload fail';
							$log_data['fail message'] = $this->CI->upload->display_errors();
							Log_message('tdebug', $log_data);
						}						
						Log_message('error', $this->CI->upload->display_errors());
						$result[$key]['result'] = FALSE;
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

						$result[$key]['result'] = $this->CI->db->insert('file_info', $file_info);
						$result[$key]['file_info'] = $file_info;
						if($this->debug == 'Y')
						{
							$log_data['log_title'] = 'file_upload data';
							$log_data['query'] = $this->CI->db->last_query();
							$log_data['file_result'] = $result;
							Log_message('tdebug', $log_data);
						}  						
					}
				}		
			}
			
			return $result;
		}
		else
		{
			Log_message('error', 'no file list');
			return FALSE;
		}
    }
	/**
	 * @title 섬네일 이미지 파일명 추출 - 섬네일 생성시 파일명 앞에 thumb_ 가 추가된다. 이부분은 차후 설정 가능하게 바꾸자
	 * @author 원종필(won0334@chol.com)
	 * @param String $file_name : 추출하고자 하는 원본 파일명
	 * @return String $thumb_file_name : 섬네일 이미지명
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
	/**
	 * @title 게시물에 연결되어 있는 파일 정보 가져오기
	 * @author 원종필(won0334@chol.com)
	 * @param Array $parm : 설정값
	 * @return NULL
	 */ 
    function get_data($parm = array())
    {

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
	
			if( $this->debug == 'Y' )
			{
				unset($log_data);
				$log_data['log_title'] = 'get data file';
				$log_data['board_table_name'] = $this->board_table_name;
				$log_data['file_info'] = $result['file_info'];
				Log_message('tdebug', $log_data);
			}			
		}    	
    }
}