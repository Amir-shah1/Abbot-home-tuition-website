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
        $student_name = $conn->real_escape_string($_POST['student_name']);
        $student_grade = $conn->real_escape_string($_POST['student_grade']);
        $subjects = isset($_POST['subjects']) ? implode(', ', $_POST['subjects']) : '';
        $frequency = $conn->real_escape_string($_POST['frequency']);
        $duration = $conn->real_escape_string($_POST['duration']);
        $preferred_timing = $conn->real_escape_string($_POST['preferred_timing']);
        $budget_min = floatval($_POST['budget_min']);
        $budget_max = floatval($_POST['budget_max']);
        $learning_goals = $conn->real_escape_string($_POST['learning_goals']);
        $additional_info = $conn->real_escape_string($_POST['additional_info']);
        
        // Insert into database
        $sql = "INSERT INTO students (full_name, email, phone, address, student_name, student_grade, subjects, frequency, duration, preferred_timing, budget_min, budget_max, learning_goals, additional_info) 
                VALUES ('$full_name', '$email', '$phone', '$address', '$student_name', '$student_grade', '$subjects', '$frequency', '$duration', '$preferred_timing', $budget_min, $budget_max, '$learning_goals', '$additional_info')";
        
        if ($conn->query($sql) === TRUE) {
            $success_message = "Registration successful! We will contact you soon.";
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
    <title>Student Registration - Abbott Home Tuitions</title>
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
            margin: 0;
            font-family: "Poppins", sans-serif;
            font-size: 1rem;
            font-weight: 1500;
            line-height: 1.5;
            color: #6C6A74;
            text-align: left;
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
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('img/carousel-1.jpg');
            background-position: center;
            background-size: cover;
            padding: 60px 0;
            margin-bottom: 50px;
        }

        .required::after {
            content: " *";
            color: red;
        }
    </style>
</head>

<body>
    <!-- Topbar Start -->
    <div class="container-fluid d-none d-lg-block">
        <div class="row align-items-center py-4 px-xl-5">
            <div class="col-lg-3">
                <a href="" class="text-decoration-none">
                    <h2 class="m-0"><span class="text-primary">Abbott </span>Tuitions</h2>
                </a>
            </div>
            <div class="col-lg-3 text-right">
                <div class="d-inline-flex align-items-center">
                    <i class="fa fa-2x fa-map-marker-alt text-primary mr-3"></i>
                    <div class="text-left">
                        <h6 class="font-weight-semi-bold mb-1">Our Office</h6>
                        <small>Kaghan Colony Mandia, Abbottabad</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 text-right">
                <div class="d-inline-flex align-items-center">
                    <i class="fa fa-2x fa-envelope text-primary mr-3"></i>
                    <div class="text-left">
                        <h6 class="font-weight-semi-bold mb-1">Email Us</h6>
                        <small>abbotthometuitions@gmail.com</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 text-right">
                <div class="d-inline-flex align-items-center">
                    <i class="fa fa-2x fa-phone text-primary mr-3"></i>
                    <div class="text-left">
                        <h6 class="font-weight-semi-bold mb-1">Call Us</h6>
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
                <a class="d-flex align-items-center justify-content-between bg-secondary w-100 text-decoration-none" data-toggle="collapse" href="#navbar-vertical" style="height: 67px; padding: 0 30px;">
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
                        <div class="navbar-nav py-0">
                            <a href="index.html" class="nav-item nav-link font-weight-semi-bold ml-2">Home</a>
                            <a href="about.html" class="nav-item nav-link font-weight-semi-bold ml-2">About</a>
                            <a href="student_form.php" class="nav-item nav-link font-weight-semi-bold ml-2">Students Reg</a>
                            <a href="teacher_form.php" class="nav-link nav-item font-weight-semi-bold ml-2">Teachers Reg</a>
                            <a href="contact.html" class="nav-item nav-link font-weight-semi-bold ml-2">Contact</a>
                            <a href="admin.php" class="nav-item nav-link font-weight-semi-bold ml-2">Admin</a>
                        </div>
                        <a class="btn btn-primary py-2 px-4 ml-auto d-none d-lg-block" href="https://wa.me/+923190964392">Join Now</a>
                    </div>
                </nav>
            </div>
        </div>
    </div>
    <!-- Navbar End -->

    <!-- Page Header Start -->
    <div class="page-header text-center">
        <h1 class="text-white text-uppercase mb-3">Student Registration</h1>
        <p class="text-white">Find the perfect tutor for your learning needs</p>
    </div>
    <!-- Page Header End -->

    <!-- Registration Form Start -->
    <div class="container-fluid py-5">
        <div class="container py-5">
            <div class="row">
                <div class="col-lg-12">
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

                    <form id="studentForm" method="POST" action="">
                        <!-- Parent/Guardian Information -->
                        <div class="form-section">
                            <h4><i class="fa fa-user text-primary mr-2"></i>Parent/Guardian Information</h4>
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

                        <!-- Student Information -->
                        <div class="form-section">
                            <h4><i class="fa fa-graduation-cap text-primary mr-2"></i>Student Information</h4>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="required">Student Name</label>
                                        <input type="text" class="form-control" name="student_name" required placeholder="Student's full name">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="required">Grade/Class</label>
                                        <select class="form-control" name="student_grade" required>
                                            <option value="">Select Grade</option>
                                            <option value="Kindergarten">Kindergarten</option>
                                            <option value="Class 1">Class 1</option>
                                            <option value="Class 2">Class 2</option>
                                            <option value="Class 3">Class 3</option>
                                            <option value="Class 4">Class 4</option>
                                            <option value="Class 5">Class 5</option>
                                            <option value="Class 6">Class 6</option>
                                            <option value="Class 7">Class 7</option>
                                            <option value="Class 8">Class 8</option>
                                            <option value="Class 9">Class 9</option>
                                            <option value="Class 10">Class 10</option>
                                            <option value="Class 11">Class 11</option>
                                            <option value="Class 12">Class 12</option>
                                            <option value="University">University</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tuition Requirements -->
                        <div class="form-section">
                            <h4><i class="fa fa-book text-primary mr-2"></i>Tuition Requirements</h4>
                            <div class="form-group">
                                <label class="required">Subjects Needed (Select all that apply)</label>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="custom-control custom-checkbox mb-2">
                                            <input type="checkbox" class="custom-control-input" id="math" name="subjects[]" value="Mathematics">
                                            <label class="custom-control-label" for="math">Mathematics</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="custom-control custom-checkbox mb-2">
                                            <input type="checkbox" class="custom-control-input" id="science" name="subjects[]" value="Science">
                                            <label class="custom-control-label" for="science">Science</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="custom-control custom-checkbox mb-2">
                                            <input type="checkbox" class="custom-control-input" id="language" name="subjects[]" value="Language Arts">
                                            <label class="custom-control-label" for="language">Language Arts</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="custom-control custom-checkbox mb-2">
                                            <input type="checkbox" class="custom-control-input" id="computer" name="subjects[]" value="Computer Science">
                                            <label class="custom-control-label" for="computer">Computer Science</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="custom-control custom-checkbox mb-2">
                                            <input type="checkbox" class="custom-control-input" id="quran" name="subjects[]" value="Quranic Studies">
                                            <label class="custom-control-label" for="quran">Quranic Studies</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="required">Frequency</label>
                                        <select class="form-control" name="frequency" required>
                                            <option value="">Select Frequency</option>
                                            <option value="Daily">Daily</option>
                                            <option value="3 times a week">3 times a week</option>
                                            <option value="2 times a week">2 times a week</option>
                                            <option value="Once a week">Once a week</option>
                                            <option value="Weekend only">Weekend only</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="required">Session Duration</label>
                                        <select class="form-control" name="duration" required>
                                            <option value="">Select Duration</option>
                                            <option value="30 minutes">30 minutes</option>
                                            <option value="1 hour">1 hour</option>
                                            <option value="1.5 hours">1.5 hours</option>
                                            <option value="2 hours">2 hours</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="required">Preferred Timing</label>
                                        <select class="form-control" name="preferred_timing" required>
                                            <option value="">Select Timing</option>
                                            <option value="Morning (8AM-12PM)">Morning (8AM-12PM)</option>
                                            <option value="Afternoon (12PM-4PM)">Afternoon (12PM-4PM)</option>
                                            <option value="Evening (4PM-8PM)">Evening (4PM-8PM)</option>
                                            <option value="Night (8PM-10PM)">Night (8PM-10PM)</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="required">Minimum Budget (PKR/hour)</label>
                                        <input type="number" class="form-control" name="budget_min" required placeholder="e.g., 500" min="0" step="100">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="required">Maximum Budget (PKR/hour)</label>
                                        <input type="number" class="form-control" name="budget_max" required placeholder="e.g., 1500" min="0" step="100">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Additional Information -->
                        <div class="form-section">
                            <h4><i class="fa fa-info-circle text-primary mr-2"></i>Additional Information</h4>
                            <div class="form-group">
                                <label>Learning Goals</label>
                                <textarea class="form-control" name="learning_goals" rows="3" placeholder="What are your learning objectives? (e.g., improve grades, exam preparation, concept clarity)"></textarea>
                            </div>
                            <div class="form-group">
                                <label>Additional Comments</label>
                                <textarea class="form-control" name="additional_info" rows="3" placeholder="Any special requirements or additional information you'd like to share"></textarea>
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
            $('#studentForm').on('submit', function(e) {
                // Check if at least one subject is selected
                var subjectsChecked = $('input[name="subjects[]"]:checked').length;
                if (subjectsChecked === 0) {
                    e.preventDefault();
                    alert('Please select at least one subject.');
                    return false;
                }

                // Validate budget
                var budgetMin = parseFloat($('input[name="budget_min"]').val());
                var budgetMax = parseFloat($('input[name="budget_max"]').val());
                
                if (budgetMax < budgetMin) {
                    e.preventDefault();
                    alert('Maximum budget must be greater than or equal to minimum budget.');
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