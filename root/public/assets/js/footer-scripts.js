// Function to copy text to clipboard
function copyToClipboard(statusText) {
    var tempInput = document.createElement('textarea');
    tempInput.value = statusText;
    document.body.appendChild(tempInput);
    tempInput.select();
    document.execCommand('copy');
    document.body.removeChild(tempInput);
}

document.addEventListener('DOMContentLoaded', function () {

    // Check if the current URL path contains '/home'
    if (window.location.pathname.includes('/home')) {
        // Get the Facebook button elements
        const facebookButtons = document.querySelectorAll('.share-buttons a[data-social="facebook"]');
        // Check if the elements exist
        if (facebookButtons) {
            // Loop through each Facebook button to add 'onclick' attribute for clipboard copy
            facebookButtons.forEach((button) => {
                const statusText = button.dataset.status;
                button.setAttribute('onclick', 'copyToClipboard("' + statusText + '")');
            });
        }

        // Get the linkedin button elements
        const linkedinButtons = document.querySelectorAll('.share-buttons a[data-social="linkedin"]');
        // Check if the elements exist
        if (linkedinButtons) {
            // Loop through each linkedin button to add 'onclick' attribute for clipboard copy
            linkedinButtons.forEach((button) => {
                const statusText = button.dataset.status;
                button.setAttribute('onclick', 'copyToClipboard("' + statusText + '")');
            });
        }
    }

    $("#copyButton").click(function(){
        /* Get the text field */
        var copyText = document.getElementById("quickresponse");

        /* Select the text field */
        copyText.select();
        copyText.setSelectionRange(0, 99999); /* For mobile devices */

        /* Copy the text inside the text field */
        document.execCommand("copy");

        /* Alert the copied text */
        alert("Copied the text: " + copyText.value);
    });
});
