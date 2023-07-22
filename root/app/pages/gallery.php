<?php
/*
 * Project: ChatGPT API
 * Author: Vontainment
 * URL: https://vontainment.com
 * File: ../app/pages/gallery.php
 * Description: ChatGPT API Image Editor
 */
?>

<?php
$accountOwner = $_SESSION['username'];
$accountDirs = glob(ACCOUNTS_DIR . "/{$accountOwner}/*", GLOB_ONLYDIR);
$accountDirs = array_filter($accountDirs, 'is_dir');  // Only keep directories
$selectedAccountName = isset($_GET['acct']) ? $_GET['acct'] : '';

if (!$selectedAccountName && count($accountDirs) > 0) {
    $selectedAccountName = basename($accountDirs[0]);
    header("Location: /gallery/$selectedAccountName");
    exit();
}

?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.7.0/min/dropzone.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.7.0/min/dropzone.min.css" rel="stylesheet" />
<div class="edit-images-box">
    <div class="edit-images-form">
        <h3>Select Account</h3>
        <form action="/gallery/<?php echo urlencode($selectedAccountName); ?>" method="POST" id="account_selection_form">
            <input type="hidden" name="page" value="gallery">
            <select name="account_name" id="account_name" required>
                <?php
                foreach ($accountDirs as $accountDir) {
                    if (is_dir($accountDir)) {
                        $accountName = basename($accountDir);
                        $selected = ($selectedAccountName === $accountName) ? 'selected' : '';
                        echo "<option value=\"$accountName\" $selected>$accountName</option>";
                    }
                }
                ?>
            </select>
        </form>

        <h3>Upload Images To Account</h3>
        <form action="/gallery/<?php echo urlencode($selectedAccountName); ?>" method="POST" class="dropzone" id="upload_images_form" enctype="multipart/form-data">
            <input type="hidden" name="page" value="gallery">
            <input type="hidden" name="account_name" value="<?php echo htmlspecialchars($selectedAccountName); ?>">
            <div class="fallback">
                <input name="image-file[]" type="file" multiple />
            </div>
        </form>

        <button class="reload-btn" onclick="window.location.reload();">Reload Page</button>
    </div>

    <div class="edit-images-list">
        <div class="images-list">
            <?php
            $accountName = $selectedAccountName;
            $acctInfo = getAcctInfo($accountOwner, $accountName);
            $statusInfo = getStatusInfo($accountOwner, $accountName);
            $image_folder = IMAGES_DIR . "/{$accountOwner}/{$selectedAccountName}/";
            $images = glob($image_folder . "*.jpg", GLOB_BRACE);
            $count = count($images);
            $imagesPerPage = 9;
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
                    <img src="/gallery/<?php echo urlencode($selectedAccountName); ?>/<?php echo urlencode($image_name); ?>" alt="<?php echo htmlspecialchars($image_name); ?>">
                        <form class="delete-image" action="/gallery/<?php echo urlencode($selectedAccountName); ?>" method="POST">
                            <input type="hidden" name="page" value="gallery">
                            <input type="hidden" name="account_name" value="<?php echo htmlspecialchars($accountName); ?>">
                            <input type="hidden" name="image_name" value="<?php echo htmlspecialchars($image_name); ?>">
                            <button class="red-button" type="submit" name="delete_image">Delete Image</button>
                        </form>
                    </div>
            <?php
                    if (($i + 1) % 3 == 0 && $i != $endIndex - 1) {
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
                } else if (this.getQueuedFiles().length === 0 && this.getUploadingFiles().length === 0) {
                    // All images have finished uploading
                    window.location.href = "/gallery/<?php echo urlencode($selectedAccountName); ?>";
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
