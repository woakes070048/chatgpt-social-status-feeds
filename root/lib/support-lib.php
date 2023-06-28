<?php
/*
 * Project: ChatGPT API
 * Author: Vontainment
 * URL: https://vontainment.com
 * File: helper.php
 * Description: ChatGPT API Status Generator
 */

function supportButton() {
    $content = '
        <div class="supportButton">?</div>

        <div class="support-overlay"></div>

        <div class="support-popup">
            <span class="support-closeButton">X</span>
            <iframe class="support-iframe" src="" frameborder="0" allowfullscreen></iframe>
        </div>
    ';

    return $content;
}

function myacctButton() {
    $content = '
        <div class="myacctButton">A</div>

        <div class="myacct-overlay"></div>

        <div class="myacct-popup">
            <span class="myacct-closeButton">X</span>
        </div>
    ';

    return $content;
}

echo supportButton();
echo myacctButton();
?>
