<?php
session_start();

$type = isset($_GET['type']) && $_GET['type'] === 'reg' ? 'reg_captcha_ans' : 'captcha_ans';
$code = (string)rand(1000, 9999);
$_SESSION[$type] = $code;

$image = imagecreatetruecolor(120, 40);
$bg = imagecolorallocate($image, 245, 245, 245);
$text_color = imagecolorallocate($image, 15, 118, 110); // Tailwind teal-700
$line_color = imagecolorallocate($image, 200, 200, 200);

imagefilledrectangle($image, 0, 0, 120, 40, $bg);

for ($i = 0; $i < 6; $i++) {
    imageline($image, 0, rand(0, 40), 120, rand(0, 40), $line_color);
}

$x = 20;
for ($i = 0; $i < 4; $i++) {
    $y = rand(8, 15);
    // Use a larger built-in font
    imagestring($image, 5, $x, $y, $code[$i], $text_color);
    $x += 20;
}

header('Content-type: image/png');
imagepng($image);
imagedestroy($image);
?>
