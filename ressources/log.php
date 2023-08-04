<?php
function logging ($action) {
    $source = $_SERVER['REMOTE_ADDR'] . ' ('. $_SESSION['username'] .')';
    $target = "log.log";
    $date = (new DateTime('NOW'))->format("y:m:d G:i:s");
    $text = "[$date] $source: $action\n";
    
    error_log($text,3, $target);
}
?>