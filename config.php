<?php

    $bot_token = "634681716:AAHR4Km93Uu0RDonrZaAbXByAk2IZc47YFw"; //here comes your bot token
    // Using the TelegramBotPHP library by Eleirbag89 - https://github.com/Eleirbag89/TelegramBotPHP
    require ("Telegram.php");



  $telegram   = new Telegram($bot_token);
  $website    ='https://api.telegram.org/bot'."$bot_token";
  $text       = $telegram->Text();
    $data       = $telegram->getData();
  $chat_id    = $telegram->ChatID();
  $type       = $telegram->getUpdateType();  

// below this is your database info
  $dbhost     = "localhost";  
  $dbname     = "id7825881_list101botname";
  $dbusername = "id7825881_list101botuser";
  $dbpassword = "list101.bot";    // noooooo I create a database and put some other password it's not the one I am talking about

?>