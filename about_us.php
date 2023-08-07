<!-- about_us.php -->

<?php
include "db.php";
session_start();
?>
<!DOCTYPE html>
<html>

<head>
    <title>About Us</title>

</head>

<body>
    <?php include "header.php" ?>
    <div class="main-container">
        <?php include "nav.php"; ?>
        <main class="main">


            <h1>About Us</h1>
            <p>Welcome to our website! We are a team of passionate individuals dedicated to providing you with the best
                web experience.</p>
            <p>Our mission is to create user-friendly and visually appealing websites that cater to your needs. Whether
                you're a small business owner or an individual looking to showcase your portfolio, we've got you
                covered.</p>
            <p>Our team consists of talented designers and developers who work tirelessly to bring your ideas to life.
                We strive to stay updated with the latest web technologies and trends to deliver modern and cutting-edge
                solutions.</p>
            <p>We value our clients and prioritize their satisfaction. We believe in open communication and
                collaboration throughout the entire development process to ensure that we meet your expectations.</p>
            <p>Thank you for visiting our website. If you have any questions or would like to discuss your project,
                please don't hesitate to <a href="contact.php">contact us</a>.</p>

        </main>
    </div>
    <?php include "footer.php" ?>
</body>

</html>