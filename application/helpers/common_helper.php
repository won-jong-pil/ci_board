<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @title 긴주소를 구글에서 제공하는 잛은 주소로 변경
 * @author 원종필(won0334@chol.com)
 * @history 구글 api에서 api key 발급및 사용 도메인 등록 후 사용 가능
 * @param String $long_url : 원주소(긴주소)(필수)
 * @return String result : input hidden field
 */
if (function_exists('get_short_url') === FALSE)
{
	function get_short_url($long_url)
	{
		$api_key = "AIzaSyCBNrC1p1alatB_R6Wl4H28jOePZnvBK1w";
		$curlopt_url = "https://www.googleapis.com/urlshortener/v1/url?key=".$api_key;
	
		$ch = curl_init();
		//$timeout = 10;
		curl_setopt($ch, CURLOPT_URL, $curlopt_url);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		//curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		$jsonArray = array('longUrl' => $long_url);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($jsonArray));
		$shortURL = curl_exec($ch);    
		curl_close($ch);
		$result_array = json_decodea($shortURL, true);
	
		return $result_array; // goo.gls
	}
}
/**
 * @title json_decode 함수가 php 5.2이상에서 사용 가능 이하 버전에서 json_decode 함수 대체용
 * @author 원종필(won0334@chol.com)
 * @param String $json : json 값
 * @return Array $x : json 디코딩값
 */
if (function_exists('json_decode') === FALSE)
{
	function json_decode($json)
	{
		$comment = false;
		$out = '$x=';

		for ($i=0; $i<strlen($json); $i++)  
		{
			if (!$comment) 
			{
				if ($json[$i] == '{') $out .= ' array(';
				else if ($json[$i] == '}') $out .= ')';
				else if ($json[$i] == ':') $out .= '=>';
				else $out .= $json[$i];
			}
			else {

				$out .= $json[$i];
			}
			if ($json[$i] == '"') $comment = !$comment;
		}

		eval($out . ';');

		return $x;
	}
}
/**
 * @title json_encode 함수가 php 5.2이상에서 사용 가능 이하 버전에서 json_encode 함수 대체용
 * @author 원종필(won0334@chol.com)
 * @param Array $a : json 변환 요청값
 * @return String $x : json 인코딩값
 */
if(function_exists("json_encode") === FALSE)
{
	function json_encode($a = false)
	{
		if(is_null($a)) return 'null';
		if($a === false) return 'false';
		if($a === true) return 'true';
		if(is_scalar($a))
		{
			if(is_float($a)) return floatval(str_replace(",", ".", strval($a)));
			if(is_string($a))
			{
				static $jsonReplaces = array(array("\\", "/", "\n", "\t", "\r", "\b", "\f", '"'), array('\\\\', '\\/', '\\n', '\\t', '\\r', '\\b', '\\f', '\"'));
				return '"' . str_replace($jsonReplaces[0], $jsonReplaces[1], $a) . '"';
			} 
			else return $a;
		}
		$isList = true;
		for($i=0, reset($a); $i<count($a); $i++, next($a))
		{
			if(key($a) !== $i)
			{
				$isList = false;
				break;
			}
		}
		$result = array();
		if($isList)
		{
			foreach($a as $v) $result[] = json_encode($v);
			return '[' . join(',', $result) . ']';
		} 
		else
		{
			foreach($a as $k => $v) $result[] = json_encode($k).':'.json_encode($v);
			return '{' . join(',', $result) . '}';
		}
	}
}
/**
 * @title 문자의 인코딩셋을 확인하기 위한 함수
 * @author 원종필(won0334@chol.com)
 * @param String $str : 문자열
 * @param String $encodingSet : 체크하려는 문자셋
 * @return Mix $encodingSet : 해당 인코딩 형식 OR FALSE
 */
if (function_exists('detectEncoding') === FALSE)
{
	function detectEncoding($str, $encodingSet = array())
	{
		foreach ($encodingSet as $v) {
			$tmp = iconv($v, $v, $str);
			if (md5($tmp) == md5($str)) return $v;
		}
		return false;
	}
}
/**
 * @title 가중치를 통한 랜덤 값 발생
 * @author 원종필(won0334@chol.com)
 * @param Array $weights : 가중치를 담은 수치 배열
 * @return Int : 램덤 추출 index
 */
if (function_exists('weighted_random') === FALSE)
{
	function weighted_random($weights)
	{
		$count = array_sum($weights);
		if($count <= 0) return 0;
		$r = rand(1, $count);
		for($i=0; $i<count($weights); $i++) {
			$r -= $weights[$i];
			if($r < 1) return $i;
		}
		return false;
	}
}
/**
 * @title 사이트 메타 정보 가져오기
 * @author 원종필(won0334@chol.com)
 * @return String meta 설정값 실패시 false
 */
if (function_exists('get_sitemeta') === FALSE)
{
	function get_sitemeta($meta_key)
	{
		$CI = & get_instance();
		$CI->db->where('meta_key', $meta_key);
		$CI->db->from('site_meta');
		$query_result = $CI->db->get();
		
		if($query_result->num_rows() > 0)
		{
			$result = $query_result->row_array();
			
			return $result['meta_value'];
		}
		else
		{
			return FALSE;
		}
	}
}
/**
 * @title 사이트 메타 정보 설정하기
 * @author 원종필(won0334@chol.com)
 * @param String $meta_key : meta key
 * @param Mix $meta_value : array일경우에는 json으로 변환 최종 문자열 저장함.
 * @return Boolean $result : 실행 결과
 */
if (function_exists('set_sitemeta') === FALSE)
{
	function set_sitemeta($meta_key, $meta_value)
	{
		$CI = & get_instance();
		if(is_array($meta_value) === TRUE) $meta_value = json_encode($meta_value);

		$CI->db->set('meta_value', $meta_value);
		if(get_sitemeta($meta_key) === FALSE)
		{
			$CI->db->set('meta_key', $meta_key);
			$result = $CI->db->insert('site_meta');
		}
		else 
		{
			$CI->db->where('meta_key', $meta_key);
			$result = $CI->db->update('site_meta');
		}
		return $result;
	}
}
/**
 * @title 모바일 판단
 * @author 원종필(won0334@chol.com)
 * @return Boolean $is_mobile : 모바일 여부 true : 모바일 false : 웹
 */
if (function_exists('is_mobile') === FALSE)
{
	function is_mobile()
	{
		static $is_mobile;
	
		if ( isset($is_mobile) )
			return $is_mobile;
	
		if ( empty($_SERVER['HTTP_USER_AGENT']) ) {
			$is_mobile = false;
		} elseif ( strpos($_SERVER['HTTP_USER_AGENT'], 'Mobile') !== false // many mobile devices (all iPhone, iPad, etc.)
			|| strpos($_SERVER['HTTP_USER_AGENT'], 'Android') !== false
			|| strpos($_SERVER['HTTP_USER_AGENT'], 'Silk/') !== false
			|| strpos($_SERVER['HTTP_USER_AGENT'], 'Kindle') !== false
			|| strpos($_SERVER['HTTP_USER_AGENT'], 'BlackBerry') !== false
			|| strpos($_SERVER['HTTP_USER_AGENT'], 'Opera Mini') !== false
			|| strpos($_SERVER['HTTP_USER_AGENT'], 'Opera Mobi') !== false ) {
			$is_mobile = true;
		} else {
			$is_mobile = false;
		}
	
		return $is_mobile;
	}
}
/**
 * @title utf8 문자열 자르기
 * @author 원종필(won0334@chol.com)
 * @return String $str 잘린 문자열
 */
if (function_exists('cut_string_utf8') === FALSE)
{
	function cut_string_utf8($str, $max_len, $suffix = "..")
	{
		$n = 0;
		$noc = 0;
		$len = strlen($str);

		while ( $n < $len )
		{
			$t = ord($str[$n]);
			if ( $t == 9 || $t == 10 || (32 <= $t && $t <= 126) )
			{
				$tn = 1;
				$n++;
				$noc++;
			}
			else if ( 127 <= $t && $t <= 223 )
			{
				$tn = 2;
				$n += 2;
				$noc += 2;
			}
			else if ( 224 <= $t && $t < 239 )
			{
				$tn = 3;
				$n += 3;
				$noc += 2;
			}
			else if ( 240 <= $t && $t <= 247 )
			{
				$tn = 4;
				$n += 4;
				$noc += 2;
			}
			else if ( 248 <= $t && $t <= 251 )
			{
				$tn = 5;
				$n += 5;
				$noc += 2;
			}
			else if ( $t == 252 || $t == 253 )
			{
				$tn = 6;
				$n += 6;
				$noc += 2;
			}
			else { $n++; }
			if ( $noc >= $max_len ) { break; }
		}

		if ( $noc > $max_len ) { $n -= $tn; }

		if($len > $max_len){
			return substr($str, 0, $n) . $suffix;
		} else {
			return $str;
		}
	}
}
/**
 * @title 자바스크립트 alert창 띄우기
 * @author - 원종필(won0334@chol.com)
 * @param String $msg : 출력메세지
 * @param String $url : 메시지 출력후 이동 경로
 * @param Boolean $exit : 메시지 출력후 페이지 처리 중지 여부
 * @param Boolean $close : 메시지 출력후 창(브라우저) 닫기 여부
 * @param Boolean $page_redirect : alert창 출려후 페이지 이동 여부
 * @return NULL
 * */
if(function_exists('alert') === FALSE) 
{
	// 경고메세지를 경고창으로
	function alert($msg = '올바른 방법으로 이용해 주십시오.', $url = '', $exit = FALSE , $close = FALSE, $page_redirect = TRUE) 
	{
		$CI =& get_instance();

		$html = array();
		$html[] = sprintf("<meta charset=\"%s\">", $CI->config->item('web_page_charset'));
		$html[] = "<script type='text/javascript'>";
		$html[] = sprintf("alert(\"%s\");", $msg);
		if($page_redirect === TRUE)
		{
			if($url)
				$html[] = sprintf("location.href=\"%s\";", $url);
			else
				$html[] = "history.go(-1);";
		}
		if($close) $html[] = "window.close();";
		$html[] = "</script>";
		echo @implode("\r\n", $html);
		if($exit)  exit;
	}
}
/**
 * @title 자바스크립트 문장 실행
 * @author - 원종필(won0334@chol.com)
 * @param String $exec : 실행 문자열
 * @param Boolean $exit : 실행 중지 여부
 * @return NULL
 * */
if(function_exists('script_exec') === FALSE)
{
	// 경고메세지를 경고창으로
	function script_exec($exec = '', $exit = FALSE)
	{
		$CI =& get_instance();

		$html = array();
		$html[] = sprintf("<meta charset=\"%s\">", $CI->config->item('web_page_charset'));
		$html[] = "<script type='text/javascript'>";
		$html[] = $exec;
		$html[] = "</script>";
		echo (@implode("\r\n", $html));
		if($exit)  exit;
	}
}
/**
 * @title 무작위 일련번호를 생성하기 위한 메소드
 * @author - 원종필(won0334@chol.com)
 * @return String : 랜덤 문자열
 * */
if(function_exists('rand_num') === FALSE)
{
	function rand_num()
	{
		return strtoupper(md5(rand(1000,2000 ).date("Y-m-d H:i:s")));
	}
}
/**
 * @title 검색 폼에서 넘어온 항목들을 리스트 처리 부분에서 처리 할 수 있도록 관리 하기 위함 검색 항목을 $_POST와 segment를 검색하여 존재하는 정보를 리턴
 * @author - 원종필(won0334@chol.com)
 * @param Array $post_array : 검색 폼 항목 이름 배열
 * @param Array $url_array : segment 배열
 * @return Array $result : 존재하는 값
 * */
if(function_exists('convert_search') === FALSE)
{
	function convert_search($post_array = array(), $url_array = array())
	{
		$CI = &get_instance();
		$result = array();
		foreach($post_array as $list)
		{
			$key_value = $CI->input->post($list);

			if( isset($key_value) === TRUE && empty($key_value) === FALSE ) $result[$list] = $key_value;
			elseif( isset($url_array[$list]) === TRUE ) $result[$list] = $url_array[$list];
			elseif( isset($result[$list]) === FALSE || empty($result[$list]) === TRUE ) unset($result[$list]);
		}

		return $result;
	}
}
/**
 * @title 폼 전송시 페이지 만료 처리 하기 위함.
 * @author - 원종필(won0334@chol.com)
 * @return NULL
 * */
if(function_exists('set_form') === FALSE)
{
   function set_form()
   {
   		$CI = &get_instance();
		header("Content-Type: text/html; charset=".$CI->config->item('web_page_charset'));
		$gmnow = gmdate("D, d M Y H:i:s") . " GMT";
		header("Expires: 0"); // rfc2616 - Section 14.21
		header("Last-Modified: " . $gmnow);
		header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
		header("Cache-Control: pre-check=0, post-check=0, max-age=0"); // HTTP/1.1
		header("Pragma: no-cache"); // HTTP/1.0		
   }
}
/**
 * @title '<pre></pre>' 태그를 앞뒤에 붙여 보기 편하게 함 
 * @author - 원종필(won0334@chol.com)
 * @history : 
 * 		20140528(원종필) - 출렫 대상이 array, object, string 일 경우에도 출력될 수 있도록 변경
 * 		20140528(원종필) - ip에 따른 출력 제어 추가, 작업 종료 시간 추가
 * 		20140620(원종필) - 타이틀 문자열 출력을 위한 변수 추가
 * 		20150427(원종필) - 옵션 전달 방식을 array로 변경
 * @param Array $data : 출력 데이터
 * @param Array $option : 출력 옵션 exit:중단여부, title:제목, ip:출력 제한 아이피
 * @return NULL
 * */
if(function_exists('print_rr') === FALSE)
{
	function print_rr($data = array(), $option = array())
	{
		if(isset($option['exit']) === FALSE) $option['exit'] = FALSE;
		if(isset($option['title']) === FALSE) $option['title'] = '';
		if(isset($option['ip']) === FALSE) $option['ip'] = array('0.0.0.0', '::1', '10.240.49.97');

		//if(isset($_SERVER["REMOTE_ADDR"]) === TRUE && in_array($_SERVER["REMOTE_ADDR"], $option['ip']))
		{
			if(empty($data) === FALSE) echo '=========================================='.$option['title'].'=========================================<br />';
			echo '<Pre>';
			if(is_array($data) === TRUE)
				print_r($data);
			else if(is_object($data) === TRUE)
				var_dump($data);
			else if(is_bool($data) === TRUE)
				echo $data===TRUE? 'TRUE':'FALSE';
			else if(is_null($data) === TRUE)
				var_dump($data);
			else
				echo $data;
			
			echo '<br />print_rr ttime : '.date('Y-m-d H:i:s').'<br />';
			echo '</Pre>';
		}
		
		if($option['exit'] === TRUE) exit;
	}
} 
/**
 * @title 입력 폼 처리시 전화번호 배열 또는 완성된 select 양식을 처리 하기 위한 함수
 * @author - 원종필(won0334@chol.com)
 * @param Int $parm['sel_type'] : 가지고올 전화번호 형태 0:tel 1: hp 2:tel&hp
 * @param String $parm['return_type'] : 리턴 형태 string:합쳐진 문자열 형태 ,array:배열 형태  , select:폼 select box 형태
 * @param String $parm['form_id'] : select box 리턴시 설정할 id값
 * @param Mix $parm['sel_value'] : 폼 수정시 선택값
 * @return Mix
 * */
if(function_exists('get_tel_num') === FALSE)
{
	function get_tel_num($parm = array())
	{
		if(isset($parm['sel_type']) === FALSE) $parm['sel_type'] = 'tel';
		switch($parm['sel_type'])
		{
			case 'tel':
				$numArray = array(
					"02"=>"02",
					"031"=>"031",
					"032"=>"032",
					"033"=>"033",
					"041"=>"041",
					"042"=>"042",
					"043"=>"043",
					"051"=>"051",
					"052"=>"052",
					"053"=>"053",
					"054"=>"054",
					"055"=>"055",
					"061"=>"061",
					"062"=>"062",
					"063"=>"063",
					"064"=>"064",
					"070"=>"070",
					"080"=>"080",
				);
			break;
					
			case 'hp':
				$numArray = array(
					"010"=>"010",
					"011"=>"011",
					"013"=>"013",
					"016"=>"016",
					"017"=>"017",
					"018"=>"018",
					"019"=>"019",
				);
			break;
					
			case 'tel&hp':
				$numArray = array(
					"010"=>"010",
					"02"=>"02 (서울)",
					"031"=>"031 (경기)",
					"032"=>"032 (인천)",
					"033"=>"033 (강원)",
					"041"=>"041 (충남)",
					"042"=>"042 (대전)",
					"043"=>"043 (충북)",
					"051"=>"051 (부산)",
					"052"=>"052 (울산)",
					"053"=>"053 (대구)",
					"054"=>"054 (경북)",
					"055"=>"055 (경남)",
					"061"=>"061 (전남)",
					"062"=>"062 (광주)",
					"063"=>"063 (전북)",
					"064"=>"064 (제주)",
					"0130"=>"0130",
					"080"=>"080",
					"070"=>"070",
					"0502"=>"0502",
					"0504"=>"0504",
					"0505"=>"0505",
					"0506"=>"0506",
					"0303"=>"0303",
				);
			break;
		}

		if(isset($parm['return_type']) === FALSE) $parm['return_type'] = 'array';
		switch($parm['return_type'])
		{
			case "string":

			break;
					
			case "array":
				return $numArray;
			break;

			case "select":
				$result = sprintf('<select name="%s" id="%s" title="%s" %s>', $parm['form_id'], $parm['form_id'], $parm['title'], $parm['style']);
				foreach($numArray as $key=>$value)
				{
					$selected = $parm['sel_value'] == $key ? " selected" : "";
					$result .= "<option value='".$key."'$selected>".$value."</option>";
				}
				$result .= "</select>";
				return $result;
			break;
		}
	}
}
/**
 * @title 입력 폼 처리시 전화번호 배열 또는 완성된 select 양식을 처리 하기 위한 함수
 * @author - 원종필(won0334@chol.com)
 * @return Array 이메일 배열
 * */
if(function_exists('get_email_addr') === FALSE)
{
	function get_email_addr($k = '')
	{
		$emailArr = array(
				""=>"직접입력",
				"nate.com"=>"nate.com",
				"hanmail.net"=>"hanmail.net",
				"dreamwiz.com"=>"dreamwiz.com",
				"naver.com"=>"naver.com",
				"lycos.co.kr"=>"lycos.co.kr",
				"yahoo.co.kr"=>"yahoo.co.kr",
				"netian.com"=>"netian.com",
				"empal.com"=>"empal.com",
				"hanmir.com"=>"hanmir.com",
				"hotmail.com"=>"hotmail.com",
				"chollian.net"=>"chol.com"
		);

		if($k != ''){
			return $emailArr[$k];
		} else {
			return $emailArr;
		}
	}
}
/*common_helper end*/