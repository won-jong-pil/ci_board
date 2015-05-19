<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2015-04-20 01:32:06 --> Severity: Warning --> mysqli::real_connect(): (HY000/2003): Can't connect to MySQL server on '0.0.0.0' (111) /home/ubuntu/workspace/system/database/drivers/mysqli/mysqli_driver.php 135
ERROR - 2015-04-20 01:32:06 --> Unable to connect to the database
ERROR - 2015-04-20 01:32:43 --> 404 Page Not Found: Faviconico/index
ERROR - 2015-04-20 01:32:43 --> 404 Page Not Found: Faviconico/index
ERROR - 2015-04-20 02:07:50 --> Severity: Error --> Call to undefined method CI_Loader::lang() /home/ubuntu/workspace/application/controllers/Board.php 15
ERROR - 2015-04-20 02:08:30 --> Could not find the language line "no_code"
ERROR - 2015-04-20 02:09:34 --> Could not find the language line "no_code"
ERROR - 2015-04-20 02:21:20 --> 404 Page Not Found: PhpMyAdmin/index
ERROR - 2015-04-20 02:24:20 --> 404 Page Not Found: PHPMyAdmin/index
ERROR - 2015-04-20 02:24:40 --> 404 Page Not Found: Phpmtadmin/index
ERROR - 2015-04-20 02:29:37 --> Severity: Notice --> Use of undefined constant MOBILE_USE - assumed 'MOBILE_USE' /home/ubuntu/workspace/application/controllers/Corp.php 48
ERROR - 2015-04-20 02:29:49 --> Severity: Notice --> Use of undefined constant MOBILE_USE - assumed 'MOBILE_USE' /home/ubuntu/workspace/application/controllers/Corp.php 48
ERROR - 2015-04-20 02:30:27 --> Non-existent class: Board_drv
ERROR - 2015-04-20 02:36:56 --> Query error: Table 'c9.board_info' doesn't exist - Invalid query: SELECT *
FROM `board_info`
WHERE `board_code` = 'notice'
ERROR - 2015-04-20 02:40:38 --> Severity: Notice --> Use of undefined constant MOBILE_USE - assumed 'MOBILE_USE' /home/ubuntu/workspace/application/controllers/Corp.php 48
ERROR - 2015-04-20 02:41:59 --> Invalid driver requested: Board_drv_notice
ERROR - 2015-04-20 02:43:02 --> Invalid driver requested: Board_drv_notice
ERROR - 2015-04-20 02:45:32 --> Invalid driver requested: Board_drv_notice
ERROR - 2015-04-20 02:46:39 --> Non-existent class: Board_drv
ERROR - 2015-04-20 02:46:50 --> Invalid driver requested: Board_drv_notice
ERROR - 2015-04-20 02:47:16 --> Invalid driver requested: Board_drv_notice
ERROR - 2015-04-20 02:47:17 --> Invalid driver requested: Board_drv_notice
ERROR - 2015-04-20 02:49:09 --> Invalid driver requested: Board_drv_notice
ERROR - 2015-04-20 02:49:41 --> Invalid driver requested: Board_drv_notice
ERROR - 2015-04-20 02:50:19 --> Invalid driver requested: Board_drv_notice
ERROR - 2015-04-20 02:54:09 --> Query error: Table 'c9.notice' doesn't exist - Invalid query: SELECT SQL_CALC_FOUND_ROWS a.*, `b`.`org_name` as `web_file_org_name`, `b`.`file_name` as `web_file_file_name`
FROM `notice` `a`
LEFT JOIN `file_info` `b` ON `b`.`field_name` = 'web_file' and `a`.`idx`=`b`.`board_idx` and `b`.`board_id` = 'notice'
WHERE `a`.`status` = 'Y'
ORDER BY `a`.`idx` DESC
 LIMIT 10
ERROR - 2015-04-20 02:55:32 --> Query error: Table 'c9.notice' doesn't exist - Invalid query: SELECT SQL_CALC_FOUND_ROWS a.*, `b`.`org_name` as `web_file_org_name`, `b`.`file_name` as `web_file_file_name`
FROM `notice` `a`
LEFT JOIN `file_info` `b` ON `b`.`field_name` = 'web_file' and `a`.`idx`=`b`.`board_idx` and `b`.`board_id` = 'notice'
WHERE `a`.`status` = 'Y'
ORDER BY `a`.`idx` DESC
 LIMIT 10
ERROR - 2015-04-20 04:12:33 --> Query error: Table 'c9.notice' doesn't exist - Invalid query: SELECT SQL_CALC_FOUND_ROWS a.*, `b`.`org_name` as `web_file_org_name`, `b`.`file_name` as `web_file_file_name`
FROM `notice` `a`
LEFT JOIN `file_info` `b` ON `b`.`field_name` = 'web_file' and `a`.`idx`=`b`.`board_idx` and `b`.`board_id` = 'notice'
WHERE `a`.`status` = 'Y'
ORDER BY `a`.`idx` DESC
 LIMIT 10
ERROR - 2015-04-20 04:12:54 --> Query error: Table 'c9.file_info' doesn't exist - Invalid query: SELECT SQL_CALC_FOUND_ROWS a.*, `b`.`org_name` as `web_file_org_name`, `b`.`file_name` as `web_file_file_name`
FROM `board_notice` `a`
LEFT JOIN `file_info` `b` ON `b`.`field_name` = 'web_file' and `a`.`idx`=`b`.`board_idx` and `b`.`board_id` = 'notice'
WHERE `a`.`status` = 'Y'
ORDER BY `a`.`idx` DESC
 LIMIT 10
ERROR - 2015-04-20 04:13:17 --> Severity: Notice --> Undefined index: page_size /home/ubuntu/workspace/application/controllers/Board.php 61
ERROR - 2015-04-20 04:13:17 --> Severity: Notice --> Undefined variable: base_skin /home/ubuntu/workspace/application/controllers/Board.php 67
ERROR - 2015-04-20 04:13:17 --> Severity: Notice --> Undefined variable: base_skin /home/ubuntu/workspace/application/controllers/Board.php 68
ERROR - 2015-04-20 04:13:17 --> Severity: Notice --> Undefined variable: base_skin /home/ubuntu/workspace/application/controllers/Board.php 69
ERROR - 2015-04-20 04:13:17 --> Severity: Notice --> Undefined variable: base_skin /home/ubuntu/workspace/application/controllers/Board.php 70
ERROR - 2015-04-20 04:13:17 --> Severity: Notice --> Undefined variable: base_skin /home/ubuntu/workspace/application/controllers/Board.php 71
ERROR - 2015-04-20 04:14:15 --> Severity: Notice --> Undefined variable: base_skin /home/ubuntu/workspace/application/controllers/Board.php 68
ERROR - 2015-04-20 04:14:15 --> Severity: Notice --> Undefined variable: base_skin /home/ubuntu/workspace/application/controllers/Board.php 69
ERROR - 2015-04-20 04:14:15 --> Severity: Notice --> Undefined variable: base_skin /home/ubuntu/workspace/application/controllers/Board.php 70
ERROR - 2015-04-20 04:14:15 --> Severity: Notice --> Undefined variable: base_skin /home/ubuntu/workspace/application/controllers/Board.php 71
ERROR - 2015-04-20 04:14:15 --> Severity: Notice --> Undefined variable: base_skin /home/ubuntu/workspace/application/controllers/Board.php 72
ERROR - 2015-04-20 04:31:36 --> Severity: Notice --> Undefined variable: base_skin /home/ubuntu/workspace/application/controllers/Board.php 59
ERROR - 2015-04-20 04:31:36 --> Severity: Notice --> Undefined variable: skin /home/ubuntu/workspace/application/controllers/Board.php 59
ERROR - 2015-04-20 04:31:36 --> Severity: Notice --> Undefined variable: base_skin /home/ubuntu/workspace/application/controllers/Board.php 60
ERROR - 2015-04-20 04:31:36 --> Severity: Notice --> Undefined variable: base_skin /home/ubuntu/workspace/application/controllers/Board.php 61
ERROR - 2015-04-20 04:31:36 --> Severity: Notice --> Undefined variable: skin /home/ubuntu/workspace/application/controllers/Board.php 61
ERROR - 2015-04-20 04:31:36 --> Severity: Notice --> Undefined variable: base_skin /home/ubuntu/workspace/application/controllers/Board.php 62
ERROR - 2015-04-20 04:31:36 --> Severity: Notice --> Undefined variable: base_skin /home/ubuntu/workspace/application/controllers/Board.php 63
ERROR - 2015-04-20 04:31:36 --> Severity: Warning --> Cannot modify header information - headers already sent by (output started at /home/ubuntu/workspace/system/core/Exceptions.php:272) /home/ubuntu/workspace/system/core/Common.php 569
ERROR - 2015-04-20 04:35:34 --> 404 Page Not Found: Cort/test
ERROR - 2015-04-20 04:35:40 --> Severity: Parsing Error --> syntax error, unexpected 'exit' (T_EXIT), expecting ',' or ';' /home/ubuntu/workspace/application/controllers/Corp.php 18
ERROR - 2015-04-20 13:36:17 --> Severity: Notice --> Use of undefined constant MOBILE_USE - assumed 'MOBILE_USE' /home/ubuntu/workspace/application/controllers/Corp.php 48
ERROR - 2015-04-20 13:36:20 --> Severity: Notice --> Use of undefined constant MOBILE_USE - assumed 'MOBILE_USE' /home/ubuntu/workspace/application/controllers/Corp.php 48
ERROR - 2015-04-20 13:37:32 --> Severity: Notice --> Undefined variable: base_skin /home/ubuntu/workspace/application/controllers/Board.php 59
ERROR - 2015-04-20 13:37:32 --> Severity: Notice --> Undefined variable: skin /home/ubuntu/workspace/application/controllers/Board.php 59
ERROR - 2015-04-20 13:37:32 --> Severity: Notice --> Undefined variable: base_skin /home/ubuntu/workspace/application/controllers/Board.php 60
ERROR - 2015-04-20 13:37:32 --> Severity: Notice --> Undefined variable: base_skin /home/ubuntu/workspace/application/controllers/Board.php 61
ERROR - 2015-04-20 13:37:32 --> Severity: Notice --> Undefined variable: skin /home/ubuntu/workspace/application/controllers/Board.php 61
ERROR - 2015-04-20 13:37:32 --> Severity: Notice --> Undefined variable: base_skin /home/ubuntu/workspace/application/controllers/Board.php 62
ERROR - 2015-04-20 13:37:32 --> Severity: Notice --> Undefined variable: base_skin /home/ubuntu/workspace/application/controllers/Board.php 63
ERROR - 2015-04-20 13:37:32 --> Severity: Warning --> Cannot modify header information - headers already sent by (output started at /home/ubuntu/workspace/system/core/Exceptions.php:272) /home/ubuntu/workspace/system/core/Common.php 569
ERROR - 2015-04-20 13:46:21 --> Severity: Notice --> Use of undefined constant MOBILE_USE - assumed 'MOBILE_USE' /home/ubuntu/workspace/application/controllers/Corp.php 48
ERROR - 2015-04-20 14:23:26 --> Severity: Notice --> Undefined variable: base_skin /home/ubuntu/workspace/application/controllers/Board.php 58
ERROR - 2015-04-20 14:23:26 --> Severity: Notice --> Undefined variable: skin /home/ubuntu/workspace/application/controllers/Board.php 58
ERROR - 2015-04-20 14:23:26 --> Severity: Notice --> Undefined variable: base_skin /home/ubuntu/workspace/application/controllers/Board.php 59
ERROR - 2015-04-20 14:23:26 --> Severity: Notice --> Undefined variable: base_skin /home/ubuntu/workspace/application/controllers/Board.php 60
ERROR - 2015-04-20 14:23:26 --> Severity: Notice --> Undefined variable: skin /home/ubuntu/workspace/application/controllers/Board.php 60
ERROR - 2015-04-20 14:23:26 --> Severity: Notice --> Undefined variable: base_skin /home/ubuntu/workspace/application/controllers/Board.php 61
ERROR - 2015-04-20 14:23:26 --> Severity: Notice --> Undefined variable: base_skin /home/ubuntu/workspace/application/controllers/Board.php 62
ERROR - 2015-04-20 14:23:26 --> Severity: Warning --> Cannot modify header information - headers already sent by (output started at /home/ubuntu/workspace/system/core/Exceptions.php:272) /home/ubuntu/workspace/system/core/Common.php 569
ERROR - 2015-04-20 14:24:00 --> Severity: Notice --> Undefined variable: skin /home/ubuntu/workspace/application/controllers/Board.php 58
ERROR - 2015-04-20 14:24:00 --> Severity: Notice --> Undefined variable: skin /home/ubuntu/workspace/application/controllers/Board.php 60
ERROR - 2015-04-20 14:27:30 --> Severity: Notice --> Undefined property: CI_Loader::$session /home/ubuntu/workspace/application/views/index/index.php 28
ERROR - 2015-04-20 14:27:30 --> Severity: Error --> Call to a member function userdata() on a non-object /home/ubuntu/workspace/application/views/index/index.php 28
ERROR - 2015-04-20 14:28:10 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near 'WHERE `id` = '9f3722803da4cfbcd9abb43611847f545076ab0f'' at line 2 - Invalid query: SELECT `data`
WHERE `id` = '9f3722803da4cfbcd9abb43611847f545076ab0f'
ERROR - 2015-04-20 14:48:36 --> Severity: Notice --> Undefined index: per_page /home/ubuntu/workspace/application/controllers/Board.php 49
ERROR - 2015-04-20 14:48:36 --> Severity: Notice --> Undefined index: page_size /home/ubuntu/workspace/application/controllers/Board.php 50
ERROR - 2015-04-20 14:48:36 --> Severity: Notice --> Undefined index: skin /home/ubuntu/workspace/application/controllers/Board.php 54
ERROR - 2015-04-20 14:48:36 --> Severity: Notice --> Undefined index: skin /home/ubuntu/workspace/application/controllers/Board.php 55
ERROR - 2015-04-20 14:49:50 --> Severity: Notice --> Undefined index: per_page /home/ubuntu/workspace/application/controllers/Board.php 50
ERROR - 2015-04-20 14:49:50 --> Severity: Notice --> Undefined index: page_size /home/ubuntu/workspace/application/controllers/Board.php 51
ERROR - 2015-04-20 14:49:50 --> Severity: Notice --> Undefined index: skin /home/ubuntu/workspace/application/controllers/Board.php 55
ERROR - 2015-04-20 14:49:50 --> Severity: Notice --> Undefined index: skin /home/ubuntu/workspace/application/controllers/Board.php 56
ERROR - 2015-04-20 14:50:39 --> Severity: Notice --> Undefined index: skin /home/ubuntu/workspace/application/controllers/Board.php 55
ERROR - 2015-04-20 14:50:39 --> Severity: Notice --> Undefined index: skin /home/ubuntu/workspace/application/controllers/Board.php 56
ERROR - 2015-04-20 14:56:20 --> Severity: Compile Error --> Cannot redeclare Board_drv_notice::$board_skin /home/ubuntu/workspace/application/libraries/Board_drv/drivers/Board_drv_notice.php 17
ERROR - 2015-04-20 14:56:55 --> Severity: Notice --> Undefined variable: page_name /home/ubuntu/workspace/application/views/board/notice/list.php 18
ERROR - 2015-04-20 14:56:55 --> Severity: Notice --> Undefined variable: page_name /home/ubuntu/workspace/application/views/board/notice/list.php 19
ERROR - 2015-04-20 14:56:55 --> Severity: Notice --> Undefined variable: page_name /home/ubuntu/workspace/application/views/board/notice/list.php 20
ERROR - 2015-04-20 15:01:52 --> Severity: Notice --> Undefined index: per_page /home/ubuntu/workspace/application/libraries/Board_drv/drivers/Board_drv_notice.php 459
ERROR - 2015-04-20 15:01:52 --> Severity: Notice --> Undefined variable: page_name /home/ubuntu/workspace/application/views/board/notice/list.php 18
ERROR - 2015-04-20 15:01:52 --> Severity: Notice --> Undefined variable: page_name /home/ubuntu/workspace/application/views/board/notice/list.php 19
ERROR - 2015-04-20 15:01:52 --> Severity: Notice --> Undefined variable: page_name /home/ubuntu/workspace/application/views/board/notice/list.php 20
ERROR - 2015-04-20 15:02:23 --> Severity: Notice --> Undefined index: per_page /home/ubuntu/workspace/application/libraries/Board_drv/drivers/Board_drv_notice.php 459
ERROR - 2015-04-20 15:02:23 --> Severity: Notice --> Undefined variable: page_name /home/ubuntu/workspace/application/views/board/notice/list.php 18
ERROR - 2015-04-20 15:02:23 --> Severity: Notice --> Undefined variable: page_name /home/ubuntu/workspace/application/views/board/notice/list.php 19
ERROR - 2015-04-20 15:02:23 --> Severity: Notice --> Undefined variable: page_name /home/ubuntu/workspace/application/views/board/notice/list.php 20
ERROR - 2015-04-20 15:02:28 --> Severity: Notice --> Undefined index: per_page /home/ubuntu/workspace/application/libraries/Board_drv/drivers/Board_drv_notice.php 459
ERROR - 2015-04-20 15:02:28 --> Severity: Notice --> Undefined variable: page_name /home/ubuntu/workspace/application/views/board/notice/list.php 18
ERROR - 2015-04-20 15:02:28 --> Severity: Notice --> Undefined variable: page_name /home/ubuntu/workspace/application/views/board/notice/list.php 19
ERROR - 2015-04-20 15:02:28 --> Severity: Notice --> Undefined variable: page_name /home/ubuntu/workspace/application/views/board/notice/list.php 20
ERROR - 2015-04-20 15:03:17 --> Severity: Notice --> Undefined variable: page_name /home/ubuntu/workspace/application/views/board/notice/list.php 18
ERROR - 2015-04-20 15:03:17 --> Severity: Notice --> Undefined variable: page_name /home/ubuntu/workspace/application/views/board/notice/list.php 19
ERROR - 2015-04-20 15:03:17 --> Severity: Notice --> Undefined variable: page_name /home/ubuntu/workspace/application/views/board/notice/list.php 20
ERROR - 2015-04-20 15:03:42 --> Severity: Notice --> Undefined variable: page_name /home/ubuntu/workspace/application/views/board/notice/list.php 18
ERROR - 2015-04-20 15:03:42 --> Severity: Notice --> Undefined variable: page_name /home/ubuntu/workspace/application/views/board/notice/list.php 19
ERROR - 2015-04-20 15:03:42 --> Severity: Notice --> Undefined variable: page_name /home/ubuntu/workspace/application/views/board/notice/list.php 20
ERROR - 2015-04-20 15:07:32 --> Severity: Notice --> Undefined variable: page_name /home/ubuntu/workspace/application/views/board/notice/list.php 18
ERROR - 2015-04-20 15:07:32 --> Severity: Notice --> Undefined variable: page_name /home/ubuntu/workspace/application/views/board/notice/list.php 19
ERROR - 2015-04-20 15:07:32 --> Severity: Notice --> Undefined variable: page_name /home/ubuntu/workspace/application/views/board/notice/list.php 20
ERROR - 2015-04-20 15:07:55 --> Severity: Notice --> Undefined variable: page_name /home/ubuntu/workspace/application/views/board/notice/list.php 18
ERROR - 2015-04-20 15:07:55 --> Severity: Notice --> Undefined variable: page_name /home/ubuntu/workspace/application/views/board/notice/list.php 19
ERROR - 2015-04-20 15:07:55 --> Severity: Notice --> Undefined variable: page_name /home/ubuntu/workspace/application/views/board/notice/list.php 20
ERROR - 2015-04-20 15:10:32 --> Severity: Notice --> Undefined variable: page_name /home/ubuntu/workspace/application/views/board/notice/list.php 18
ERROR - 2015-04-20 15:10:32 --> Severity: Notice --> Undefined variable: page_name /home/ubuntu/workspace/application/views/board/notice/list.php 19
ERROR - 2015-04-20 15:10:32 --> Severity: Notice --> Undefined variable: page_name /home/ubuntu/workspace/application/views/board/notice/list.php 20
ERROR - 2015-04-20 15:11:48 --> Severity: Notice --> Undefined variable: page_name /home/ubuntu/workspace/application/views/board/notice/list.php 18
ERROR - 2015-04-20 15:11:48 --> Severity: Notice --> Undefined variable: page_name /home/ubuntu/workspace/application/views/board/notice/list.php 19
ERROR - 2015-04-20 15:11:48 --> Severity: Notice --> Undefined variable: page_name /home/ubuntu/workspace/application/views/board/notice/list.php 20
ERROR - 2015-04-20 15:25:12 --> Severity: Error --> Call to undefined method Board_drv::get_arttr() /home/ubuntu/workspace/application/views/board/notice/list.php 13
ERROR - 2015-04-20 16:35:44 --> Invalid driver requested: Board_drv_
ERROR - 2015-04-20 16:39:34 --> Invalid driver requested: Board_drv_
ERROR - 2015-04-20 16:40:57 --> Severity: Error --> Call to a member function select() on a non-object /home/ubuntu/workspace/application/libraries/Board_drv/drivers/Board_drv_notice.php 427
ERROR - 2015-04-20 16:46:40 --> Severity: 4096 --> Object of class CI_Loader could not be converted to string /home/ubuntu/workspace/application/controllers/Board.php 37
ERROR - 2015-04-20 16:47:15 --> Severity: Notice --> Object of class CI_Loader could not be converted to int /home/ubuntu/workspace/application/controllers/Board.php 37
ERROR - 2015-04-20 16:48:43 --> Severity: Notice --> Undefined variable: result /home/ubuntu/workspace/application/controllers/Board.php 38
ERROR - 2015-04-20 16:48:50 --> Severity: Notice --> Undefined variable: result /home/ubuntu/workspace/application/controllers/Board.php 38
ERROR - 2015-04-20 17:12:35 --> Invalid driver requested: Board_drv_
ERROR - 2015-04-20 17:21:35 --> Invalid driver requested: Board_drv_error
ERROR - 2015-04-20 17:22:54 --> Invalid driver requested: Board_drv_error
ERROR - 2015-04-20 17:22:55 --> Invalid driver requested: Board_drv_error
ERROR - 2015-04-20 17:22:55 --> Invalid driver requested: Board_drv_error
ERROR - 2015-04-20 17:22:56 --> Invalid driver requested: Board_drv_error
ERROR - 2015-04-20 17:22:56 --> Invalid driver requested: Board_drv_error
ERROR - 2015-04-20 17:22:56 --> Invalid driver requested: Board_drv_error
ERROR - 2015-04-20 17:22:57 --> Invalid driver requested: Board_drv_error
ERROR - 2015-04-20 17:24:02 --> Invalid driver requested: Board_drv_error
ERROR - 2015-04-20 17:24:58 --> Invalid driver requested: Board_drv_error
ERROR - 2015-04-20 17:25:09 --> Invalid driver requested: Board_drv_error
ERROR - 2015-04-20 17:25:10 --> Invalid driver requested: Board_drv_error
ERROR - 2015-04-20 17:25:54 --> Invalid driver requested: Board_drv_error
ERROR - 2015-04-20 17:25:55 --> Invalid driver requested: Board_drv_error
ERROR - 2015-04-20 17:26:22 --> Invalid driver requested: Board_drv_error
ERROR - 2015-04-20 17:27:40 --> Invalid driver requested: Board_drv_error
