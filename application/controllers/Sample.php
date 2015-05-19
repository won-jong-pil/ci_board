<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @title 샘플용 코드
 * @author 원종필(won0334@chol.com)
 */
class Sample extends CI_Controller 
{
    /**
     * @title Qrcode 라이브러리 샘플
     * @author 원종필(won0334@chol.com)
     */  
    function qrcode()
    {
    	$this->load->library('ciqrcode');
		header("Content-Type: image/png");
		$url = $this->uri->uri_to_assoc(3);
		$params['data'] = $this->config->item('base_url').$this->uri->assoc_to_uri($url);
		$this->ciqrcode->generate($params);
    }
    /**
     * @title 페이지 로드시 no cache 처리
     * @author 원종필(won0334@chol.com)
     */  
    function set_no_cache()
    {
		header("Progma:no-cache");
		header("Cache-Control:no-cache,must-revalidate");		
		session_cache_limiter('private');        
    }
    /**
     * @title 구글 번역기 api
     * @author 원종필(won0334@chol.com)
     */  
    function google_trnce_api()
    {
		$this->load->view('sample/google_trance_api');
    }
    /**
     * @title bootstrap sample
     * @author 원종필(won0334@chol.com)
     */  
    function bootstrap()
    {
        $this->load->view('sample/bootstrap');
    }
    /**
     * @title bootstrap sample2
     * @author 원종필(won0334@chol.com)
     */  
    function bootstrap2()
    {
        $this->load->view('sample/bootstrap2');
    }    
}