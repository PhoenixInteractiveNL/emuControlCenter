<?php
/******************************************************************
* Projectname:   PHP INI Class 
* Version:       1.0
* Author:        Radovan Janjic <rade@it-radionica.com>
* Last modified: 29 06 2013
* Copyright (C): 2011 IT-radionica.com, All Rights Reserved
*
*** GNU General Public License (Version 2, June 1991)
*
* This program is free software; you can redistribute
* it and/or modify it under the terms of the GNU
* General Public License as published by the Free
* Software Foundation; either version 2 of the License,
* or (at your option) any later version.
*
* This program is distributed in the hope that it will
* be useful, but WITHOUT ANY WARRANTY; without even the
* implied warranty of MERCHANTABILITY or FITNESS FOR A
* PARTICULAR PURPOSE. See the GNU General Public License
* for more details.
*
*** Description
*
* Simple PHP Class to manage INI files (read and write).
*
*******************************************************************
*
** Examples
*

// Parse config.ini
$ini = new INI('config.ini');

echo '<pre>';
echo 'Content of: config.ini' . PHP_EOL;
print_r($ini->data);

// Udate settings
$ini->data['first_section']['animal'] = 'COW';

// Save settings to file
$ini->write();

// Update settings
$ini->data['first_section']['animal'] = 'HORSE';

// Add new setting to section third_section
$ini->data['third_section']['phpversion'][] = 5.4;

// Add new section third_section and new item something
$ini->data['fourth_section']['something'] = 'some data';

// Save settings to new file
$ini->write('config-2.ini');

// INI obj is now using ini 2 file
echo '<hr>Content of: config-2.ini' . PHP_EOL;
print_r($ini->data);

// Parse config.ini
$ini->read('config.ini');

// Remove item from second_section
unset($ini->data['second_section']['URL']);

// Remove third_section from second ini file and save to third file
unset($ini->data['third_section']);

// Save settings to new file
$ini->write('config-3.ini');

// INI obj is now using ini 3 file
echo '<hr>Content of: config-3.ini' . PHP_EOL;
print_r($ini->data);

******************************************************************/

class INI
{
	/** INI file path
	 * @var String
	 */
	var $file = NULL;
	
	/** INI data
	 * @var Array
	 */
	var $data = array();
	
	/** Process sections
	 * @var Boolean
	 */
	var $sections = TRUE;
	
	/** Parse INI file
	 * @param 	String		$file 		- INI file path
	 * @param 	Boolean		$sections 	- Process sections
	 */
	function INI() {
		if (func_num_args()) {
			$args = func_get_args();
			call_user_func_array(array($this, 'read'), $args);
		}
	}
	
	/** Parse INI file
	 * @param 	String		$file 		- INI file path
	 * @param 	Boolean		$sections 	- Process sections
	 */
	function read($file = NULL, $sections = TRUE) {
		$this->file = ($file) ? $file : $this->file;
		$this->sections = $sections;
		$this->data = parse_ini_file(realpath($this->file), $this->sections);
		return $this->data;
	}
	
	/** Write INI file
	 * @param 	String		$file 		- INI file path
	 * @param 	Array		$data 		- Data (Associative Array)
	 * @param 	Boolean		$sections 	- Process sections
	 */
	function write($file = NULL, $data = array(), $sections = TRUE) {
		$this->data = (!empty($data)) ? $data : $this->data;
		$this->file = ($file) ? $file : $this->file;
		$this->sections = $sections;
		$content = NULL;
		
		if ($this->sections) {
			foreach ($this->data as $section => $data) {
				$content .= '[' . $section . ']' . PHP_EOL;
				foreach ($data as $key => $val) {
					if (is_array($val)) {
						foreach ($val as $v) {
							$content .= $key . '[] = ' . (is_numeric($v) ? $v : '"' . $v . '"') . PHP_EOL;
						}
					} elseif (empty($val)) {
						$content .= $key . ' = ' . PHP_EOL;
					} else {
						$content .= $key . ' = ' . (is_numeric($val) ? $val : '"' . $val . '"') . PHP_EOL;
					}
				}
				$content .= PHP_EOL;
			}
		} else {
			foreach ($this->data as $key => $val) {
				if (is_array($val)) {
					foreach ($val as $v) {
						$content .= $key . '[] = ' . (is_numeric($v) ? $v : '"' . $v . '"') . PHP_EOL;
					}
				} elseif (empty($val)) {
					$content .= $key . ' = ' . PHP_EOL;
				} else {
					$content .= $key . ' = ' . (is_numeric($val) ? $val : '"' . $val . '"') . PHP_EOL;
				}
			}
		}
		
		return (($handle = fopen($this->file, 'w')) && fwrite($handle, trim($content)) && fclose($handle)) ? TRUE : FALSE;
	}
}