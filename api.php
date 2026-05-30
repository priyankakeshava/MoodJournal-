<?php
header('Content-Type: application/json');
include 'db.php';

$method = $_SERVER['REQUEST_METHOD'];

// --- GET: Fetch all data (Newest First) ---
if ($method === 'GET') {
    $sql = "SELECT date, mood, journal FROM mood ORDER BY date DESC";
    $result = $conn->query($sql);
    
    $data = [];
    while($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    echo json_encode($data);
    exit;
}

// --- POST: Save or Update ---
if ($method === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $date = $input['date'];
    $mood = $input['mood'];
    $journal = $input['journal'];

    if(!$date || !$mood) {
        echo json_encode(["status" => "error", "message" => "Missing date or mood"]);
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO mood (date, mood, journal) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE mood=?, journal=?");
    $stmt->bind_param("sssss", $date, $mood, $journal, $mood, $journal);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Mood saved!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "SQL Error: " . $conn->error]);
    }
    $stmt->close();
    exit;
}

// --- DELETE: Remove an entry ---
if ($method === 'DELETE') {
    $input = json_decode(file_get_contents('php://input'), true);
    $date = $input['date'];

    if(!$date) {
        echo json_encode(["status" => "error", "message" => "Missing date"]);
        exit;
    }

    $stmt = $conn->prepare("DELETE FROM mood WHERE date = ?");
    $stmt->bind_param("s", $date);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Entry deleted."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Delete failed: " . $conn->error]);
    }
    $stmt->close();
    exit;
}

$conn->close();
?>