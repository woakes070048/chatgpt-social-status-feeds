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
    // Always execute the following code, regardless of the page
    document.querySelector('.supportButton').addEventListener('click', function () {
        document.querySelector('.support-popup').style.display = 'block';
        document.querySelector('.support-overlay').style.display = 'block';
        document.querySelector('.support-iframe').src = "https://crm.vontainment.com/forms/ticket";
    });

    document.querySelector('.myacctButton').addEventListener('click', function () {
        document.querySelector('.myacct-popup').style.display = 'block';
        document.querySelector('.myacct-overlay').style.display = 'block';
    });

    document.querySelector('.support-closeButton').addEventListener('click', function () {
        document.querySelector('.support-popup').style.display = 'none';
        document.querySelector('.support-overlay').style.display = 'none';
        document.querySelector('.support-iframe').src = "";
    });

    document.querySelector('.myacct-closeButton').addEventListener('click', function () {
        document.querySelector('.myacct-popup').style.display = 'none';
        document.querySelector('.myacct-overlay').style.display = 'none';
    });

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
});
