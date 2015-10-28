<?php
include("config.php");
include("functions.php");

$dir=isset($_GET["dir"]) ? $_GET["dir"] : "";
$all=isset($_GET["all"]) ? $_GET["all"] : true;

$thumbfolder = "./".$gallery_path."/thumb/".$dir;
$fullfolder = "./".$gallery_path."/".$full_images."/".$dir;
$dir_name = returnUmlaute($dir);

//cut path to dirname
$pathinfo = array();
$pathinfo = explode("/", $dir_name);

$pathinfor = array_reverse($pathinfo);
$dir_name = $pathinfor[0];

?>
	<p class="heading"><?php echo $dir_name; ?></p>
<?php

$line = 0;

$pic=$_GET["pic"];
if(!isset($pic))
	$pic = 0;
	
$dirfolders = array();
$dirfolders = get_folder_content($fullfolder, "none", "folders");
	
// get number of folders
$lines = 0;
foreach ($dirfolders as $file) {
	$lines++;
}

$dirfiles = array();
$dirfiles = get_folder_content($fullfolder, "none", "all");

// get name  and number of files
$pics = 0;
$file = "";
foreach ($dirfiles as $item) {
	if($pics == $pic)
		$file = $item;
	$pics++;
}

if(isset($file)) {
	$props = getimagesize($fullfolder."/".$file);
?>
	<p class="listing">
		<a href="gallery.php?dir=<?php echo $dir; ?>&amp;page=<?php echo (floor(($pic+$lines)/$picnum)+1); if($all == true) { 
			echo "&all=".$all; } ?>" class="active">&lt;&lt; zur&uuml;ck zur Galerie</a>
		 | <?php if($pic>0) { ?>
		<a href="picture.php?dir=<?php echo $dir; ?>&amp;pic=<?php echo ($pic-1); ?>" class="active"><img class="nolink" src="blue_back.gif" alt="back" /></a>
		<?php } else echo "<img class=\"nolink\"  src=\"back_grey.gif\" alt=\"back no element\" />"; ?>
		 | <?php if($pic<($pics-1)) { ?>
		<a href="picture.php?dir=<?php echo $dir; ?>&amp;pic=<?php echo ($pic+1); ?>" class="active"><img class="nolink"  src="blue_next.gif" alt="next" /></a>
		<?php } else echo "<img class=\"nolink\"  src=\"next_grey.gif\" alt=\"next no element\" />"; ?>
	</p>
	<p class="flowtext">
		<img src="<?php echo $fullfolder."/".$file; ?>" 
<?php 
	//wenn querformat
	if($props[0]>=$props[1]) {
		if($props[0]<650)
			echo "width=\"$props[0]\"";
		else
			echo "width=\"650\"";
	}
	//hochformat
	else {
		if($props[1]<650)
			echo "height=\"$props[1]\"";
		else
			echo "height=\"650\"";
	}
	echo " alt=\"".$file."\" /></p>";	
}
else {
?>
	<p class="flowtext">
		<a href="gallery.php?dir=<?php echo $dir; ?>&amp;page=<?php echo (floor(($pic+$lines)/$picnum)+1); ?>" class="active">&lt;&lt; zur&uuml;ck zur Galerie</a>
		 | Bild nicht gefunden
	</p>
<?php } ?>