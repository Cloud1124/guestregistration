<?php

require_once 'admin/db_config.php';

function respond_json($data) {
    header('Content-Type: application/json');
    echo json_encode($data);
    exit();
}

// Helper to save base64 PNG to file
function save_signature_image($base64_string, $save_dir = 'admin/signature') {
    if (!is_dir($save_dir)) {
        mkdir($save_dir, 0777, true);
    }
    if (preg_match('/^data:image\/png;base64,/', $base64_string)) {
        $base64_string = substr($base64_string, strpos($base64_string, ',') + 1);
        $base64_string = str_replace(' ', '+', $base64_string);
        $data = base64_decode($base64_string);
        if ($data === false) return false;
        $filename = uniqid('sig_', true) . '.png';
        $filepath = $save_dir . '/' . $filename;
        if (file_put_contents($filepath, $data)) {
            return $filename; // return only the filename
        }
    }
    return false;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $complete_name = trim($_POST['name'] ?? '');
    $agency = trim($_POST['agency_org'] ?? '');
    $designation = trim($_POST['designation'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $mobile = trim($_POST['mobile'] ?? '');
    $gender = trim($_POST['gender'] ?? '');
    $signature = $_POST['signature_data'] ?? '';

    // Basic validation
    if ($complete_name && $email && $gender && $signature) {
        // Save signature image and get filename
        $signature_filename = save_signature_image($signature);
        if (!$signature_filename) {
            respond_json(['success' => false, 'message' => 'Signature could not be saved.']);
        }

        $stmt = $conn->prepare("INSERT INTO guests 
            (name, agency_org, designation, email, mobile, gender, signature_data, registered_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("sssssss", $complete_name, $agency, $designation, $email, $mobile, $gender, $signature_filename);

        if ($stmt->execute()) {
            respond_json(['success' => true]);
        } else {
            respond_json(['success' => false, 'message' => 'Database error: ' . $conn->error]);
        }
    } else {
        respond_json(['success' => false, 'message' => 'Please fill in all required fields.']);
    }
} else {
    respond_json(['success' => false, 'message' => 'Invalid request.']);
}
?>