<?php

mysqli_report(MYSQLI_REPORT_OFF);
$conn = new mysqli("localhost","ad2_db","MS@SWS0We!m6IffE","ad2_db");

if ($conn->connect_error) {
    die("DB ERROR");
}

$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    echo "NO DATA";
    exit;
}

foreach ($data as $row) {

    $user_id = $conn->real_escape_string(isset($row['user_id']) ? (string)$row['user_id'] : '');
    $name = $conn->real_escape_string(isset($row['name']) ? (string)$row['name'] : '');
    $timestamp = $conn->real_escape_string(isset($row['timestamp']) ? (string)$row['timestamp'] : '');
    $status = intval(isset($row['status']) ? $row['status'] : 0);
    $punch = intval(isset($row['punch']) ? $row['punch'] : 0);
    $uid = intval(isset($row['uid']) ? $row['uid'] : 0);

    $conn->query("
        INSERT IGNORE INTO attendance_logs 
        (user_id, name, timestamp, status, punch, uid)
        VALUES 
        ('$user_id','$name','$timestamp','$status','$punch','$uid')
    ");
}

echo "OK";
