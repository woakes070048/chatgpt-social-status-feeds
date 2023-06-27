<?php
/*
 * Project: ChatGPT API
 * Author: Vontainment
 * URL: https://vontainment.com
 * File: /form/gallery.php
 * Description: ChatGPT API Status Generator
 */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['upload_image'])) {
        $accountName = $_POST['account_name'];
        $imageFolder = "images/" . ($accountName) . "/";
        if (!file_exists($imageFolder)) {
            mkdir($imageFolder, 0755, true);
        }

        $imageErrors = $_FILES['image-file']['error']; // Updated line

        foreach ($imageErrors as $key => $imageError) { // Updated line
            if ($imageError === UPLOAD_ERR_OK) {
                $imageTmpName = $_FILES['image-file']['tmp_name'][$key]; // Updated line
                $extension = pathinfo($_FILES['image-file']['name'][$key], PATHINFO_EXTENSION); // Updated line
                $newFileName = generateUniqueFileName($extension);
                $imagePath = $imageFolder . $newFileName;

                if (move_uploaded_file($imageTmpName, $imagePath)) {
                    // Optimize and resize the uploaded image
                    optimizeAndResizeImage($imagePath);

                    // File uploaded and optimized successfully
                    header("Location: /gallery/" . urlencode($accountName));
                    exit;
                } else {
                    // Handle the file move error
                    echo "Error moving uploaded file.";
                }
            } else {
                // Handle the upload error
                echo "Error uploading file.";
            }
        }
    } elseif (isset($_POST['delete-image'])) {
        $accountName = $_POST['account_name'];
        $imageName = $_POST['image_name'];
        $imagePath = "images/{$accountName}/{$imageName}";

        if (file_exists($imagePath)) {
            unlink($imagePath);
            // File deleted successfully

            $imgFile = "images/{$accountName}/img";
            if (file_exists($imgFile)) {
                $imageLines = file($imgFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                $index = array_search($imagePath, $imageLines);
                if ($index !== false) {
                    unset($imageLines[$index]);
                    file_put_contents($imgFile, implode(PHP_EOL, $imageLines));
                }
            }
        }
    }
}
