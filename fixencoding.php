<?php

// Does not support flag GLOB_BRACE
function rglob($pattern, $flags = 0) {
    $files = glob($pattern, $flags); 
    foreach (glob(dirname($pattern).'/*', GLOB_ONLYDIR|GLOB_NOSORT) as $dir) {
        $files = array_merge($files, rglob($dir.'/'.basename($pattern), $flags));
    }
    return $files;
}

function rsearch($folder, $pattern) {
    $dir = new RecursiveDirectoryIterator($folder);
    $ite = new RecursiveIteratorIterator($dir);
    $files = new RegexIterator($ite, $pattern, RegexIterator::GET_MATCH);
    $fileList = array();
    foreach($files as $file) {
        $fileList = array_merge($fileList, $file);
    }
    return $fileList;
}

function fix_file($file) {
	$fback = "{$file}.bak";
	if (!is_file($fback)) {
		copy($file, $fback);
	}
	
	$data = file_get_contents($fback);
	$fixedData = $data;
	//echo html_entity_decode($data);
	//$fixedData = html_entity_decode(mb_convert_encoding($data, 'utf-8', 'ISO-8859-1'), ENT_COMPAT, 'utf-8');
	//$fixedData = mb_convert_encoding($data, 'utf-8', 'ISO-8859-1');
	$fixedData = html_entity_decode($fixedData);
	$fixedData = mb_convert_encoding($fixedData, 'ISO-8859-1', 'utf-8');
	file_put_contents($file, $fixedData);
	echo $fixedData;
}

foreach (rglob('*.html') as $file) {
	echo "$file\n";
	fix_file($file);
}

//fix_file('_posts/2011-03-28-insertado-caracter-n.html');
