<?php
function initDrop() {
	$options = get_option('drop_drop_options');
	$loc_dir = rtrim( $options['drop_drop_loc_dir'], "/\\" );
	$loc_dir = charset_x_win( $loc_dir );
	if ( file_exists( $loc_dir ) && !is_dir( $loc_dir ) ) {
		$files = array( 0 => $loc_dir );
	} elseif ( ListFiles($loc_dir) != FALSE ) {
		$files = ListFiles( $loc_dir );
	}
	$files_count = count( $files );
	$files = serialize( $files );
	$files = htmlentities($files,ENT_QUOTES);
	if( $files ) {
		if( !get_option( 'drop_drop_all_files' ) ) {
			add_option( 'drop_drop_all_files', $files );
		} else {
			update_option( 'drop_drop_all_files', $files );
		}
		
		$runflag = dirname(__FILE__) . '/tmp/drop_running';
		touch( $runflag );

		if( dropNow( 0, 'run2', TRUE ) == TRUE ) { 
			$url = WP_PLUGIN_URL . '/drop-in-dropbox/run1.php';
			$params = array( 'count' => 1 );
			$asynchronous_call = curlPostAsyncDD( $url, $params );
			echo '<p><strong style="color: green">Currently uploading: </strong></span><code>' . file_get_contents( $runflag ) . '</code></p>';
		} else { 
			cleanTmp(); 
		}
	}
}

function fixEnc( $string ) {
	$string = iconv("CP1251", "UTF-8//TRANSLIT", charset_x_win( $string ) );
	return $string;
}
function cleanTmp() {
	foreach ( ListFiles( dirname(__FILE__) . '/tmp/' ) as $key=>$file){
		if( strpos( $file, 'delete-me-not-118346814134' ) ) continue;
		unlink( $file );
	}
	if( get_option( 'drop_drop_all_files' ) ) {
		delete_option( 'drop_drop_all_files' );
	}
}
function dropNow( $count, $run, $test=FALSE ) {
	try { set_time_limit(0); } catch(Exception $e) { echo ''; };
	ini_set("max_execution_time", "3000000000");
	ini_set('memory_limit','128M');
	ini_set('output_buffering', 0);
	ini_set('implicit_flush', 1);
	
	$options = get_option('drop_drop_options');
	$uploader = new DropboxUploader($options['drop_drop_email'], $options['drop_drop_pwd']);
	$uploader->setCaCertificateFile(dirname(__FILE__) . '/certificate.cer');
	
	$rem_dir = trim( $options['drop_drop_rem_dir'], "/\\" );
	$loc_dir = rtrim( $options['drop_drop_loc_dir'], "/\\" );
	$loc_dir = charset_x_win( $loc_dir );
	
	if( !get_option( 'drop_drop_all_files' ) ) exit;
	$files = get_option( 'drop_drop_all_files' );
	$files = html_entity_decode($files,ENT_QUOTES);
	$files = unserialize($files);
	$files_count = count($files);
	if( $count < $files_count ) {
		$time_start = time();
		for( $i=$count; $i<$files_count; $i++ ) {
			if( ( time()-60 ) > $time_start ) { 
				$url = WP_PLUGIN_URL . '/drop-in-dropbox/' . $run . '.php';
				$params = array( 'count' => $i );
				$asynchronous_call = curlPostAsyncDD( $url, $params );
				break; 
			}
			if( ( strpos( $files[$i], 'plugins/drop-in-dropbox/tmp' ) != FALSE ) && ( strpos( $files[$i], 'drop-in-dropbox/tmp/delete-me-not-118346814134' ) ) == FALSE ) continue;
			$runflag = dirname(__FILE__) . '/tmp/drop_running';
			if( !file_exists( $runflag )  ) { 
				break; 
			} else {
				$filetime = filemtime( $runflag );
				$timeout = time()-1200; 
				if ($filetime <= $timeout) {
					unlink( $runflag );
				} 
				
				$files[$i] = str_replace( '\\', '/', $files[$i] );
				$file_base = str_replace( dirname( $files[$i] ) . '/', '', $files[$i] );
				$file_new_base = fixEnc( $file_base );
				$i_dir = substr( $files[$i], 0, strlen( $files[$i] ) - strlen( $file_base ) ); 
				$i_dir = substr( $i_dir, strlen( $loc_dir ), strlen( $i_dir ) );
				$temp_file = dirname(__FILE__) . '/tmp/' . $file_new_base;
				$temp_name_ext = $file_new_base;

				if( strlen( $file_new_base ) > 200 ) {
					$info = pathinfo($file_new_base);
					$file_name = substr( $file_new_base, 0, strlen( $file_new_base ) - strlen( $info['extension'] ) );
					$file_name = fixEnc( substr( $file_name, 0, 179 ) ) . "[...]";
					$temp_file = dirname( $temp_file ) . '/' . $file_name . '.' . $info['extension'];
					$temp_name_ext = $file_name . '.' . $info['extension'];
				}
				try { full_copy( $files[$i], $temp_file ); } catch(Exception $e) { echo 'COPY FAILED'; }

				$up_dir = $rem_dir . fixEnc( $i_dir );
				try { $uploader -> upload( $temp_file, $up_dir ); } catch(Exception $e) { $error=$e->getMessage(); echo '<p style="color:red"><strong>UPLOAD FAILED: ' . $e->getMessage() . '</strong></p>'; }
				file_put_contents( $runflag, ($i+1) . ' out of ' . $files_count . ': ' . fixEnc( $i_dir ) . $temp_name_ext ); // write currently uploaded filename to flagfile
				if( strpos( $temp_file, 'delete-me-not-118346814134' ) == FALSE ) unlink( $temp_file );
				$c = $i;
				if( $test == TRUE ) { // end function if this is a first 'test' run
					if( isset($error) ) { return FALSE; } else { return TRUE; }
				}
			}
		}
	}
	if( ($c+1) >= $files_count ) { cleanTmp(); } 
}

function full_copy( $source, $target ) {
	if ( is_dir( $source ) ) {
		@mkdir( $target );
		$d = dir( $source );
		while ( FALSE !== ( $entry = $d->read() ) ) {
			if ( $entry == '.' || $entry == '..' ) {
				continue;
			}
			$Entry = $source . '/' . $entry; 
			if ( is_dir( $Entry ) ) {
				full_copy( $Entry, $target . '/' . $entry );
				continue;
			}
			copy( $Entry, $target . '/' . $entry );
		}
 
		$d->close();
	} else {
		copy( $source, $target );
	}
}

function ListFiles($dir) {
	if( is_dir( $dir ) ) { 
		if($dh = opendir($dir)) {

			$files = Array();
			$inner_files = Array();

			while($file = readdir($dh)) {
				if($file != "." && $file != ".." && $file[0] != '.') {
					if(is_dir($dir . "/" . $file)) {
						$inner_files = ListFiles($dir . "/" . $file);
						if(is_array($inner_files)) $files = array_merge($files, $inner_files); 
					} else {
						array_push($files, $dir . "/" . $file);
					}
				}
			}

			closedir($dh);
			return $files;
		}
	} else {
		echo '<p style="color:red"><strong>Error message: No such directory.</strong></p>';
		return FALSE;
	}
}

function curlPostAsyncDD($url, $params = null) { 
	if($params) { 
		foreach ($params as $key => &$val) { 
			if (is_array($val)) $val = implode(',', $val); 
				$post_params[] = $key.'='.urlencode($val); 
		} 
		if($post_params) $post_string = implode('&', $post_params); 
	}

	$parts=parse_url($url);

	$fp = fsockopen($parts['host'], 
	isset($parts['port'])?$parts['port']:80, 
	$errno, $errstr, 30);

	if($fp) { 
		$out = "POST ".$parts['path']." HTTP/1.1\r\n"; 
		$out.= "Host: ".$parts['host']."\r\n"; 
		$out.= "Content-Type: application/x-www-form-urlencoded\r\n"; 
		$out.= "Content-Length: ".strlen($post_string)."\r\n"; 
		$out.= "Connection: Close\r\n\r\n"; 
		if (isset($post_string)) $out.= $post_string;
		fwrite($fp, $out); 
		fclose($fp);
		return true; 
	} else { 
		return false; 
	} 
}

?>