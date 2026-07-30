<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

mysqli_report(MYSQLI_REPORT_OFF);
require_once dirname(__DIR__) . '/bootrap/config.php';

$conn = new mysqli($dbhost, $dbuser, $dbpasswd, $dbname);

if ($conn->connect_error) {
    die("DB ERROR: " . $conn->connect_error);
}

$raw = file_get_contents("php://input");
file_put_contents("debug_json.txt", $raw);

$data = json_decode($raw, true);

if (!$data || !is_array($data)) {
    exit("NO DATA");
}

$stmt = $conn->prepare("
    INSERT INTO tbl_chamcong
    (user_id, name, work_date, check_in, check_out, status, total_minutes, work_time, note)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
    ON DUPLICATE KEY UPDATE
        name = VALUES(name),
        check_in = VALUES(check_in),
        check_out = VALUES(check_out),
        status = VALUES(status),
        total_minutes = VALUES(total_minutes),
        work_time = VALUES(work_time),
        note = VALUES(note)
");

if (!$stmt) {
    die("PREPARE ERROR: " . $conn->error);
}

$success = 0;
$failed = 0;

foreach ($data as $row) {

    $user_id = isset($row['user_id']) ? (string)$row['user_id'] : '';
    $name = isset($row['name']) ? (string)$row['name'] : '';
    $work_date = isset($row['work_date']) ? (string)$row['work_date'] : '';

    $check_in = !empty($row['check_in']) ? $row['check_in'] : null;
    $check_out = !empty($row['check_out']) ? $row['check_out'] : null;

    $status = isset($row['status']) ? (string)$row['status'] : '';
    $note = isset($row['note']) ? (string)$row['note'] : '';

    $total_minutes = isset($row['total_minutes']) ? intval($row['total_minutes']) : 0;

    // Chuẩn hóa time HH:MM -> HH:MM:SS
    if ($check_in && strlen($check_in) == 5) {
        $check_in .= ":00";
    }

    if ($check_out && strlen($check_out) == 5) {
        $check_out .= ":00";
    }

    // Tính work_time
    $hours = floor($total_minutes / 60);
    $minutes = $total_minutes % 60;

    $work_time = sprintf("%02d:%02d:00", $hours, $minutes);

    $stmt->bind_param(
        "ssssssiss",
        $user_id,
        $name,
        $work_date,
        $check_in,
        $check_out,
        $status,
        $total_minutes,
        $work_time,
        $note
    );

    if ($stmt->execute()) {
        $success++;
    } else {
        $failed++;
    }
}

$stmt->close();
$conn->close();

echo "OK | Success: $success | Failed: $failed";
