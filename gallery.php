<?php
include("config.php");
include("functions.php");

// TStarGallery 1.0 engine
// With basic PHP knowledge you can understand what's happening here.
// Don't blame me for writing non-perfect code for I do not care.
// If there is an error or something, mail me at tstar@taillandier.de


// Read the current directory, throw out non-jpg/gif/png + thumbfiles
// --------------------------------------------------------------------
$dir=isset($_GET["dir"]) ? $_GET["dir"] : ".";
if(!isset($dir) || $dir == "." || stripos($dir, "..")!=false )
	$dir = "";
	
if($dir != "") {	
	$thumbfolder = "./".$gallery_path."/thumb/".$dir;
	$fullfolder = "./".$gallery_path."/".$full_images."/".$dir;
}
else {
	$thumbfolder = "./".$gallery_path."/thumb";
	$fullfolder = "./".$gallery_path."/".$full_images;
}
$dirname = returnUmlaute($dir);

$items = get_folder_content($fullfolder, $folder_ordering_mode, "all");

$lines = 0;
foreach ($items as $item) 
	$lines++;

// Write the beginning of the basic table for displaying the thumbs.
// Modify this section to make it fit your own website.
// -----------------------------------------------------------------

$page = isset($_GET["page"]) ? $_GET["page"] : 1;
$all = isset($_GET["all"]) ? $_GET["all"] : false;
$pages = ceil($lines/$picnum);

//cut path to dirname
$backlink = "gallery.php";

$pathinfo = array();
$pathinfo = explode("/", $dirname);

$pathinfor = array_reverse($pathinfo);

$dir_name = $pathinfor[0];

if($dir != "") {
	$backlink = $backlink."?dir=";
	$num = 0;
	
	$oldpath = "";
	foreach($pathinfo as $path) {
		if($path != $dir_name) {
			if($num == 0)
				$oldpath = $oldpath.$path;
			else
				$oldpath = $oldpath."/".$path;
		}
			
		$num++;
	}
	$backlink = $backlink.$oldpath;
	
	//determine page
	$oldfullfolder = "./".$gallery_path."/".$full_images."/".$oldpath;
	
	$olddirs = get_folder_content($oldfullfolder, $folder_ordering_mode, "folders");
	
	$oldlines = 0;
	foreach($olddirs as $olddir) {
		if($olddir == $dirname)
			break;
		$oldlines++;
	}

	$oldpage = floor($oldlines/$picnum)+1;
	$backlink = $backlink."&page=".$oldpage;	
}

?>
<p class="heading"><?php echo $dir_name; ?></p>
<p class="listing">
<?php if($dir != "") { ?>
	<a href="<?php echo $backlink; ?>" class="active">&lt;&lt; zur&uuml;ck</a>
	<?php } ?>
	<?php if(($pages>1)&&($all==false)) { ?>
	<?php if($page>1) { ?>
	<?php if($page>1 && $dir != "") { ?> | <?php } ?>
	<a href="gallery.php?dir=<?php echo $dir; ?>&amp;page=<?php echo ($page-1); ?>" class="active"><img class="nolink" src="blue_back.gif" alt="back" /></a>
	<?php } else echo "<img class=\"nolink\"  src=\"back_grey.gif\" alt=\"back no element\" />"; ?>
	 | <?php if($page<($pages)) { ?>
	<a href="gallery.php?dir=<?php echo $dir; ?>&amp;page=<?php echo ($page+1); ?>" class="active"><img class="nolink"  src="blue_next.gif" alt="next" /></a>
	<?php } else echo "<img class=\"nolink\"  src=\"next_grey.gif\" alt=\"next no element\" />"; ?>
	
<?php
	$break = false;
	for($a = 1; $a <= $pages; $a++) {
		if((-3<($a-$page)&&($a-$page)<3)||($a==1)||($a==$pages)) {
			if($break) {
				echo " ... ";
				$break = false;
			}
			else	
				echo " | ";
			if($page==$a)
				echo "$a";
			else
				echo "<a href=\"gallery.php?dir=$dir&amp;page=$a\" class=\"active\">$a</a>";
		}
		else
			$break = true;
	}
}
?>
</p>
<?php
echo "<table width=\"750\" cellpadding=\"30\" cellspacing=\"0\" id=\"structure\">";


// Read the valid filenames from the array, have your way with every single one of them
// ------------------------------------------------------------------------------------

$start = (($page-1)*$picnum);
$i = 1;

foreach($items as $aktuellesfile)
{
	if(!is_dir($aktuellesfile)) {
		$dateiendung = strrchr( $aktuellesfile, '.' );
		$dateiname = substr_replace ($aktuellesfile, '', -strlen($dateiendung) );
		
		// First a routine for creating a thumb
		createthumb ($thumbfolder, $fullfolder, $dateiname, $dateiendung);
	}
}
$line = ($page-1)*$picnum;
$cnt = 0;

foreach($items as $aktuellesfile)
{
	if(($i < ($start+1))&&($all==false)) {
		$i++;
		continue;
	}
	if(++$cnt % $colnum == 1) echo "<tr align=\"center\" valign=\"middle\">";

	// Now open up a table cell
	echo "<td>";

	if(!is_dir($fullfolder."/".$aktuellesfile)) {
		// Elements of the filename are cut into pieces
		$dateiendung = strrchr( $aktuellesfile, '.' );
		$dateiname = substr_replace ($aktuellesfile, '', -strlen($dateiendung) );

		// Second a routine for showing a thumb
		showthumb ($thumbfolder, $fullfolder, $dateiname, $dateiendung,$line,$dir);
	}

	else {
		if($dir!="")
			$newdirname = $dir."/".$aktuellesfile;
		else
			$newdirname = $aktuellesfile;

		// display all link only when in root directory
		echo "<a class=\"inactive\" href=\"gallery.php?dir=$newdirname\"><img src=\"folder.png\" alt=\"Subfolder\" /></a><br /><a class=\"active\" href=\"gallery.php?dir=$newdirname\">$aktuellesfile</a>";
	}

	// Close the table cell
	echo "</td>";

	$line++;

	// And make a linebreak after every 5 thumbs
	if(++$cnt % $colnum == 0) echo "</tr>";
	$i++;
	if(($i>($start+$picnum))&&($all==false)) break;
}
echo "</table>";
// Finished
//exit;

   
// Function to create a thumbnail if it doesn't already exist
// -----------------------------------------------------------------
function createthumb ($thumbfolder, $fullfolder, $thumbdateiname, $thumbdateiendung)
{
	$fullname = $fullfolder."/".$thumbdateiname.$thumbdateiendung;
	$fullthumbname = $thumbfolder."/".$thumbdateiname.$thumbdateiendung;
	
	//create folder if needed
	if(!file_exists($thumbfolder))
		mkdir($thumbfolder, 0777, true);

	// If thumb exists,nothing will happen
	if (file_exists($fullthumbname))
	{   
	}
	// If thumb doesn't exist,it's created now
	else
	{	
		
		if ((strtolower($thumbdateiendung) == ".jpg") OR (strtolower($thumbdateiendung) == ".jpeg")){
		$src_img = imagecreatefromjpeg($fullname); 
		}
		if (strtolower($thumbdateiendung) == ".gif"){
		$src_img = imagecreatefromgif($fullname); 
		}
		if (strtolower($thumbdateiendung) == ".png"){
		$src_img = imagecreatefrompng($fullname); 
		}
	
		$origx=imagesx($src_img);
		$origy=imagesy($src_img);
		
		// Maximum width and height of the thumbnails
		$max_x = 180;
		$max_y = 180;
  	
		// Calc, if thumb has has to be squeezed from width or height
		if($origx >= $origy AND $origx > $max_x)
		{
			$faktor = $origx / $max_x;	
			$new_x = $origx / $faktor;
			$new_y = $origy / $faktor;	
		}
		
		elseif($origy > $origx AND $origy > $max_y)
		{
			$faktor = $origy / $max_y;	
			$new_x = $origx / $faktor;
			$new_y = $origy / $faktor;	
		}
		
		else
		{
			$new_x = $origx;
			$new_y = $origy;
		}
  	
		// Squeeze and write it into a file
		$dst_img = imagecreatetruecolor($new_x,$new_y);
		imagecopyresampled($dst_img,$src_img,0,0,0,0,$new_x,$new_y,imagesx($src_img),imagesy($src_img));
		imagejpeg($dst_img, $fullthumbname, 50);
	}
}                    

// Function to show a thumbnail
// -----------------------------------------------------------------
function showthumb ($thumbfolder, $fullfolder, $thumbdateiname, $thumbdateiendung,$line,$dir)
{
	$fullname = $fullfolder."/".$thumbdateiname.$thumbdateiendung;
	$fullthumbname = $thumbfolder."/".$thumbdateiname.$thumbdateiendung;
	
	if (file_exists($fullthumbname))
	{
	echo "<a href=\"picture.php?dir=$dir&amp;pic=$line";
	echo "\"><img src =\"$fullthumbname\" alt=\"$fullthumbname\" /></a>";
	// echo "<!-- =========================== -->";
	// echo "<!-- powered by TStarGallery 1.0 -->";
	// echo "<!-- =========================== -->";

	}
	else
	{
	}

} ?>