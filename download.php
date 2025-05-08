<?php
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="user_registration_report.csv"');

include 'config.php';

// Open output stream
$output = fopen('php://output', 'w');

// Column headers
fputcsv($output, [
    'ID', 'First Name', 'Middle Name', 'Last Name', "Father's Name", 'Gender',
    'DOB', 'Blood Group', 'Occupation', 'Contact Number', 'Email',
    'House Number', 'Street', 'Ward Number', 'Full Address',
    'Document Type', 'Document Number', 'Document Path',
    'Created At', 'Updated At'
]);

// Fetch data
$query = "SELECT * FROM users";
$result = mysqli_query($conn, $query);

if (!$result) {
    echo "Error: " . mysqli_error($conn);
    exit;
}

// Output rows
while ($row = mysqli_fetch_assoc($result)) {
    fputcsv($output, [
        $row['id'],
        $row['first_name'] ?: 'N/A',
        $row['middle_name'] ?: 'N/A',
        $row['last_name'] ?: 'N/A',
        $row['father_name'] ?: 'N/A',
        $row['gender'] ?: 'N/A',
        $row['dob'] ?: 'N/A',
        $row['blood_group'] ?: 'N/A',
        $row['occupation'] ?: 'N/A',
        $row['contact_number'] ?: 'N/A',
        $row['email'] ?: 'N/A',
        $row['house_number'] ?: 'N/A',
        $row['street'] ?: 'N/A',
        $row['ward_number'] ?: 'N/A',
        $row['full_address'] ?: 'N/A',
        $row['document_type'] ?: 'N/A',
        $row['document_number'] ?: 'N/A',
        $row['document_path'] ?: 'N/A',
        $row['created_at'] ?: 'N/A',
        $row['updated_at'] ?: 'N/A'
    ]);
}

fclose($output);
exit;
?>
