<?PHP if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<?php if(in_array($_SERVER['HTTP_HOST'], $this->config->item('test_domain')) === TRUE):?>
<meta name="robots" content="noindex">
<?php endif;?>
<link rel="stylesheet" type="text/css" media="all" href="/static/css/jquery-ui.min.css">
<!-- 부트스트랩 -->
<!-- 합쳐지고 최소화된 최신 CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
<!-- 부가적인 테마 -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap-theme.min.css">
<!-- //ie9이하 -->
<script type="text/javascript" src="/static/js/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="/static/js/jquery-ui-1.10.3.custom.min.js"></script>
<!-- jQuery (부트스트랩의 자바스크립트 플러그인을 위해 필요합니다) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>    
<!-- 합쳐지고 최소화된 최신 자바스크립트 -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
<!-- IE8 에서 HTML5 요소와 미디어 쿼리를 위한 HTML5 shim 와 Respond.js -->
<!-- WARNING: Respond.js 는 당신이 file:// 을 통해 페이지를 볼 때는 동작하지 않습니다. -->
<!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
<style>
body {
  min-height: 2000px;
  padding-top: 70px;
}
</style>