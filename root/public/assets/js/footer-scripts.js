document.addEventListener('DOMContentLoaded', function () {
    document.querySelector('.supportButton').addEventListener('click', function() {
        document.querySelector('.support-popup').style.display = 'block';
        document.querySelector('.support-overlay').style.display = 'block';
        document.querySelector('.support-iframe').src = "https://crm.vontainment.com/forms/ticket";
    });

    document.querySelector('.myacctButton').addEventListener('click', function() {
        document.querySelector('.myacct-popup').style.display = 'block';
        document.querySelector('.myacct-overlay').style.display = 'block';
    });

    document.querySelector('.support-closeButton').addEventListener('click', function() {
        document.querySelector('.support-popup').style.display = 'none';
        document.querySelector('.support-overlay').style.display = 'none';
        document.querySelector('.support-iframe').src = "";
    });

    document.querySelector('.myacct-closeButton').addEventListener('click', function() {
        document.querySelector('.myacct-popup').style.display = 'none';
        document.querySelector('.myacct-overlay').style.display = 'none';
    });
});
