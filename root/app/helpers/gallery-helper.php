<?php
/*
 * Project: ChatGPT API
 * Author: Vontainment
 * URL: https://vontainment.com
 * File: ../app/helpers/gallery-helper.php
 * Description: ChatGPT API Status Generator
 */

// Require the Intervention Image library
require '../vendor/autoload.php';

use Intervention\Image\ImageManagerStatic as Image;

function generateUniqueFileName($extension)
{
    $uniqueId = uniqid();
    $newFileName = $uniqueId . '.' . $extension;

    // Logging
    error_log("Generated a unique file name: " . $newFileName . PHP_EOL, 3, LOG_DIR . "/imgs.log");

    return $newFileName;
}

function optimizeAndResizeImage($imagePath)
{
    // Load the image using Intervention Image
    $image = Image::make($imagePath);

    // Convert the image to JPEG format
    $image->encode('jpg');

    // Get the current dimensions of the image
    $currentWidth = $image->width();
    $currentHeight = $image->height();

    // Logging
    error_log("Current image dimensions (W x H): " . $currentWidth . " x " . $currentHeight . PHP_EOL, 3, LOG_DIR . "/imgs.log");

    // Set the maximum width for resizing
    $maxWidth = MAX_WIDTH;

    // Calculate the new dimensions while maintaining aspect ratio
    if ($currentWidth > $maxWidth) {
        $newWidth = $maxWidth;
        $newHeight = intval($currentHeight * ($newWidth / $currentWidth));

        // Logging
        error_log("New image dimensions (W x H): " . $newWidth . " x " . $newHeight . PHP_EOL, 3, LOG_DIR . "/imgs.log");

        // Resize the image
        $image->resize($newWidth, $newHeight, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });

        // Save the optimized image with the .jpg extension
        $image->save($imagePath, 80); // Adjust the image quality (0-100) as needed

        // Change the extension of the imagePath to .jpg
        $imagePath = preg_replace('/\\.[^.\\s]{3,4}$/', '.jpg', $imagePath);

        // Logging
        error_log("Image saved successfully at: " . $imagePath . PHP_EOL, 3, LOG_DIR . "/imgs.log");
    }
}