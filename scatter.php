<?php
$csvFile = 'data.csv';
if (!file_exists($csvFile)) {
    die("CSV file not found.");
}

$data = [];
if (($handle = fopen($csvFile, 'r')) !== FALSE) {
    while (($row = fgetcsv($handle, 1000, ',')) !== FALSE) {
        $data[] = $row;
    }
    fclose($handle);
}

// Extract headers and data points
$headers = array_shift($data);
$xValues = array_column($data, 0);
$yValues = array_column($data, 1);

// Image dimensions
$width = 800;
$height = 800;
$padding = 50;

// Creating image from define dimentions
$image = imagecreatetruecolor($width, $height);

// colors for each element
$backgroundColor = imagecolorallocate($image, 255, 255, 255); // White
$axisColor = imagecolorallocate($image, 0, 0, 0); // Black
$pointColor = imagecolorallocate($image, 255, 0, 0); // Red

// Fill the background
imagefilledrectangle($image, 0, 0, $width, $height, $backgroundColor);

// Calculate scaling factors
$xMin = min($xValues);
$xMax = max($xValues);
$yMin = min($yValues);
$yMax = max($yValues);

$xScale = ($width - 2 * $padding) / ($xMax - $xMin);
$yScale = ($height - 2 * $padding) / ($yMax - $yMin);

// Draw axes
imageline($image, $padding, $height - $padding, $width - $padding, $height - $padding, $axisColor); // X-axis
imageline($image, $padding, $height - $padding, $padding, $padding, $axisColor); // Y-axis

// Draw data points
foreach ($xValues as $index => $x) {
    $y = $yValues[$index];
    $xPixel = $padding + ($x - $xMin) * $xScale;
    $yPixel = $height - $padding - ($y - $yMin) * $yScale;
    imagefilledellipse($image, $xPixel, $yPixel, 8, 8, $pointColor);
}

// Output image
header('Content-Type: image/png');
imagepng($image);
imagedestroy($image);
?>
