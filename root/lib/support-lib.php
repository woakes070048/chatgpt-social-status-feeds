<?php
/*
 * Project: ChatGPT API
 * Author: Vontainment
 * URL: https://vontainment.com
 * File: helper.php
 * Description: ChatGPT API Status Generator
 */

function supportButton()
{
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

function blklistButton()
{
    $json = file_get_contents(BLACKLIST_DIR . '/BLACKLIST.json');
    $data = json_decode($json, true);

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ip'])) {
        $ip = $_POST['ip'];
        if (isset($data[$ip])) {
            unset($data[$ip]);
            file_put_contents(BLACKLIST_DIR . '/BLACKLIST.json', json_encode($data));
        }
        header("Location: " . $_SERVER['PHP_SELF']); // to refresh the page after deleting an IP
        exit;
    }

    $content = '
        <div class="blklistButton">BL</div>

        <div class="blklist-overlay"></div>

        <div class="blklist-popup">
            <span class="blklist-closeButton">X</span>

            <table style="width:100%; margin-top: 40px; border-collapse:collapse;">
            <thead>
            <tr style="text-align:left; background-color:#f2f2f2;">
                <th>IP</th>
                <th>ATTEMPTS</th>
                <th>BLACKLISTED</th>
                <th>ACTION</th>
            </tr>
            </thead>
            <tbody>';

    foreach ($data as $ip => $details) {
        $content .= '<tr>';
        $content .= '<td style="padding: 10px 0;">' . $ip . '</td>';
        $content .= '<td style="padding: 10px 0;">' . $details['login_attempts'] . '</td>';
        $content .= '<td style="padding: 10px 0;">' . ($details['blacklisted'] ? 'YES' : 'NO') . '</td>';
        $content .= '<td style="padding: 10px 0;"><form method="POST" onsubmit="return confirm(\'Are you sure you want to delete this entry?\');"><input type="hidden" name="ip" value="' . $ip . '"><button type="submit" class="red-button">Unblock</button></form></td>';
        $content .= '</tr>';
    }

    $content .= '</tbody></table></div>';

    return $content;
}

function statusButton()
{
    $content = '
        <div class="statusButton">QS</div>
        <div class="status-overlay"></div>
        <div class="status-popup">
            <span class="status-closeButton">X</span>
            <form id="quickstatusForm" method="POST" style="width:100%; margin-top: 40px;">
            <label for="prompt" style="font-size: 1.5em; font-weight: bold;">Explain The Status You Need:</label>
            <textarea id="prompt" name="prompt" style="height: 200px;"></textarea>
                <input class="green-button" type="submit" value="Submit">
            </form>
            <div style="margin-top: 60px;">
            <label for="response" style="font-size: 1.5em; font-weight: bold;">Your Status:</label>
            <textarea id="quickresponse" name="response"></textarea>
            <button id="copyButton">Copy to Clipboard</button>
            </div>
            </div>
    ';

    return $content;
}

if (isset($_SESSION['username'])) {
    $userData = getUserInfo($_SESSION['username']);
    if ($userData && isset($userData['admin'])) {
        if ($userData['admin'] == 1) {
            echo supportButton();
            echo statusButton();
            echo blklistButton();
        } else {
            echo supportButton();
            echo statusButton();
        }
    }
}