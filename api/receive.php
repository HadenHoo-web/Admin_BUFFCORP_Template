<?php

// Ghi toàn bộ request vào log
file_put_contents("debug.txt",
    "TIME: ".date("Y-m-d H:i:s")."\n".
    "GET:\n".print_r($_GET,true)."\n".
    "POST:\n".print_r($_POST,true)."\n".
    "RAW:\n".file_get_contents("php://input")."\n\n",
    FILE_APPEND
);

echo "OK";
?>