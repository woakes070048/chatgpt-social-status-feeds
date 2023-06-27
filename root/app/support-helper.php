<?php
/*
 * Project: ChatGPT API
 * Author: Vontainment
 * URL: https://vontainment.com
 * File: support-helper.php
 * Description: ChatGPT API Status Generator
 */

echo '
    <style>
        #floatingButton {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: #3066d6;
            color: white;
            text-align: center;
            line-height: 50px;
            position: fixed;
            bottom: 50px;
            right: 20px;
            cursor: pointer;
            font-size: 24px;
            z-index: 10000;
            box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;
        }

        #popup {
            display: none;
            position: fixed;
            width: 700px;
            height: calc(100vh - 85px);
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            border: 2px solid #66cc33;
            box-shadow: 0px 0px 10px rgba(0,0,0,0.25);
            z-index: 10001;
        }

        #popup iframe {
            height: calc(100vh - 85px);
            width: 700px;
        }

        #closeButton {
            position: absolute;
            right: 60px;
            top: 10px;
            cursor: pointer;
            font-size: 2em;
        }

        #overlay {
            display: none;
            position: fixed;
            width: 100vw;
            height: 100vh;
            background: rgba(0,0,0,0.5);
            backdrop-filter: blur(10px);
            z-index: 9999;
            top: 0;
            left: 0;
        }
    </style>

    <div id="floatingButton">?</div>

    <div id="overlay"></div>

    <div id="popup">
        <span id="closeButton">X</span>
        <iframe id="iframe" src="" frameborder="0" allowfullscreen></iframe>
    </div>

    <script>
        document.getElementById(\'floatingButton\').addEventListener(\'click\', function() {
            document.getElementById(\'popup\').style.display = \'block\';
            document.getElementById(\'overlay\').style.display = \'block\';
            document.getElementById(\'iframe\').src = "https://crm.vontainment.com/forms/ticket";
        });

        document.getElementById(\'closeButton\').addEventListener(\'click\', function() {
            document.getElementById(\'popup\').style.display = \'none\';
            document.getElementById(\'overlay\').style.display = \'none\';
            document.getElementById(\'iframe\').src = "";
        });
    </script>
';
?>