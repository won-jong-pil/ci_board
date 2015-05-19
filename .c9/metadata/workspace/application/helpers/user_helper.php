{"filter":false,"title":"user_helper.php","tooltip":"/application/helpers/user_helper.php","undoManager":{"mark":33,"position":33,"stack":[[{"group":"doc","deltas":[{"start":{"row":0,"column":0},"end":{"row":0,"column":75},"action":"insert","lines":["<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');"]}]}],[{"group":"doc","deltas":[{"start":{"row":0,"column":75},"end":{"row":1,"column":0},"action":"insert","lines":["",""]}]}],[{"group":"doc","deltas":[{"start":{"row":1,"column":0},"end":{"row":21,"column":1},"action":"insert","lines":["/**"," * @title 사용자 로그인 여부 판단."," * @author 원종필(won0334@chol.com)"," * @history"," * @param "," * @return boolean true : 로그인된 상태 false : 비 로그인"," */","function is_login()","{","\t$CI = & get_instance();","\t$id = $CI->session->userdata('id');","\t","\tif($id !== FALSE)","\t{","\t\treturn TRUE;","\t}","\telse","\t{","\t\treturn FALSE;","\t}","}"]}]}],[{"group":"doc","deltas":[{"start":{"row":21,"column":1},"end":{"row":22,"column":0},"action":"insert","lines":["",""]}]}],[{"group":"doc","deltas":[{"start":{"row":22,"column":0},"end":{"row":130,"column":1},"action":"insert","lines":["/**"," * @title sms 추가 함수"," * @author 나이스"," * @history"," * @param "," * @return"," */","if ( ! function_exists('GetValue'))","{","\tfunction GetValue($str , $name)","\t{","\t\t$pos1 = 0;  //length의 시작 위치","\t\t$pos2 = 0;  //:의 위치","\t","\t\twhile( $pos1 <= strlen($str) )","\t\t{","\t\t\t$pos2 = strpos( $str , \":\" , $pos1);","\t\t\t$len = substr($str , $pos1 , $pos2 - $pos1);","\t\t\t$key = substr($str , $pos2 + 1 , $len);","\t\t\t$pos1 = $pos2 + $len + 1;","\t\t\tif( $key == $name )","\t\t\t{","\t\t\t\t$pos2 = strpos( $str , \":\" , $pos1);","\t\t\t\t$len = substr($str , $pos1 , $pos2 - $pos1);","\t\t\t\t$value = substr($str , $pos2 + 1 , $len);","\t\t\t\treturn $value;","\t\t\t}","\t\t\telse","\t\t\t{","\t\t\t\t// 다르면 스킵한다.","\t\t\t\t$pos2 = strpos( $str , \":\" , $pos1);","\t\t\t\t$len = substr($str , $pos1 , $pos2 - $pos1);","\t\t\t\t$pos1 = $pos2 + $len + 1;","\t\t\t}","\t\t}","\t}","}","/**"," * @title sms 암호화 코드 생성하기"," * @author 원종필(won0334@chol.com)"," * @history"," * @param $url : 이동 url"," * @return"," */","if ( ! function_exists('get_sms_code'))","{","\tfunction get_sms_code($sReturnURL = '', $serrorUrl = '', $cust = '')","\t{","\t\t$CI = & get_instance();","","\t    $sitecode = $CI->config->item('sms_site_code');\t\t\t\t// NICE로부터 부여받은 사이트 코드","\t    $sitepasswd = $CI->config->item('sms_site_pass');\t\t\t// NICE로부터 부여받은 사이트 패스워드","\t    $cb_encode_path = $CI->config->item('sms_module_pass');\t\t// NICE로부터 받은 암호화 프로그램의 위치 (절대경로+모듈명)","","\t    // CheckPlus(본인인증) 처리 후, 결과 데이타를 리턴 받기위해 다음예제와 같이 http부터 입력합니다.","\t    $returnurl = $sReturnURL;\t// 성공시 이동될 URL","\t    $errorurl = $serrorUrl;\t\t// 실패시 이동될 URL","\t    $authtype = \"\";      \t// 없으면 기본 선택화면, X: 공인인증서, M: 핸드폰, C: 카드","\t\t$popgubun \t= \"N\";\t\t//Y : 취소버튼 있음 / N : 취소버튼 없음","\t\t$customize \t= $cust;\t\t\t//없으면 기본 웹페이지 / Mobile : 모바일페이지","","\t    $reqseq = `$cb_encode_path SEQ $sitecode`;","\t    // reqseq값은 성공페이지로 갈 경우 검증을 위하여 세션에 담아둔다.","\t    $CI->session->set_userdata('REQ_SEQ', $reqseq) ;","\t","\t    // 입력될 plain 데이타를 만든다.","\t    $plaindata =  \"7:REQ_SEQ\" . strlen($reqseq) . \":\" . $reqseq .","\t\t\t\t    \t\t\t  \"8:SITECODE\" . strlen($sitecode) . \":\" . $sitecode .","\t\t\t\t    \t\t\t  \"9:AUTH_TYPE\" . strlen($authtype) . \":\". $authtype .","\t\t\t\t    \t\t\t  \"7:RTN_URL\" . strlen($returnurl) . \":\" . $returnurl .","\t\t\t\t    \t\t\t  \"7:ERR_URL\" . strlen($errorurl) . \":\" . $errorurl .","\t\t\t\t    \t\t\t  \"11:POPUP_GUBUN\" . strlen($popgubun) . \":\" . $popgubun .","\t\t\t\t    \t\t\t  \"9:CUSTOMIZE\" . strlen($customize) . \":\" . $customize ;","","\t    $enc_data = `$cb_encode_path ENC $sitecode $sitepasswd $plaindata`;","","\t\treturn $enc_data;","\t}","}","/**"," * @title ipin 암호화 코드 생성하기"," * @author 원종필(won0334@chol.com)"," * @history"," * @param $url : 이동 url"," * @return"," */","if ( ! function_exists('get_ipin_code'))","{","\tfunction get_ipin_code($sReturnURL = '')","\t{","\t\t$CI = & get_instance();","\t\t","\t\t$sEncData\t\t\t\t\t= \"\";\t\t\t// 암호화 된 데이타","\t\t$sRtnMsg\t\t\t\t\t= \"\";\t\t\t// 처리결과 메세지","\t\t$sCPRequest\t\t\t\t\t= \"\";\t\t\t// 하단내용 참조","\t\t","\t\t$sSiteCode\t\t\t\t\t= $CI->config->item('site_code');\t\t\t// IPIN 서비스 사이트 코드\t\t(NICE평가정보에서 발급한 사이트코드)","\t\t$sSitePw\t\t\t\t\t= $CI->config->item('site_pass');\t\t\t// IPIN 서비스 사이트 패스워드\t(NICE평가정보에서 발급한 사이트패스워드)","\t\t$sModulePath\t\t\t\t= $CI->config->item('module_pass');","\t\t","\t\t$sCPRequest = `$sModulePath SEQ $sSiteCode`;","\t\t$CI->session->set_userdata('CPREQUEST', $sCPRequest) ;","\t\t// 리턴 결과값에 따라, 프로세스 진행여부를 파악합니다.","\t\t// 실행방법은 싱글쿼터(`) 외에도, 'exec(), system(), shell_exec()' 등등 귀사 정책에 맞게 처리하시기 바랍니다.","\t\t$sEncData\t= `$sModulePath REQ $sSiteCode $sSitePw $sCPRequest $sReturnURL`;","\t\t","\t\treturn $sEncData;","\t}","}"]}]}],[{"group":"doc","deltas":[{"start":{"row":64,"column":10},"end":{"row":64,"column":11},"action":"insert","lines":[" "]}]}],[{"group":"doc","deltas":[{"start":{"row":64,"column":11},"end":{"row":64,"column":12},"action":"insert","lines":["ㄴ"]}]}],[{"group":"doc","deltas":[{"start":{"row":64,"column":12},"end":{"row":64,"column":13},"action":"insert","lines":["ㅅ"]}]}],[{"group":"doc","deltas":[{"start":{"row":64,"column":13},"end":{"row":64,"column":14},"action":"insert","lines":["갸"]}]}],[{"group":"doc","deltas":[{"start":{"row":64,"column":14},"end":{"row":64,"column":15},"action":"insert","lines":["ㅜ"]}]}],[{"group":"doc","deltas":[{"start":{"row":64,"column":14},"end":{"row":64,"column":15},"action":"remove","lines":["ㅜ"]}]}],[{"group":"doc","deltas":[{"start":{"row":64,"column":13},"end":{"row":64,"column":14},"action":"remove","lines":["갸"]}]}],[{"group":"doc","deltas":[{"start":{"row":64,"column":12},"end":{"row":64,"column":13},"action":"remove","lines":["ㅅ"]}]}],[{"group":"doc","deltas":[{"start":{"row":64,"column":11},"end":{"row":64,"column":12},"action":"remove","lines":["ㄴ"]}]}],[{"group":"doc","deltas":[{"start":{"row":64,"column":11},"end":{"row":64,"column":12},"action":"insert","lines":["s"]}]}],[{"group":"doc","deltas":[{"start":{"row":64,"column":12},"end":{"row":64,"column":13},"action":"insert","lines":["t"]}]}],[{"group":"doc","deltas":[{"start":{"row":64,"column":13},"end":{"row":64,"column":14},"action":"insert","lines":["r"]}]}],[{"group":"doc","deltas":[{"start":{"row":64,"column":14},"end":{"row":64,"column":15},"action":"insert","lines":["i"]}]}],[{"group":"doc","deltas":[{"start":{"row":64,"column":15},"end":{"row":64,"column":16},"action":"insert","lines":["n"]}]}],[{"group":"doc","deltas":[{"start":{"row":64,"column":16},"end":{"row":64,"column":17},"action":"insert","lines":["g"]}]}],[{"group":"doc","deltas":[{"start":{"row":64,"column":17},"end":{"row":64,"column":18},"action":"insert","lines":[" "]}]}],[{"group":"doc","deltas":[{"start":{"row":64,"column":18},"end":{"row":64,"column":27},"action":"insert","lines":["$enc_data"]}]}],[{"group":"doc","deltas":[{"start":{"row":64,"column":27},"end":{"row":64,"column":28},"action":"insert","lines":[" "]}]}],[{"group":"doc","deltas":[{"start":{"row":64,"column":28},"end":{"row":64,"column":29},"action":"insert","lines":[":"]}]}],[{"group":"doc","deltas":[{"start":{"row":64,"column":29},"end":{"row":64,"column":30},"action":"insert","lines":[" "]}]}],[{"group":"doc","deltas":[{"start":{"row":64,"column":29},"end":{"row":64,"column":30},"action":"remove","lines":[" "]}]}],[{"group":"doc","deltas":[{"start":{"row":64,"column":29},"end":{"row":64,"column":30},"action":"insert","lines":[" "]}]}],[{"group":"doc","deltas":[{"start":{"row":64,"column":30},"end":{"row":64,"column":31},"action":"insert","lines":["인"]}]}],[{"group":"doc","deltas":[{"start":{"row":64,"column":31},"end":{"row":64,"column":32},"action":"insert","lines":["코"]}]}],[{"group":"doc","deltas":[{"start":{"row":64,"column":32},"end":{"row":64,"column":33},"action":"insert","lines":["딩"]}]}],[{"group":"doc","deltas":[{"start":{"row":64,"column":33},"end":{"row":64,"column":34},"action":"insert","lines":[" "]}]}],[{"group":"doc","deltas":[{"start":{"row":64,"column":34},"end":{"row":64,"column":35},"action":"insert","lines":["문"]}]}],[{"group":"doc","deltas":[{"start":{"row":64,"column":35},"end":{"row":64,"column":36},"action":"insert","lines":["자"]}]}],[{"group":"doc","deltas":[{"start":{"row":64,"column":36},"end":{"row":64,"column":37},"action":"insert","lines":["열"]}]}]]},"ace":{"folds":[],"scrolltop":0,"scrollleft":0,"selection":{"start":{"row":15,"column":14},"end":{"row":18,"column":2},"isBackwards":false},"options":{"guessTabSize":true,"useWrapMode":false,"wrapToView":true},"firstLineState":0},"timestamp":1428648072105,"hash":"ef115fdd95ac375a85cba6812924781960e17306"}