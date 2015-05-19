{"filter":false,"title":"MY_Exceptions.php","tooltip":"/application/core/MY_Exceptions.php","undoManager":{"stack":[[{"start":{"row":60,"column":0},"end":{"row":244,"column":0},"action":"remove","lines":["","\t/**","\t * Exception Logger","\t *","\t * Logs PHP generated error messages","\t *","\t * @param\tint\t$severity\tLog level","\t * @param\tstring\t$message\tError message","\t * @param\tstring\t$filepath\tFile path","\t * @param\tint\t$line\t\tLine number","\t * @return\tvoid","\t */","\tpublic function log_exception($severity, $message, $filepath, $line)","\t{","\t\t$severity = isset($this->levels[$severity]) ? $this->levels[$severity] : $severity;","\t\tlog_message('error', 'Severity: '.$severity.' --> '.$message.' '.$filepath.' '.$line);","\t}","","\t// --------------------------------------------------------------------","","\t/**","\t * 404 Error Handler","\t *","\t * @uses\tCI_Exceptions::show_error()","\t *","\t * @param\tstring\t$page\t\tPage URI","\t * @param \tbool\t$log_error\tWhether to log the error","\t * @return\tvoid","\t */","\tpublic function show_404($page = '', $log_error = TRUE)","\t{","\t\tif (is_cli())","\t\t{","\t\t\t$heading = 'Not Found';","\t\t\t$message = 'The controller/method pair you requested was not found.';","\t\t}","\t\telse","\t\t{","\t\t\t$heading = '404 Page Not Found';","\t\t\t$message = 'The page you requested was not found.';","\t\t}","","\t\t// By default we log this, but allow a dev to skip it","\t\tif ($log_error)","\t\t{","\t\t\tlog_message('error', $heading.': '.$page);","\t\t}","","\t\techo $this->show_error($heading, $message, 'error_404', 404);","\t\texit(4); // EXIT_UNKNOWN_FILE","\t}","","\t// --------------------------------------------------------------------","","\t/**","\t * General Error Page","\t *","\t * Takes an error message as input (either as a string or an array)","\t * and displays it using the specified template.","\t *","\t * @param\tstring\t\t$heading\tPage heading","\t * @param\tstring|string[]\t$message\tError message","\t * @param\tstring\t\t$template\tTemplate name","\t * @param \tint\t\t$status_code\t(default: 500)","\t *","\t * @return\tstring\tError page output","\t */","\tpublic function show_error($heading, $message, $template = 'error_general', $status_code = 500)","\t{","\t\t$templates_path = config_item('error_views_path');","\t\tif (empty($templates_path))","\t\t{","\t\t\t$templates_path = VIEWPATH.'errors'.DIRECTORY_SEPARATOR;","\t\t}","","\t\tif (is_cli())","\t\t{","\t\t\t$message = \"\\t\".(is_array($message) ? implode(\"\\n\\t\", $message) : $message);","\t\t\t$template = 'cli'.DIRECTORY_SEPARATOR.$template;","\t\t}","\t\telse","\t\t{","\t\t\tset_status_header($status_code);","\t\t\t$message = '<p>'.(is_array($message) ? implode('</p><p>', $message) : $message).'</p>';","\t\t\t$template = 'html'.DIRECTORY_SEPARATOR.$template;","\t\t}","","\t\tif (ob_get_level() > $this->ob_level + 1)","\t\t{","\t\t\tob_end_flush();","\t\t}","\t\tob_start();","\t\tinclude($templates_path.$template.'.php');","\t\t$buffer = ob_get_contents();","\t\tob_end_clean();","\t\treturn $buffer;","\t}","","\t// --------------------------------------------------------------------","","\tpublic function show_exception(Exception $exception)","\t{","\t\t$templates_path = config_item('error_views_path');","\t\tif (empty($templates_path))","\t\t{","\t\t\t$templates_path = VIEWPATH.'errors'.DIRECTORY_SEPARATOR;","\t\t}","","\t\t$message = $exception->getMessage();","\t\tif (empty($message))","\t\t{","\t\t\t$message = '(null)';","\t\t}","","\t\tif (is_cli())","\t\t{","\t\t\t$templates_path .= 'cli'.DIRECTORY_SEPARATOR;","\t\t}","\t\telse","\t\t{","\t\t\tset_status_header(500);","\t\t\t$templates_path .= 'html'.DIRECTORY_SEPARATOR;","\t\t}","","\t\tif (ob_get_level() > $this->ob_level + 1)","\t\t{","\t\t\tob_end_flush();","\t\t}","","\t\tob_start();","\t\tinclude($templates_path.'error_exception.php');","\t\t$buffer = ob_get_contents();","\t\tob_end_clean();","\t\techo $buffer;","\t}","","\t// --------------------------------------------------------------------","","\t/**","\t * Native PHP error handler","\t *","\t * @param\tint\t$severity\tError level","\t * @param\tstring\t$message\tError message","\t * @param\tstring\t$filepath\tFile path","\t * @param\tint\t$line\t\tLine number","\t * @return\tstring\tError page output","\t */","\tpublic function show_php_error($severity, $message, $filepath, $line)","\t{","\t\t$templates_path = config_item('error_views_path');","\t\tif (empty($templates_path))","\t\t{","\t\t\t$templates_path = VIEWPATH.'errors'.DIRECTORY_SEPARATOR;","\t\t}","","\t\t$severity = isset($this->levels[$severity]) ? $this->levels[$severity] : $severity;","","\t\t// For safety reasons we don't show the full file path in non-CLI requests","\t\tif ( ! is_cli())","\t\t{","\t\t\t$filepath = str_replace('\\\\', '/', $filepath);","\t\t\tif (FALSE !== strpos($filepath, '/'))","\t\t\t{","\t\t\t\t$x = explode('/', $filepath);","\t\t\t\t$filepath = $x[count($x)-2].'/'.end($x);","\t\t\t}","","\t\t\t$template = 'html'.DIRECTORY_SEPARATOR.'error_php';","\t\t}","\t\telse","\t\t{","\t\t\t$template = 'cli'.DIRECTORY_SEPARATOR.'error_php';","\t\t}","","\t\tif (ob_get_level() > $this->ob_level + 1)","\t\t{","\t\t\tob_end_flush();","\t\t}","\t\tob_start();","\t\tinclude($templates_path.$template.'.php');","\t\t$buffer = ob_get_contents();","\t\tob_end_clean();","\t\techo $buffer;","\t}",""]}],[{"start":{"row":60,"column":0},"end":{"row":61,"column":0},"action":"remove","lines":["",""]}]],"mark":-1,"position":1},"ace":{"folds":[],"scrolltop":550,"scrollleft":0,"selection":{"start":{"row":53,"column":4},"end":{"row":53,"column":4},"isBackwards":false},"options":{"guessTabSize":true,"useWrapMode":false,"wrapToView":true},"firstLineState":{"row":33,"state":"php-doc-start","mode":"ace/mode/php"}},"timestamp":1429258509653,"hash":"7347779b0ec1c7329b1782ac22c2f694884aa525"}