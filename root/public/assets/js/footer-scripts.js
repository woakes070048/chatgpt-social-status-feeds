document.addEventListener("DOMContentLoaded", function () {
  // Handle copy to clipboard
  const clipboardButtons = document.querySelectorAll(
    '.share-buttons button[data-action="copy"]'
  );
  clipboardButtons.forEach((button) => {
    button.addEventListener("click", function () {
      const text = this.getAttribute("data-status-text");
      navigator.clipboard
        .writeText(text)
        .then(() => {
          alert("Text copied to clipboard!");
        })
        .catch((err) => {
          console.error("Failed to copy text: ", err);
        });
    });
  });

  // Handle image download
  const downloadButtons = document.querySelectorAll(
    '.share-buttons button[data-action="download"]'
  );
  downloadButtons.forEach((button) => {
    button.addEventListener("click", function () {
      const imagePath = this.getAttribute("data-image-path");
      const filename = imagePath.substring(imagePath.lastIndexOf("/") + 1); // Extract filename from path
      const link = document.createElement("a");
      link.href = imagePath;
      link.download = filename; // Ensure the 'download' attribute is set with the filename
      document.body.appendChild(link);
      link.click();
      document.body.removeChild(link);
    });
  });
});
