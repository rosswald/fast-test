<?php

namespace Services;

use Core\App;

class ReportService
{
    public function __construct(
        private App $app
    ) {}

    public function createChart(array $data): \GdImage
    {
        $width = 800;
        $height = 600;
        $image = imagecreatetruecolor($width, $height);

        $white = imagecolorallocate($image, 255, 255, 255);
        $black = imagecolorallocate($image, 0, 0, 0);
        $grey = imagecolorallocate($image, 128, 128, 128);
        $lightgrey = imagecolorallocate($image, 200, 200, 200);
        $chartColors = [
            imagecolorallocate($image, 200, 0, 0),
            imagecolorallocate($image, 0, 200, 0),
            imagecolorallocate($image, 0, 0, 200),
            imagecolorallocate($image, 200, 200, 0),
            imagecolorallocate($image, 0, 200, 200),
        ];

        imagefill($image, 0, 0, $white);

        // frame
        imageline($image, 0, 0, 0, $height-1, $black);
        imageline($image, 0, 0, $width-1, 0, $black);
        imageline($image, $width-1, 0, $width-1, $height-1, $black);
        imageline($image, 0, $height-1, $width-1, $height-1, $black);

        // axis
        imageline($image, 10, 10, 10, $height-12, $grey);
        imageline($image, 10, $height-12, $width-12, $height-12, $grey);


        $numDates = count($data);
        if ($numDates > 1) {
            $xOffset = ($width -20 - 2) / ($numDates - 1);
        } else {
            $xOffset = 0;
        }

        $max = 0;
        foreach ($data as $row) {
            foreach ($row as $key => $value) {
                if ($key === 'date') {
                    continue;
                }

                $max = max($max, (int)$value);
            }
        }

        $yOffset = round(($height - 20 - 2) / ($max + 1), 0, PHP_ROUND_HALF_DOWN);

        for ($i = 0, $x = 10; $i < $numDates - 1; $i++, $x += $xOffset) {
            imageline($image, $x, 10, $x, $height-12, $lightgrey); // vertical lines for dates
            imageline($image, $x, $height-12 - $data[$i]['page_views_a'] * $yOffset, $x + $xOffset, $height-12 - $data[$i + 1]['page_views_a'] * $yOffset, $chartColors[0]);
            imageline($image, $x, $height-12 - $data[$i]['page_views_b'] * $yOffset, $x + $xOffset, $height-12 - $data[$i + 1]['page_views_b'] * $yOffset, $chartColors[1]);
            imageline($image, $x, $height-12 - $data[$i]['button_clicks_buy'] * $yOffset, $x + $xOffset, $height-12 - $data[$i + 1]['button_clicks_buy'] * $yOffset, $chartColors[2]);
            imageline($image, $x, $height-12 - $data[$i]['button_clicks_download'] * $yOffset, $x + $xOffset, $height-12 - $data[$i + 1]['button_clicks_download'] * $yOffset, $chartColors[3]);
        }

        return $image;
    }
}