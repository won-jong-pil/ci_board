<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @title 회원용 language
 * @author 원종필(won0334@chol.com)
 * @history
 */ 
defined('BASEPATH') OR exit('No direct script access allowed');

$lang['member_manager_login_form_title'] = '관리자 로그인 폼';
//id check
$lang['member_check_id_success']	   = '사용할 수 있는 아이디입니다.';
$lang['member_check_id_fail']	   = '사용할 수 없는 아이디입니다.';
$lang['member_check_id_exists']	   = '존재하는 아이디입니다.';
//login form
$lang['member_placehold_id']	   = '아이디';
$lang['member_placehold_password']	   = '패스워드';
$lang['member_login_button']	   = '로그인';
//login form validation check
$lang['member_incorrect_js_id_require']	   = '아이디를 입력하세요.';
$lang['member_incorrect_js_id_minlength']	   = '아이디는 최소 {0}자 이상이어야 합니다.';
$lang['member_incorrect_js_id_maxlength']	   = '아이디는 최대 {0}자 이하이어야 합니다.';
$lang['member_incorrect_js_password_require']	   = '비밀번호를 입력하세요.';
$lang['member_incorrect_js_password_minlength']	   = '비밀번호는 최소 {0}자 이상이어야 합니다.';
$lang['member_incorrect_js_password_maxlength']	   = '비밀번호는 최대 {0}자 이하이어야 합니다.';
//login processor
$lang['member_incorrect_id_rule']	   = '아이디 형식이 올바르지 않습니다.';
$lang['member_incorrect_password_rule'] = '패스워드 형식이 올바르지 않습니다.';
//login processor error
$lang['member_no_login']	   = '로그인 해주세요.';
$lang['member_incorrect_manager_info']  = '관리자 정보가 올바르지 않습니다.';
$lang['member_member_lock']             = '회원 계정이 잠금처리 되었습니다. 관리자에게 문의 하세요.';
$lang['member_incorrect_password']      = '회원정보가 올바르지 않습니다.';
$lang['member_over_password_time']	   = '패스워드를 변경한지 90일이 지났습니다. 패스워드를 변경해 주세요.';