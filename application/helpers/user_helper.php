<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @title 사용자 로그인 여부 판단.
 * @author 원종필(won0334@chol.com)
 * @history
 * @param 
 * @return boolean true : 로그인된 상태 false : 비 로그인
 */
function is_login()
{
	$CI = & get_instance();
	$id = $CI->session->userdata('id');
	
	if($id !== FALSE)
	{
		return TRUE;
	}
	else
	{
		return FALSE;
	}
}
/**
 * @title sms 추가 함수
 * @author 나이스
 * @history
 * @param 
 * @return
 */
if ( ! function_exists('GetValue'))
{
	function GetValue($str , $name)
	{
		$pos1 = 0;  //length의 시작 위치
		$pos2 = 0;  //:의 위치
	
		while( $pos1 <= strlen($str) )
		{
			$pos2 = strpos( $str , ":" , $pos1);
			$len = substr($str , $pos1 , $pos2 - $pos1);
			$key = substr($str , $pos2 + 1 , $len);
			$pos1 = $pos2 + $len + 1;
			if( $key == $name )
			{
				$pos2 = strpos( $str , ":" , $pos1);
				$len = substr($str , $pos1 , $pos2 - $pos1);
				$value = substr($str , $pos2 + 1 , $len);
				return $value;
			}
			else
			{
				// 다르면 스킵한다.
				$pos2 = strpos( $str , ":" , $pos1);
				$len = substr($str , $pos1 , $pos2 - $pos1);
				$pos1 = $pos2 + $len + 1;
			}
		}
	}
}
/**
 * @title sms 암호화 코드 생성하기
 * @author 원종필(won0334@chol.com)
 * @history
 * @param $url : 이동 url
 * @return string $enc_data : 인코딩 문자열
 */
if ( ! function_exists('get_sms_code'))
{
	function get_sms_code($sReturnURL = '', $serrorUrl = '', $cust = '')
	{
		$CI = & get_instance();

	    $sitecode = $CI->config->item('sms_site_code');				// NICE로부터 부여받은 사이트 코드
	    $sitepasswd = $CI->config->item('sms_site_pass');			// NICE로부터 부여받은 사이트 패스워드
	    $cb_encode_path = $CI->config->item('sms_module_pass');		// NICE로부터 받은 암호화 프로그램의 위치 (절대경로+모듈명)

	    // CheckPlus(본인인증) 처리 후, 결과 데이타를 리턴 받기위해 다음예제와 같이 http부터 입력합니다.
	    $returnurl = $sReturnURL;	// 성공시 이동될 URL
	    $errorurl = $serrorUrl;		// 실패시 이동될 URL
	    $authtype = "";      	// 없으면 기본 선택화면, X: 공인인증서, M: 핸드폰, C: 카드
		$popgubun 	= "N";		//Y : 취소버튼 있음 / N : 취소버튼 없음
		$customize 	= $cust;			//없으면 기본 웹페이지 / Mobile : 모바일페이지

	    $reqseq = `$cb_encode_path SEQ $sitecode`;
	    // reqseq값은 성공페이지로 갈 경우 검증을 위하여 세션에 담아둔다.
	    $CI->session->set_userdata('REQ_SEQ', $reqseq) ;
	
	    // 입력될 plain 데이타를 만든다.
	    $plaindata =  "7:REQ_SEQ" . strlen($reqseq) . ":" . $reqseq .
				    			  "8:SITECODE" . strlen($sitecode) . ":" . $sitecode .
				    			  "9:AUTH_TYPE" . strlen($authtype) . ":". $authtype .
				    			  "7:RTN_URL" . strlen($returnurl) . ":" . $returnurl .
				    			  "7:ERR_URL" . strlen($errorurl) . ":" . $errorurl .
				    			  "11:POPUP_GUBUN" . strlen($popgubun) . ":" . $popgubun .
				    			  "9:CUSTOMIZE" . strlen($customize) . ":" . $customize ;

	    $enc_data = `$cb_encode_path ENC $sitecode $sitepasswd $plaindata`;

		return $enc_data;
	}
}
/**
 * @title ipin 암호화 코드 생성하기
 * @author 원종필(won0334@chol.com)
 * @history
 * @param $url : 이동 url
 * @return
 */
if ( ! function_exists('get_ipin_code'))
{
	function get_ipin_code($sReturnURL = '')
	{
		$CI = & get_instance();
		
		$sEncData					= "";			// 암호화 된 데이타
		$sRtnMsg					= "";			// 처리결과 메세지
		$sCPRequest					= "";			// 하단내용 참조
		
		$sSiteCode					= $CI->config->item('site_code');			// IPIN 서비스 사이트 코드		(NICE평가정보에서 발급한 사이트코드)
		$sSitePw					= $CI->config->item('site_pass');			// IPIN 서비스 사이트 패스워드	(NICE평가정보에서 발급한 사이트패스워드)
		$sModulePath				= $CI->config->item('module_pass');
		
		$sCPRequest = `$sModulePath SEQ $sSiteCode`;
		$CI->session->set_userdata('CPREQUEST', $sCPRequest) ;
		// 리턴 결과값에 따라, 프로세스 진행여부를 파악합니다.
		// 실행방법은 싱글쿼터(`) 외에도, 'exec(), system(), shell_exec()' 등등 귀사 정책에 맞게 처리하시기 바랍니다.
		$sEncData	= `$sModulePath REQ $sSiteCode $sSitePw $sCPRequest $sReturnURL`;
		
		return $sEncData;
	}
}