<?
ini_set("memory_limit","512M");

//$im=imageCreate(430,360);
$force_file_name = strtolower(@$_GET['force_file_name']); ////////Принудительно задать имя
$create_type = strtolower(@$_GET['create_type']);  ////////////////создать изображение типа
if ($force_file_name) $outpfn=$force_file_name;
else $outpfn=strtolower(basename($_GET['imgname']));
if (isset($_GET['outfldr'])) $outfldr = strtolower($_GET['outfldr']);
$dirname=strtolower(dirname($_GET['imgname']));


$imgname = $_GET['imgname'];



if (isset($_GET['width'])) $width = $_GET['width'];
else  $width=NULL;
if (isset($_GET['height'])) $height = $_GET['height'];
else $height = NULL;
$create = $_GET['create'];
if(!isset($outfldr )) $outfldr = "pictures/add/icons";
$imgname2=$_SERVER['DOCUMENT_ROOT']."/$outfldr/$outpfn";
$imgname3="http://".$_SERVER['HTTP_HOST']."/$outfldr/add/icons/$outpfn";
//$imgname="http://$HTTP_HOST/$imgname";
$imgname=$_SERVER['DOCUMENT_ROOT']."/$imgname";



$tok = explode(".", $outpfn, 4);
$num_of_parts=sizeof($tok);
$extension = strtolower($tok[$num_of_parts-1]);

//echo $extension;

if ($extension=="jpg" OR $extension=="tmp" OR $extension=="jpeg") $im = imagecreatefromjpeg ($imgname);
else if ($extension=="png") $im = imagecreatefrompng ($imgname);

if (($extension=="jpg" OR $extension=="jpeg") AND @$create_type != 'jpg') $outpfn = str_replace("jpg", "png",$outpfn);
//if (!@$im) $im = @ImageCreateFromGIF ($imgname);
//$im = @ImageCreateFromGif ($imgname);

//print_r($im);
$im_width = ImageSX($im);
$im_height = ImageSY($im);
if (!@$width & !@$height) {
	$new_width=50;
	$k=$im_width/$new_width;
	$im_h_n=$im_height/$k;
}

if (@$width)  {
	$new_width=$width+1;
	$k=$im_width/$new_width;
	$im_h_n=$im_height/$k;
}

if (@$height) {
	$im_h_n=$height+1;
	$k=$im_height/$im_h_n;
	$new_width=$im_width/$k;
}

////////////////���� ���������� ��� ������ 85
//If ($new_width>85) $new_width=85;
//////////////////

//$im2 = ImageCreate($new_width,$im_h_n);
$im2 = ImageCreateTruecolor (($new_width-1), ($im_h_n-1));
//Imagecopyresized ( resource dst_im, resource src_im, int dstX, int dstY, int srcX, int srcY, int dstW, int dstH, int srcW, int srcH)


//ImageCopyResized ($im2, $im, 0,0,0,0,$new_width,$im_h_n,$im_width,$im_height);
Imagecopyresampled ($im2, $im, 0,0,0,0,$new_width,$im_h_n,$im_width,$im_height);
ImageDestroy($im);
//$whte=imageColorAllocate($im, 255, 255, 255);
//$main=imageColorAllocate($im, 33, 66, 99);
//$red=imageColorAllocate($im, 255, 0, 0);
//$black=imageColorAllocate($im, 0, 0, 0);
//$blue=imageColorAllocate($im, 0, 0, 255);
//$green=imageColorAllocate($im, 0, 255, 0);
//$fon=imageColorAllocate($im, 244, 242, 235);
//$osi=imageColorAllocate($im, 175, 169,146);
//imageFill($im,1,1,$fon);

//imageString($im,  3, 10, 10,  $im_width, $blue);
//imageString($im,  3, 10, 20,  $im_height, $blue);
//imageString($im,  3, 10, 30,  $im_h_n, $main);
//imageString($im,  3, 10, 40,  $mul, $main);
//imageString($im,  3, 10, 50,  $asd, $main);
//imageString($im,  3, 10, 60,  $quant, $main);
//imageString($im,  3, 10, 60,  $aaa, $main);
//imageString($im,  3, 10, 10,  $maxv, $main);
//imageString($im,  3, 10, 20,  $miniv, $main);

//imageString($im2,  3, 10, 10,  $dirname, $blue);

//ImageInterlace($im2, 1);

//if  (@$create) ImagePNG($im2, "$DOCUMENT_ROOT/$dirname/small/$outpfn");
//echo $im2;
if  (@$create) {
	//if ($extension=="jpg") ImageJPEG($im2, $_SERVER['DOCUMENT_ROOT']."/pictures/add/icons/$outpfn");
	//elseif ($extension=="png") ImagePNG($im2, $_SERVER['DOCUMENT_ROOT']."/pictures/add/icons/$outpfn");
	//echo $_SERVER['DOCUMENT_ROOT']."/$outfldr/$outpfn";
	if(@$create_type=='jpg')  imagejpeg($im2, $_SERVER['DOCUMENT_ROOT']."/$outfldr/$outpfn");
	else ImagePNG($im2, $_SERVER['DOCUMENT_ROOT']."/$outfldr/$outpfn");
}///if  (@$create) {

//ImagePNG($im2);
ImageJPEG($im2);
@ImageDestroy($im2);
exit();

?>

