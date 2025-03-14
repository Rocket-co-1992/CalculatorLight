<?php
http_response_code(503);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Maintenance Mode</title>
    <style>
        body {
            background: #0a0b1e;
            color: #fff;
            font-family: Arial, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }
        .maintenance-card {
            background: rgba(16, 18, 27, 0.4);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 30px;
            text-align: center;
            max-width: 500px;
        }
    </style>
</head>
<body>
    <div class="maintenance-card">
        <h1>Under Maintenance</h1>
        <p>We're performing scheduled maintenance. We'll be back shortly.</p>
    </div>
</body>
</html>
