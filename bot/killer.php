<?php

require_once ('app/Mage.php');

/**
* 
*/
class Killer
{

	/* Captcha name 											*/
	protected $name 				=	'token';

	/* Encryption key 											*/
	protected $key 					=	'';

	/* Captcha settings 										*/
	protected $type					=	'random';	// random (default), number, character, dictionary
	protected $minWordLength		=	3;
	protected $maxWordLength		=	5;
	protected $fuzzy 				=	true;

	/* Captcha image settings 									*/
	protected $width				=	200;
	protected $height				=	70;
	protected $bgColor				=	'fff';	
	protected $mimeType				=	'image/png';
	protected $scaleCanvas 			=	1;
	protected $quality 				=	75;

	/* Captcha font settings 									*/
	protected $font 				=	'TimesNewRomanBold';
	protected $fgColor				=	'000';
	protected $shadowColor			=	'000';	
	protected $scaleFont 			=	2;

	/* Captcha wave settings 							*/
	protected $Yperiod    			= 	12;
	protected $Yamplitude 			= 	14;
	protected $Xperiod    			= 	11;
	protected $Xamplitude 			= 	5;

	/* Captcha noise settings 					*/
	protected $noiseColor1			=	'fff';
	protected $noiseColor2			=	'000';
	protected $noiseDensity 		=	15;

	/* Captcha random line settings 					*/
	protected $lineColor1			=	'fff';
	protected $lineColor2			=	'000';
	protected $lineDensity 			=	15;

	/* Captcha smooth filter settings 					*/
	protected $smoothQuality 		=	8;	//	-6 to 8

	protected $assets 				=	array(
											'fonts'		=>	'/assets/fonts/',
											'dictionary'=>	'/assets/dictionary/'
										);

	protected $debug 				=	false;

	protected $mage 				=	null;

	/* ==========================================================================
	   General functions
	   ========================================================================== */


	public function __construct()	{

		require_once ('app/Mage.php');
		Mage::app(); 

		// Define the path to the root of Magento installation.
		define('ROOT', Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB));

		$this->mage = Mage::getSingleton('core/session');

	}

	public function __get($name)	{

		if ( isset($name) )	{

			return $this->$name;
		}
	}

	public function __set($name, $value)	{

		if ( isset($name) )	{

			$this->$name 	=	$value;
		}
	}

	/* ==========================================================================
	   Captcha functions
	   ========================================================================== */


	public function getCaptcha()	{

		$text 		=	$this->getCaptchaText();

		$newData 	=	strlen($this->key) > 0 ? $this->encrypt( $text ) : $text;
		
		$this->setSession( $newData );

		$canvas 	=	$this->createCanvas($this->width, $this->height, $this->bgColor, $this->scaleCanvas, $this->debug);

		$textCanvas	=	$this->createText($text, $this->bgColor, $this->fgColor, $this->scaleFont, $this->debug);

		$canvas 	=	$this->copyCanvas($canvas, $textCanvas);

		$canvas 	=	$this->addNose($canvas, $this->noiseColor1, $this->noiseColor2, $this->noiseDensity);

		$canvas 	=	$this->addRandomLines($canvas, $this->lineColor1, $this->lineColor2, $this->lineDensity);

		$canvas 	=	$this->addGaussianFilter($canvas);

		$canvas 	=	$this->addWave($canvas, $this->Xperiod, $this->Xamplitude, $this->Yperiod, $this->Yamplitude);

		$canvas 	=	$this->addGaussianFilter($canvas);

		$canvas 	=	$this->addSmoothFilter($canvas, $this->smoothQuality);

		$this->writeCanvas($canvas);
	}

	public function isCorrect( $challenge )	{
	
		$challenge 	=	strlen($this->key) > 0 ? $this->encrypt( $challenge ) : $challenge;

		$sessData 	=	$this->getSession();
		if ( $challenge == $sessData )
			return true;	

		return false;
	}

	public function setSession($value)	{

		require_once ('app/Mage.php');
		Mage::app(); 

		// Define the path to the root of Magento installation.
		define('ROOT', Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB));

		Mage::getSingleton('core/session')->setToken($value);

	}

	public function getSession()
	{
		require_once ('app/Mage.php');
		Mage::app(); 

		// Define the path to the root of Magento installation.
		define('ROOT', Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB));

		return Mage::getSingleton('core/session')->getToken();
	}

	private function getCaptchaText()	{

		$output 	=	'default';

		if ( $this->fuzzy )
			$output 	=	'default';

		if ( $this->type == 'random' )	{

			$choice 	=	mt_rand(1,3);
			
			if ( 1 == $choice )
				return $this->randomNumbers( null, $this->minWordLength, $this->maxWordLength );

			if ( 2 == $choice )
				return $this->randomCharacters( null, $this->minWordLength, $this->maxWordLength );

			if ( 3 == $choice )
				return $this->randomWord($output , $this->minWordLength, $this->maxWordLength );
		}

		if ( $this->type == 'number' )
			return $this->randomNumbers( null, $this->minWordLength, $this->maxWordLength );		

		if ( $this->type == 'character' )
			return $this->randomCharacters( null, $this->minWordLength, $this->maxWordLength );

		if ( $this->type == 'dictionary' )
			return $this->randomWord( $output , $this->minWordLength, $this->maxWordLength );
	}

	/* ==========================================================================
	   Random number, characters or word generators functions
	   ========================================================================== */


	/**
	 * Random number generator
	 * 
	 * @param  integer $length    length of characters
	 * @param  integer $minLength minimum length
	 * @param  integer $maxLength maximum length
	 * @return string             random characters
	 */
	protected function randomNumbers( $length = null, $minLength = 5, $maxLength =	8 ) {

		if ( empty( $length ) ) {
	        $length = rand($minLength, $maxLength);
	    }

		$numbers	=	implode('', range(0, 9) );

		if ( $length > strlen($numbers) )
			$numbers	=	str_repeat($numbers, $length / 10);

	    return substr(str_shuffle($numbers), 0, $length);
	}

	/**
	 * Random character generator
	 * 
	 * @param  integer $length    length of characters
	 * @param  integer $minLength minimum length
	 * @param  integer $maxLength maximum length
	 * @return string             random characters
	 */
	protected function randomCharacters( $length = null, $minLength = 5, $maxLength = 8 ) {

		if ( empty( $length ) ) {
	        $length = mt_rand($minLength, $maxLength);
	    }

		$characters	=	implode('', range('a', 'z') );
		$numbers	=	implode('', range(0, 9) );

		$string 	=	$numbers . $characters . strtoupper($characters);

		if ( $length > strlen($string) )
			$string	=	str_repeat($string, $length / 10);

	    return substr(str_shuffle($string), 0, $length);
	}

	/**
	 * Random word from given dictionary
	 * 
	 * @param  integer $minWordSize  minimum length for the word
	 * @param  integer $maxWordSize  maximum length for the word
	 * @param  string  $output       default or fuzzy
	 * @param  string  $filename     dictionary filename
	 * @param  string  $splitPattern pattern to split words into array
	 * @return string                random word
	 */
	protected function randomWord( $output = 'default', $minWordSize = 5, $maxWordSize = 8, $filename = null , $splitPattern = '/[\s,]+/' )	{

		$filepath 	= dirname( __FILE__ ) . $this->assets['dictionary'] . $filename;

		if ( is_null($filename) )	{
			$filename 	= $filepath . 'words';
		}

		if ( ! file_exists($filename) )	{
			throw new Exception('Invalid input parameters: '.$filename.' does not exist'); 
		}
		
		if ( ( $filestring = file_get_contents($filename)) === false )	{
			throw new Exception('Cannot read input file '.$filename);
		}	

		if ( ( $words = preg_split($splitPattern, $filestring)) === false )	{
			throw new Exception('Invalid input parameters Cannot split words in '.$filename.' using pattern '.$splitPattern);
		}

		foreach ($words as $index => $word)	{

	        if ( strlen( trim( $word ) ) < $minWordSize  )	{

	        	unset ( $words[$index] );
	        }        

	        if ( strlen( trim( $word ) ) > $maxWordSize  )	{

	        	unset ( $words[$index] );
	        }
	    }

	    $min 	=	0;
	    $max 	=	count($words);

	    shuffle ( $words );
	    $word 	=	$words [ mt_rand ($min , $max) ];

	    if ( $output == 'fuzzy' )
			return implode( '', array_map( function($v) { return mt_rand(0,1) ? strtoupper($v) : $v;  }, str_split ($word)));

	    return $word;
	}


	/* ==========================================================================
	   Convert hex color, name color or rgb color to array
	   ========================================================================== */


	/**
	 * Get color into rgb array format
	 * 
	 * @param  mixed $color array(255, 255, 255), #FFFFFF, #FFF, white 
	 * @return array        array(255, 255, 255)
	 */
	protected function getColor( $color = null )	{

		if ( is_array( $color ) && count($color) == 3 )	{

			return $color;	
		}	
		
		if ( is_string( $color ) )	{

			$color = trim($color, '#');

			if ( strlen( $color ) == 6 )	{

				return array_map('hexdec', str_split($color, 2));
			}

			if ( strlen( $color ) == 3 )	{

				$color = $color[0].$color[0].$color[1].$color[1].$color[2].$color[2];
				return array_map('hexdec', str_split($color, 2));
			}

			$colors  =  array(
		        'aliceblue'=>'F0F8FF',
		        'antiquewhite'=>'FAEBD7',
		        'aqua'=>'00FFFF',
		        'aquamarine'=>'7FFFD4',
		        'azure'=>'F0FFFF',
		        'beige'=>'F5F5DC',
		        'bisque'=>'FFE4C4',
		        'black'=>'000000',
		        'blanchedalmond'=>'FFEBCD',
		        'blue'=>'0000FF',
		        'blueviolet'=>'8A2BE2',
		        'brown'=>'A52A2A',
		        'burlywood'=>'DEB887',
		        'cadetblue'=>'5F9EA0',
		        'chartreuse'=>'7FFF00',
		        'chocolate'=>'D2691E',
		        'coral'=>'FF7F50',
		        'cornflowerblue'=>'6495ED',
		        'cornsilk'=>'FFF8DC',
		        'crimson'=>'DC143C',
		        'cyan'=>'00FFFF',
		        'darkblue'=>'00008B',
		        'darkcyan'=>'008B8B',
		        'darkgoldenrod'=>'B8860B',
		        'darkgray'=>'A9A9A9',
		        'darkgreen'=>'006400',
		        'darkgrey'=>'A9A9A9',
		        'darkkhaki'=>'BDB76B',
		        'darkmagenta'=>'8B008B',
		        'darkolivegreen'=>'556B2F',
		        'darkorange'=>'FF8C00',
		        'darkorchid'=>'9932CC',
		        'darkred'=>'8B0000',
		        'darksalmon'=>'E9967A',
		        'darkseagreen'=>'8FBC8F',
		        'darkslateblue'=>'483D8B',
		        'darkslategray'=>'2F4F4F',
		        'darkslategrey'=>'2F4F4F',
		        'darkturquoise'=>'00CED1',
		        'darkviolet'=>'9400D3',
		        'deeppink'=>'FF1493',
		        'deepskyblue'=>'00BFFF',
		        'dimgray'=>'696969',
		        'dimgrey'=>'696969',
		        'dodgerblue'=>'1E90FF',
		        'firebrick'=>'B22222',
		        'floralwhite'=>'FFFAF0',
		        'forestgreen'=>'228B22',
		        'fuchsia'=>'FF00FF',
		        'gainsboro'=>'DCDCDC',
		        'ghostwhite'=>'F8F8FF',
		        'gold'=>'FFD700',
		        'goldenrod'=>'DAA520',
		        'gray'=>'808080',
		        'green'=>'008000',
		        'greenyellow'=>'ADFF2F',
		        'grey'=>'808080',
		        'honeydew'=>'F0FFF0',
		        'hotpink'=>'FF69B4',
		        'indianred'=>'CD5C5C',
		        'indigo'=>'4B0082',
		        'ivory'=>'FFFFF0',
		        'khaki'=>'F0E68C',
		        'lavender'=>'E6E6FA',
		        'lavenderblush'=>'FFF0F5',
		        'lawngreen'=>'7CFC00',
		        'lemonchiffon'=>'FFFACD',
		        'lightblue'=>'ADD8E6',
		        'lightcoral'=>'F08080',
		        'lightcyan'=>'E0FFFF',
		        'lightgoldenrodyellow'=>'FAFAD2',
		        'lightgray'=>'D3D3D3',
		        'lightgreen'=>'90EE90',
		        'lightgrey'=>'D3D3D3',
		        'lightpink'=>'FFB6C1',
		        'lightsalmon'=>'FFA07A',
		        'lightseagreen'=>'20B2AA',
		        'lightskyblue'=>'87CEFA',
		        'lightslategray'=>'778899',
		        'lightslategrey'=>'778899',
		        'lightsteelblue'=>'B0C4DE',
		        'lightyellow'=>'FFFFE0',
		        'lime'=>'00FF00',
		        'limegreen'=>'32CD32',
		        'linen'=>'FAF0E6',
		        'magenta'=>'FF00FF',
		        'maroon'=>'800000',
		        'mediumaquamarine'=>'66CDAA',
		        'mediumblue'=>'0000CD',
		        'mediumorchid'=>'BA55D3',
		        'mediumpurple'=>'9370D0',
		        'mediumseagreen'=>'3CB371',
		        'mediumslateblue'=>'7B68EE',
		        'mediumspringgreen'=>'00FA9A',
		        'mediumturquoise'=>'48D1CC',
		        'mediumvioletred'=>'C71585',
		        'midnightblue'=>'191970',
		        'mintcream'=>'F5FFFA',
		        'mistyrose'=>'FFE4E1',
		        'moccasin'=>'FFE4B5',
		        'navajowhite'=>'FFDEAD',
		        'navy'=>'000080',
		        'oldlace'=>'FDF5E6',
		        'olive'=>'808000',
		        'olivedrab'=>'6B8E23',
		        'orange'=>'FFA500',
		        'orangered'=>'FF4500',
		        'orchid'=>'DA70D6',
		        'palegoldenrod'=>'EEE8AA',
		        'palegreen'=>'98FB98',
		        'paleturquoise'=>'AFEEEE',
		        'palevioletred'=>'DB7093',
		        'papayawhip'=>'FFEFD5',
		        'peachpuff'=>'FFDAB9',
		        'peru'=>'CD853F',
		        'pink'=>'FFC0CB',
		        'plum'=>'DDA0DD',
		        'powderblue'=>'B0E0E6',
		        'purple'=>'800080',
		        'red'=>'FF0000',
		        'rosybrown'=>'BC8F8F',
		        'royalblue'=>'4169E1',
		        'saddlebrown'=>'8B4513',
		        'salmon'=>'FA8072',
		        'sandybrown'=>'F4A460',
		        'seagreen'=>'2E8B57',
		        'seashell'=>'FFF5EE',
		        'sienna'=>'A0522D',
		        'silver'=>'C0C0C0',
		        'skyblue'=>'87CEEB',
		        'slateblue'=>'6A5ACD',
		        'slategray'=>'708090',
		        'slategrey'=>'708090',
		        'snow'=>'FFFAFA',
		        'springgreen'=>'00FF7F',
		        'steelblue'=>'4682B4',
		        'tan'=>'D2B48C',
		        'teal'=>'008080',
		        'thistle'=>'D8BFD8',
		        'tomato'=>'FF6347',
		        'turquoise'=>'40E0D0',
		        'violet'=>'EE82EE',
		        'wheat'=>'F5DEB3',
		        'white'=>'FFFFFF',
		        'whitesmoke'=>'F5F5F5',
		        'yellow'=>'FFFF00',
		        'yellowgreen'=>'9ACD32'
		    );

			if (array_key_exists(strtolower($color), $colors)) {
	      		
	      		return array_map('hexdec', str_split($colors[$color], 2));
			}
		}

		return array( 0, 0, 0 );
	}


	/* ==========================================================================
	   Canvas functions
	   ========================================================================== */

	protected function createCanvas($width = 1, $height = 1, $bgcolor = '#fff', $scale = 1, $debug = 0)	{

		$width 		*= 	$scale;
		$height 	*=	$scale;

		$image 		= 	imagecreatetruecolor($width, $height);

		list($red, $green, $blue)	=	$this->getColor($bgcolor);
		$bgcolor	=	imagecolorallocate($image, $red, $green, $blue);

		imagefill($image, 0, 0, $bgcolor);

		if ( $debug )	{
			$red	= 	imagecolorallocate($image, 255, 0, 0);
			imageline($image, $width/2, 0, $width/2, $height, $red);
			imageline($image, 0, $height/2, $width, $height/2, $red);
			imageline($image, 0, 0, $width, 0, $red);
			imageline($image, 0, 0, 0, $height, $red);
			imageline($image, $width-1, 0, $width-1, $height, $red);
			imageline($image, 0, $height-1, $width, $height-1, $red);
		}

		return $image;	
	}

	protected function writeCanvas($image)	{
		
		switch ( $this->mimeType )	{

			case 'image/png':
				
				$pngQuality = ($this->quality) / 11.111111;
				$pngQuality = round(abs($pngQuality));

				header('Content-type: image/png');
				imagepng($image, NULL, $pngQuality);

				break;			

			case 'image/jpeg':
				
				if ( $this->quality < 0 ||  $this->quality > 100 )
				$jpgQuality 	=	abs( $this->quality );

				header('Content-Type: image/jpeg');
				imagejpeg($im, NULL, $jpgQuality);

				break;
			
			default:

				$pngQuality = ($this->quality) / 11.111111;
				$pngQuality = round(abs($pngQuality));

				header('Content-type: image/png');
				imagepng($image, NULL, 9);

				break;
		}

		imagedestroy($image);
	}

	protected function createText($text, $bgcolor = '#fff', $fgcolor = '#000', $scale = 1, $debug = 0)	{

		$fontExt	=	'.ttf';
		$fontName 	=	$this->font;
		$fontFile	=	$fontName . $fontExt;

		$fontFile 	= 	dirname( __FILE__ ) . $this->assets['fonts'] . $fontFile;

		$fontSize	=	12 * $scale;
		
		$type_space = 	imagettfbbox($fontSize, 0, $fontFile, $text);
		$width 		= 	abs($type_space[4] - $type_space[0]) + 10;
		$height 	= 	abs($type_space[5] - $type_space[1]) + 10;
		
		$image 		=	imagecreatetruecolor($width, $height);
		
		list($red, $green, $blue)	=	$this->getColor($bgcolor);
		$bgcolor	=	imagecolorallocate($image, $red, $green, $blue);

		imagefill($image, 0, 0, $bgcolor);

		if ( $debug )	{
			$red	= 	imagecolorallocate($image, 255, 0, 0);
			imageline($image, $width/2, 0, $width/2, $height, $red);
			imageline($image, 0, $height/2, $width, $height/2, $red);
			imageline($image, 0, 0, $width, 0, $red);
			imageline($image, 0, 0, 0, $height, $red);
			imageline($image, $width-1, 0, $width-1, $height, $red);
			imageline($image, 0, $height-1, $width, $height-1, $red);
		}

		list($red, $green, $blue)	=	$this->getColor($fgcolor);
		$fgcolor	=	imagecolorallocate($image, $red, $green, $blue);

		$x = 5 + $scale;
		$y = $height - 5 * $scale;
		imagettftext($image, $fontSize, 0, $x, $y, $fgcolor, $fontFile, $text);

		return $image;
	}

	protected function copyCanvas($dst, $src)	{

		$dwidth 	=	imagesx( $dst );
		$dheight 	=	imagesy( $dst );
		$swidth 	=	imagesx( $src );	
		$sheight 	=	imagesy( $src );

		$centerX =	$dwidth / 2 - $swidth / 2;
		$centerY = 	$dheight / 2 - $sheight / 2;
		imagecopy ( $dst, $src, $centerX, $centerY, 0, 0, $swidth, $sheight);

		return $dst;
	}

	protected function addWave($image, $Xperiod = 11, $Xamplitude = 5, $Yperiod = 12, $Yamplitude = 14) {

		$width 	=	imagesx( $image );
		$height =	imagesy( $image );

	    // X-axis wave generation
	    $xp = $Xperiod*rand(1,3);
	    $k = rand(0, 100);
	    for ($i = 0; $i < $width; $i++) {
	        imagecopy($image, $image,
	            $i-1, sin($k+$i/$xp) * $Xamplitude,
	            $i, 0, 1, $height);
	    }

	    // Y-axis wave generation
	    $k = rand(0, 100);
	    $yp = $Yperiod*rand(1,2);
	    for ($i = 0; $i < $height; $i++) {
	        imagecopy($image, $image,
	            sin($k+$i/$yp) * $Yamplitude, $i-1,
	            0, $i, $width, 1);
	    }

	    return $image;
	}

	protected function addNose($image, $color1, $color2, $percent = 25)	{

		$width 		=	imagesx( $image );
		$height 	=	imagesy( $image );

		$noise 		= 	ImageCreateTrueColor($width, $height);

		if (is_resource($noise)) {

			list($red, $green, $blue)	=	$this->getColor($color1);
			$color1	=	imagecolorallocate($noise, $red, $green, $blue);

			list($red, $green, $blue)	=	$this->getColor($color2);
			$color2	=	imagecolorallocate($noise, $red, $green, $blue);

		    for ($w = 0; $w < $width; $w++) {
		        for ($h = 0; $h < $height; $h++) {
		            if (mt_rand(1, 100) >= 50)
		                ImageSetPixel($noise, $w, $h, $color1);
		            else
		                ImageSetPixel($noise, $w, $h, $color2);
		        }
		    }
		}

		imagecopymerge ( $image, $noise, 0, 0, 0, 0, $width, $height, $percent);

		return $image;
	}

	protected function addRandomLines($image, $color1 = '#ffffff', $color2 = '#000000', $density = 5) {

		list($red, $green, $blue)	=	$this->getColor($color1);
		$color1	=	imagecolorallocate($image, $red, $green, $blue);

		list($red, $green, $blue)	=	$this->getColor($color2);
		$color2	=	imagecolorallocate($image, $red, $green, $blue);

		for ($i = 0; $i < $density; $i++) {
		    //imagefilledrectangle($image, $i + $i2, 5, $i + $i3, 70, $color2);
		    imagesetthickness($image, rand(1, 5));
		    imagearc(
		        $image,
		        rand(1, 300), // x-coordinate of the center.
		        rand(1, 300), // y-coordinate of the center.
		        rand(1, 300), // The arc width.
		        rand(1, 300), // The arc height.
		        rand(1, 300), // The arc start angle, in degrees.
		        rand(1, 300), // The arc end angle, in degrees.
		        (rand(0, 1) ? $color2 : $color1) // A color identifier.
		    );
		}

		return $image;
	}

	protected function addGaussianFilter($image)	{

		$gaussian = array(array(1.0, 2.0, 1.0), array(2.0, 4.0, 2.0), array(1.0, 2.0, 1.0));
		imageconvolution($image, $gaussian, 16, 0);

		return $image;
	}

	protected function addSmoothFilter($image, $quality = 8)	{

		if ( $quality < -6 || $quality > 8 )
			$quality  = 8;

		imagefilter($image, IMG_FILTER_SMOOTH, $quality);

		return $image;
	}

	/* ==========================================================================
	   Encryption/Decryption functions
	   ========================================================================== */

	protected function decrypt($data)	{

	    return $this->unpad( mcrypt_decrypt(
		        MCRYPT_RIJNDAEL_128,
		        $this->key,
		        base64_decode($data),
		        MCRYPT_MODE_CBC,
		        "\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0"
		    ));
	}

	protected function encrypt($data)	{

		$padded =	$this->pad( $data, 16 );

	    return base64_encode(
		    mcrypt_encrypt(
		        MCRYPT_RIJNDAEL_128,
		        $this->key,
		        $padded,
		        MCRYPT_MODE_CBC,
		        "\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0"
		    )
	    );
	}

	protected function pad($data, $block_size) {
        $padding = $block_size - (strlen($data) % $block_size);
        $pattern = chr($padding);
        return $data . str_repeat($pattern, $padding);
    }

    protected function unpad($data) {
        $padChar = substr($data, -1);
        $padLength = ord($padChar);
        return substr($data, 0, -$padLength);
    }
}