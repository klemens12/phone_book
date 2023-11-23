<!doctype html>
<html lang="<?=$currentLang?>">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?=$translates['title']?></title>
    
    <link rel="apple-touch-icon" sizes="180x180" href="/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon/favicon-16x16.png">
    <link rel="manifest" href="site.webmanifest">
    <link rel="mask-icon" href="/favicon/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#2d89ef">
    <meta name="theme-color" content="#ffffff">
    
    <!-- CSRF Token -->
    <meta name="csrf-token" content="<?=$csrf?>">

    <link rel="dns-prefetch" href="//cdn.jsdelivr.net">
     
    <link href="/incl/fontawesome/css/fontawesome.min.css" rel="stylesheet">
    <link href="/incl/fontawesome/css/solid.min.css" rel="stylesheet">
    <link href="/incl/fontawesome/css/brands.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" href="/css/style.css">
    <script>
        window.translations = {
            <?= implode(', ', array_map(function($key, $value) {
                return "'$key': '" . addslashes($value) . "'";
            }, array_keys($translates), $translates)) ?>
        };

        /**
         * Load the translation item.
         * @param {string} msg
         * @returns {window.translations}
         */
        window.translate = function (msg) {
            return translations[msg] || msg;
        };
    </script>
  </head>
  <body>