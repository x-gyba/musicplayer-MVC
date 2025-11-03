<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo $title ?? 'Cestas Online'; ?></title>
    
    <link rel="shortcut icon" href="/public/assets/images/favicon.ico" type="image/x-icon" />
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" />
    <link rel="stylesheet" href="public/assets/css/style.css" />
</head>
<body>
    <?php include __DIR__ . '/header.php'; ?>

    <main>
        <?php echo $content; ?>
    </main>

    <?php include __DIR__ . '/footer.php'; ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="/public/assets/js/script.js"></script>
    <script src="/public/assets/js/cart.js"></script>
</body>
</html>