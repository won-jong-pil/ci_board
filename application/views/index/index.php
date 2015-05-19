<?PHP if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="Generator" content="">
    <meta name="Author" content="">
    <meta name="Keywords" content="">
    <meta name="Description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, target-densitydpi=medium-dpi" />

	<title>EDIYA</title>

	<?php if(in_array($_SERVER['HTTP_HOST'], array('localhost', 'ediya.lhsoft.co.kr', 'new.ediya.com')) === TRUE):?><meta name="robots" content="noindex"><?php endif;?>
	<?php if(isset($default_head_file_name) === TRUE && empty( $default_head_file_name ) === FALSE) $this->load->view($default_head_file_name);?>
	<?php if(isset($head_file_name) === TRUE && empty( $head_file_name ) === FALSE) $this->load->view($head_file_name);?>
</head>

<body>
<div class="wrap">
    <!-- header -->
    <header>
        헤더
    </header>
    <!-- //header -->

    <!-- container -->
    <div class="container<?php if(isset($main_class) === TRUE) echo $main_class;?>">
        <?php if( isset( $left_file_name ) === TRUE && empty( $left_file_name ) === FALSE ) $this->load->view( $left_file_name );?>

        <!-- content -->
        <?php if( isset( $main_file_name ) === TRUE && empty( $main_file_name ) === FALSE ) $this->load->view( $main_file_name );?>
        <!-- //content -->
    </div>
    <!-- //container -->

    <!-- footer -->
    <footer>
        <section>
            <div class="foot_inner1">
                <a href="/board/listing/brd/head_career" target="_blank">채용안내</a><span>|</span>
                <a href="/company/terms" target="_blank">이용약관</a><span>|</span>
                <a href="/company/privacy" target="_blank">개인정보 처리방침</a><span>|</span>
                <a href="#none" id="email_denied">이메일 수집거부</a><span>|</span>
                <a href="/company/sitemap" target="_blank">사이트맵</a><span>|</span>
                <a href="http://www.ediya.com/ADMEdiya/login/loginFm.asp" target="_blank">점주의 방</a>
            </div>
        </section>
        <section>
            <div class="foot_inner2">
                <img src="/images/common/img_footlogo.jpg" alt="ediya" />
                <p>
                    서울특별시 강남구 논현로 508 GS 타워 14-15F (서울특별시 강남구 역삼동 679 GS 타워 14-15F)&nbsp;&nbsp;&nbsp;&nbsp;TEL : 02-543-6467&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;FAX : 02-543-7191<br/>
                    사업자등록번호 : 107-86-16302&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;통신판매업 신고 : 강남 제 002519호&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;대표이사 : 문창기<br/>
                    <span class="copy">@2014 EDIYA COFFEE COMPANY. ALL RIGHTS RESERVED.</span>
                </p>
				<div id="google_translate_element">
				<script type="text/javascript">
				function googleTranslateElementInit() {
				  new google.translate.TranslateElement({pageLanguage: 'ko', layout: google.translate.TranslateElement.InlineLayout.SIMPLE}, 'google_translate_element');
				}
				</script><script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
				</div>
        </section>
    </footer>
    <!-- //footer -->
</div>
<div id="modalBase" name="modalBase" style="display:none;"></div>
</body>
</html>