<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DesignController;

Route::get('/', [DesignController::class, 'index'])->name('designer.index');
Route::post('/design/save', [DesignController::class, 'save'])->name('design.save');
Route::get('/design/load/{id}', [DesignController::class, 'load'])->name('design.load');
Route::get('/design/list', [DesignController::class, 'list'])->name('design.list');


Route::get('/placeholder/{width}/{height}/{text?}', function ($width, $height, $text = null) {
    $image = imagecreatetruecolor($width, $height);
    $bgColor = imagecolorallocate($image, 240, 240, 240);
    $textColor = imagecolorallocate($image, 150, 150, 150);

    imagefill($image, 0, 0, $bgColor);

    if ($text) {
        $font = 5; // Built-in font
        $textWidth = imagefontwidth($font) * strlen($text);
        $textHeight = imagefontheight($font);
        $x = ($width - $textWidth) / 2;
        $y = ($height - $textHeight) / 2;
        imagestring($image, $font, $x, $y, $text, $textColor);
    }

    header('Content-Type: image/png');
    imagepng($image);
    imagedestroy($image);
})->where(['width' => '[0-9]+', 'height' => '[0-9]+']);



Route::get('/placeholder2/{width}x{height}/{text?}', function ($width, $height) {
    // Validate dimensions
    $width = min(max((int) $width, 10), 5000);
    $height = min(max((int) $height, 10), 5000);

    // Create image
    $image = imagecreatetruecolor($width, $height);

    // Colors
    $bgColor = imagecolorallocate($image, 238, 238, 238); // Light gray
    $textColor = imagecolorallocate($image, 170, 170, 170); // Dark gray
    $borderColor = imagecolorallocate($image, 204, 204, 204); // Medium gray

    // Fill background and add border
    imagefill($image, 0, 0, $bgColor);
    imagerectangle($image, 0, 0, $width - 1, $height - 1, $borderColor);

    // Add text
    $text = "{$width}×{$height}";
    $font = 5; // Built-in GD font
    $textWidth = imagefontwidth($font) * strlen($text);
    $textHeight = imagefontheight($font);
    $x = ($width - $textWidth) / 2;
    $y = ($height - $textHeight) / 2;
    imagestring($image, $font, $x, $y, $text, $textColor);

    // Output image
    header('Content-Type: image/png');
    imagepng($image);
    imagedestroy($image);
    exit;
})->where(['width' => '[0-9]+', 'height' => '[0-9]+']);