<?php
include 'config.php';
session_start();

function prepareAndExecute($conn, $sql, $params)
{
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die('mysqli error: ' . htmlspecialchars($conn->error));
    }
    $stmt->bind_param(str_repeat('s', count($params)), ...$params);
    $stmt->execute();
    return $stmt;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/login.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- Sweet Alert -->
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <!-- AOS Animation -->
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    <!-- Loading Bar -->
    <script src="https://cdn.jsdelivr.net/npm/pace-js@latest/pace.min.js"></script>
    <link rel="stylesheet" href="./css/flash.css">
    <title>Hotel Blue Bird</title>
    <style>
        /* Login Page Styles */
:root {
    --primary-blue: #1a4b8c;
    --secondary-blue: #2a6ec6;
    --light-blue: #e6f0fa;
    --white: #ffffff;
    --light-gray: #f8f9fa;
    --dark-gray: #333333;
    --shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
}

body {
    font-family: 'Montserrat', sans-serif;
    color: var(--dark-gray);
    line-height: 1.6;
    margin: 0;
    padding: 0;
    background-color: var(--light-gray);
    overflow-x: hidden;
}

/* Carousel Section */
.carousel_section {
    height: 100vh;
    width: 100%;
    position: fixed;
    top: 0;
    left: 0;
    z-index: 0;
}

.carousel-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    filter: brightness(0.4);
}

/* Auth Section */
#auth_section {
    position: relative;
    z-index: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-height: 100vh;
    padding: 40px 20px;
}

.logo {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 30px;
    z-index: 2;
}

.bluebirdlogo {
    height: 50px;
    width: auto;
}

.logo p {
    font-family: 'Playfair Display', serif;
    font-weight: 700;
    font-size: 2rem;
    color: var(--primary-blue);
    margin: 0;
    text-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
}

.auth_container {
    background-color: var(--white);
    border-radius: 15px;
    box-shadow: var(--shadow);
    width: 100%;
    max-width: 500px;
    padding: 40px;
    position: relative;
    overflow: hidden;
}

.auth_container h2 {
    color: var(--primary-blue);
    font-family: 'Playfair Display', serif;
    text-align: center;
    margin-bottom: 30px;
    font-size: 2rem;
}

/* Role Buttons */
.role_btn {
    display: flex;
    justify-content: center;
    margin-bottom: 25px;
    gap: 10px;
}

.btns {
    padding: 10px 25px;
    background-color: var(--light-blue);
    color: var(--primary-blue);
    border-radius: 30px;
    cursor: pointer;
    font-weight: 500;
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.btns.active {
    background-color: var(--primary-blue);
    color: var(--white);
    border-color: var(--primary-blue);
}

.btns:hover:not(.active) {
    background-color: var(--secondary-blue);
    color: var(--white);
}

/* Form Styles */
.authsection {
    display: none;
    animation: fadeIn 0.5s ease;
}

.authsection.active {
    display: block;
}

.form-floating {
    margin-bottom: 20px;
    position: relative;
}

.form-control {
    width: 100%;
    padding: 15px;
    border: 1px solid #ddd;
    border-radius: 8px;
    font-size: 1rem;
    transition: all 0.3s ease;
    background-color: var(--light-gray);
}

.form-control:focus {
    border-color: var(--primary-blue);
    box-shadow: 0 0 0 0.25rem rgba(26, 75, 140, 0.25);
    background-color: var(--white);
}

label {
    color: #666;
    font-weight: 400;
}

/* Auth Button */
.auth_btn {
    width: 100%;
    padding: 12px;
    background-color: var(--primary-blue);
    color: var(--white);
    border: none;
    border-radius: 8px;
    font-size: 1rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    margin-top: 10px;
}

.auth_btn:hover {
    background-color: var(--secondary-blue);
    transform: translateY(-2px);
    box-shadow: 0 4px 10px rgba(26, 75, 140, 0.3);
}

/* Footer Line */
.footer_line {
    text-align: center;
    margin-top: 20px;
    color: var(--dark-gray);
}

.page_move_btn {
    color: var(--primary-blue);
    font-weight: 600;
    cursor: pointer;
    transition: color 0.3s ease;
    text-decoration: underline;
}

.page_move_btn:hover {
    color: var(--secondary-blue);
}

/* Animations */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Responsive Design */
@media (max-width: 768px) {
    .auth_container {
        padding: 30px;
    }
    
    .logo p {
        font-size: 1.8rem;
    }
    
    .bluebirdlogo {
        height: 40px;
    }
}

@media (max-width: 576px) {
    .auth_container {
        padding: 25px 20px;
    }
    
    .logo {
        flex-direction: column;
        text-align: center;
        gap: 10px;
    }
    
    .role_btn {
        flex-direction: column;
        align-items: center;
    }
    
    .btns {
        width: 100%;
        text-align: center;
    }
}

/* Loading Bar */
.pace {
    -webkit-pointer-events: none;
    pointer-events: none;
    -webkit-user-select: none;
    -moz-user-select: none;
    user-select: none;
    z-index: 2000;
    position: fixed;
    height: 4px;
    width: 100%;
    background: var(--light-gray);
}

.pace .pace-progress {
    background-color: var(--primary-blue);
    position: fixed;
    z-index: 2000;
    top: 0;
    left: 0;
    height: 4px;
    transition: width 1s;
}

/* Flash Messages */
.alert {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 1000;
    padding: 15px 25px;
    border-radius: 8px;
    box-shadow: var(--shadow);
    animation: slideIn 0.5s ease, fadeOut 0.5s ease 4.5s forwards;
    max-width: 350px;
}

.alert-success {
    background-color: #d4edda;
    color: #155724;
    border-left: 5px solid #28a745;
}

.alert-danger {
    background-color: #f8d7da;
    color: #721c24;
    border-left: 5px solid #dc3545;
}

@keyframes slideIn {
    from { transform: translateX(100%); opacity: 0; }
    to { transform: translateX(0); opacity: 1; }
}

@keyframes fadeOut {
    from { opacity: 1; }
    to { opacity: 0; }
}
    </style>
    <!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>

<body>
    <!-- Carousel -->
    <section id="carouselExampleControls" class="carousel slide carousel_section" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img class="carousel-image" src="./image/hotel1.jpg">
            </div>
            <div class="carousel-item">
                <img class="carousel-image" src="./image/hotel2.jpg">
            </div>
            <div class="carousel-item">
                <img class="carousel-image" src="./image/hotel3.jpg">
            </div>
            <div class="carousel-item">
                <img class="carousel-image" src="./image/hotel4.jpg">
            </div>
        </div>
    </section>

    <!-- Main Section -->
    <section id="auth_section">
        <div class="logo">
            <img class="bluebirdlogo" src="./image/bluebirdlogo.png" alt="logo">
            <p>BLUEBIRD</p>
        </div>
        <div class="auth_container">
            <!-- Login -->
            <div id="Log_in">
                <h2>Log In</h2>
                <div class="role_btn">
                    <div class="btns active">User</div>
                    <div class="btns">Staff</div>
                </div>

                <!-- User Login -->
                <?php
                if (isset($_POST['user_login_submit'])) {
                    $email = $_POST['Email'];
                    $password = $_POST['Password'];
                    $sql = "SELECT * FROM signup WHERE Email = ? AND Password = BINARY ?";
                    $stmt = prepareAndExecute($conn, $sql, [$email, $password]);
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        $_SESSION['usermail'] = $email;
                        header("Location: home.php");
                        exit();
                    } else {
                        echo "<script>swal({ title: 'Something went wrong', icon: 'error', });</script>";
                    }
                }
                ?>
                <form class="user_login authsection active" id="userlogin" action="" method="POST">
                    <div class="form-floating">
                        <input type="text" class="form-control" name="Username" placeholder=" ">
                        <label for="Username">Username</label>
                    </div>
                    <div class="form-floating">
                        <input type="email" class="form-control" name="Email" placeholder=" ">
                        <label for="Email">Email</label>
                    </div>
                    <div class="form-floating">
                        <input type="password" class="form-control" name="Password" placeholder=" ">
                        <label for="Password">Password</label>
                    </div>
                    <button type="submit" name="user_login_submit" class="auth_btn">Log in</button>
                    <div class="footer_line">
                        <h6>Don't have an account? <span class="page_move_btn" onclick="signuppage()">sign up</span></h6>
                    </div>
                </form>

                <!-- Employee Login -->
                <?php
                if (isset($_POST['Emp_login_submit'])) {
                    $email = $_POST['Emp_Email'];
                    $password = $_POST['Emp_Password'];
                    $sql = "SELECT * FROM emp_login WHERE Emp_Email = ? AND Emp_Password = BINARY ?";
                    $stmt = prepareAndExecute($conn, $sql, [$email, $password]);
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        $_SESSION['usermail'] = $email;
                        header("Location: admin/admin.php");
                        exit();
                    } else {
                        echo "<script>swal({ title: 'Something went wrong', icon: 'error', });</script>";
                    }
                }
                ?>
                <form class="employee_login authsection" id="employeelogin" action="" method="POST">
                    <div class="form-floating">
                        <input type="email" class="form-control" name="Emp_Email" placeholder=" ">
                        <label for="floatingInput">Email</label>
                    </div>
                    <div class="form-floating">
                        <input type="password" class="form-control" name="Emp_Password" placeholder=" ">
                        <label for="floatingPassword">Password</label>
                    </div>
                    <button type="submit" name="Emp_login_submit" class="auth_btn">Log in</button>
                </form>
            </div>

            <!-- Sign Up -->
            <?php
            if (isset($_POST['user_signup_submit'])) {
                $username = $_POST['Username'];
                $email = $_POST['Email'];
                $password = $_POST['Password'];
                $cpassword = $_POST['CPassword'];

                if ($username == "" || $email == "" || $password == "") {
                    echo "<script>swal({ title: 'Fill the proper details', icon: 'error', });</script>";
                } else {
                    if ($password == $cpassword) {
                        $sql_check = "SELECT * FROM signup WHERE Email = ?";
                        $stmt_check = prepareAndExecute($conn, $sql_check, [$email]);
                        $result = $stmt_check->get_result();

                        if ($result->num_rows > 0) {
                            echo "<script>swal({ title: 'Email already exists', icon: 'error', });</script>";
                        } else {
                            $sql_insert = "INSERT INTO signup (Username, Email, Password) VALUES (?, ?, ?)";
                            $stmt_insert = prepareAndExecute($conn, $sql_insert, [$username, $email, $password]);

                            if ($stmt_insert->affected_rows > 0) {
                                $_SESSION['usermail'] = $email;
                                header("Location: home.php");
                                exit();
                            } else {
                                echo "<script>swal({ title: 'Something went wrong', icon: 'error', });</script>";
                            }
                        }
                    } else {
                        echo "<script>swal({ title: 'Password does not match', icon: 'error', });</script>";
                    }
                }
            }
            ?>
            <div id="sign_up">
                <h2>Sign Up</h2>
                <form class="user_signup" id="usersignup" action="" method="POST">
                    <div class="form-floating">
                        <input type="text" class="form-control" name="Username" placeholder=" ">
                        <label for="Username">Username</label>
                    </div>
                    <div class="form-floating">
                        <input type="email" class="form-control" name="Email" placeholder=" ">
                        <label for="Email">Email</label>
                    </div>
                    <div class="form-floating">
                        <input type="password" class="form-control" name="Password" placeholder=" ">
                        <label for="Password">Password</label>
                    </div>
                    <div class="form-floating">
                        <input type="password" class="form-control" name="CPassword" placeholder=" ">
                        <label for="CPassword">Confirm Password</label>
                    </div>
                    <button type="submit" name="user_signup_submit" class="auth_btn">Sign up</button>
                    <div class="footer_line">
                        <h6>Already have an account? <span class="page_move_btn" onclick="loginpage()">Log in</span></h6>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <script src="./javascript/index.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <!-- AOS Animation -->
    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script>
        AOS.init();
    </script>
</body>

</html>
