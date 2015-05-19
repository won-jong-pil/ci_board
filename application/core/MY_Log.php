<?php
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2014 - 2015, British Columbia Institute of Technology
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package	CodeIgniter
 * @author	EllisLab Dev Team
 * @copyright	Copyright (c) 2008 - 2014, EllisLab, Inc. (http://ellislab.com/)
 * @copyright	Copyright (c) 2014 - 2015, British Columbia Institute of Technology (http://bcit.ca/)
 * @license	http://opensource.org/licenses/MIT	MIT License
 * @link	http://codeigniter.com
 * @since	Version 1.0.0
 * @filesource
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Logging Class
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Logging
 * @author		EllisLab Dev Team
 * @link		http://codeigniter.com/user_guide/general/errors.html
 */
class MY_Log extends CI_Log {

	protected $_levels = array('ERROR' => 1, 'DEBUG' => 2, 'INFO' => 3, 'ALL' => 4, 'TEST' => 5);

    public function __construct()
    {
        parent::__construct();
    }

	// --------------------------------------------------------------------

	/**
	 * Write Log File
	 *
	 * Generally this function will be called using the global log_message() function
	 *
	 * @param	string	the error level: 'error', 'debug' or 'info'
	 * @param	string	the error message
	 * @return	bool
	 */
	public function write_log($level, $msg)
	{
		if ($this->_enabled === FALSE)
		{
			return FALSE;
		}

		$level = strtoupper($level);

		if (( ! isset($this->_levels[$level]) OR ($this->_levels[$level] > $this->_threshold))
			&& ! isset($this->_threshold_array[$this->_levels[$level]]))
		{
			return FALSE;
		}

		$filepath = $this->_log_path.'log-'.$level.'-'.date('Y-m-d').'.'.$this->_file_ext;
		$message = '';

		if ( ! file_exists($filepath))
		{
			$newfile = TRUE;
			// Only add protection to php files
			if ($this->_file_ext === 'php')
			{
				$message .= "<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>\n\n";
			}
		}

		if ( ! $fp = @fopen($filepath, 'ab'))
		{
			return FALSE;
		}

		// Instantiating DateTime with microseconds appended to initial date is needed for proper support of this format
		if (strpos($this->_date_fmt, 'u') !== FALSE)
		{
			$microtime_full = microtime(TRUE);
			$microtime_short = sprintf("%06d", ($microtime_full - floor($microtime_full)) * 1000000);
			$date = new DateTime(date('Y-m-d H:i:s.'.$microtime_short, $microtime_full));
			$date = $date->format($this->_date_fmt);
		}
		else
		{
			$date = date($this->_date_fmt);
		}

		$message .= $level.' - '.$date.' --> '.$msg."\n";

		flock($fp, LOCK_EX);

		for ($written = 0, $length = strlen($message); $written < $length; $written += $result)
		{
			if (($result = fwrite($fp, substr($message, $written))) === FALSE)
			{
				break;
			}
		}

		flock($fp, LOCK_UN);
		fclose($fp);

		if (isset($newfile) && $newfile === TRUE)
		{
			chmod($filepath, $this->_file_permissions);
		}

		return is_int($result);
	}

}
