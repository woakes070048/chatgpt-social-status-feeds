<?php
/*
 * Project: ChatGPT API
 * Author: Vontainment
 * URL: https://vontainment.com
 * File: ../app/forms/gallery-forms.php
 * Description: ChatGPT API Status Generator
 */

 if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['upload_image'])) {
        $accountName = $_POST['account_name'];
        $accountOwner = $_SESSION['username'];
        $imageFolder = IMAGES_DIR . "/{$accountOwner}/{$accountName}/";

        if (!file_exists($imageFolder)) {
            mkdir($imageFolder, 0755, true);
        }

        $imageErrors = $_FILES['image-file']['error'];
        $imageFileNames = $_FILES['image-file']['name'];
        $imageTmpNames = $_FILES['image-file']['tmp_name'];

        foreach ($imageErrors as $key => $imageError) {
            if ($imageError === UPLOAD_ERR_OK) {
                $imageTmpName = $imageTmpNames[$key];
                $extension = 'jpg'; // Set the extension to jpg since all images will be converted to jpg
                $newFileName = generateUniqueFileName($extension);
                $imagePath = $imageFolder . $newFileName;

                if (move_uploaded_file($imageTmpName, $imagePath)) {
                    // Optimize and resize the uploaded image
                    optimizeAndResizeImage($imagePath);
                    // File uploaded and optimized successfully
                    header("Location: /gallery/" . urlencode($accountName));
                    exit;
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
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
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
