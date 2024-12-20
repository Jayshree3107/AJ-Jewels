<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- Font Awesome for Social Media Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        /* Footer */
        .footer {
            background-color: #6e4b3a; /* Dark brown */
            color: #f8f1e4; /* Light beige */
            padding: 20px 0;
            text-align: center;
            font-family: 'Arial', sans-serif;
        }

        .footer p {
            margin: 0;
            font-size: 14px;
            letter-spacing: 0.5px;
        }

        .footer .social-icons {
            margin-top: 10px;
        }

        .footer .social-icons a {
            color: #f8f1e4;
            font-size: 18px;
            margin: 0 10px;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer .social-icons a:hover {
            color: #f9e0b6; /* Lighter beige */
        }

        .footer .footer-links {
            margin-top: 15px;
            font-size: 14px;
        }

        .footer .footer-links a {
            color: #f8f1e4;
            margin: 0 10px;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer .footer-links a:hover {
            color: #f9e0b6; /* Lighter beige */
        }

        .footer small {
            display: block;
            margin-top: 15px;
            font-size: 12px;
            color: #d3c4af; /* Muted beige */
        }

        /* Button styling */
        .footer .feedback-btn {
            background-color: burlywood; /* Lighter beige */
            color: #6e4b3a; /* Dark brown */
            font-size: 14px;
            padding: 10px 20px;
            border: none;
            border-radius: 25px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .footer .feedback-btn:hover {
            background-color: #d3c4af; /* Muted beige */
        }
    </style>
</head>

<body>
    <!-- Footer -->
    <footer class="footer">
        <p>&copy; 2024 AJ Jewels. All Rights Reserved.</p>
        <div class="social-icons">
            <a aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
            <a aria-label="Twitter"><i class="fab fa-twitter"></i></a>
            <a aria-label="Instagram"><i class="fab fa-instagram"></i></a>
            <a aria-label="Pinterest"><i class="fab fa-pinterest"></i></a>
        </div>
        
        <!-- Feedback Button -->
        <div class="footer-links">
            <a href="feedback.php" class="feedback-btn">Leave Feedback</a>
        </div>

        <small>Designed with ♥ by AJ Jewels Team</small>
    </footer>
</body>

</html>
