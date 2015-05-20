<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @title 게시판
 * @author 원종필(won0334@chol.com)
 * @history
 * */
class Board_drv extends CI_Driver_Library {
	public $CI = NULL;//CI instance
	protected $valid_drivers = array(
		'manager', 'users', 'movie', 'logs', 'product', 'popup', 'banner', 'event', 'faq',
		'notice', 'product', 'career', 'comment', 'store', 'event_app', 'rbt', 'point'
	);//게시판 유형 정의(각 드라이버 파일명)	
	protected $_now_type = '';//선택된 드라이버
	public $debug = 'N';//디버깅 여부	N:디버그 안함, Y:로그파일에 디버깅용 데이터를 기록
	/**
	 * @title 게시판 - 게시판
	 * @author 원종필(won0334@chol.com)
	 * @history
	 */	
	public function __construct($parm = array())
	{
		if(isset($parm['debug']) === TRUE && empty($parm['debug']) === FALSE) $this->debug = $parm['debug'];
		if(isset($parm['board_code']) === TRUE && empty($parm['board_code']) === FALSE)
		{
			if($this->debug == 'Y')
			{
				$parm['log_title'] = 'construct config';
				Log_message('tdebug', $parm);
			}
			$this->CI = &get_instance();
			$this->_initialize($parm);
		}
		else
		{
			Log_message('error', 'board_no_code');
			throw new Exception('board_no_code');
		}
	}
	/**
	 * @title 게시판 - 게시판 종료 처리
	 * @author 원종필(won0334@chol.com)
	 * @history
	 */
	public function __destruct(){}
	/**
	 * @title 데이터 베이스 설정 변경
	 * @author 원종필(won0334@chol.com)
	 * @history
	 * @param Array $config : 게시판 설정값
	 * @return NULL
	 */
	public function set_db_instance($database_name = 'erp')
	{
		//putenv('FREETDSCONF=/usr/local/freetds/etc/freetds.conf');
		$this->CI->db = $this->CI->load->database($database_name, TRUE);

		return TRUE;
	}
	/**
	 * @title 게시판 - 게시판 초기화 처리
	 * @author 원종필(won0334@chol.com)
	 * @history
	 * @param Array $parm : 게시판 설정값
	 * @return NULL
	 */
	public function _initialize($parm = array())
	{
		$info = $this->get_board_info($parm['board_code']);
		if($this->debug == 'Y')
		{
			$parm['log_title'] = 'init cofig';
			Log_message('tdebug', $parm);
			$info['log_title'] = 'init get board info';
			Log_message('tdebug', $info);
		}
		$parm = array_merge($info, $parm);
		$this->_now_type = $parm['board_type'];
		$this->{$this->_now_type}->initialize($parm);
	}
	/**
	 * @title 게시판 기본 속성 정보 가져오기
	 * @author 원종필(won0334@chol.com)
	 * @history
	 * @param String $attr_name : 속성명
	 * @return NULL
	 */
	public function get_attr($attr_name)
	{
		return $this->{$this->_now_type}->get_attr($attr_name);
	}
	/**
	 * @title 게시판 기본 속성 정보 설정하기
	 * @author 원종필(won0334@chol.com)
	 * @history
	 * @param String $attr_name : 속성명
	 * @param Mix $attr_value : 속성값
	 * @return NULL
	 */
	public function set_attr($attr_name, $attr_value)
	{
		return $this->{$this->_now_type}->set_attr($attr_name, $attr_value);
	}
	/**
	 * @title 게시판
	 * @author 원종필(won0334@chol.com)
	 * @history
	 * @param Array $config : 게시판 설정값
	 * @return Boolean $result['result'] : insert 결과 ( true: 성공 false : 실패)
	 * @return String $result['error'] : 에러 메세지 
	 */
	public function insert($parm = array())
	{
		if($this->debug == 'Y')
		{
			$parm['log_title'] = 'insert config';
			Log_message('tdebug', $parm);
		}
		return $this->{$this->_now_type}->insert($parm);
	}
	/**
	 * @title 게시판 - 수정
	 * @author 원종필(won0334@chol.com)
	 * @history
	 * @param Int $idx : 게시물 일련번호
	 * @param Array $config : 게시판 설정값
	 * @return NULL
	 */
	public function update($idx, $parm)
	{
		if($this->debug == 'Y')
		{
			$parm['log_title'] = 'update config';
			$parm['idx'] = $idx;
			Log_message('tdebug', $parm);
		}
		return $this->{$this->_now_type}->update($idx, $parm);
	}
	/**
	 * @title 게시판 - 삭제
	 * @author 원종필(won0334@chol.com)
	 * @history
	 * @param Int $idx : 게시물 일련번호
	 * @param Array $config : 게시판 설정값
	 * @return NULL
	 */
	public function delete($idx, $parm = array())
	{
		if($this->debug == 'Y')
		{
			$parm['log_title'] = 'delete config';
			$parm['idx'] = $idx;
			Log_message('tdebug', $parm);
		}		
		return $this->{$this->_now_type}->delete($idx, $parm);
	}
	/**
	 * @title 게시판 - 리스트
	 * @author 원종필(won0334@chol.com)
	 * @history
	 * @param Array $parm : 리스트 설정값
	 * @return NULL
	 */
	public function get_list($parm = array())
	{
		if($this->debug == 'Y')
		{
			$parm['log_title'] = 'dget list config';
			Log_message('tdebug', $parm);
		}			
		return $this->{$this->_now_type}->get_list($parm);
	}
	/**
	 * @title 게시판 - 단일 게시물 정보 기져오기
	 * @author 원종필(won0334@chol.com)
	 * @history
	 * @param Int $idx : 게시물 일련번호
	 * @return NULL
	 */
	public function get_data($idx)
	{
		if($this->debug == 'Y')
		{
			$parm['log_title'] = 'get data idx';
			$parm['idx'] = $idx;
			Log_message('tdebug', $parm);
		}			
		return $this->{$this->_now_type}->get_data($idx);
	}
	/**
	 * @title 게시판 최근 데이터 가져오기
	 * @author 원종필(won0334@chol.com)
	 * @history 
	 * @param Int $count : 게시물 갯수
	 * @return NULL
	 */
	public function get_top_data($parm = array())
	{
		if($this->debug == 'Y')
		{
			$parm['log_title'] = 'get top data config';
			Log_message('tdebug', $parm);
		}		
		return $this->{$this->_now_type}->get_top_data($parm);
	}
	/**
	 * @title 게시판 - 조회수 증가
	 * @author 원종필(won0334@chol.com)
	 * @history
	 * @param Int $idx : 게시물 일련번호
	 * @return NULL
	 */
	public function hit($idx)
	{
		if($this->debug == 'Y')
		{
			$parm['log_title'] = 'hit idx';
			$parm['idx'] = $idx;
			Log_message('tdebug', $parm);
		}		
		return $this->{$this->_now_type}->hit($idx);
	}

	/**
	 * @title 게시판 - 다음 게시물 조회
	 * @author 원종필(won0334@chol.com)
	 * @history
	 * @param Int $idx : 게시물 일련번호
	 * @return 다음 게시물 정보
	 */
	public function get_next($idx)
	{
		if($this->debug == 'Y')
		{
			$parm['log_title'] = 'get next idx';
			$parm['idx'] = $idx;
			Log_message('tdebug', $parm);
		}		
		return $this->{$this->_now_type}->get_next($idx);
	}
	/**
	 * @title 게시판 - 이전 게시물 조회
	 * @author 원종필(won0334@chol.com)
	 * @history
	 * @param Int $idx : 게시물 일련번호
	 * @return 이전 게시물 정보
	 */
	public function get_prev($idx)
	{
		if($this->debug == 'Y')
		{
			$parm['log_title'] = 'get prev idx';
			$parm['idx'] = $idx;
			Log_message('tdebug', $parm);
		}		
		return $this->{$this->_now_type}->get_prev($idx);
	}
	/**
	 * @title 게시판 기본 정보 가져오기
	 * @author 원종필(won0334@chol.com)
	 * @history
	 * @param String $board_code : 게시판 코드
	 * @return $board_info : 게시판 정보 배열
	 */
	public function get_board_info($board_code)
	{
		if( empty($board_code) === TRUE)
		{
			Log_message('error', 'board_no_code');
			throw new Exception('board_no_code');
		}
		if($this->debug == 'Y')	Log_message('tdebug', 'get board info board code : '.$board_code);
		
		$this->CI->db->from('board_info');
		$this->CI->db->where('board_code', $board_code);
		$board_info = $this->CI->db->get()->row_array();
		if( $board_info === FALSE OR $board_info === NULL OR count($board_info) === 0)
		{
			Log_message('error', 'board_no_info');
			throw new Exception('board_no_info');
		}
		if($this->debug == 'Y')
		{
			$board_info['log_title'] = 'get board info';
			Log_message('tdebug', $board_info);			
		}
		if(is_null($board_info['etc']) === FALSE && empty($board_info['etc']) === FALSE)
		{
			$etc = json_decode($board_info['etc'], true);
			$board_info['etc'] = $etc;
		}
		//디비상의 기본값을 설정토록한다. 설정이 안되있을 경우를 대비해 필수 항목에 대한 기본값을 설정한다.
		if( isset($board_info['etc']['per_page']) === FALSE OR empty($board_info['etc']['per_page']) === TRUE) $board_info['etc']['per_page'] = '10';//한화면에 뿌려질 리스트 갯수
		if( isset($board_info['etc']['page_size']) === FALSE OR empty($board_info['etc']['page_size']) === TRUE) $board_info['etc']['page_size'] = '10';//한화면에 뿌려질 페이징 갯수
		if( isset($board_info['etc']['board_file_use']) === FALSE OR empty($board_info['etc']['board_file_use']) === TRUE) $board_info['etc']['board_file_use'] = 'N';//파일 사용 여부

		if( isset($board_info['etc']['file_list']) === FALSE OR empty($board_info['etc']['file_list']) === TRUE)
		{
			$board_info['etc']['file_list'] = array('file');//게시판 저장 파일 폼 필드명
			$board_info['etc']['file']['board_file_size'] = 2048;//업로드 파일 가능 크기
			$board_info['etc']['file']['board_file_allow'] = 'jpg|jpeg|gif|png|zip|rar|alz|pdf|xls|xlsx|ppt|pptx|doc|docx';//게시판 허용 파일 타입
			$board_info['etc']['file']['upload_path'] = $_SERVER['DOCUMENT_ROOT'].'/updata/';//게시판 저장 파일 실제 저장 위치(절대경로)
			$board_info['etc']['file']['upload_url'] = '/updata/';//게시판 저장 파일 웹 접근 경로
			$board_info['etc']['file']['thumb_use'] = 'N';//섬네일 사용 여부
		}
		if($this->debug == 'Y')
		{
			$board_info['log_title'] = 'return board info';
			Log_message('tdebug', $board_info);			
		}
		return $board_info;
	}
}
// End Class