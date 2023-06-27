document.addEventListener('DOMContentLoaded', function () {
    // Get all update buttons
    const updateButtons = document.querySelectorAll('#update-user-btn');

    // Add event listener to all buttons
    updateButtons.forEach(button => {
        button.addEventListener('click', function () {
            // Get the form fields
            const usernameField = document.querySelector('#username');
            const passwordField = document.querySelector('#password');
            const adminCheckbox = document.querySelector('#admin');
            const totalAccountsSelect = document.querySelector('#total-accounts');
            const maxApiCallsSelect = document.querySelector('#max-api-calls');
            const usedApiCallsField = document.querySelector('#used-api-calls');  // New field

            // Get data from button
            const username = this.dataset.username;
            const password = this.dataset.password;
            const admin = this.dataset.admin;
            const totalAccounts = this.dataset.totalAccounts;
            const maxApiCalls = this.dataset.maxApiCalls;
            const usedApiCalls = this.dataset.usedApiCalls;  // New data

            // Populate form fields with data
            usernameField.value = username || '';
            passwordField.value = decodeURIComponent(password) || '';
            adminCheckbox.checked = admin === '1' ? true : false;
            totalAccountsSelect.value = totalAccounts || '';
            maxApiCallsSelect.value = maxApiCalls || '';
            usedApiCallsField.value = usedApiCalls || '';  // Populate new field
        });
    });
});