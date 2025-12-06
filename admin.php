<?php
session_start();

// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "abbott_tuitions";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error_message = "";
$success_message = "";

// Handle Login
if (isset($_POST['login'])) {
    $admin_username = $conn->real_escape_string($_POST['username']);
    $admin_password = $_POST['password'];
    
    $sql = "SELECT * FROM admin_users WHERE username = '$admin_username'";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($admin_password, $row['password_hash'])) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_username'] = $admin_username;
            $_SESSION['admin_name'] = $row['full_name'];
        } else {
            $error_message = "Invalid username or password!";
        }
    } else {
        $error_message = "Invalid username or password!";
    }
}

// Handle Logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: admin.php");
    exit();
}

// Handle Delete Student
if (isset($_GET['delete_student'])) {
    $student_id = intval($_GET['delete_student']);
    $conn->query("DELETE FROM students WHERE id = $student_id");
    $success_message = "Student record deleted successfully!";
}

// Handle Delete Teacher
if (isset($_GET['delete_teacher'])) {
    $teacher_id = intval($_GET['delete_teacher']);
    $conn->query("DELETE FROM teachers WHERE id = $teacher_id");
    $success_message = "Teacher record deleted successfully!";
}

// Handle Update Student Status
if (isset($_POST['update_student_status'])) {
    $student_id = intval($_POST['student_id']);
    $status = $conn->real_escape_string($_POST['status']);
    $conn->query("UPDATE students SET status = '$status' WHERE id = $student_id");
    $success_message = "Student status updated successfully!";
}

// Handle Update Teacher Status
if (isset($_POST['update_teacher_status'])) {
    $teacher_id = intval($_POST['teacher_id']);
    $status = $conn->real_escape_string($_POST['status']);
    $conn->query("UPDATE teachers SET status = '$status' WHERE id = $teacher_id");
    $success_message = "Teacher status updated successfully!";
}

// Handle Create Match
if (isset($_POST['create_match'])) {
    $student_id = intval($_POST['match_student_id']);
    $teacher_id = intval($_POST['match_teacher_id']);
    
    $conn->query("INSERT INTO matches (student_id, teacher_id) VALUES ($student_id, $teacher_id)");
    $conn->query("UPDATE students SET status = 'matched' WHERE id = $student_id");
    $conn->query("UPDATE teachers SET status = 'active' WHERE id = $teacher_id");
    $success_message = "Match created successfully!";
}

// Check if admin is logged in
$is_logged_in = isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;

// Get filter parameters
$student_filter = isset($_GET['student_filter']) ? $_GET['student_filter'] : 'all';
$teacher_filter = isset($_GET['teacher_filter']) ? $_GET['teacher_filter'] : 'all';
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';

// Fetch Students with filters
$student_query = "SELECT * FROM students WHERE 1=1";
if ($student_filter != 'all') {
    $student_query .= " AND status = '$student_filter'";
}
if ($search) {
    $student_query .= " AND (full_name LIKE '%$search%' OR email LIKE '%$search%' OR phone LIKE '%$search%' OR student_name LIKE '%$search%')";
}
$student_query .= " ORDER BY submitted_at DESC";
$students = $conn->query($student_query);

// Fetch Teachers with filters
$teacher_query = "SELECT * FROM teachers WHERE 1=1";
if ($teacher_filter != 'all') {
    $teacher_query .= " AND status = '$teacher_filter'";
}
if ($search) {
    $teacher_query .= " AND (full_name LIKE '%$search%' OR email LIKE '%$search%' OR phone LIKE '%$search%')";
}
$teacher_query .= " ORDER BY submitted_at DESC";
$teachers = $conn->query($teacher_query);

// Fetch Matches
$matches = $conn->query("SELECT m.*, s.student_name, s.full_name as parent_name, t.full_name as teacher_name 
                         FROM matches m 
                         JOIN students s ON m.student_id = s.id 
                         JOIN teachers t ON m.teacher_id = t.id 
                         ORDER BY m.match_date DESC");

// Get statistics
$total_students = $conn->query("SELECT COUNT(*) as count FROM students")->fetch_assoc()['count'];
$total_teachers = $conn->query("SELECT COUNT(*) as count FROM teachers")->fetch_assoc()['count'];
$pending_students = $conn->query("SELECT COUNT(*) as count FROM students WHERE status='pending'")->fetch_assoc()['count'];
$pending_teachers = $conn->query("SELECT COUNT(*) as count FROM teachers WHERE status='pending'")->fetch_assoc()['count'];
$total_matches = $conn->query("SELECT COUNT(*) as count FROM matches")->fetch_assoc()['count'];

// For modals - fetch all data
$all_students = $conn->query("SELECT * FROM students");
$all_teachers = $conn->query("SELECT * FROM teachers");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Admin Dashboard - Abbott Home Tuitions</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    
    <link href="img/favicon.ico" rel="icon">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet"> 
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --primary: #FF6600;
            --secondary: #FEF5F1;
            --light: #F0F0F0;
            --dark: #1C1C1C;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }

        .text-primary { color: var(--primary) !important; }
        .bg-primary { background-color: var(--primary) !important; }
        .btn-primary {
            background-color: var(--primary);
            border-color: var(--primary);
        }
        .btn-primary:hover {
            background-color: #e55a00;
            border-color: #e55a00;
        }

        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #FF6600 0%, #FF8533 100%);
        }

        .login-card {
            background: white;
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            max-width: 450px;
            width: 100%;
        }

        .dashboard-header {
            background: linear-gradient(135deg, #FF6600 0%, #FF8533 100%);
            color: white;
            padding: 20px 0;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.15);
        }

        .stat-icon {
            font-size: 40px;
            color: var(--primary);
        }

        .data-table {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .table thead th {
            background-color: var(--primary);
            color: white;
            border: none;
        }

        .badge-pending { background-color: #ffc107; }
        .badge-matched { background-color: #28a745; }
        .badge-completed { background-color: #17a2b8; }
        .badge-active { background-color: #28a745; }
        .badge-inactive { background-color: #6c757d; }

        .filter-section {
            background: white;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .modal-header {
            background-color: var(--primary);
            color: white;
        }

        .nav-tabs .nav-link.active {
            background-color: var(--primary);
            color: white !important;
            border-color: var(--primary);
        }

        .nav-tabs .nav-link {
            color: var(--primary);
        }
    </style>
</head>

<body>
    <?php if (!$is_logged_in): ?>
        <!-- Login Page -->
        <div class="login-container">
            <div class="login-card">
                <div class="text-center mb-4">
                    <h2 class="mb-2" style="font-weight: bold;"><span class="text-primary">Abbott</span> Tuitions</h2>
                    <p class="text-muted">Admin Panel Login</p>
                </div>

                <?php if($error_message): ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $error_message; ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="form-group">
                        <label>Username</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-user"></i></span>
                            </div>
                            <input type="text" class="form-control" name="username" required placeholder="Enter username">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-lock"></i></span>
                            </div>
                            <input type="password" class="form-control" name="password" required placeholder="Enter password">
                        </div>
                    </div>
                    <button type="submit" name="login" class="btn btn-primary btn-block btn-lg">
                        <i class="fa fa-sign-in-alt mr-2"></i>Login
                    </button>
                    <div class="text-center mt-3">
                        <small class="text-muted"></small>
                    </div>
                </form>
            </div>
        </div>
    <?php else: ?>
        <!-- Dashboard -->
        <div class="dashboard-header">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h3 class="mb-0"><i class="fa fa-tachometer-alt mr-2"></i>Admin Dashboard</h3>
                        <small>Welcome, <?php echo $_SESSION['admin_name']; ?></small>
                    </div>
                    <div class="col-md-6 text-right">
                        <a href="index.html" class="btn btn-light mr-2"><i class="fa fa-home mr-2"></i>Home</a>
                        <a href="?logout=1" class="btn btn-danger"><i class="fa fa-sign-out-alt mr-2"></i>Logout</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid py-4">
            <?php if($success_message): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php echo $success_message; ?>
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
            <?php endif; ?>

            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1">Total Students</h6>
                                <h2 class="mb-0"><?php echo $total_students; ?></h2>
                            </div>
                            <div class="stat-icon"><i class="fa fa-user-graduate"></i></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1">Total Teachers</h6>
                                <h2 class="mb-0"><?php echo $total_teachers; ?></h2>
                            </div>
                            <div class="stat-icon"><i class="fa fa-chalkboard-teacher"></i></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1">Pending Requests</h6>
                                <h2 class="mb-0"><?php echo $pending_students + $pending_teachers; ?></h2>
                            </div>
                            <div class="stat-icon"><i class="fa fa-clock"></i></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1">Total Matches</h6>
                                <h2 class="mb-0"><?php echo $total_matches; ?></h2>
                            </div>
                            <div class="stat-icon"><i class="fa fa-handshake"></i></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter and Search -->
            <div class="filter-section">
                <form method="GET" action="" class="form-inline">
                    <div class="form-group mr-3">
                        <label class="mr-2">Search:</label>
                        <input type="text" class="form-control" name="search" placeholder="Name, Email, Phone..." value="<?php echo htmlspecialchars($search); ?>">
                    </div>
                    <div class="form-group mr-3">
                        <label class="mr-2">Student Status:</label>
                        <select class="form-control" name="student_filter">
                            <option value="all" <?php echo $student_filter == 'all' ? 'selected' : ''; ?>>All</option>
                            <option value="pending" <?php echo $student_filter == 'pending' ? 'selected' : ''; ?>>Pending</option>
                            <option value="matched" <?php echo $student_filter == 'matched' ? 'selected' : ''; ?>>Matched</option>
                            <option value="completed" <?php echo $student_filter == 'completed' ? 'selected' : ''; ?>>Completed</option>
                        </select>
                    </div>
                    <div class="form-group mr-3">
                        <label class="mr-2">Teacher Status:</label>
                        <select class="form-control" name="teacher_filter">
                            <option value="all" <?php echo $teacher_filter == 'all' ? 'selected' : ''; ?>>All</option>
                            <option value="pending" <?php echo $teacher_filter == 'pending' ? 'selected' : ''; ?>>Pending</option>
                            <option value="active" <?php echo $teacher_filter == 'active' ? 'selected' : ''; ?>>Active</option>
                            <option value="inactive" <?php echo $teacher_filter == 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-filter mr-2"></i>Filter</button>
                    <a href="admin.php" class="btn btn-secondary ml-2"><i class="fa fa-redo mr-2"></i>Reset</a>
                </form>
            </div>

            <!-- Tabs -->
            <ul class="nav nav-tabs mb-3" id="adminTabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="students-tab" data-toggle="tab" href="#students">
                        <i class="fa fa-user-graduate mr-2"></i>Students (<?php echo $students->num_rows; ?>)
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="teachers-tab" data-toggle="tab" href="#teachers">
                        <i class="fa fa-chalkboard-teacher mr-2"></i>Teachers (<?php echo $teachers->num_rows; ?>)
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="matches-tab" data-toggle="tab" href="#matches">
                        <i class="fa fa-handshake mr-2"></i>Matches (<?php echo $total_matches; ?>)
                    </a>
                </li>
            </ul>

            <div class="tab-content">
                <!-- Students Tab -->
                <div class="tab-pane fade show active" id="students">
                    <div class="data-table">
                        <h5 class="mb-3"><i class="fa fa-user-graduate text-primary mr-2"></i>Student Registrations</h5>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Parent</th>
                                        <th>Student</th>
                                        <th>Grade</th>
                                        <th>Subjects</th>
                                        <th>Contact</th>
                                        <th>Budget</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $students->data_seek(0);
                                    while($student = $students->fetch_assoc()): 
                                    ?>
                                    <tr>
                                        <td><?php echo $student['id']; ?></td>
                                        <td><?php echo htmlspecialchars($student['full_name']); ?></td>
                                        <td><?php echo htmlspecialchars($student['student_name']); ?></td>
                                        <td><?php echo htmlspecialchars($student['student_grade']); ?></td>
                                        <td><small><?php echo htmlspecialchars(substr($student['subjects'], 0, 25)) . '...'; ?></small></td>
                                        <td><small><?php echo htmlspecialchars($student['phone']); ?></small></td>
                                        <td><small>PKR <?php echo number_format($student['budget_min']); ?>-<?php echo number_format($student['budget_max']); ?></small></td>
                                        <td><span class="badge badge-<?php echo $student['status']; ?>"><?php echo ucfirst($student['status']); ?></span></td>
                                        <td>
                                            <button class="btn btn-sm btn-info" onclick='viewStudent(<?php echo json_encode($student); ?>)'>
                                                <i class="fa fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-success" onclick="updateStudentStatus(<?php echo $student['id']; ?>, '<?php echo $student['status']; ?>')">
                                                <i class="fa fa-edit"></i>
                                            </button>
                                            <a href="?delete_student=<?php echo $student['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this record?')">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Teachers Tab -->
                <div class="tab-pane fade" id="teachers">
                    <div class="data-table">
                        <h5 class="mb-3"><i class="fa fa-chalkboard-teacher text-primary mr-2"></i>Teacher Registrations</h5>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Qualification</th>
                                        <th>Exp</th>
                                        <th>Subjects</th>
                                        <th>Contact</th>
                                        <th>Rate</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $teachers->data_seek(0);
                                    while($teacher = $teachers->fetch_assoc()): 
                                    ?>
                                    <tr>
                                        <td><?php echo $teacher['id']; ?></td>
                                        <td><?php echo htmlspecialchars($teacher['full_name']); ?></td>
                                        <td><?php echo htmlspecialchars($teacher['qualification']); ?></td>
                                        <td><?php echo $teacher['experience_years']; ?>y</td>
                                        <td><small><?php echo htmlspecialchars(substr($teacher['subjects'], 0, 25)) . '...'; ?></small></td>
                                        <td><small><?php echo htmlspecialchars($teacher['phone']); ?></small></td>
                                        <td><small>PKR <?php echo number_format($teacher['hourly_rate']); ?></small></td>
                                        <td><span class="badge badge-<?php echo $teacher['status']; ?>"><?php echo ucfirst($teacher['status']); ?></span></td>
                                        <td>
                                            <button class="btn btn-sm btn-info" onclick='viewTeacher(<?php echo json_encode($teacher); ?>)'>
                                                <i class="fa fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-success" onclick="updateTeacherStatus(<?php echo $teacher['id']; ?>, '<?php echo $teacher['status']; ?>')">
                                                <i class="fa fa-edit"></i>
                                            </button>
                                            <a href="?delete_teacher=<?php echo $teacher['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this record?')">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Matches Tab -->
                <div class="tab-pane fade" id="matches">
                    <div class="data-table">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0"><i class="fa fa-handshake text-primary mr-2"></i>Student-Teacher Matches</h5>
                            <button class="btn btn-primary" data-toggle="modal" data-target="#createMatchModal">
                                <i class="fa fa-plus mr-2"></i>Create Match
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Student</th>
                                        <th>Parent</th>
                                        <th>Teacher</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $matches->data_seek(0);
                                    while($match = $matches->fetch_assoc()): 
                                    ?>
                                    <tr>
                                        <td><?php echo $match['id']; ?></td>
                                        <td><?php echo htmlspecialchars($match['student_name']); ?></td>
                                        <td><?php echo htmlspecialchars($match['parent_name']); ?></td>
                                        <td><?php echo htmlspecialchars($match['teacher_name']); ?></td>
                                        <td><?php echo date('M d, Y', strtotime($match['match_date'])); ?></td>
                                        <td><span class="badge badge-<?php echo $match['status']; ?>"><?php echo ucfirst($match['status']); ?></span></td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- View Student Modal -->
        <div class="modal fade" id="viewStudentModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="fa fa-user-graduate mr-2"></i>Student Details</h5>
                        <button type="button" class="close" data-dismiss="modal" style="color: white;"><span>&times;</span></button>
                    </div>
                    <div class="modal-body" id="studentDetails"></div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- View Teacher Modal -->
        <div class="modal fade" id="viewTeacherModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="fa fa-chalkboard-teacher mr-2"></i>Teacher Details</h5>
                        <button type="button" class="close" data-dismiss="modal" style="color: white;"><span>&times;</span></button>
                    </div>
                    <div class="modal-body" id="teacherDetails"></div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Update Student Status Modal -->
        <div class="modal fade" id="updateStudentStatusModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="fa fa-edit mr-2"></i>Update Student Status</h5>
                        <button type="button" class="close" data-dismiss="modal" style="color: white;"><span>&times;</span></button>
                    </div>
                    <form method="POST">
                        <div class="modal-body">
                            <input type="hidden" name="student_id" id="update_student_id">
                            <div class="form-group">
                                <label>Select Status</label>
                                <select class="form-control" name="status" id="update_student_status">
                                    <option value="pending">Pending</option>
                                    <option value="matched">Matched</option>
                                    <option value="completed">Completed</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="submit" name="update_student_status" class="btn btn-primary">Update Status</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Update Teacher Status Modal -->
        <div class="modal fade" id="updateTeacherStatusModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="fa fa-edit mr-2"></i>Update Teacher Status</h5>
                        <button type="button" class="close" data-dismiss="modal" style="color: white;"><span>&times;</span></button>
                    </div>
                    <form method="POST">
                        <div class="modal-body">
                            <input type="hidden" name="teacher_id" id="update_teacher_id">
                            <div class="form-group">
                                <label>Select Status</label>
                                <select class="form-control" name="status" id="update_teacher_status">
                                    <option value="pending">Pending</option>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="submit" name="update_teacher_status" class="btn btn-primary">Update Status</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Create Match Modal -->
        <div class="modal fade" id="createMatchModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="fa fa-handshake mr-2"></i>Create New Match</h5>
                        <button type="button" class="close" data-dismiss="modal" style="color: white;"><span>&times;</span></button>
                    </div>
                    <form method="POST">
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Select Student</label>
                                <select class="form-control" name="match_student_id" required>
                                    <option value="">Choose a student...</option>
                                    <?php
                                    $students_list = $conn->query("SELECT id, full_name, student_name, subjects FROM students WHERE status='pending'");
                                    while($s = $students_list->fetch_assoc()):
                                    ?>
                                        <option value="<?php echo $s['id']; ?>">
                                            <?php echo htmlspecialchars($s['student_name'] . ' (Parent: ' . $s['full_name'] . ') - ' . $s['subjects']); ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Select Teacher</label>
                                <select class="form-control" name="match_teacher_id" required>
                                    <option value="">Choose a teacher...</option>
                                    <?php
                                    $teachers_list = $conn->query("SELECT id, full_name, subjects, hourly_rate FROM teachers WHERE status='pending' OR status='active'");
                                    while($t = $teachers_list->fetch_assoc()):
                                    ?>
                                        <option value="<?php echo $t['id']; ?>">
                                            <?php echo htmlspecialchars($t['full_name'] . ' - ' . $t['subjects'] . ' (PKR ' . number_format($t['hourly_rate']) . '/hr)'); ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="alert alert-info">
                                <small><i class="fa fa-info-circle mr-2"></i>Creating a match will update student status to "Matched" and teacher status to "Active"</small>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="submit" name="create_match" class="btn btn-primary">Create Match</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    <?php endif; ?>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>

    <script>
        // View Student Details
        function viewStudent(student) {
            const details = `
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-primary">Parent/Guardian Information</h6>
                        <p><strong>Name:</strong> ${student.full_name}</p>
                        <p><strong>Email:</strong> ${student.email}</p>
                        <p><strong>Phone:</strong> ${student.phone}</p>
                        <p><strong>Address:</strong> ${student.address}</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-primary">Student Information</h6>
                        <p><strong>Student Name:</strong> ${student.student_name}</p>
                        <p><strong>Grade:</strong> ${student.student_grade}</p>
                        <p><strong>Status:</strong> <span class="badge badge-${student.status}">${student.status}</span></p>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-12">
                        <h6 class="text-primary">Tuition Requirements</h6>
                        <p><strong>Subjects:</strong> ${student.subjects}</p>
                        <p><strong>Frequency:</strong> ${student.frequency}</p>
                        <p><strong>Duration:</strong> ${student.duration}</p>
                        <p><strong>Preferred Timing:</strong> ${student.preferred_timing}</p>
                        <p><strong>Budget:</strong> PKR ${Number(student.budget_min).toLocaleString()} - PKR ${Number(student.budget_max).toLocaleString()}</p>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-12">
                        <h6 class="text-primary">Additional Information</h6>
                        <p><strong>Learning Goals:</strong> ${student.learning_goals || 'N/A'}</p>
                        <p><strong>Additional Info:</strong> ${student.additional_info || 'N/A'}</p>
                        <p><strong>Submitted:</strong> ${new Date(student.submitted_at).toLocaleString()}</p>
                    </div>
                </div>
            `;
            document.getElementById('studentDetails').innerHTML = details;
            $('#viewStudentModal').modal('show');
        }

        // View Teacher Details
        function viewTeacher(teacher) {
            const details = `
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-primary">Personal Information</h6>
                        <p><strong>Name:</strong> ${teacher.full_name}</p>
                        <p><strong>Email:</strong> ${teacher.email}</p>
                        <p><strong>Phone:</strong> ${teacher.phone}</p>
                        <p><strong>Address:</strong> ${teacher.address}</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-primary">Professional Details</h6>
                        <p><strong>Qualification:</strong> ${teacher.qualification}</p>
                        <p><strong>Experience:</strong> ${teacher.experience_years} years</p>
                        <p><strong>Status:</strong> <span class="badge badge-${teacher.status}">${teacher.status}</span></p>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-12">
                        <h6 class="text-primary">Teaching Information</h6>
                        <p><strong>Subjects:</strong> ${teacher.subjects}</p>
                        <p><strong>Available Days:</strong> ${teacher.available_days}</p>
                        <p><strong>Preferred Timing:</strong> ${teacher.preferred_timing}</p>
                        <p><strong>Hourly Rate:</strong> PKR ${Number(teacher.hourly_rate).toLocaleString()}</p>
                        <p><strong>Preferred Gender:</strong> ${teacher.preferred_gender || 'No Preference'}</p>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-12">
                        <h6 class="text-primary">Additional Information</h6>
                        <p>${teacher.additional_info || 'N/A'}</p>
                        <p><strong>Submitted:</strong> ${new Date(teacher.submitted_at).toLocaleString()}</p>
                    </div>
                </div>
            `;
            document.getElementById('teacherDetails').innerHTML = details;
            $('#viewTeacherModal').modal('show');
        }

        // Update Student Status
        function updateStudentStatus(id, currentStatus) {
            document.getElementById('update_student_id').value = id;
            document.getElementById('update_student_status').value = currentStatus;
            $('#updateStudentStatusModal').modal('show');
        }

        // Update Teacher Status
        function updateTeacherStatus(id, currentStatus) {
            document.getElementById('update_teacher_id').value = id;
            document.getElementById('update_teacher_status').value = currentStatus;
            $('#updateTeacherStatusModal').modal('show');
        }

        // Auto-hide alerts after 5 seconds
        $(document).ready(function() {
            setTimeout(function() {
                $('.alert').fadeOut('slow');
            }, 5000);
        });
    </script>
</body>
</html>
<?php $conn->close(); ?>