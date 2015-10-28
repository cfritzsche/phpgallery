<?php
function getProperLetters($original) {
	// Umlaute entfernen
	$umlaute = Array("/ä/","/ö/","/ü/","/Ä/","/Ö/","/Ü/","/ß/");
	$replace = Array("&auml;","&ouml;","&uuml;","&Auml;","&Ouml;","&Uuml;","&szlig;");
	$result = preg_replace($umlaute, $replace, $original);
	return $result;
}
function returnUmlaute($result) {
	// Umlaute einsetzen
	$umlaute = Array("&auml;","&ouml;","&uuml;","&Auml;","&Ouml;","&Uuml;");
	$replace = Array("ae","oe","ue","Ae","Oe","Ue");
	$original = str_replace($replace, $umlaute, $result);
	return $original;
}
function link2click($str)
{
  $s_patter[]='"(((ftp|http|https){1}://)[-a-zA-Z0-9@:%_\+.~#?&//=]+)"i';
  $r_patter[]='<a href="\1" class="active">\\1</a>';
  //$s_patter[]='"(www.[-a-zA-Z0-9@:%_\+.~#?&//=]+)"i';
  //$r_patter[]='\\1<a href="http://\2" class="active">\\2</a>';
  //$s_patter[]='"([_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,3})"i';
  //$r_patter[]='<a href="mailto:\1" class="active">\\1</a>';
  $str=preg_replace($s_patter,$r_patter,$str);
  return $str;
}
/*
	item type can be
	all		folders and pictures
	folder	only folders
	picture	only pictures
*/
function get_folder_content($foldername, $folder_ordering_mode, $item_type) {
	
	$handle = opendir($foldername);
	
	$list = array();
	$files = array();
	$folders = array();

	while ($item = readdir($handle)) {
		if(is_dir($foldername."/".$item) && $item != "." && $item != ".." )
		{
			array_push($folders, $item);
		}
		
		if(
			strtolower(strrchr($item, '.')) == ".jpg" OR
			strtolower(strrchr($item, '.')) == ".jpeg" OR
			strtolower(strrchr($item, '.')) == ".png" OR
			strtolower(strrchr($item, '.')) == ".gif"
		)
		{
			array_push($files, $item);
		}
	}
	
	closedir($handle);
	$folders = order_folders($folders, $folder_ordering_mode);
	sort($files);
	
	$list = array_merge($folders, $files);
	
	switch($item_type) {
		case "all": return $list;
		case "folders" : return $folders;
		case "pictures" : return $files;
		default: return null;
	}
}

function order_folders($folders, $folder_ordering_mode) {
	// cover all cases
	switch($folder_ordering_mode) {
		case "sort":
			sort($folders);
			break;
		case "reverse_sort":
			sort($folders);
		case "reverse":
			$folders = array_reverse($folders);
			break;
	}
	return $folders;
}
?>