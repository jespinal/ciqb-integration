<!DOCTYPE html>
<!--
I think this goes as minimalist as it can be.
-->
<html>
    <head>
        <title>CIQB integration</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
        <h3>Some vendors from my QB Sandbox</h3>
        <?php foreach($vendors as $vendor): ?>
            <strong>Display Name: </strong><span><?=$vendor->DisplayName?></span>
            <br>
            <strong>Currency: </strong><span><?=$vendor->CurrencyRef?></span>
            <br>
            <br>
        <?php endforeach; ?>
    </body>
</html>