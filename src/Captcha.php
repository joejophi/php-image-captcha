<?php

namespace Joejophi\PhpImageCaptcha;

class Captcha
{
    /**
     * Generate Captcha Image
     *
     * @return image file
     */
    public function generate()
    {
        if (!function_exists('gd_info')) {
            throw new Exception('Required GD library is missing');
        }

        $dir = dirname(__FILE__);
        $fonts = [
            [
                "file" => $dir . "/fonts/a.ttf",
                "size" => 30,
            ],
            [
                "file" => $dir . "/fonts/b.otf",
                "size" => 30,
            ],
            [
                "file" => $dir . "/fonts/c.ttf",
                "size" => 30,
            ],
        ];

        $config = [
            "code" => "",
            "min_length" => 6,
            "max_length" => 6,
            "width" => 220,
            "height" => 100,
            "characters" => 'ABCDEFGHJKLMNPRSTUVWXYZ23456789',
        ];

        // Generate CAPTCHA code if not set by user
        if (empty($config["code"])) {
            $length = mt_rand($config['min_length'], $config['max_length']);
            while (strlen($config['code']) < $length) {
                $index = rand(0, strlen($config["characters"]) - 1);
                $config['code'] .= $config["characters"][$index];
            }
        }

        session_start();
        $_SESSION["generateCaptcha"] = $config["code"];
        $temp = str_split($config["code"]);
        $config["code"] = implode(" ", $temp);

        $captcha = imagecreate($config["width"], $config["height"]);

        $bg = imagecolorallocate($captcha, 255, 255, 255);
        $textcolor = imagecolorallocate($captcha, 0, 0, 0);

        imagefill($captcha, 0, 0, $bg);

        // $textBox = imagettfbbox($fonts[0]["size"], 0, $fonts[0]["file"], $config['code']);

        // $textWidth = abs(max($textBox[2], $textBox[4]));
        // $textHeight = abs(max($textBox[5], $textBox[7]));
        // $x = (imagesx($captcha) - $textWidth) / 2;
        // $y = (imagesy($captcha) + $textHeight) / 2;

        $x = (int) (($config["width"] - (15 * strlen($config["code"]))) / 2);
        $y = (int) (($config["height"] / 2));

        $start_x = $x;
        $temp = explode(" ", $config["code"]);
        foreach ($temp as $t) {
            $angle = [];
            $angle[] = rand(0, 10);
            $angle[] = rand(350, 360);

            $f = $fonts[array_rand($fonts)];
            $start_y = rand($y - 12, $y + 18);
            imagettftext($captcha, $f["size"], $angle[array_rand($angle)], $start_x, $start_y, $textcolor, $f["file"], $t);
            $start_x += 30;
        }

        $linecolor = [
            imagecolorallocate($captcha, 240, 240, 240),
            imagecolorallocate($captcha, 230, 230, 230),
            imagecolorallocate($captcha, 220, 220, 220),
            imagecolorallocate($captcha, 210, 210, 210),
            imagecolorallocate($captcha, 200, 200, 200),
        ];
        for ($i = 0; $i <= $config["width"]; $i += rand(2, 7)) {
            imageline($captcha, $i, 0, $i, rand($config["width"] / 2, $config["width"]), $linecolor[array_rand($linecolor)]);
        }
        for ($i = 0; $i <= $config["height"]; $i += rand(2, 7)) {
            imageline($captcha, 0, $i, $config["width"], $i, $linecolor[array_rand($linecolor)]);
        }

        header("Content-type: image/png");
        imagepng($captcha);
        die();
    }

    /**
     * Verify Captcha
     *
     * Returns true if captcha matches, else returns false
     *
     * @param string $captcha
     * @return boolean
     */
    public function verify($captcha)
    {
        session_start();

        if (array_key_exists("generateCaptcha", $_SESSION) && !empty($_SESSION["generateCaptcha"])) {
            if ($_SESSION["generateCaptcha"] == $captcha) {
                return true;
            }
        }

        return false;
    }
}
