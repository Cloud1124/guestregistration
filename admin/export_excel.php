<?php

require_once 'db_config.php';

// Set headers to force download as CSV
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=guests_export_' . date('Ymd_His') . '.csv');

// Open output stream
$output = fopen('php://output', 'w');

// Output column headings
fputcsv($output, [
    'ID',
    'Name',
    'Agency/Org.',
    'Designation',
    'Email',
    'Mobile',
    'Gender',
    'Signature',
    'Registered At'
], ",", '"', "\\");

// Fetch data from the database
$query = $conn->query("SELECT id, name, agency_org, designation, email, mobile, gender, signature_data, registered_at FROM guests ORDER BY registered_at DESC");

while ($row = $query->fetch_assoc()) {
    fputcsv($output, [
        $row['id'],
        $row['name'],
        $row['agency_org'],
        $row['designation'],
        $row['email'],
        $row['mobile'],
        $row['gender'],
        $row['signature_data'],
        $row['registered_at']
    ], ",", '"', "\\");
}

fclose($output);