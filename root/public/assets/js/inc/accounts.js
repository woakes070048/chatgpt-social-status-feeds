document.addEventListener('DOMContentLoaded', function() {
    // Get all update buttons
    const updateButtons = document.querySelectorAll('#update-account-btn');

    // Add event listener to all buttons
    updateButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Get the form fields
            const accountNameField = document.querySelector('#account_name');
            const keyField = document.querySelector('#key');
            const promptField = document.querySelector('#add-prompt');
            const linkField = document.querySelector('#link');
            const hashtagCheckbox = document.querySelector('#hashtags');

            // Get data from button
            const accountName = this.dataset.accountName;
            const key = this.dataset.key;
            const prompt = decodeURIComponent(this.dataset.prompt.replace(/\+/g, ' '));
            const link = decodeURIComponent(this.dataset.link.replace(/\+/g, ' '));
            const hashtags = this.dataset.hashtags;

            // Populate form fields with data
            accountNameField.value = accountName || '';
            keyField.value = key || '';
            promptField.value = prompt || '';
            linkField.value = link || '';
            hashtagCheckbox.checked = hashtags === 'true' ? true : false;
        });
    });
});