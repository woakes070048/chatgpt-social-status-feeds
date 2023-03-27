document.addEventListener("DOMContentLoaded", function () {
  // Original code for add account popup
  const addAccountBtn = document.getElementById("add-account-btn");
  const accountPopup = document.getElementById("add-account-popup");
  const closePopupBtn = document.getElementById("close-add-popup-btn");

  addAccountBtn.addEventListener("click", function () {
    accountPopup.style.display = "block";
  });

  closePopupBtn.addEventListener("click", function () {
    accountPopup.style.display = "none";
  });

  window.addEventListener("click", function (event) {
    if (event.target == accountPopup) {
      accountPopup.style.display = "none";
    }
  });

  // New code for update/delete account popup
  const updateAccountButtons = document.querySelectorAll("#update-account-btn");
  const updateAccountPopup = document.getElementById("update-account-popup");
  const closeUpdatePopupBtn = document.getElementById("close-update-popup-btn");
  const updateAccountNameField = document.getElementById("update-account-name");
  const updateKeyField = document.getElementById("update-key");
  const updatePromptField = document.getElementById("update-prompt");

  updateAccountButtons.forEach((button) => {
    button.addEventListener("click", () => {
      const accountName = button.getAttribute("data-account-name");
      updateAccountNameField.value = accountName;
      updateAccountPopup.style.display = "block";
          updateKeyField.value = button.getAttribute('data-key');
    updatePromptField.value = button.getAttribute('data-prompt');
    });
  });

  closeUpdatePopupBtn.addEventListener("click", () => {
    updateAccountPopup.style.display = "none";
  });

  window.addEventListener("click", function (event) {
    if (event.target == updateAccountPopup) {
      updateAccountPopup.style.display = "none";
    }
  });
});
