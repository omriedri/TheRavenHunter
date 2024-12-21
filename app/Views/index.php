<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bird Catcher</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <?php include_once __DIR__ . '/header.php'; ?>
    <main>
        <section>
            <h2>Catch the Birds</h2>
            <p>Join us in our adventure to catch the most exotic birds around the world.</p>
            <button onclick="startAdventure()">Start Adventure</button>
        </section>
    </main>
    <footer>
        <p>&copy; 2023 Bird Catcher. All rights reserved.</p>
    </footer>
    <script>
        function startAdventure() {
            alert('Adventure started!');
        }
    </script>
</body>

</html>