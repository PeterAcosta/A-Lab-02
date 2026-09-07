<?php

function createH1Header($text) {
    return '<h1>' . htmlspecialchars($text, ENT_QUOTES, 'UTF-8') . '</h1>';
}