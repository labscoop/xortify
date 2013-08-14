<?php
/**
 * Xortify API Function
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       Chronolabs Coopertive (Australia)  http://web.labs.coop
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         api
 * @subpackage		xml
 * @author         	Simon Roberts (wishcraft) - meshy@labs.coop
 */

class FunctionsHandler {

	var $functions=array();

	/*	Constructor
	 *  __construct($wsdl)
	 */
	function __construct($wsdl) {
	
	}
	
	/*	GEt the server Extensions/Plugins/Functions
	 *  GetServerExtensions ()
	 *
	 *  @return array
	 */
	function GetServerExtensions () {
		$files = array();
		$f = array();
		$files = $this->getFileListAsArray(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'plugins');
		static $f_count;
		static $f_buffer;
		
		if ($f_count != count($files)){
			$f_count = count($files);
			foreach($files as $k => $l){
				if (strpos($k,".php",1)== (strlen($k)-4)){
					$f[] = $k;
				}
			}
			$f_buffer = $f;
		}
		
		return $f_buffer;
			
	}

	/*	Gets Dir Folder/Path list as an array
	 *  getDirListAsArray( $dirname )
	 *
	 *  @param string $dirname			Full Path to provide directory listing of
	 *  @return array
	 */
	function getDirListAsArray( $dirname ) {
		$ignored = array();
		$list = array();
		if ( substr( $dirname, -1 ) != '/' ) {
			$dirname .= '/';
		}
		if ( $handle = opendir( $dirname ) ) {
			while ( $file = readdir( $handle ) ) {
				if ( substr( $file, 0, 1 ) == '.' || in_array( strtolower( $file ), $ignored ) )	continue;
				if ( is_dir( $dirname . $file ) ) {
					$list[$file] = $file;
				}
			}
			closedir( $handle );
			asort( $list );
			reset( $list );
		}
		//print_r($list);
		return $list;
	}

	/* gets list of all files in a directory
	 *  getFileListAsArray($dirname, $prefix="")
	 *  
	 * 	@param string $dirname			Full Path to provide file listing of
	 *  @param string $prefix			File prefix to return on
	 *  @return array
	 */
	function getFileListAsArray($dirname, $prefix="")
	{
		$filelist = array();
		if (substr($dirname, -1) == '/') {
			$dirname = substr($dirname, 0, -1);
		}
		if (is_dir($dirname) && $handle = opendir($dirname)) {
			while (false !== ($file = readdir($handle))) {
				if (!preg_match("/^[\.]{1,2}$/",$file) && is_file($dirname.'/'.$file)) {
					$file = $prefix.$file;
					$filelist[$file] = $file;
				}
			}
			closedir($handle);
			asort($filelist);
			reset($filelist);
		}
		return $filelist;
	}
		
	/*	Deconstructor
	 *  __destruct()
	 */
	 function __destruct() {
	
	}
	
}

?>