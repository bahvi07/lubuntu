<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fname = isset($_POST['fname']) ? trim($_POST['fname']) : '';
    $mname = isset($_POST['mname']) ? trim($_POST['mname']) : '';
    $lname = isset($_POST['lname']) ? trim($_POST['lname']) : '';
    $father_name = isset($_POST['father_name']) ? trim($_POST['father_name']) : '';
    $gender = isset($_POST['gender']) ? trim($_POST['gender']) : '';
    $dob = isset($_POST['dob']) ? trim($_POST['dob']) : '';
    $blood_group = isset($_POST['blood']) ? trim($_POST['blood']) : '';
    $occupation = isset($_POST['occupation']) ? trim($_POST['occupation']) : '';
    $contact = isset($_POST['contact']) ? trim($_POST['contact']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $panchayat = isset($_POST['panchayat']) ? trim($_POST['panchayat']) : '';
    $gram = isset($_POST['grampanchayat']) ? trim($_POST['grampanchayat']) : '';
    $pincode=isset($_POST['pincode']) ? trim($_POST['pincode']) : '';
    $tehsil = isset($_POST['tehsil']) ? trim($_POST['tehsil']) : '';
    $district = isset($_POST['district']) ? trim($_POST['district']) : '';
    $state = isset($_POST['state']) ? trim($_POST['state']) : '';
    $docType = isset($_POST['document-type']) ? trim($_POST['document-type']) : '';
    $docNumber = isset($_POST['document-number']) ? trim($_POST['document-number']) : '';
    $doc = isset($_FILES['document-file']) ? $_FILES['document-file'] : null;

    $errors = [];

    // Validation
    if (empty($fname)) $errors['fname'] = "First name is required.";
    if (empty($lname)) $errors['lname'] = "Last name is required.";
    if (empty($father_name)) $errors['father_name'] = "Father's name is required.";
    if (empty($gender)) $errors['gender'] = "Please select your gender.";
    if (empty($dob)) $errors['dob'] = "Date of birth is required.";
    if (empty($blood_group)) $errors['blood'] = "Blood group is required.";
    if (empty($occupation)) $errors['occupation'] = "Occupation is required.";
    if (empty($contact)) {
        $errors['contact'] = "Contact number is required.";
    } elseif (!preg_match('/^[0-9]{10}$/', $contact)) {
        $errors['contact'] = "Contact number must be 10 digits.";
    } elseif (!preg_match("/^[9876]/", $contact)) {
        $errors['contact'] = "Contact number must start with 9, 8, 7, or 6.";
    }
    if (empty($email)) {
        $errors['email'] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Invalid email format.";
    }
    if (empty($panchayat)) $errors['panchayat'] = "panchayat is required.";
    if (empty($gram)) {
        $errors['grampanchayat'] = "grampanchayat is required.";
    }
    if (empty($pincode)) {
        $errors['pincode'] = "Pincode is required.";
    } elseif (!preg_match('/^[0-9]{6}$/', $pincode)) {
        $errors['pincode'] = "Pincode must be 6 digits.";
    }
    if (empty($tehsil)) $errors['tehsil'] = "tehsil is required.";
    if (empty($district)) $errors['district'] = "district is required.";
    if (empty($state)) $errors['state'] = "state is required.";
    if (empty($docType)) $errors['document-type'] = "Document type is required.";
    if (empty($docNumber)) {
        $errors['document-number'] = "Document number is required.";
    } elseif ($docType == 'Aadhar' && !preg_match('/^[0-9]{12}$/', $docNumber)) {
        $errors['document-number'] = "Aadhar number must be 12 digits.";
    } elseif ($docType == 'Driving-License' && !preg_match('/^[A-Z]{3}[0-9]{7}$/', $docNumber)) {
        $errors['document-number'] = "Driving License number must be in the format ABC1234567.";
    }

    // File Upload Validation
    if ($doc && $doc['error'] === UPLOAD_ERR_OK) {
        $allowed_types = ['image/jpeg', 'image/png', 'application/pdf'];
        $fileTmpPath = $doc['tmp_name'];
        $fileName = basename($doc['name']);
        $fileType = $doc['type'];
        $fileSize = $doc['size'];
        $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'pdf'];

        if (!in_array($fileType, $allowed_types)) {
            $errors['document-file'] = "Invalid document file type. Only JPEG, PNG, and PDF are allowed.";
        }
        if (!in_array($ext, $allowed_extensions)) {
            $errors['document-file'] = "Invalid file extension. Only JPG, JPEG, PNG, and PDF are allowed.";
        }
        if ($fileSize > 2000000) {
            $errors['document-file'] = "Document file size must be less than 2MB.";
        }

        if (empty($errors)) {
            $uploadDir = 'uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $newFileName = uniqid('doc_', true) . '.' . $ext;
            $dest = $uploadDir . $newFileName;

            if (move_uploaded_file($fileTmpPath, $dest)) {
                $imagePath = $dest;
            } else {
                $errors['document-file'] = "Failed to upload the document.";
            }
        }
    } else {
        $errors['document-file'] = "Document file is required.";
    }

    // Insert into Database
    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO users (first_name, middle_name, last_name, father_name, gender, dob, blood_group, occupation, contact_number, email, panchayat, gram,pincode, tehsil,district,state, document_type, document_number, document_path) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
        $stmt->bind_param("sssssssssssssssssss", 
        $fname, $mname, $lname, $father_name, $gender, $dob, $blood_group,
        $occupation, $contact, $email, $panchayat, $gram,$pincode, $tehsil,
        $district,$state, $docType, $docNumber, $imagePath
    );
    
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo "Form submitted successfully.";
        header("Location: user-form.html");
            exit;
        } else {
            echo "Error submitting form: " . $stmt->error;
            header("Location: user-form.html");
        }
        $stmt->close();
    } else {
        foreach ($errors as $error) {
            echo "<script>alert('$error');
            window.location.href='user-form.html';</script>";
         
        }
        echo "<script>window.history.back();</script>";
    }

    $conn->close();
} else {
    echo "Invalid request method.";
    header("Location: user-form.html");
    exit;
}
?>