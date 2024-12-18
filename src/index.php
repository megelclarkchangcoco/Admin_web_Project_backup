<?php
session_start();

    // Database Connection
    $connection = mysqli_connect("localhost", "root", "", "dental_database");

    if (!$connection) {
        die("Connection failed: " . mysqli_connect_error());
    }


    // Initialize variables
    $alert = [];
    $alert2 = [];
    $success = false;
    $active_section = 'register-section';

    // Handle form submission
    if (isset($_POST['submit'])) {
        // Generate unique ID for admin
        $ran_id = rand(time(), 1000000000);

        // Sanitize inputs
        $firstname = mysqli_real_escape_string($connection, trim($_POST['firstname']));
        $lastname = mysqli_real_escape_string($connection, trim($_POST['lastname']));
        $email = mysqli_real_escape_string($connection, trim($_POST['email']));
        $contact = mysqli_real_escape_string($connection, trim($_POST['contact']));
        $gender = mysqli_real_escape_string($connection, trim($_POST['gender']));
        $password = mysqli_real_escape_string($connection, trim($_POST['password']));
        $cpassword = mysqli_real_escape_string($connection, trim($_POST['cpassword']));

        // Validate email
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            // Validate image upload
            $allowed_types = ['image/jpeg', 'image/png', 'image/jpg'];
            $image = $_FILES['image']['name'];
            $image_size = $_FILES['image']['size'];
            $image_tmp_name = $_FILES['image']['tmp_name'];
            $image_type = $_FILES['image']['type'];
            $image_rename = uniqid() . "_" . basename($image);
            $image_folder = 'img/' . $image_rename;

            if (!in_array($image_type, $allowed_types)) {
                $alert[] = "Invalid image format!";
            } elseif ($image_size > 2000000) {
                $alert[] = "Image size is too large!";
            } elseif ($password !== $cpassword) {
                $alert[] = "Passwords do not match!";
            } else {
                // Check if email already exists
                $stmt = $connection->prepare("SELECT * FROM admin WHERE email = ?");
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $alert[] = "User already exists!";
                } else {
                    // Hash the password
                    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

                    // Insert user data into the database
                    $stmt = $connection->prepare("INSERT INTO admin(id, firstname, lastname, email, contact, password, gender, img) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                    $stmt->bind_param("isssssss", $ran_id, $firstname, $lastname, $email, $contact, $hashed_password, $gender, $image_rename);

                    if ($stmt->execute()) {
                        move_uploaded_file($image_tmp_name, $image_folder);
                        $success = true;
                    } else {
                        $alert[] = "Database error: " . $connection->error;
                    }
                }
            }
        } else {
            $alert[] = "$email is not a valid email!";
        }
    }

    // Handle login form submission
    if (isset($_POST['login_submit'])) {
        $email = mysqli_real_escape_string($connection, trim($_POST['email']));
        $password = trim($_POST['password']);

        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            // Fetch user from the database
            $stmt = $connection->prepare("SELECT * FROM admin WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();

                if (password_verify($password, $row['password'])) {
                    // Store user data in session
                    $_SESSION['id'] = $row['id'];                
                    $_SESSION['firstname'] = $row['firstname'];  
                    $_SESSION['lastname'] = $row['lastname'];    
                    $_SESSION['email'] = $email;                 
                    $_SESSION['img'] = $row['img'];              

                    // Combine firstname and lastname for fullname
                    $fullname = $_SESSION['firstname'] . ' ' . $_SESSION['lastname'];

                    // Set the profile image path
                    $profile_img = isset($_SESSION['img']) ? 'img/' . $_SESSION['img'] : 'icon/default_profile.png';

                    // Update user status to "Active Now"
                    $status = 'Active Now';
                    $update_stmt = $connection->prepare("UPDATE admin SET status = ? WHERE id = ?");
                    $update_stmt->bind_param("si", $status, $row['id']);
                    $update_stmt->execute();

                    // Redirect to homepage after successful login
                    header("Location: index.php#homepage-section");
                    exit;
                } else {
                    $alert2[] = "Incorrect password!";
                }
            } else {
                $alert2[] = "No user found with this email!";
            }
        } else {
            $alert2[] = "$email is not a valid email!";
        }
    }

    // Ensure session variables exist before using them
    $fullname = isset($_SESSION['firstname']) && isset($_SESSION['lastname']) 
        ? $_SESSION['firstname'] . ' ' . $_SESSION['lastname'] 
        : 'Guest';

    $profile_img = isset($_SESSION['img']) ? 'img/' . $_SESSION['img'] : 'icon/default_profile.png'; 


    // Fetch and display upcoming appointments
    $upcoming_appointments = getUpcomingAppointments($connection);


    // Check if the user is logged in
    if (!isset($_SESSION['id'])) {
        $active_section = 'login-section'; // Activate the login section if not logged in
    } else {
        $active_section = 'homepage-section'; // Activate the homepage section if logged in
    }

    // Logout Handling
    if (isset($_GET['logout'])) {
        // Destroy the session and unset all session variables
        session_unset();
        session_destroy();
        
        // Redirect to login page after logout
        header("Location: index.php#login-section"); // Redirect to the login section
        exit;
    }

    // Handle deletion
    if (isset($_POST['delete_appointment'])) {
        deleteAppointment($connection, $_POST['delete_id']);
    }
    

    // Functio nto display appointment request in the table
    function displayAppointmentRequestTable(){
        global $connection;
        $query = "SELECT * FROM appointment_request";
        $result = mysqli_query($connection, $query);

        if(mysqli_num_rows($result) > 0){
            while($row = mysqli_fetch_assoc($result)){
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row["PatientID"] ?? 'N/A'). "</td>";
                echo "<td>" . htmlspecialchars($row["PatientName"] ?? 'N/A'). "</td>";
                echo "<td>" . htmlspecialchars($row["PaymentType"] ?? 'N/A'). "</td>";
                echo "<td class='actions'>";
                echo "<button class='status-" . strtolower(string: $row["PaymentStatus"]) . "'>" . $row["PaymentStatus"] . "</button>";
                echo "</td>";
                echo "<td>" . htmlspecialchars($row["RequestedDate"] ?? 'N/A'). "</td>";
                echo "<td>" . htmlspecialchars($row["RequestedTime"] ?? 'N/A'). "</td>";
                echo "<td>" . htmlspecialchars($row["DateOfRequest"] ?? 'N/a' ). "</td>";
                echo "<td>" . htmlspecialchars($row["RequestedDentist"] ?? 'N/A' ). "</td>";
                echo "<td>" . htmlspecialchars($row["ReasonForBooking"] ?? 'N/A' ). "</td>";
                echo "</tr>";

            }
        }

    }

    // Function to search Appointment Request based on search term
    function searchRequest($searchAppointmentTerm) {
        global $connection;
    
        $searchTerm = mysqli_real_escape_string($connection, $searchAppointmentTerm);
    
        // Query to search patients by first name, last name, or email
        $query = "SELECT * FROM appointment_request WHERE PatientID LIKE '%$searchTerm%' OR PatientName LIKE '%$searchTerm%'";
        $result = mysqli_query($connection, $query);
    
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row["PatientID"] ?? 'N/A') . "</td>";
                echo "<td>" . htmlspecialchars($row["PatientName"] ?? 'N/A') . "</td>";
                echo "<td>" . htmlspecialchars($row["PaymentType"] ?? 'N/A') . "</td>";
                echo "<td class='actions'>";
                echo "<button class='status-" . strtolower($row["PaymentStatus"]) . "'>" . $row["PaymentStatus"] . "</button>";
                echo "</td>";
                echo "<td>" . htmlspecialchars($row["RequestedDate"] ?? 'N/A') . "</td>";
                echo "<td>" . htmlspecialchars($row["RequestedTime"] ?? 'N/A') . "</td>";
                echo "<td>" . htmlspecialchars($row["DateOfRequest"] ?? 'N/A') . "</td>";
                echo "<td>" . htmlspecialchars($row["RequestedDentist"] ?? 'N/A') . "</td>";
                echo "<td>" . htmlspecialchars($row["ReasonForBooking"] ?? 'N/A') . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='7'>No Appointment found</td></tr>";
        }
    }
    

    // Function to display appointment list in the table
    function displayAppointmentListTable() {
        global $connection;
        $query = "SELECT * FROM appointment_list";
        $result = mysqli_query($connection, $query);

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row["patient_id"] ?? 'N/A') . "</td>";
                echo "<td>" . htmlspecialchars($row["patient_name"] ?? 'N/A') . "</td>";
                echo "<td>" . htmlspecialchars($row["payment_type"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["payment_status"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["requested_date"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["requested_time"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["date_of_request"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["requested_dentist"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["appointment_status"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["reason_for_booking"]) . "</td>";
                echo "<td>
                    <form method='post' action='' >
                        <input type='hidden' name='delete_id' class='cancel-btn' value='" . htmlspecialchars($row['patient_id']) . "'>
                        <button class='cancel-btn' type='submit' name='delete_appointment' onclick='return confirm(\"Are you sure you want to delete this appointment?\");'>Delete</button>
                    </form>
                </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='11'>No appointments found.</td></tr>";
        }
    }
    // Function delete row in the table 
    function deleteAppointment($connection, $delete_id) {
        $delete_id = $connection->real_escape_string($delete_id);
        $delete_sql = "DELETE FROM appointment_list WHERE patient_id = '$delete_id'";

        if ($connection->query($delete_sql) === TRUE) {
            echo "Appointment deleted successfully.";
            // Optionally, you can redirect to the same page to refresh the list
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } else {
            echo "Error deleting appointment: " . $connection->error;
        }
    }

    // Function to search Appointment list in the table
    function searchList($searchListTerm) {
        global $connection;
    
        $searchTerm = mysqli_real_escape_string($connection, $searchListTerm);
    
        // Query to search patients by first name, last name, or email
        $query = "SELECT * FROM appointment_list WHERE patient_id LIKE '%$searchTerm%' OR patient_name LIKE '%$searchTerm%'";
        $result = mysqli_query($connection, $query);
    
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row["patient_id"] ?? 'N/A') . "</td>";
                echo "<td>" . htmlspecialchars($row["patient_name"] ?? 'N/A') . "</td>";
                echo "<td>" . htmlspecialchars($row["payment_type"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["payment_status"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["requested_date"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["requested_time"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["date_of_request"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["requested_dentist"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["appointment_status"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["reason_for_booking"]) . "</td>";
                echo "<td>
                    <form method='post' action='' >
                        <input type='hidden' name='delete_id' class='cancel-btn' value='" . htmlspecialchars($row['patient_id']) . "'>
                        <button class='cancel-btn' type='submit' name='delete_appointment' onclick='return confirm(\"Are you sure you want to delete this appointment?\");'>Delete</button>
                    </form>
                </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='11'>No appointments found.</td></tr>";
        }
    }

    // Function to fetch upcoming appointments
    function getUpcomingAppointments($connection) {
        $current_date = date('Y-m-d');
        $current_time = date('H:i:s');
            
        $query = "SELECT patient_id, patient_name, payment_type, payment_status, requested_date, requested_time, date_of_request, requested_dentist, appointment_status, reason_for_booking 
                FROM appointment_list 
                WHERE (date_of_request > '$current_date') 
                    OR (date_of_request = '$current_date' AND requested_time > '$current_time')
                ORDER BY date_of_request, requested_time";
    
        $result = $connection->query($query);
    
        if ($result === false) {
            // Query failed, handle error
            echo "Error: " . $connection->error;
             return [];
        } else {
            $appointments = [];
            while ($row = $result->fetch_assoc()) {
                $appointments[] = array(
                    'title' => $row['reason_for_booking'],
                    'start' => $row['date_of_request'] . 'T' . $row['requested_time'],
                    'end' => $row['date_of_request'] . 'T' . date('H:i:s', strtotime($row['requested_time']) + 3600)
                );
            }
            return $appointments;
        }
    }
    

    // Function to display Patient Table
    function displayPatientTable() {
        global $connection;
        $query = "SELECT * FROM Patient";
        $result = mysqli_query($connection, $query);
        
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['Patient_ID'] ?? 'N/A') . "</td>";
                echo "<td>" . htmlspecialchars(($row['Patient_Firstname'] ?? 'N/A') . ' ' . ($row['Patient_Lastname'] ?? 'N/A')) . "</td>";
                echo "<td>" . htmlspecialchars($row['Patient_Gender'] ?? 'N/A') . "</td>";
                echo "<td>" . htmlspecialchars($row['Patient_Age'] ?? 'N/A') . "</td>"; 
                echo "<td>" . htmlspecialchars($row['Patient_Contact'] ?? 'N/A') . "</td>";
                echo "<td>" . htmlspecialchars($row['Patient_Email'] ?? 'N/A') . "</td>";
                echo "<td>
                    <div class='btn-group'>
                        <button class='btn btn-info' onclick='viewPatient(" . ($row['Patient_ID'] ?? 'N/A') . ")'>View</button>
                        <button class='btn btn-warning' onclick='editPatient(" . ($row['Patient_ID'] ?? 'N/A') . ")'>Edit</button>
                        <button class='btn btn-danger' onclick='deletePatient(" . ($row['Patient_ID'] ?? 'N/A') . ")'>Delete</button>
                    </div>
                    </td>";
                echo "</tr>";
            }
        }
    }

    // Function to View Patient Details
    function viewPatientDetails($patientId) {
        global $connection;
        $query = "SELECT * FROM Patient WHERE Patient_ID = ?";
        $stmt = mysqli_prepare($connection, $query);
        mysqli_stmt_bind_param($stmt, "i", $patientId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if ($row = mysqli_fetch_assoc($result)) {
            return $row; // Return as associative array
        }
        return null;
    }

    // Function to Add Patient
    function addPatient($firstname, $lastname, $email, $contact, $password, $gender, $age, $image) {
        global $connection;

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Handle image upload
        $imagePath = "uploads/default.png"; // Default image path
        if ($image && $image['size'] > 0) {
            $uploadDir = "uploads/";
            // Ensure upload directory exists
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            
            $filename = uniqid() . '_' . basename($image['name']);
            $imagePath = $uploadDir . $filename;
            move_uploaded_file($image['tmp_name'], $imagePath);
        }

        $query = "INSERT INTO Patient (Patient_Firstname, Patient_Lastname, Patient_Email, Patient_Contact, Patient_Gender, Patient_Age, Patient_Password, Patient_Img) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($connection, $query);
        mysqli_stmt_bind_param($stmt, "sssssiss", $firstname, $lastname, $email, $contact, $gender, $age, $hashedPassword, $imagePath);

        $result = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $result;
    }

    // Function to Edit Patient
    function editPatient($patientId, $firstname, $lastname, $email, $contact, $password, $gender, $age, $image) {
        global $connection;
        
        // Only hash password if it's not empty
        $hashedPassword = !empty($password) ? password_hash($password, PASSWORD_DEFAULT) : null;

        // Handle image upload
        $imagePath = null;
        if ($image && $image['size'] > 0) {
            $uploadDir = "uploads/";
            // Ensure upload directory exists
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            
            $filename = uniqid() . '_' . basename($image['name']);
            $imagePath = $uploadDir . $filename;
            move_uploaded_file($image['tmp_name'], $imagePath);
        }

        // Determine the query based on whether password and image are updated
        if ($hashedPassword && $imagePath) {
            $query = "UPDATE Patient SET 
                Patient_Firstname = ?, 
                Patient_Lastname = ?, 
                Patient_Email = ?, 
                Patient_Contact = ?, 
                Patient_Gender = ?,
                Patient_Age = ?,
                Patient_Password = ?, 
                Patient_Img = ?
                WHERE Patient_ID = ?";
            
            $stmt = mysqli_prepare($connection, $query);
            mysqli_stmt_bind_param($stmt, "sssssissi", $firstname, $lastname, $email, $contact, $gender, $age, $hashedPassword, $imagePath, $patientId);
        } elseif ($hashedPassword) {
            $query = "UPDATE Patient SET 
                Patient_Firstname = ?, 
                Patient_Lastname = ?, 
                Patient_Email = ?, 
                Patient_Contact = ?, 
                Patient_Gender = ?,
                Patient_Age = ?,
                Patient_Password = ?
                WHERE Patient_ID = ?";
            
            $stmt = mysqli_prepare($connection, $query);
            mysqli_stmt_bind_param($stmt, "sssssisi", $firstname, $lastname, $email, $contact, $gender, $age, $hashedPassword, $patientId);
        } elseif ($imagePath) {
            $query = "UPDATE Patient SET 
                Patient_Firstname = ?, 
                Patient_Lastname = ?, 
                Patient_Email = ?, 
                Patient_Contact = ?, 
                Patient_Gender = ?,
                Patient_Age = ?,
                Patient_Img = ?
                WHERE Patient_ID = ?";
            
            $stmt = mysqli_prepare($connection, $query);
            mysqli_stmt_bind_param($stmt, "sssssisi", $firstname, $lastname, $email, $contact, $gender, $age, $imagePath, $patientId);
        } else {
            $query = "UPDATE Patient SET 
                Patient_Firstname = ?, 
                Patient_Lastname = ?, 
                Patient_Email = ?, 
                Patient_Contact = ?, 
                Patient_Gender = ?,
                Patient_Age = ?
                WHERE Patient_ID = ?";
            
            $stmt = mysqli_prepare($connection, $query);
            mysqli_stmt_bind_param($stmt, "sssssii", $firstname, $lastname, $email, $contact, $gender, $age, $patientId);
        }
        
        return mysqli_stmt_execute($stmt);
    }

    // Function to Delete Patient
    function deletePatient($patientId) {
        global $connection;
        $query = "DELETE FROM Patient WHERE Patient_ID = ?";
        
        $stmt = mysqli_prepare($connection, $query);
        mysqli_stmt_bind_param($stmt, "i", $patientId);
        
        return mysqli_stmt_execute($stmt);
    }

    // Function to search Patients based on search term
    function searchPatients($searchTerm) {
        global $connection;
        // Sanitize input to prevent SQL injection
        $searchTerm = mysqli_real_escape_string($connection, $searchTerm);
        
        // Query to search patients by first name, last name, or email
        $query = "SELECT * FROM Patient WHERE Patient_Firstname LIKE '%$searchTerm%' OR Patient_Lastname LIKE '%$searchTerm%' OR Patient_Email LIKE '%$searchTerm%'";
        $result = mysqli_query($connection, $query);
        
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['Patient_ID'] ?? 'N/A') . "</td>";
                echo "<td>" . htmlspecialchars(($row['Patient_Firstname'] ?? 'N/A') . ' ' . ($row['Patient_Lastname'] ?? 'N/A')) . "</td>";
                echo "<td>" . htmlspecialchars($row['Patient_Gender'] ?? 'N/A') . "</td>";
                echo "<td>" . htmlspecialchars($row['Patient_Age'] ?? 'N/A') . "</td>"; 
                echo "<td>" . htmlspecialchars($row['Patient_Contact'] ?? 'N/A') . "</td>";
                echo "<td>" . htmlspecialchars($row['Patient_Email'] ?? 'N/A') . "</td>";
                echo "<td>
                    <div class='btn-group'>
                        <button class='btn btn-info' onclick='viewPatient(" . ($row['Patient_ID'] ?? 'N/A') . ")'>View</button>
                        <button class='btn btn-warning' onclick='editPatient(" . ($row['Patient_ID'] ?? 'N/A') . ")'>Edit</button>
                        <button class='btn btn-danger' onclick='deletePatient(" . ($row['Patient_ID'] ?? 'N/A') . ")'>Delete</button>
                    </div>
                    </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='7'>No patients found</td></tr>";
        }
    }


    // Handle AJAX Requests
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['action'])) {
            switch ($_POST['action']) {
                case 'view':
                    $patient = viewPatientDetails($_POST['patientId']);
                    echo json_encode($patient);
                    break;
                
                case 'add':
                    $result = addPatient(
                        $_POST['firstname'], 
                        $_POST['lastname'], 
                        $_POST['email'], 
                        $_POST['contact'], 
                        $_POST['password'], 
                        $_POST['gender'],
                        $_POST['age'],
                        $_FILES['image'] ?? null
                    );
                    echo json_encode(['success' => $result]);
                    break;
                
                case 'edit':
                    $result = editPatient(
                        $_POST['patientId'],
                        $_POST['firstname'], 
                        $_POST['lastname'], 
                        $_POST['email'], 
                        $_POST['contact'], 
                        $_POST['password'], 
                        $_POST['gender'], 
                        $_POST['age'],
                        $_FILES['image'] ?? null
                    );
                    echo json_encode(['success' => $result]);
                    break;
                
                case 'delete':
                    $result = deletePatient($_POST['patientId']);
                    echo json_encode(['success' => $result]);
                    break;
            }
            exit();
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/admin.css">
    <link rel="stylesheet" href="css/alert.css">
    <link rel="stylesheet" href="css/adminhomepage.css">
    <link rel="stylesheet" href="css/appointment_requests.css">
    <link rel="stylesheet" href="css/appoinment_listview.css">
    <link rel="stylesheet" href="css/appointmetn_calendar.css">
    <link rel="stylesheet" href="css/patient.css">

    <!---JS chart-->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!--JS calendar-->
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar/index.global.min.js'></script>
    <!---->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


    <title>Daborder's</title>

</head>
<body>
        <!-- Login Section -->
        <div id="login-section" class="section <?php echo ($active_section == 'login-section') ? 'active' : ''; ?>">
        <div class="header">
                <img src="icon/logo.png" alt="">
            </div>
            <div class="container">
                <div class="background-overlay"></div>
                <div class="form-container">
                    <form action="" method="POST">
                        <div class="tabs">
                            <a href="#" class="active" onclick="showSection('login-section')">SIGN IN</a>
                            <a href="#" onclick="showSection('register-section')">SIGN UP</a>
                        </div>
                        <h3>Sign in to your Account</h3>
                        <p class="subtitle">Book an appointment and access medical records, anytime, anywhere.</p>
                        <div class="input-group">
                            <!-- Display alert messages -->
                            <?php 
                            if (!empty($alert2)) {
                                foreach ($alert2 as $message) {
                                    echo '<h3 class="alert">' . htmlspecialchars($message) . '</h3>';
                                }
                            }
                            ?>
                            <input type="email" name="email" placeholder="Email" class="box" required>
                            <input type="password" name="password" placeholder="Password" class="box" required>
                        </div>
                        <button type="submit" name="login_submit" class="btn">Submit</button>
                        <div class="footer-links">
                            <a href="#">Privacy Policy</a>
                            <a href="#">Terms of Use</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        




        <!-- Register Section -->
        <div id="register-section" class="section">
            <div class="header">
                <img src="icon/logo.png" alt="">
            </div>
            <div class="register-container">
                <div class="register-background-overlay"></div>
                <div class="register-form-container">
                    <form action="" method="post" enctype="multipart/form-data">
                        <div class="register-tabs">
                            <a href="#" onclick="showSection('login-section')">SIGN IN</a>
                            <a href="#" class="active" onclick="showSection('register-section')">SIGN UP</a>    
                        </div>
                        <h3>Create Account</h3>
                        <p class="register-subtitle">Book an appointment and access medical records, anytime, anywhare</p>
                        <div class="register-input-group">
                             <!-- Display error alerts -->
                             <?php if (!empty($alert)): ?>
                                <?php foreach ($alert as $message): ?>
                                    <h3 class="register-alert"><?php echo $message; ?></h3>
                                <?php endforeach; ?>
                            <?php endif; ?>

                            <!-- Success alert -->
                            <?php if ($success): ?>
                                <div class="success-alert" id="successAlert">
                                    <div class="icon"></div>
                                    <h2>Registration Successful!</h2>
                                    <p>Your account has been created!</p>
                                    <button type="button" onclick="proceedToLogin()">OK</button>
                                </div>
                            <?php endif; ?>
                            <input type="text" name="firstname" placeholder="Enter First Name" class="box" required>
                            <input type="text" name="lastname" placeholder="Enter Last Name" class="box" required>
                            <input type="email" name="email" placeholder="Enter Email" class="box" required>
                            <input type="text" name="contact" placeholder="Enter Contact Number" class="box" required>
                            <input type="password" name="password" placeholder="Enter Password" class="box" required>
                            <input type="password" name="cpassword" placeholder="Enter Confirm Passowrd" class="box" required>
                            <select name="gender" class="box" required>
                                <option value="" disabled selected>Select Gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                            <input type="file" name="image" class="register-logo" accept="image/*">
                            <button type="submit" name="submit" class="register-btn">Submit</button>
                        </div>
                        <div class="register-footer-links">
                            <a href="#">Privacy Policy</a>
                            <a href="#">Terms of Use</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        

        <!-- Homepage Section (Visible after login) -->
        <?php
            
            $active_section = isset($_SESSION['id']) ? 'homepage-section' : 'login-section';
        ?>
        <div id="homepage-section" class="section <?php echo $active_section === 'homepage-section' ? 'active' : ''; ?>">

            <div class="wrapper">
                <!-- Left Panel -->
                <div class="left_panel">
                    <img id="logo" src="icon/logo.png" alt="Logo">
                    <label><a href="#" onclick="showSection('homepage-section')"><img src="icon/dashboard_icon.png" alt="Dashboard"> Dashboard</a></label>
                    <label><a href="#" onclick="showSection('appointment-section')"><img src="icon/Appointment_icon.png" alt="Appointments"> Appointments</a></label>
                    <label><a href="#" onclick="showSection('patient-section')"><img src="icon/Patient_icon.png" alt="Patients"> Patients</a></label>
                    <label><a href="index.php?logout=true" onclick="showSection('login-section')"><img src="icon/signout_icon.png" alt="Sign Out"> Sign Out</a></label>
                </div>
    
                <!-- Right Panel -->
                <div class="right_panel">
                <div id="header">
                <div id="info">
                    <p id="fullname"><?php echo htmlspecialchars($fullname); ?></p>
                    <p id="status">Admin</p>
                </div>
                <img id="profile_icon" src="<?php echo htmlspecialchars($profile_img); ?> " alt="Profile Icon" onclick="openAdminEditModal()">
            </div>

                    <div class="main_content">
                        <h1>Dashboard</h1>
                        <p>October 18, 2024 &nbsp;&nbsp; 09:32:07 AM</p>
                    
                        <div class="content_wrapper">
                            <div class="stats_and_income">
                                <div class="stats">
                                    <div class="stat">
                                        <h2>₱5,310,000</h2>
                                        <p>Total Income</p>
                                    </div>
                                    <div class="stat">
                                        <h2>7</h2>
                                        <p>Number of equipment that needs restocking</p>
                                    </div>
                                    <div class="stat">
                                        <h2>39</h2>
                                        <p>Equipment available</p>
                                    </div>
                                    <div class="stat">
                                        <h2>12</h2>
                                        <p>Expired consumables</p>
                                    </div>
                                </div>
                    
                                <div class="income_distribution">
                                    <h3>Income Distribution</h3>
                                    <div id="chart-container" class="chart-contaienr">
                                        <button class="chart-button" onclick="showChart('yearly', this)">Yearly</button>
                                        <button class="chart-button" onclick="showChart('monthly', this)">Monthly</button>
                                        <button class="chart-button" onclick="showChart('weekly', this)">Weekly</button>
                                        <canvas id="incomeChart"></canvas>
                                    </div>
                                </div>
                            </div>
                    
                            <div class="kpi">
                                <h3>KEY PERFORMANCE INDICATOR</h3>
                                <div class="kpi-item">
                                    <p>156</p>
                                    <p>Registered dentists</p>
                                </div>
                                <div class="kpi-item">
                                    <p>13</p>
                                    <p>Dental assistants on duty</p>
                                </div>
                                <div class="kpi-item">
                                    <p>120</p>
                                    <p>Available staffs</p>
                                </div>
                                <div class="kpi-item">
                                    <p>12,234</p>
                                    <p>Patients</p>
                                </div>
                                <div class="kpi-item">
                                    <p>34</p>
                                    <p>Pending patients</p>
                                </div>
                                <div class="kpi-item">
                                    <p>23</p>
                                    <p>New Patient Registrations (This Month)</p>
                                </div>
                            </div>
                        </div>
                    
                        <div class="details">
                                <div class="top5_appointment_types">
                                    <h3>Top 5 Most Requested Appointment Types</h3>
                                    <p>1. Routine Check-up: 48 appointments</p>
                                    <p>2. Cleaning: 32 appointments</p>
                                    <p>3. Consultation: 20 appointments</p>
                                    <p>4. Follow-up: 15 appointments</p>
                                    <p>5. Surgical Procedure: 10 appointments</p>
                                </div>
                                <div class="request_order">
                                    <h3>Request Order</h3>
                                    <p>1. Composite Filling Kits</p>
                                    <p>2. Mouth Rinses</p>
                                    <p>3. Dental Cements</p>
                                    <p>4. Dental Bibs</p>
                                    <p>5. Latex Gloves</p>
                                    <p>6. Cotton Rolls</p>
                                </div>
                                <div class="top5_used_items">
                                    <h3>Top 5 Used Items</h3>
                                    <p>1. Latex Gloves: 540 units</p>
                                    <p>2. Cotton Rolls: 300 units</p>
                                    <p>3. Disposable Masks: 250 units</p>
                                    <p>4. Dental Bibs: 200 units</p>
                                    <p>5. Anesthetic Cartridges: 150 units</p>
                                </div>
                                <div class="usage_category">
                                    <h3>Usage by Category (Monthly):</h3>
                                    <p>Consumables: 275 usages</p>
                                    <p>Apparatus: 115 usages</p>
                                    <p>Large Equipment: 60 usages</p>
                                </div>
                            </div>
                        </div>
                    
                    </div>
                </div>
            </div>

            <!-- admin appointment page -->
            <div id="appointment-section" class="section">
                <div class="appointment_wrapper">
                    <!-- Left Panel -->
                    <div class="appointmet_left_panel">
                        <img id="logo" src="icon/logo.png" alt="Logo">
                        <label><a href="#" onclick="showSection('homepage-section')"><img src="icon/dashboard_icon.png" alt="Dashboard"> Dashboard</a></label>
                        <label><a href="#" onclick="showSection('appointment-section')"><img src="icon/Appointment_icon.png" alt="Appointments"> Appointments</a></label>
                        <label><a href="#" onclick="showSection('patient-section')"><img src="icon/Patient_icon.png" alt="Patients"> Patients</a></label>
                        <label><a href="index.php#logot=true" onclick="showSection('login-section')"><img src="icon/signout_icon.png" alt="Sign Out"> Sign Out</a></label>
                    </div>

                    <!-- Right Panel -->
                    <div class="appointment_right_panel">
                        <div id="appointment_header">
                            <div class="sub-navigation">
                                <a href="#" onclick="showRightPanelSection('request_section')">Request</a>
                                <a href="#" onclick="showRightPanelSection('list_view_section')">List View</a>
                                <a href="#" onclick="showRightPanelSection('calendar_view_section')">Calendar View</a>
                            </div>
                            <div id="appointment_info">
                                <p id="fullname"><?php echo htmlspecialchars($fullname); ?></p>
                                <p id="status">Admin</p>
                            </div>
                            <img id="profile_icon" src="<?php echo htmlspecialchars($profile_img); ?> " alt="Profile Icon">
                        </div>
                        <!-- request sectio-->
                        <div id="request_section" class="section">

                             <!-- Request  sections -->
                            <div class="main_content">
                                <h1>Appointment Requests</h1>
                                    <form method="GET" action="" class="search_filter">
                                        <input type="text" name="request_search" placeholder="Search Appointment Request" class="search-bar" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>" />
                                        <button type="submit" class="search_button">SEARCH</button>
                                    </form>
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Patient ID</th>
                                            <th>Patient Name</th>
                                            <th>Payment Type</th>
                                            <th>Payment Status</th>
                                            <th>Requested Date</th>
                                            <th>Requested Time</th>
                                            <th>Date of Request</th>
                                            <th>Requested Dentist</th>
                                            <th>Reason for Booking the Appointment</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                             <?php
                                                if (isset($_GET['request_search']) && !empty($_GET['request_search'])) {
                                                    $requstTerm = $_GET['request_search'];
                                                    searchRequest($requstTerm); 
                                                } else {
                                                    displayAppointmentRequestTable(); 
                                                }
                                            
                                            ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- List View Sectio-->
                        <div id="list_view_section" class="section">
                            <div class="main_content">
                                <h1>Appointment List</h1>
                                    <form method="GET" action="" class="search_filter">
                                        <input type="text"  name="list_search" placeholder="Search appointments" class="search-bar" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>" />
                                        <button type="submit" class="search_button">SEARCH</button>
                                    </form>
                                <table class="appointments-table">
                                <thead>
                                    <tr>
                                        <th>Patient ID</th>
                                        <th>Patient Name</th>
                                        <th>Payment Type</th>
                                        <th>Payment Status</th>
                                        <th>Requested Date</th>
                                        <th>Requested Time</th>
                                        <th>Date of Request</th>
                                        <th>Requested Dentist</th>
                                        <th>Appointment Status</th>
                                        <th>Reason for Booking</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                       if(isset($_GET['list_search']) && !empty($_GET['list_search'])) {
                                            $listTerm = $_GET['list_search'];
                                            searchList($listTerm);
                                       } else{
                                         displayAppointmentListTable();
                                       }
                                        
                                    ?>
                                </tbody>
                            </table>
                            </div>
                        </div>
                

                        <!-- Calendar View Section -->
                        <div id="calendar_view_section" class="section">
                            <h2>Calendar View</h2>
                            <div id="calendar">
                            </div>

                        </div>
                      
                    </div>
                </div>
            </div>

            <div id="patient-section" class="section">
                <div class="wrapper">
                    <!-- Left Panel -->
                    <div class="left_panel">
                        <img id="logo" src="icon/logo.png" alt="Logo">
                        <label><a href="#" onclick="showSection('homepage-section')"><img src="icon/dashboard_icon.png" alt="Dashboard"> Dashboard</a></label>
                        <label><a href="#" onclick="showSection('appointment-section')"><img src="icon/Appointment_icon.png" alt="Appointments"> Appointments</a></label>
                        <label><a href="#" onclick="showSection('patient-section')"><img src="icon/Patient_icon.png" alt="Patients"> Patients</a></label>
                        <label><a href="index.php#logout=true" onclick="showSection('login-section')"><img src="icon/signout_icon.png" alt="Sign Out"> Sign Out</a></label>
                    </div>
        
                    <!-- Right Panel -->
                    <div class="right_panel">
                        <div id="header">
                            <div id="info">
                                <p id="fullname"><?php echo htmlspecialchars($fullname); ?></p>
                                <p id="status">Admin</p>
                            </div>
                            <img id="profile_icon" src="<?php echo htmlspecialchars($profile_img); ?>" alt="Profile Icon">
                        </div>
                        <div id="content_panel">
                            <div class="search-bar">
                                <h1 style="color: hsl(22, 62%, 50%); font-size: 24px;  margin-bottom: 20px;">Patient Info</h1>
                                <form method="GET" action="" class="search_filter">
                                    <input type="text" name="search" placeholder="Search Patient" class="search-bar" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>" />
                                    <button type="submit" class="search_button">SEARCH</button>
                                </form>

                            </div>
                            <button class="add-item" onclick="openAddPatientModal()">Add</button>
                            <main>
                                <div class="patient-content">
                                    <table class="table table-striped">
                                      <thead>
                                        <tr>
                                          <th>Patient ID</th>
                                          <th>Patient Name</th>
                                          <th>Sex</th>
                                          <th>Age</th>
                                          <th>Contact Details</th>
                                          <th>Email</th>
                                          <th>Action</th>
                                        </tr>
                                      </thead>
                                      <tbody>
                                            <?php
                                                // Handle the search term from the GET request (if any)
                                                if (isset($_GET['search']) && !empty($_GET['search'])) {
                                                    $searchTerm = $_GET['search'];
                                                    searchPatients($searchTerm); // Call the search function
                                                } else {
                                                    displayPatientTable(); // Call the display function if no search term
                                                }
                                            
                                            ?>
                                    </table>
                                  
                                
                                    <!-- View Modal -->
                                    <div id="viewModal" class="modal">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5>View Patient</h5>
                                                <span class="close" onclick="closeModal('viewModal')">&times;</span>
                                            </div>
                                            <div class="modal-body">
                                                <div class="profile-picture">
                                                    <img id="profile-image" src="#" alt="Profile Picture" width="100" height="100">
                                                </div>
                                                <form>
                                                    <label>Patient Name:</label>
                                                    <input type="text" readonly><br><br>
                                                    <label>Sex:</label>
                                                    <select disabled>
                                                        <option value="M">M</option>
                                                        <option value="F">F</option>
                                                    </select><br><br>
                                                    <label>Age:</label>
                                                    <input type="number" readonly><br><br>
                                                    <label>Contact Details:</label>
                                                    <input type="text" readonly><br><br>
                                                    <label>Email:</label>
                                                    <input type="email" readonly><br><br>
                                                    <label>Password:</label>
                                                    <input type="password" readonly><br>
                                                </form>
                                            </div>
                                            <div class="modal-footer">
                                                <button class="btn btn-secondary" onclick="closeModal('viewModal')">Close</button>
                                            </div>
                                        </div>
                                    </div>

                                
                                    <!-- Edit Modal -->
                                    <div id="editModal" class="modal">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5>Edit Patient</h5>
                                                <span class="close" onclick="closeModal('editModal')">&times;</span>
                                            </div>
                                            <div class="modal-body">
                                                <div class="profile-picture">
                                                         <img id="profile-image" src="default-profile.png" alt="Profile Image" style="width: 100px; height: 100px;">
                                                    <div class="file-input">
                                                        <input type="file" id="profile-file" onchange="updateProfileImage(this)" style="display: none;">
                                                        <label for="profile-file" class="btn btn-secondary">Choose File</label>
                                                    </div>
                                                </div>
                                                <form id="editPatientForm" method="POST">
                                                    <label>Patient Name:</label>
                                                    <input type="text" value="Amanda R. Flores"><br><br>
                                
                                                    <label>Sex:</label>
                                                    <select>
                                                        <option selected>F</option>
                                                        <option>M</option>
                                                    </select><br><br>
                                
                                                    <label>Age:</label>
                                                    <input type="number" value="34"><br><br>
                                
                                                    <label>Contact Details:</label>
                                                    <input type="text" value="0930-555-0123"><br><br>
                                
                                                    <label>Email:</label>
                                                    <input type="email" value="amanda@gmail.com"><br><br>
                                
                                                    <label>Password:</label>
                                                    <input type="password" value="password123"><br>
                                                </form>
                                            </div>
                                            <div class="modal-footer" id="editPatientForm" method="POST">
                                                <button class="btn btn-secondary" onclick="closeModal('editModal')">Close</button>
                                                <button class="btn btn-primary"  type="submit">Save Changes</button>
                                            </div>
                                        </div>
                                    </div>
                                
                                    <!-- Delete Modal -->
                                    <div id="deleteModal" class="modal">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5>Delete Patient</h5>
                                                <span class="close" onclick="closeModal('deleteModal')">&times;</span>
                                            </div>
                                            <div class="modal-body">
                                                Are you sure you want to delete this patient?
                                            </div>
                                            <div class="modal-footer">
                                                <button class="btn btn-secondary" onclick="closeModal('deleteModal')">No</button>
                                                <button class="btn btn-danger">Yes</button>
                                            </div>
                                        </div>
                                    </div>


                                    <!---Add Patient Modal-->
                                    <div id="addPatientModal" class="modal">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5>Add Patient</h5>
                                                <span class="close" onclick="closeModal('addPatientModal')">&times;</span>
                                            </div>
                                            <div class="modal-body">
                                                <div class="profile-picture">
                                                     <img id="profile-image" src="default-profile.png" style="width: 100px; height: 100px;">
                                                    <div class="file-input">
                                                        <input type="file" id="profile-file" onchange="updateProfileImage(this)" style="display: none;">
                                                        <label for="profile-file" class="btn btn-secondary">Choose File</label>
                                                    </div>
                                                </div>
                                                <form>
                                                    <label>Patient First Name:</label>
                                                    <input type="text" id="patient-firstname"><br><br>
                                    
                                                    <label>Patient Last Name:</label>
                                                    <input type="text" id="patient-lastname"><br><br>
                                    
                                                    <label>Contact Details:</label>
                                                    <input type="text" id="patient-contact"><br><br>
                                    
                                                    <label>Email:</label>
                                                    <input type="email" id="patient-email"><br><br>

                                                    <label>Gender:</label>
                                                    <select id="patient-gender">
                                                        <option value="F">F</option>
                                                        <option value="M">M</option>
                                                    </select><br><br>

                                                    <label>Age:</label>
                                                    <input type="text" id="patient-age"><br><br>
                                    
                                                    <label>Password:</label>
                                                    <input type="password" id="patient-password"><br><br>
                                    

                                                </form>
                                            </div>
                                            <div class="modal-footer">
                                                <button class="btn btn-secondary" onclick="closeModal('addPatientModal')">Close</button>
                                                <button class="btn btn-primary" onclick="savePatientData()">Save Changes</button>
                                            </div>
                                        </div>
                                    </div>
                            </main>    
                        </div>
                    </div>    
                </div>       
           </div>            
      </div>

        <!-- Script for  display div in one frame-->
        <script>
            // Function to display a specific section and hide others
            function showSection(sectionId) {
                document.querySelectorAll('.section').forEach(section => {
                    section.classList.remove('active');
                });
                document.getElementById(sectionId).classList.add('active');
            }

            // Function to handle the transition to the login section
            function proceedToLogin() {
                document.getElementById('successAlert').style.display = 'none'; // hide alert message
                showSection('login-section'); // switch to the login
            }

        </script>
        
        <!-- Script for display div in one frame (Appointment Section Specific) -->
        <script>
            // Function to display a specific section inside the right panel and hide others
            function showRightPanelSection(sectionId) {
                // Hide all sections in the right panel
                const sections = document.querySelectorAll('.appointment_right_panel .section');
                sections.forEach(section => {
                    section.style.display = 'none'; // Hide each section
                });
        
                // Show the selected section inside the right panel
                const selectedSection = document.getElementById(sectionId);
                if (selectedSection) {
                    selectedSection.style.display = 'block'; // Show the selected section
                }
            }
        
            // Initialize the sections to be hidden on page load and show the first section (Request)
            window.onload = function() {
                // function to hide all sections initially in the right panel
                const sections = document.querySelectorAll('.appointment_right_panel .section');
                sections.forEach(section => {
                    section.style.display = 'none';
                });
        
                // function to show the default section (Request section)
                const defaultSection = document.getElementById('request_section');
                if (defaultSection) {
                    defaultSection.style.display = 'block';
                }
            }
        </script>
        

        

        <!-- Script for chart-->>
        <script>
                    const ctx = document.getElementById('incomeChart').getContext('2d');
                    let incomeChart;
            
                    const data = {
                        yearly: {
                            labels: ['2018', '2019', '2020', '2021', '2022'],
                            datasets: [{
                                label: 'Yearly Income',
                                data: [50000, 55000, 60000, 65000, 70000],
                                backgroundColor: 'skyblue'
                            }]
                        },
                        monthly: {
                            labels: ['2018', '2019', '2020', '2021', '2022'],
                            datasets: [{
                                label: 'Monthly Income',
                                data: [50000 / 12, 55000 / 12, 60000 / 12, 65000 / 12, 70000 / 12],
                                backgroundColor: 'lightgreen'
                            }]
                        },
                        weekly: {
                            labels: ['2018', '2019', '2020', '2021', '2022'],
                            datasets: [{
                                label: 'Weekly Income',
                                data: [50000 / 52, 55000 / 52, 60000 / 52, 65000 / 52, 70000 / 52],
                                backgroundColor: 'lightcoral'
                            }]
                        }
                    };
            
                    function showChart(period) {
                        if (incomeChart) {
                            incomeChart.destroy();
                        }
                        incomeChart = new Chart(ctx, {
                            type: 'bar',
                            data: data[period],
                            options: {
                                responsive: true,
                                scales: {
                                    y: {
                                        beginAtZero: true
                                    }
                                }
                            }
                        });
                    }
            
                    // Show yearly chart by default
                    showChart('yearly');
                </script>

        <!-- Script for js calendar-->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var calendarEl = document.getElementById('calendar');
                var calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth',
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,timeGridDay'
                    },
                    nowIndicator: true, // This will add the current time indicator
                    events: <?php echo json_encode($upcoming_appointments); ?>
                });
                calendar.render();
            });
        </script>
         
        <!--JS for modal-->
        <script>
        function openModal(id) {
            document.getElementById(id).style.display = "block";
        }
    
        function closeModal(id) {
            document.getElementById(id).style.display = "none";
        }
        </script>

    <!-- JS for patient view,edit,delete,add -->
    <script>
        // function for view
        function viewPatient(patientId) {
            $.ajax({
                url: '',
                method: 'POST',
                data: {action: 'view', patientId: patientId},
                dataType: 'json',
                success: function(patient) {
                    if (patient) {
                        // Populate view modal fields
                        $('#profile-image').attr('src', patient.Patient_Img || 'default-profile.png');
                        $('#viewModal input[type="text"]').val(patient.Patient_Firstname + ' ' + patient.Patient_Lastname);
                        $('#viewModal select').val(patient.Patient_Gender);
                        $('#viewModal input[type="number"]').val(patient.Patient_Age);
                        $('#viewModal input[type="email"]').val(patient.Patient_Email);
                        $('#viewModal input[type="text"]:eq(1)').val(patient.Patient_Contact);
                        $('#viewModal input[type="password"]').val('****');

                        $('#viewModal').show();
                    }
                },
                error: function() {
                    alert('Failed to fetch patient details');
                }
            });
        }
        // function for edit
        function editPatient(patientId) {
            $.ajax({
                url: '', 
                method: 'POST',
                data: { action: 'view', patientId: patientId },
                dataType: 'json',
                success: function(patient) {
                    if (patient) {
                        // Populate edit modal fields
                        $('#editModal input[type="text"]:first').val(patient.Patient_Firstname + ' ' + patient.Patient_Lastname);
                        $('#editModal select').val(patient.Patient_Gender);
                        $('#editModal input[type="number"]').val(patient.Patient_Age);
                        $('#editModal input[type="text"]:eq(1)').val(patient.Patient_Contact);
                        $('#editModal input[type="email"]').val(patient.Patient_Email);
                        $('#patient-image').attr('src', patient.Patient_Img || 'default-profile.png');
                        
                        // Add a hidden input to store patient ID for submission
                        $('<input>').attr({
                            type: 'hidden',
                            id: 'edit-patient-id',
                            name: 'patientId',
                            value: patient.Patient_ID
                        }).appendTo('#editPatientForm');

                        $('#editModal').show();
                    } else {
                        alert('Failed to fetch patient details');
                    }
                }
            });
        }
        // function for save edit 
        function saveEditPatientData() {
            let formData = new FormData();
            formData.append('action', 'edit');
            formData.append('patientId', $('#edit-patient-id').val());
            
            // Split name into first and last name
            let fullName = $('#editModal input[type="text"]:first').val().split(' ');
            formData.append('firstname', fullName[0]);
            formData.append('lastname', fullName.slice(1).join(' '));
            
            formData.append('email', $('#editModal input[type="email"]').val());
            formData.append('contact', $('#editModal input[type="text"]:eq(1)').val());
            formData.append('gender', $('#editModal select').val());
            formData.append('age', $('#editModal input[type="number"]').val());
            formData.append('password', $('#editModal input[type="password"]').val());
            
            // Handle image upload
            let imageFile = $('#profile-file')[0].files[0];
            if (imageFile) {
                formData.append('image', imageFile);
            }

            $.ajax({
                url: '', 
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    const result = JSON.parse(response);
                    if (result.success) {
                        location.reload(); 
                    } else {
                        alert('Failed to update patient');
                    }
                },
                error: function() {
                    alert('Error occurred while updating the patient.');
                }
            });
        }
        // function for profile  
        function updateProfileImage(input) {
            if (input.files && input.files[0]) {
                let reader = new FileReader();
                reader.onload = function(e) {
                    $('#profile-image').attr('src', e.target.result);
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        // function for delete
        function deletePatient(patientId) {
            if (confirm('Are you sure you want to delete this patient?')) {
                $.ajax({
                    url: '',
                    method: 'POST',
                    data: { action: 'delete', patientId: patientId },
                    success: function(response) {
                        const result = JSON.parse(response);
                        if (result.success) {
                            alert('Patient deleted successfully');
                            location.reload(); // Refresh page to update table
                        } else {
                            alert('Failed to delete patient');
                        }
                    },
                    error: function() {
                        alert('Error occurred while deleting the patient.');
                    }
                });
            }
        }
        
        // open modal of add patient
        function openAddPatientModal() {
            // Clear previous values in the modal form
            $('#patient-firstname').val('');
            $('#patient-lastname').val('');
            $('#patient-email').val('');
            $('#patient-contact').val('');
            $('#patient-password').val('');
            $('#patient-gender').val(''); 
            $('#patient-age').val('');
            $('#profile-image').attr('src', 'default-profile.png'); // Reset profile image

            $('#addPatientModal').show(); // Show the modal
        }

        // function for save patient data
        function savePatientData() {
            let formData = new FormData();
            formData.append('action', 'add');
            formData.append('firstname', $('#patient-firstname').val());
            formData.append('lastname', $('#patient-lastname').val());
            formData.append('email', $('#patient-email').val());
            formData.append('contact', $('#patient-contact').val());
            formData.append('password', $('#patient-password').val());
            formData.append('gender', $('#patient-gender').val());
            formData.append('age', $('#patient-age').val());
            
            // Handle image upload
            let imageFile = $('#profile-file')[0].files[0];
            if (imageFile) {
                formData.append('image', imageFile);
            }

            $.ajax({
                url: '', 
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    const result = JSON.parse(response);
                    if (result.success) {
                        location.reload(); 
                    } else {
                        alert('Failed to add patient');
                    }
                },
                error: function() {
                    alert('Error occurred while adding the patient.');
                }
            });
        }


        // Modify the edit modal save button to call saveEditPatientData
        $(document).ready(function() {
            $('#editModal .btn-primary').attr('onclick', 'saveEditPatientData()');
        });
    </script>
</body>
</html>