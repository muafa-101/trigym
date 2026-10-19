<?php
/**
 * @var string $content
 * @var string $pageTitle
 * @var string $header
 * @var string $footer
 */
use TriGym\Core\View;

$bodyClass = $bodyClass ?? '';
?>
<!DOCTYPE html>
<html lang="id" class="scroll-smooth scroll-pt-24">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?= e(TriGym\Core\Session::csrfToken()) ?>">
    <title><?= e($pageTitle ?? 'TRIGYM') ?></title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Anton&family=Asap:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="./src/output.css" rel="stylesheet">

    <style>
        body { font-family: 'Asap', sans-serif; }
        .font-display { font-family: 'Anton', 'Impact', sans-serif; font-weight: 400; letter-spacing: 0.2px; }
        body {
            background-color: #C9EFFA;
            background-image: url('assets/BACKGROUND.png');
            background-size: 100% auto;
            background-position: top center;
            background-repeat: no-repeat;
        }
        summary::-webkit-details-marker { display: none; }
    </style>
</head>
<body class="<?= e(trim('text-black leading-relaxed antialiased ' . $bodyClass)) ?>">

<?php View::partial('header-' . ($header ?? 'home'), get_defined_vars()); ?>

<?= $content ?>

<?php View::partial('footer-' . ($footer ?? 'legal'), get_defined_vars()); ?>

<?php if (!empty($script)) { View::partial('scripts/' . $script, get_defined_vars()); } ?>
</body>
</html>
