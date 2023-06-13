(function () {
  document.addEventListener("DOMContentLoaded", () => {
    // New code for manage users popup
    const manageUsersBtn = document.getElementById("manage-users-btn");
    const manageUsersPopup = document.getElementById("manage-users-popup");
    const closeManageUsersPopupBtn = document.getElementById(
      "close-manage-users-popup-btn"
    );
    const manageUserForm = document.getElementById("manage-user-form");
    const updateUserBtns = document.querySelectorAll(".update-user-btn");

    manageUsersBtn.addEventListener("click", () => {
      manageUsersPopup.style.display = "block";
    });

    closeManageUsersPopupBtn.addEventListener("click", () => {
      manageUsersPopup.style.display = "none";
    });

    window.addEventListener("click", (event) => {
      if (event.target == manageUsersPopup) {
        manageUsersPopup.style.display = "none";
      }
    });

    updateUserBtns.forEach((button) => {
      button.addEventListener("click", () => {
        // Here is where we parse the user data from the button's data attribute
        const user = JSON.parse(button.getAttribute("data-user"));
        
        manageUserForm["username"].value = user.username;
        manageUserForm["password"].value = user.password;
        manageUserForm["admin"].checked = user.admin == 1;
        manageUserForm["total-accounts"].value = user['total-accounts'];
        
        // 'account-access' is a multi-select dropdown, so we need to loop over the options and set the 'selected' property for each one that should be selected
        Array.from(manageUserForm["account-access"].options).forEach((option) => {
            option.selected = user['account-access'].includes(option.value);
          }
        );
      });
    });
    
  

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
    const updateHashtagsCheckbox = document.getElementById("update-hashtags");

    updateAccountButtons.forEach((button) => {
      button.addEventListener("click", () => {
        const accountName = button.getAttribute("data-account-name");
        const key = button.getAttribute("data-key");
        const prompt = button.getAttribute("data-prompt");
        const link = button.getAttribute("data-link");
        const Hashtags = button.getAttribute("data-hashtags") === "true";

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

  function initializeImageButtons() {
    const imagesBtns = document.querySelectorAll(".images-btn");
    const imagesPopups = document.querySelectorAll(".images-popup");
    const closeBtns = document.querySelectorAll(".close-btn");

    imagesBtns.forEach((imagesBtn, index) => {
      imagesBtn.addEventListener("click", () => {
        imagesPopups[index].style.display = "block";
      });
    });

    closeBtns.forEach((closeBtn, index) => {
      closeBtn.addEventListener("click", () => {
        imagesPopups[index].style.display = "none";
      });
    });

    window.addEventListener("click", (event) => {
      imagesPopups.forEach((imagesPopup) => {
        if (event.target == imagesPopup) {
          imagesPopup.style.display = "none";
        }
      });
    });
  }

  // Call initializeImageButtons after the page has loaded
  document.addEventListener("DOMContentLoaded", () => {
    initializeImageButtons();
  });
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
      const updateAccountPopup = document.getElementById(
        "update-account-popup"
      );
      const closeUpdatePopupBtn = document.getElementById(
        "close-update-popup-btn"
      );
      const updateAccountNameField = document.getElementById(
        "update-account-name"
      );
      const updateKeyField = document.getElementById("update-key");
      const updatePromptField = document.getElementById("update-prompt");
      const updateLinkField = document.getElementById("update-link");
      const updateHashtagsCheckbox = document.getElementById("update-hashtags");

      updateAccountButtons.forEach((button) => {
        button.addEventListener("click", () => {
          const accountName = button.getAttribute("data-account-name");
          const key = button.getAttribute("data-key");
          const prompt = button.getAttribute("data-prompt");
          const link = button.getAttribute("data-link");
          const Hashtags = button.getAttribute("data-hashtags") === "true";

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

    function initializeImageButtons() {
      const imagesBtns = document.querySelectorAll(".images-btn");
      const imagesPopups = document.querySelectorAll(".images-popup");
      const closeBtns = document.querySelectorAll(".close-btn");

      imagesBtns.forEach((imagesBtn, index) => {
        imagesBtn.addEventListener("click", () => {
          imagesPopups[index].style.display = "block";
        });
      });

      closeBtns.forEach((closeBtn, index) => {
        closeBtn.addEventListener("click", () => {
          imagesPopups[index].style.display = "none";
        });
      });

      window.addEventListener("click", (event) => {
        imagesPopups.forEach((imagesPopup) => {
          if (event.target == imagesPopup) {
            imagesPopup.style.display = "none";
          }
        });
      });
    }

    // Call initializeImageButtons after the page has loaded
    document.addEventListener("DOMContentLoaded", () => {
      initializeImageButtons();
    });
  });
})();
