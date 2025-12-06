<?php
// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "abbott_tuitions";

$success_message = "";
$error_message = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    // Check connection
    if ($conn->connect_error) {
        $error_message = "Connection failed: " . $conn->connect_error;
    } else {
        // Sanitize and get form data
        $full_name = $conn->real_escape_string($_POST['full_name']);
        $email = $conn->real_escape_string($_POST['email']);
        $phone = $conn->real_escape_string($_POST['phone']);
        $address = $conn->real_escape_string($_POST['address']);
        $qualification = $conn->real_escape_string($_POST['qualification']);
        $experience_years = intval($_POST['experience_years']);
        $subjects = isset($_POST['subjects']) ? implode(', ', $_POST['subjects']) : '';
        $available_days = isset($_POST['available_days']) ? implode(', ', $_POST['available_days']) : '';
        $preferred_timing = $conn->real_escape_string($_POST['preferred_timing']);
        $hourly_rate = floatval($_POST['hourly_rate']);
        $preferred_gender = $conn->real_escape_string($_POST['preferred_gender']);
        $additional_info = $conn->real_escape_string($_POST['additional_info']);
        
        // Insert into database
        $sql = "INSERT INTO teachers (full_name, email, phone, address, qualification, experience_years, subjects, available_days, preferred_timing, hourly_rate, preferred_gender, additional_info) 
                VALUES ('$full_name', '$email', '$phone', '$address', '$qualification', $experience_years, '$subjects', '$available_days', '$preferred_timing', $hourly_rate, '$preferred_gender', '$additional_info')";
        
        if ($conn->query($sql) === TRUE) {
            $success_message = "Registration successful! We will review your profile and contact you soon.";
        } else {
            $error_message = "Error: " . $sql . "<br>" . $conn->error;
        }
        
        $conn->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Teacher Registration - Abbott Home Tuitions</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    
    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet"> 

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Bootstrap Stylesheet -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom Stylesheet -->
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

        .text-primary {
            color: var(--primary) !important;
        }

        .bg-primary {
            background-color: var(--primary) !important;
        }

        .btn-primary {
            background-color: var(--primary);
            border-color: var(--primary);
        }

        .btn-primary:hover {
            background-color: #e55a00;
            border-color: #e55a00;
        }

        .form-section {
            background: white;
            border-radius: 10px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }

        .form-section h4 {
            color: var(--primary);
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--primary);
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.2rem rgba(255, 102, 0, 0.25);
        }

        .custom-checkbox .custom-control-input:checked ~ .custom-control-label::before {
            background-color: var(--primary);
            border-color: var(--primary);
        }

        .alert {
            border-radius: 10px;
        }

        .page-header {
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('img/carousel-2.jpg');
            background-position: center;
            background-size: cover;
            padding: 60px 0;
            margin-bottom: 50px;
        }

        .required::after {
            content: " *";
            color: red;
        }

        .info-box {
            background: #FFF3E0;
            border-left: 4px solid var(--primary);
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .info-box i {
            color: var(--primary);
        }

        #tuition{
            color: #44425A;
            font-weight: bold;
        }

        #menu:hover{
            color: #e55a00;
        }

        #menu{
            color: #44425A;
            margin-top: 12px;
            font-size: 18px;
            font-weight: 600;
            padding-left: 14px;
        }

        #subject{
            background-color: #ffffff;
        }

        #join{
            margin-top: 12px;
        }

        #office{
            color: #44425A;
            font-weight: 600;
        }
    </style>
</head>

<body>
    <!-- Topbar Start -->
    <div class="container-fluid d-none d-lg-block">
        <div class="row align-items-center py-4 px-xl-5">
            <div class="col-lg-3">
                <a href="./index.html" class="text-decoration-none">
                    <h2 class="m-0"  id="tuition"><span class="text-primary">Abbott </span>Tuitions</h2>
                </a>
            </div>
            <div class="col-lg-3 text-right">
                <div class="d-inline-flex align-items-center">
                    <i class="fa fa-2x fa-map-marker-alt text-primary mr-3"></i>
                    <div class="text-left">
                        <h6 id= "office" class="font-weight-semi-bold mb-1">Our Office</h6>
                        <small>Kaghan Colony Mandia, Abbottabad</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 text-right">
                <div class="d-inline-flex align-items-center">
                    <i class="fa fa-2x fa-envelope text-primary mr-3"></i>
                    <div class="text-left">
                        <h6 id= "office" class="font-weight-semi-bold mb-1">Email Us</h6>
                        <small>abbotthometuitions@gmail.com</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 text-right">
                <div class="d-inline-flex align-items-center">
                    <i class="fa fa-2x fa-phone text-primary mr-3"></i>
                    <div class="text-left">
                        <h6 id= "office" class="font-weight-semi-bold mb-1">Call Us</h6>
                        <a href="https://wa.me/+92 319 0964392"><small >+92 319 0964392</small></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Topbar End -->

    <!-- Navbar Start -->
    <div class="container-fluid">
        <div class="row border-top px-xl-5">
            <div class="col-lg-3 d-none d-lg-block">
                <a id="subject" class="d-flex align-items-center justify-content-between w-100 text-decoration-none" data-toggle="collapse" href="#navbar-vertical" style="height: 67px; padding: 0 30px; background-color: #f2f1f8;">
                    <h5 class="text-primary m-0"><i class="fa fa-book-open mr-2"></i>Subjects</h5>
                    <i class="fa fa-angle-down text-primary"></i>
                </a>
                <nav class="collapse position-absolute navbar navbar-vertical navbar-light align-items-start p-0 border border-top-0 border-bottom-0 bg-light" id="navbar-vertical" style="width: calc(100% - 30px); z-index: 9;">
                    <div class="navbar-nav w-100">
                        <a href="" class="nav-item nav-link">Computer Science</a>
                        <a href="" class="nav-item nav-link">Quranic Studies</a>
                        <a href="" class="nav-item nav-link">Language Art</a>
                        <a href="" class="nav-item nav-link">Mathematics</a>
                        <a href="" class="nav-item nav-link">Science</a>
                    </div>
                </nav>
            </div>
            <div class="col-lg-9">
                <nav class="navbar navbar-expand-lg bg-light navbar-light py-3 py-lg-0 px-0">
                    <a href="" class="text-decoration-none d-block d-lg-none">
                        <h1 class="m-0"><span class="text-primary">Abbott</span> Tuitions</h1>
                    </a>
                    <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse justify-content-between" id="navbarCollapse">
                        <div class="navbar-nav py-0" >
                            <a id="menu" href="index.html" class="nav-item nav-link ml-2 mx-2">Home</a>
                            <a id="menu" href="about.html" class="nav-item nav-link ml-2 mx-2">About</a>
                            <a id="menu" href="student_form.php" class="nav-item nav-link ml-2 mx-2">Students Reg</a>
                            <a id="menu" href="teacher_form.php" class="nav-link nav-item ml-2 mx-2">Teachers Reg</a>
                            <a id="menu" href="contact.html" class="nav-item nav-link ml-2 mx-2">Contact</a>
                            <a id="menu" href="admin.php" class="nav-item nav-link ml-2 mx-2">Admin</a>
                        </div>
                        <a id="join"class="btn btn-primary py-2 px-4 ml-auto d-none d-lg-block" href="https://wa.me/+923190964392">Join Now</a>
                    </div>
                </nav>
            </div>
        </div>
    </div>
    <!-- Navbar End -->

    <!-- Page Header Start -->
    <div class="page-header text-center">
        <h1 class="text-white text-uppercase mb-3">Teacher Registration</h1>
        <p class="text-white">Join our team of qualified educators and make a difference</p>
    </div>
    <!-- Page Header End -->

    <!-- Registration Form Start -->
    <div class="container-fluid py-5">
        <div class="container py-5">
            <div class="row">
                <div class="col-lg-12">
                    <div class="info-box">
                        <i class="fas fa-info-circle mr-2"></i>
                        <strong>Why Join Abbott Home Tuitions?</strong>
                        <p class="mb-0 mt-2">We connect qualified tutors with students seeking personalized education. Register today to expand your teaching opportunities and earn competitive rates.</p>
                    </div>

                    <?php if($success_message): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Success!</strong> <?php echo $success_message; ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>
                    
                    <?php if($error_message): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Error!</strong> <?php echo $error_message; ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>

                    <form id="teacherForm" method="POST" action="">
                        <!-- Personal Information -->
                        <div class="form-section">
                            <h4><i class="fa fa-user text-primary mr-2"></i>Personal Information</h4>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="required">Full Name</label>
                                        <input type="text" class="form-control" name="full_name" required placeholder="Enter your full name">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="required">Email Address</label>
                                        <input type="email" class="form-control" name="email" required placeholder="your.email@example.com">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="required">Phone Number</label>
                                        <input type="tel" class="form-control" name="phone" required placeholder="+92 XXX XXXXXXX">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="required">Address</label>
                                        <input type="text" class="form-control" name="address" required placeholder="Your complete address">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Professional Qualifications -->
                        <div class="form-section">
                            <h4><i class="fa fa-graduation-cap text-primary mr-2"></i>Professional Qualifications</h4>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="required">Highest Qualification</label>
                                        <select class="form-control" name="qualification" required>
                                            <option value="">Select Qualification</option>
                                            <option value="Matric">Matric</option>
                                            <option value="Intermediate">Intermediate</option>
                                            <option value="Bachelor's Degree">Bachelor's Degree</option>
                                            <option value="Master's Degree">Master's Degree</option>
                                            <option value="M.Phil">M.Phil</option>
                                            <option value="PhD">PhD</option>
                                            <option value="Professional Certification">Professional Certification</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="required">Years of Teaching Experience</label>
                                        <input type="number" class="form-control" name="experience_years" required placeholder="e.g., 3" min="0" max="50">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Teaching Expertise -->
                        <div class="form-section">
                            <h4><i class="fa fa-book text-primary mr-2"></i>Teaching Expertise</h4>
                            <div class="form-group">
                                <label class="required">Subjects You Can Teach (Select all that apply)</label>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="custom-control custom-checkbox mb-2">
                                            <input type="checkbox" class="custom-control-input" id="teach_math" name="subjects[]" value="Mathematics">
                                            <label class="custom-control-label" for="teach_math">Mathematics</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="custom-control custom-checkbox mb-2">
                                            <input type="checkbox" class="custom-control-input" id="teach_science" name="subjects[]" value="Science">
                                            <label class="custom-control-label" for="teach_science">Science</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="custom-control custom-checkbox mb-2">
                                            <input type="checkbox" class="custom-control-input" id="teach_language" name="subjects[]" value="Language Arts">
                                            <label class="custom-control-label" for="teach_language">Language Arts</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="custom-control custom-checkbox mb-2">
                                            <input type="checkbox" class="custom-control-input" id="teach_computer" name="subjects[]" value="Computer Science">
                                            <label class="custom-control-label" for="teach_computer">Computer Science</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="custom-control custom-checkbox mb-2">
                                            <input type="checkbox" class="custom-control-input" id="teach_quran" name="subjects[]" value="Quranic Studies">
                                            <label class="custom-control-label" for="teach_quran">Quranic Studies</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Availability & Preferences -->
                        <div class="form-section">
                            <h4><i class="fa fa-clock text-primary mr-2"></i>Availability & Preferences</h4>
                            <div class="form-group">
                                <label class="required">Available Days (Select all that apply)</label>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="custom-control custom-checkbox mb-2">
                                            <input type="checkbox" class="custom-control-input" id="monday" name="available_days[]" value="Monday">
                                            <label class="custom-control-label" for="monday">Monday</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="custom-control custom-checkbox mb-2">
                                            <input type="checkbox" class="custom-control-input" id="tuesday" name="available_days[]" value="Tuesday">
                                            <label class="custom-control-label" for="tuesday">Tuesday</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="custom-control custom-checkbox mb-2">
                                            <input type="checkbox" class="custom-control-input" id="wednesday" name="available_days[]" value="Wednesday">
                                            <label class="custom-control-label" for="wednesday">Wednesday</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="custom-control custom-checkbox mb-2">
                                            <input type="checkbox" class="custom-control-input" id="thursday" name="available_days[]" value="Thursday">
                                            <label class="custom-control-label" for="thursday">Thursday</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="custom-control custom-checkbox mb-2">
                                            <input type="checkbox" class="custom-control-input" id="friday" name="available_days[]" value="Friday">
                                            <label class="custom-control-label" for="friday">Friday</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="custom-control custom-checkbox mb-2">
                                            <input type="checkbox" class="custom-control-input" id="saturday" name="available_days[]" value="Saturday">
                                            <label class="custom-control-label" for="saturday">Saturday</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="custom-control custom-checkbox mb-2">
                                            <input type="checkbox" class="custom-control-input" id="sunday" name="available_days[]" value="Sunday">
                                            <label class="custom-control-label" for="sunday">Sunday</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="required">Preferred Timing</label>
                                        <select class="form-control" name="preferred_timing" required>
                                            <option value="">Select Timing</option>
                                            <option value="Morning (8AM-12PM)">Morning (8AM-12PM)</option>
                                            <option value="Afternoon (12PM-4PM)">Afternoon (12PM-4PM)</option>
                                            <option value="Evening (4PM-8PM)">Evening (4PM-8PM)</option>
                                            <option value="Night (8PM-10PM)">Night (8PM-10PM)</option>
                                            <option value="Flexible">Flexible</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="required">Hourly Rate (PKR)</label>
                                        <input type="number" class="form-control" name="hourly_rate" required placeholder="e.g., 1000" min="0" step="100">
                                        <small class="form-text text-muted">Your expected rate per hour</small>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Preferred Student Gender</label>
                                        <select class="form-control" name="preferred_gender">
                                            <option value="">No Preference</option>
                                            <option value="Male">Male Only</option>
                                            <option value="Female">Female Only</option>
                                            <option value="Any">Any</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Additional Information -->
                        <div class="form-section">
                            <h4><i class="fa fa-info-circle text-primary mr-2"></i>Additional Information</h4>
                            <div class="form-group">
                                <label>Tell us about yourself</label>
                                <textarea class="form-control" name="additional_info" rows="4" placeholder="Share your teaching philosophy, certifications, special skills, or any other relevant information..."></textarea>
                                <small class="form-text text-muted">This helps students and parents understand your teaching approach</small>
                            </div>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary py-3 px-5">
                                <i class="fa fa-paper-plane mr-2"></i>Submit Registration
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Registration Form End -->

    <!-- Footer Start -->
    <div class="container-fluid bg-dark text-white py-5 px-sm-3 px-lg-5" style="margin-top: 0px;">
        <div class="row pt-5">
            <div class="col-lg-7 col-md-12">
                <div class="row">
                    <div class="col-md-6 mb-5">
                        <h5 class="text-primary text-uppercase mb-4" style="letter-spacing: 5px;">Get In Touch</h5>
                        <p><i class="fa fa-map-marker-alt mr-2"></i>Kaghan Colony Mandia, Abbottabad</p>
                        <a href="tel:+923190964392"><p style="color: white;"><i class="fa fa-phone-alt mr-2"></i>+92 319 0964392</p></a>
                        <p><i class="fa fa-envelope mr-2"></i>abbotthometuitions@gmail.com</p>
                        <div class="d-flex justify-content-start mt-4">
                            <a class="btn btn-outline-light btn-square mr-2" href="https://whatsapp.com/channel/0029Vaowr1oDuMRnqFCS6i1i"><i class="fab fa-twitter"></i></a>
                            <a class="btn btn-outline-light btn-square mr-2" href="https://whatsapp.com/channel/0029Vaowr1oDuMRnqFCS6i1i"><i class="fab fa-facebook-f"></i></a>
                            <a class="btn btn-outline-light btn-square mr-2" href="https://whatsapp.com/channel/0029Vaowr1oDuMRnqFCS6i1i"><i class="fab fa-linkedin-in"></i></a>
                            <a class="btn btn-outline-light btn-square" href="https://wa.me/+923190964392"><i class="fab fa-whatsapp"></i></a>
                        </div>
                    </div>
                    <div class="col-md-6 mb-5">
                        <h5 class="text-primary text-uppercase mb-4" style="letter-spacing: 5px;">Our Courses</h5>
                        <div class="d-flex flex-column justify-content-start">
                            <a class="text-white mb-2" href="#"><i class="fa fa-angle-right mr-2"></i>Mathematics</a>
                            <a class="text-white mb-2" href="#"><i class="fa fa-angle-right mr-2"></i>Science</a>
                            <a class="text-white mb-2" href="#"><i class="fa fa-angle-right mr-2"></i>Language Art</a>
                            <a class="text-white mb-2" href="#"><i class="fa fa-angle-right mr-2"></i>Computer Science</a>
                            <a class="text-white" href="#"><i class="fa fa-angle-right mr-2"></i>Quranic Studies</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-5 col-md-12 mb-5">
                <h5 class="text-primary text-uppercase mb-4" style="letter-spacing: 5px;">Newsletter</h5>
                <p>Sign Up now by entering your email address 
                    below to stay updated on educational news and events happening in 
                    Abbottabad. We promise to keep you informed with relevant insights and 
                    opportunities!</p>
                <div class="w-100">
                    <div class="input-group">
                        <input type="text" class="form-control border-light" style="padding: 30px;" placeholder="Your Email Address">
                        <div class="input-group-append">
                            <button class="btn btn-primary px-4">Sign Up</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid bg-dark text-white border-top py-4 px-sm-3 px-md-5" style="border-color: rgba(256, 256, 256, .1) !important;">
        <div class="row">
            <div class="col-lg-6 text-center text-md-left mb-3 mb-md-0">
                <p class="m-0 text-white">Copyright&copy; 2025 <a href="#">abbotthometuitions</a> All Rights Reserved.</p>
            </div>
            <div class="col-lg-6 text-center text-md-right">
                <ul class="nav d-inline-flex">
                    <li class="nav-item">
                        <a class="nav-link text-white py-0" href="#">Privacy</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white py-0" href="#">Terms</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white py-0" href="#">FAQs</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white py-0" href="#">Help</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!-- Footer End -->

    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="fa fa-angle-double-up"></i></a>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>

    <!-- Form Validation -->
    <script>
        $(document).ready(function() {
            // Form validation
            $('#teacherForm').on('submit', function(e) {
                // Check if at least one subject is selected
                var subjectsChecked = $('input[name="subjects[]"]:checked').length;
                if (subjectsChecked === 0) {
                    e.preventDefault();
                    alert('Please select at least one subject you can teach.');
                    return false;
                }

                // Check if at least one day is selected
                var daysChecked = $('input[name="available_days[]"]:checked').length;
                if (daysChecked === 0) {
                    e.preventDefault();
                    alert('Please select at least one available day.');
                    return false;
                }

                // Validate hourly rate
                var hourlyRate = parseFloat($('input[name="hourly_rate"]').val());
                if (hourlyRate < 0) {
                    e.preventDefault();
                    alert('Hourly rate must be a positive number.');
                    return false;
                }
            });

            // Back to top button
            $(window).scroll(function () {
                if ($(this).scrollTop() > 100) {
                    $('.back-to-top').fadeIn('slow');
                } else {
                    $('.back-to-top').fadeOut('slow');
                }
            });
            $('.back-to-top').click(function () {
                $('html, body').animate({scrollTop: 0}, 1500, 'easeInOutExpo');
                return false;
            });

            // Auto-hide alerts after 5 seconds
            setTimeout(function() {
                $('.alert').fadeOut('slow');
            }, 5000);
        });
    </script>

    <style>
        .back-to-top {
            position: fixed;
            display: none;
            right: 30px;
            bottom: 30px;
            z-index: 99;
        }
    </style>
</body>
</html>