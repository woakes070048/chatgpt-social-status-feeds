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

        $imageError = $_FILES['image-file']['error'];

        if ($imageError === UPLOAD_ERR_OK) {
            $imageTmpName = $_FILES['image-file']['tmp_name'];
            $extension = pathinfo($_FILES['image-file']['name'], PATHINFO_EXTENSION);
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
?>

<?php
/*
 * Project: ChatGPT API
 * Author: Vontainment
 * URL: https://vontainment.com
 * File: /pages/gallery.php
 * Description: ChatGPT API Image Editor
 */
?>

<?php
$accountOwner = $_SESSION['username'];
$accountDirs = glob("../storage/accounts/{$accountOwner}/*");
$selectedAccountName = isset($_GET['acct']) ? $_GET['acct'] : '';
if (!$selectedAccountName && count($accountDirs) > 0) {
    $selectedAccountName = basename($accountDirs[0]);
    header("Location: /gallery/$selectedAccountName");
    exit();
}
?>

<div class="edit-images-box">
    <div class="edit-images-form">
        <h3>Select Account</h3>
        <form action="/gallery/<?php echo urlencode($selectedAccountName); ?>" method="POST" id="account_selection_form">
            <input type="hidden" name="page" value="gallery">
            <select name="account_name" id="account_name" required>
                <?php
                foreach ($accountDirs as $accountDir) {
                    $accountName = basename($accountDir);
                    $selected = ($selectedAccountName === $accountName) ? 'selected' : '';
                    echo "<option value=\"$accountName\" $selected>$accountName</option>";
                }
                ?>
            </select>
        </form>

        <h3>Upload Images To Account</h3>
        <form action="/gallery/<?php echo urlencode($selectedAccountName); ?>" method="POST" class="dropzone" id="upload_images_form" enctype="multipart/form-data">
            <input type="hidden" name="page" value="gallery">
            <input type="hidden" name="account_name" value="<?php echo htmlspecialchars($selectedAccountName); ?>">
            <div class="fallback">
                <input name="image-file" type="file" multiple />
            </div>
        </form>



        <button class="reload-btn" onclick="window.location.reload();">Reload Page</button>
    </div>

    <div class="edit-images-list">
        <h3>Images</h3>
        <div class="images-list">
            <?php
            $accountName = $selectedAccountName;
            $image_folder = "images/{$accountName}/";
            $images = glob($image_folder . "*.{jpg,jpeg,png}", GLOB_BRACE);
            $count = count($images);
            $imagesPerPage = 6;
            $totalPages = ceil($count / $imagesPerPage);
            $currentPage = isset($_GET['p']) ? max(1, min((int)$_GET['p'], $totalPages)) : 1;
            $startIndex = ($currentPage - 1) * $imagesPerPage;
            $endIndex = min($startIndex + $imagesPerPage, $count);

            if ($count === 0) {
                echo '<p class="no-images">Sorry, you have no images! Please upload some.</p>';
            } else {
                for ($i = $startIndex; $i < $endIndex; $i++) {
                    $image_name = basename($images[$i]);
            ?>
                    <div class="image-item">
                        <div class="image-placeholder" style="background-color: #66cc33;"></div>
                        <img src="/<?php echo htmlspecialchars($images[$i]); ?>" alt="<?php echo htmlspecialchars($image_name); ?>">
                        <form class="delete-form" action="/gallery/<?php echo urlencode($selectedAccountName); ?>" method="POST">
                            <input type="hidden" name="page" value="gallery">
                            <input type="hidden" name="account_name" value="<?php echo htmlspecialchars($accountName); ?>">
                            <input type="hidden" name="image_name" value="<?php echo htmlspecialchars($image_name); ?>">
                            <button class="red-button" type="submit" name="delete-image">Delete Image</button>
                        </form>
                    </div>
            <?php
                    if (($i + 1) % 3 == 0) {
                        echo '<div style="clear:both;"></div>';
                    }
                }
            }
            ?>
        </div>
        <?php if ($totalPages > 1) : ?>
            <div class="pagination">
                <?php if ($currentPage > 1) : ?>
                    <a href="/gallery/<?php echo urlencode($selectedAccountName); ?>/<?php echo $currentPage - 1; ?>">Previous</a>
                <?php endif; ?>
                <?php for ($page = 1; $page <= $totalPages; $page++) : ?>
                    <a href="/gallery/<?php echo urlencode($selectedAccountName); ?>/<?php echo $page; ?>" <?php echo ($page == $currentPage) ? 'class="active"' : ''; ?>><?php echo $page; ?></a>
                <?php endfor; ?>
                <?php if ($currentPage < $totalPages) : ?>
                    <a href="/gallery/<?php echo urlencode($selectedAccountName); ?>/<?php echo $currentPage + 1; ?>">Next</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
<script>
    Dropzone.autoDiscover = false;

    var myDropzone = new Dropzone("#upload_images_form", {
        paramName: "image-file[]", // This must match the name attribute of your input tag
        maxFilesize: 10, // Size in MB
        acceptedFiles: "image/*",
        autoProcessQueue: false, // Disable auto processing of files
        parallelUploads: 6, // Set the number of parallel uploads to 6
        init: function() {
            this.on("sending", function(file, xhr, formData) {
                formData.append("upload_image", "true"); // Include the upload_image parameter
                formData.append("account_name", document.querySelector('input[name="account_name"]').value);
            });
            this.on("success", function(file, response) {
                // File uploaded successfully
                console.log(response); // You can handle the response from the server here
                if (this.getQueuedFiles().length > 0 && this.getUploadingFiles().length < 6) {
                    // If there are more queued files and fewer than 6 uploading files, start the upload for the next file
                    this.processQueue();
                }
            });
            this.on("error", function(file, errorMessage) {
                // File upload error
                console.log(errorMessage);
                if (this.getQueuedFiles().length > 0 && this.getUploadingFiles().length < 6) {
                    // If there are more queued files and fewer than 6 uploading files, start the upload for the next file
                    this.processQueue();
                }
            });
            this.on("addedfiles", function() {
                if (this.getUploadingFiles().length < 6) {
                    // If there are fewer than 6 uploading files, start the upload for the newly added file
                    this.processQueue();
                }
            });
        }
    });

    var accountSelect = document.getElementById('account_name');
    accountSelect.addEventListener('change', function() {
        destroyDropzone(); // Destroy existing Dropzone instance before changing the account
        var selectedAccountName = this.value;
        var form = document.getElementById('account_selection_form');
        form.action = "/gallery/" + encodeURIComponent(selectedAccountName);
        form.submit();
    });

    function destroyDropzone() {
        if (myDropzone !== null) {
            myDropzone.destroy(); // Destroy the Dropzone instance
            myDropzone = null; // Reset the instance variable
        }
    }
</script>
