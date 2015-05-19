<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Google trancerate api sample</title>
</head>
<body>

<div id="container">
    <div id="google_translate_element">
        <script type="text/javascript">
        function googleTranslateElementInit() {
          new google.translate.TranslateElement({pageLanguage: 'ko', layout: google.translate.TranslateElement.InlineLayout.SIMPLE}, 'google_translate_element');
        }
        </script><script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
    </div>
</div>

</body>
</html>