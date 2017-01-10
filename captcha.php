<?php

include_once 'bot/killer.php';

$minion	=	new Killer();

$minion->key 	=	'd41d8cd98f00b204e9800998ecf8427e';

// $color 	=	array('fff', '000', 'darkgray', 'greenyellow', 'midnightblue');
// $font 	=	array('TimesNewRomanBold', 'AntykwaBold', 'Candice', 'Ding-DongDaddyO', 'Heineken');

// $minion->bgColor 		=	$color[mt_rand(0, 4)];
// $minion->fgColor 		=	$color[mt_rand(0, 4)];
// $minion->font 			=	$font[mt_rand(0, 4)];
// $minion->noiseColor1		=	$color[mt_rand(0, 4)];
// $minion->noiseColor2		=	$color[mt_rand(0, 4)];
// $minion->noiseDensity 	=	mt_rand(5, 15);
// $minion->lineColor1 		=	$color[mt_rand(0, 4)];
// $minion->lineColor2 		=	$color[mt_rand(0, 4)];
// $minion->lineDensity 	=	mt_rand(5, 15);

$minion->getCaptcha();