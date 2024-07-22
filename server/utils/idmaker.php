<?php 
function idmaker() {
    $date = date('dmyyHis');
    $hash = hash("sha256", $date);
    return substr($hash, 0, 6);
}
