<?php
 /*
  * Project: ChatGPT API
  * Author: Vontainment
  * URL: https://vontainment.com
  * File: ../app/forms/gallery-forms.php
  * Description: ChatGPT API Status Generator
  */

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
     if (isset($_FILES['image_file'])) {
         $accountName = $_POST['account_name'];
         $accountOwner = $_SESSION['username'];
         $imageFolder = IMAGES_DIR . "/{$accountOwner}/{$accountName}/";

         // Log that the statement is triggered
         error_log("Triggered: if (isset(_FILES['image_file']))" . PHP_EOL, 3, LOG_DIR . "/imgs.log");

         $allowed_extensions = ['jpg', 'jpeg', 'png', 'webp'];
         $total_files = count($_FILES['image_file']['name']);

         for ($i = 0; $i < $total_files; $i++) {
             $imageFileName = $_FILES['image_file']['name'][$i];
             $imageTmpName = $_FILES['image_file']['tmp_name'][$i];
             $imageSize = $_FILES['image_file']['size'][$i];
             $imageError = $_FILES['image_file']['error'][$i];
             $imageExtension = strtolower(pathinfo($imageFileName, PATHINFO_EXTENSION));

             if ($imageError === UPLOAD_ERR_OK && in_array($imageExtension, $allowed_extensions)) {
                 $imagePath = $imageFolder . $imageFileName; // Temporarily use original filename

                 if (move_uploaded_file($imageTmpName, $imagePath)) {
                     // Optimize and resize the uploaded image
                     optimizeAndResizeImage($imagePath);

                     // Generate a new unique filename
                     $newFileName = generateUniqueFileName('jpg'); // JPG extension is used as the image was converted to JPG during optimization
                     $newImagePath = $imageFolder . $newFileName;

                     // Rename the optimized image with the new unique filename
                     rename($imagePath, $newImagePath);

                     // Logging
                     error_log("Image uploaded, optimized and renamed: " . $newImagePath . PHP_EOL, 3, LOG_DIR . "/imgs.log");

                     // File uploaded and optimized successfully
                     header("Location: /gallery/" . urlencode($accountName));
                     exit;
                 } else {
                     // Log if the file was not moved successfully
                     error_log("File not moved: " . $imageTmpName . PHP_EOL, 3, LOG_DIR . "/imgs.log");
                 }
             }
         }
     } elseif (isset($_POST['delete_image'])) {
        $accountName = $_POST['account_name'];
        $accountOwner = $_SESSION['username'];
        $imageName = $_POST['image_name'];
        $imagePath = IMAGES_DIR . "/{$accountOwner}/{$accountName}/{$imageName}";

        if (!file_exists($imagePath)) {
            return;
        }

        unlink($imagePath);
        // File deleted successfully

        // Logging
        error_log("Image deleted: " . $imagePath . PHP_EOL, 3, LOG_DIR . "/imgs.log");
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['acct']) && isset($_GET['image'])) {
        $accountOwner = $_SESSION['username'];
        $accountName = $_GET['acct'];
        $imageName = $_GET['image'];

        $imagePath = IMAGES_DIR . "/{$accountOwner}/{$accountName}/{$imageName}";

        if (file_exists($imagePath)) {
            header('Content-Type: image/jpeg');
            readfile($imagePath);
            exit;
        } else {
            // Image not found, you can show a default image or return an error message
            $imagePath = 'assets/images/default.jpg';
        }
    }
}