<?php

ini_set('display_errors', 1);
require 'config.php';
$database = new mysqli($dbhost, $dbusername, $dbpassword, $dbname);


$welcome = 'Welcome to ABC community. To get free 55 ABC Token, you must do steps as shown below:

1. Visit the Website (https://abc.com) and subscribe us

2. Join our Telegram community (https://t.me/abc)

3. Subscribe our Telegram channel (https://t.me/abc_New)

4. After that, fill your information by typing /submit';


if (stripos($text, '/start ') !== false)
{
    $refid = str_replace("/start ", "", $text);
    $refid = $telegram->crypt('%^', $refid);
    
    if ($refid == $telegram->UserID())
    {
        $content = array(
            "chat_id" => $telegram->UserID(),
            'text' => 'You cannot invite yourself to join🤔'
        );
        $telegram->sendMessage($content);
    }
    else
    {
        if ($refid > 0)
        {
            $res = $database->query("SELECT * FROM referral WHERE id={$refid}");
            if (mysqli_num_rows($res) > 0)
            {
                $ros = $database->query("SELECT * FROM referral WHERE id={$telegram->UserID()}");
                if (mysqli_num_rows($ros) == 0)
                {
                    $database->query("INSERT INTO referral (id,points,invites) VALUES ({$telegram->UserID()},5,0)");
                    $database->query("UPDATE referral SET points = points+5 ,invites = invites+1 WHERE id = {$refid}");
                    
                    $content = array(
                        'chat_id' => $telegram->UserID(),
                        'text' => 'Thanks for joining! You and your friend got 5 HTS'
                    );
                    
                    $telegram->sendMessage($content);
                    $content = array(
                        'chat_id' => $refid,
                        'text' => "Thanks for inviting cause your friend just joined! You and your friend got 5 HTS"
                    );
                    $telegram->sendMessage($content);
                    goto con;
                }
                
                else
                {
                    $content = array(
                        'chat_id' => $telegram->UserID(),
                        'text' => 'You have already registered😃'
                    );
                    
                    $telegram->sendMessage($content);
                }
            }
            else
            {
                $content = array(
                    'chat_id' => $telegram->UserID(),
                    'text' => 'Invalid user referral Code😶'
                );
                $telegram->sendMessage();
            }
        }
        else
        {
            $content = array(
                'chat_id' => $telegram->UserID(),
                'text' => 'You cannot create your own referral code😤'
            );
            $telegram->sendMessage($content);
        }
    }
}
else if ($text == '/start')
{
    $query = "INSERT INTO referral (id) VALUES ({$telegram->UserID()})";
    $database->query($query);
    
con:
    $content      = array(
        'chat_id' => $telegram->UserID(),
        'text' => "$welcome",
        "parse_mode" => "Html",
        "disable_web_page_preview" => "true"
    );
    $telegram->sendMessage($content);
    
}

else if ($text == '/submit')
 {      
    $reply_markup = $telegram->buildForceReply(false);
    $content = array(
    'chat_id' => $telegram->UserID(),
    'text' =>  "As a reply to this message enter your Email please",
    "parse_mode" => "Html",
    "disable_web_page_preview" => "true",
    "reply_markup" => $reply_markup
    );
    $telegram->sendMessage($content);
 }

 else if ($text == '/referral')
{
    $secret  = $telegram->crypt('EC', $telegram->UserID());
    $option  = array(
        array(
            $telegram->buildInlineKeyBoardButton("Share this bot Now ", $url = "https://t.me/share/url?url=https://t.me/{$bot_username}?start={$secret}&text={$bot_sharetext}")
        )
    );
    $keyb    = $telegram->buildInlineKeyBoard($option);
    $content = array(
        'chat_id' => $telegram->UserID(),
        'reply_markup' => $keyb,
        'text' => $ctx . "_Refer Your Friend With Your Link_...*So You And You Will Get 5 HTS!! Its Cool ah!!* "
    );
    $telegram->sendMessage($content);
}

else if ($text == "/balance")
{
    $result  = $database->query("select * from referral where id={$telegram->UserID()}");
    $data    = mysqli_fetch_array($result);
    $ctx     = "Your Stats on Bot : 📊
🆔️ : {$data['id']}  HTX : {$data['points']} 👥 : {$data['invites']}\n";
 $content = array(
   'chat_id' => $telegram->UserID(),
   'text' =>  $ctx ,
   "parse_mode" => "Html",
   "disable_web_page_preview" => "true"
   );
   $telegram->sendMessage($content);
}


else if ($text == '/reset')
{      
   $reply_markup = $telegram->buildForceReply(false);
   $content = array(
   'chat_id' => $telegram->UserID(),
   'text' =>  "Reply me with your updated ether wallet address ( Please note that it will be added as it is, So just send the address and nothing else )",
   "parse_mode" => "Html",
   "disable_web_page_preview" => "true",
   "reply_markup" => $reply_markup
   );
   $telegram->sendMessage($content);
}

 else if (strstr($data['message']['reply_to_message']['text'],"your Email please")!=false)
{   
    $reply;
    $text    = trim($text," ");
    $username = "@".$data['message']['from']['username'];

    if(strstr($text,"@")==false)
     $reply = "Fooling me or fooling yourself? Please enter a valid email id";

    else 
    {
     $sql     = "insert into user(chat_id,username,email) values ($chat_id,'$username','$text')";
     $result  = $database->query($sql);
     $reply   = "Email added, Now Please reply this message with your ERC-20 wallet address";
    }

    $reply_markup = $telegram->buildForceReply(false);
        $content = array(
        'chat_id' => $telegram->UserID(),
        'text' =>  $reply,
        "parse_mode" => "Html",
        "disable_web_page_preview" => "true",
        "reply_markup" => $reply_markup
        );
        $telegram->sendMessage($content);
 }

 else if (strstr($data['message']['reply_to_message']['text'],"ERC-20 wallet address")!=false)
{   
    $reply;
    $text    = trim($text," ");
    if(strlen($text)<10)
    $reply   = "Invalid ether address, Reply to that message again";

    else 
    {
     $sql     = "Update user set etc ='$text' where chat_id = '$chat_id'";
     $result  = $database->query($sql);
     $reply = "Wallet address updated to $text,
If you think you have put wrong address, Click /reset command to update your wallet address";
    }
        $content = array(
        'chat_id' => $telegram->UserID(),
        'text' =>  $reply,
        "parse_mode" => "Html",
        "disable_web_page_preview" => "true",
        );
        $telegram->sendMessage($content);

        
        $reply = "Thank you for submitting your information.
Share to your friends to get 35 ABC Token each referral. To get your referral link, click /referral";
        $content = array(
            'chat_id' => $telegram->UserID(),
            'text' =>  $reply,
            "parse_mode" => "Html",
            "disable_web_page_preview" => "true",
            );
            $telegram->sendMessage($content);
 }

 else if(strstr($data['message']['reply_to_message']['text'],"updated ether wallet address")!=false)
{
    $text  = trim($text," ");
    $query = "update user set etc= '$text' where chat_id = $chat_id";
    if($database->query($query))
    {
     $content = array(
        'chat_id' => $telegram->UserID(),
        'text' =>  "*You wallet address has been updated to *: $text",
        "parse_mode" => "markdown",
        "disable_web_page_preview" => "true",
        );
        $telegram->sendMessage($content);
    }  
}


?>

    