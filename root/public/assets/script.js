document.addEventListener('DOMContentLoaded', function() {
    const addAccountBtn = document.getElementById('add-account-btn');
    const accountPopup = document.getElementById('account-popup');
    const closePopupBtn = document.getElementById('close-popup-btn');

    addAccountBtn.addEventListener('click', function() {
        accountPopup.style.display = 'block';
    });

    closePopupBtn.addEventListener('click', function() {
        accountPopup.style.display = 'none';
    });

    window.addEventListener('click', function(event) {
        if (event.target == accountPopup) {
            accountPopup.style.display = 'none';
        }
    });
});