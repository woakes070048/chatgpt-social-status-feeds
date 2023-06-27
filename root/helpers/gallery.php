<?php
/*
 * Project: ChatGPT API
 * Author: Vontainment
 * URL: https://vontainment.com
 * File: /helpers/gallery.php
 * Description: ChatGPT API Status Generator
 */

 // Require the Intervention Image library
 require '../vendor/autoload.php';

 use Intervention\Image\ImageManagerStatic as Image;

 function optimizeAndResizeImage($imagePath)
 {
     // Load the image using Intervention Image
     $image = Image::make($imagePath);

     // Convert the image to JPEG format
     $image->encode('jpg');

     // Get the current dimensions of the image
     $currentWidth = $image->width();
     $currentHeight = $image->height();

     // Set the maximum width for resizing
     $maxWidth = 720;

     // Calculate the new dimensions while maintaining aspect ratio
     if ($currentWidth > $maxWidth) {
         $newWidth = $maxWidth;
         $newHeight = intval($currentHeight * ($newWidth / $currentWidth));

         // Resize the image
         $image->resize($newWidth, $newHeight, function ($constraint) {
             $constraint->aspectRatio();
             $constraint->upsize();
         });

         // Save the optimized image with the .jpg extension
         $image->save($imagePath, 80); // Adjust the image quality (0-100) as needed
     }
 }

 function generateUniqueFileName($extension) {
    $uniqueId = uniqid();
    $newFileName = $uniqueId . '.' . $extension;
    return $newFileName;
}