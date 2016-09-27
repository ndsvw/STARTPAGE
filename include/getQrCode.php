<?php
	require_once('QrCode/src/QrCode.php');

	use Endroid\QrCode\QrCode;

	$text = "";
	$hyperlink = "/";
	$size = 100;
	$foregroundColor = array('r' => 0, 'g' => 0, 'b' => 0, 'a' => 0);
	$backgroundColor = array('r' => 255, 'g' => 255, 'b' => 255, 'a' => 0);
	$padding = 5;


	if(isset($_GET['text'])){
		$text = $_GET['text'];
	}

	if(isset($_GET['link'])){
		$hyperlink = $_GET['link'];
	}

	if(isset($_GET['size'])){
		$size = $_GET['size'];
	}

	if(isset($_GET['padding'])){
		$padding = $_GET['padding'];
	}

	if(isset($_GET['color'])){
		$color = urldecode($_GET['color']);
		if(substr($color, 0, 1) == "#"){
			$red = hexdec(substr($color, 1, 2));
			$green = hexdec(substr($color, 3, 2));
			$blue = hexdec(substr($color, 5, 2));
			$foregroundColor = array('r' => $red, 'g' => $green, 'b' => $blue, 'a' => 0);
		} else { //bei rgba
			$red = 0;
			$green = 0;
			$blue = 0;
			$foregroundColor = array('r' => $red, 'g' => $green, 'b' => $blue, 'a' => 0);
		}
	}

	if(isset($_GET['bgcolor'])){
		$color = urldecode($_GET['bgcolor']);
		if(substr($color, 0, 1) == "#"){
			$red = hexdec(substr($color, 1, 2));
			$green = hexdec(substr($color, 3, 2));
			$blue = hexdec(substr($color, 5, 2));
			$backgroundColor = array('r' => $red, 'g' => $green, 'b' => $blue, 'a' => 0);
		} else { //bei rgba
			$red = 255;
			$green = 255;
			$blue = 255;
			$backgroundColor = array('r' => $red, 'g' => $green, 'b' => $blue, 'a' => 0);
		}
	}


	$qr = new QrCode;
	$qr
		-> setText($hyperlink)
		-> setSize($size)
		-> setForegroundColor($foregroundColor)
    		-> setBackgroundColor($backgroundColor)
		-> setPadding($padding)
    		-> setImageType(QrCode::IMAGE_TYPE_PNG)
		-> render();
?>
