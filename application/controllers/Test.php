<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @title 메인 페이지 및 순수 안내 페이지 처리
 * @author 원종필(won0334@chol.com)
 * */
class Test extends CI_Controller 
{
    function __construct()
    {
        parent::__construct();
    }
	/**
	 * @title phpinfo
	 * @author 원종필(won0334@chol.com)
	 * */    
    function index()
    {
        phpinfo();
    }
    function temp()
    {
        print_r($this->config->item('test_domain'));
        print_r($_SERVER['HTTP_HOST']);
    }    
	/**
	 * @title 페이징 처리 테스트
	 * @author 원종필(won0334@chol.com)
	 * */
    function test1()
    {
		$config['base_url'] = '/corp/test/';
		$config['total_rows'] = 200;
		$config['per_page'] = 10;
		$config['page_size'] = 5;

		$this->load->library('pagination');
		$this->pagination->initialize($config);
		echo $this->pagination->create_links();
    }
	/**
	 * @title 서버 환결 정보 가져오기
	 * @author 원종필(won0334@chol.com)
	 * */
    function test2()
    {
		echo $_SERVER["REMOTE_ADDR"];
		echo '<br />';
		echo getenv('IP');
		echo '<br />';
		print_rr($_SERVER);
    }
	/**
	 * @title 패스워드 
	 * @author 원종필(won0334@chol.com)
	 * */    
    function test3()
    {
        echo password_hash('12345678', PASSWORD_BCRYPT);
    }
	/**
	 * @title 패스워드 
	 * @author 원종필(won0334@chol.com)
	 * */    
    function test4()
    {
        echo date('Y-m-d').'<br />'; 
        echo date('Y-m-d', strtotime('+90 days', strtotime($this->session->userdata('pass_update_date'))));
        print_rr($this->session->all_userdata());
    }
    

}