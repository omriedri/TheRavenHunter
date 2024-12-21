<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .profile-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: center;
        }

        .profile-container h1 {
            margin-bottom: 20px;
            color: #333;
        }

        .profile-details p {
            margin: 10px 0;
            color: #555;
            text-align: start;
        }

        .profile-details strong {
            color: #333;
        }
    </style>
</head>

<body>
    <div class="profile-container">
        <h1>User Profile</h1>
        <div class="profile-details">
            <p><strong>ID:</strong> <?php echo htmlspecialchars($user->id); ?></p>
            <p><strong>Name:</strong> <?php echo htmlspecialchars($user->first_name); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($user->email); ?></p>
            <?php if ($user->phone): ?>
                <p><strong>Phone:</strong> <?php echo htmlspecialchars($user->phone); ?></p>
            <?php endif; ?>
            <!-- Add more fields as necessary -->
        </div>
    </div>
</body>

</html>