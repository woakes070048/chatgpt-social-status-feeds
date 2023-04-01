(function () {
  document.addEventListener("DOMContentLoaded", () => {
    // Original code for add account popup
    const addAccountBtn = document.getElementById("add-account-btn");
    const accountPopup = document.getElementById("add-account-popup");
    const closePopupBtn = document.getElementById("close-add-popup-btn");
    addAccountBtn.addEventListener("click", () => {
      accountPopup.style.display = "block";
    });

    closePopupBtn.addEventListener("click", () => {
      accountPopup.style.display = "none";
    });

    window.addEventListener("click", (event) => {
      if (event.target == accountPopup) {
        accountPopup.style.display = "none";
      }
    });

    // New code for update/delete account popup
    const updateAccountButtons = document.querySelectorAll(
      "#update-account-btn"
    );
    const updateAccountPopup = document.getElementById("update-account-popup");
    const closeUpdatePopupBtn = document.getElementById(
      "close-update-popup-btn"
    );
    const updateAccountNameField = document.getElementById(
      "update-account-name"
    );
    const updateKeyField = document.getElementById("update-key");
    const updatePromptField = document.getElementById("update-prompt");
    const updateLinkField = document.getElementById("update-link");
    const updateHashtagsCheckbox = document.getElementById(
      "update-hashtags"
    );

    updateAccountButtons.forEach((button) => {
      button.addEventListener("click", () => {
        const accountName = button.getAttribute("data-account-name");
        const key = button.getAttribute("data-key");
        const prompt = button.getAttribute("data-prompt");
        const link = button.getAttribute("data-link");
        const Hashtags =
          button.getAttribute("data-hashtags") === "true";

        updateAccountNameField.value = accountName;
        updateKeyField.value = key;
        updatePromptField.value = prompt;
        updateLinkField.value = link;
        updateHashtagsCheckbox.checked = Hashtags;

        updateAccountPopup.style.display = "block";
      });
    });

    closeUpdatePopupBtn.addEventListener("click", () => {
      updateAccountPopup.style.display = "none";
    });

    window.addEventListener("click", (event) => {
      if (event.target == updateAccountPopup) {
        updateAccountPopup.style.display = "none";
      }
    });
  });
})();