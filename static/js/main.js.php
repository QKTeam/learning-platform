<?php
function addFiles($path) {
	global $jsCode;
	$files = scandir($path);
	for($i = 0; $i < count($files); $i++) {
		switch ($files[$i]) {
			case '.': break;
			case '..': break;
			default:
				if(is_dir($path.'/'.$files[$i])) {
					addFiles($path.'/'.$files[$i]);
				}
				else {
					$jsCode .= file_get_contents($path.'/'.$files[$i]);
				}
				break;
		}
	}
}
$jsCode = '';
$folders = ['public', 'angular/config', 'angular/directive', 'angular/run', 'angular/controller'];
for($i = 0; $i < count($folders); $i++) {
	addFiles('./'.$folders[$i]);
}
header('Content-type: text/javascript');
echo '(function(){'.$jsCode.'})()';