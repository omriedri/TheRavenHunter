<?php

require_once __DIR__ . '/../Services/AuthService.php';

?>

<!DOCTYPE html>
<html lang="en">


<?php include_once __DIR__ . '/head.php'; ?>

<body dir="ltr">
    <?php include_once __DIR__ . '/header.php'; ?>
    <?php include_once __DIR__ . '/home.php'; ?>
    <?php include_once __DIR__ . '/game.php'; ?>
    <?php include_once __DIR__ . '/records.php'; ?>
    <?php include_once __DIR__ . '/settings.php'; ?>
    <?php include_once __DIR__ . '/about.php'; ?>

    <modals>
        <?php require_once __DIR__ . '/modals/login.php'; ?>
        <?php require_once __DIR__ . '/modals/register.php'; ?>
        <?php require_once __DIR__ . '/modals/profile.php'; ?>
        <?php require_once __DIR__ . '/modals/gameEnding.php'; ?>
        <?php require_once __DIR__ . '/modals/gameOver.php'; ?>
        <?php require_once __DIR__ . '/modals/privacyPolicy.php'; ?>
    </modals>
    
    <?php include_once __DIR__ . '/appearance.php'; ?>
    <?php include_once __DIR__ . '/footer.php'; ?>
    <?php include_once __DIR__ . '/scripts.php'; ?>
</body>

</html>