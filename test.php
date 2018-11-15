<?php

ini_set('display_errors', 1);
require 'config.php';
if($text=="/stop")
file_put_contents("code.txt",1);

if($text=="/startbot")
file_put_contents("code.txt" ,0);



$database = new mysqli($dbhost, $dbusername, $dbpassword, $dbname);


$welcome= "🖐️Hello new user! Welcome to this bot! Reply this message with your Ether wallet address to continue.

If you have already registered your wallet, Kindly ignore this message and continue, Thanks!";

if(stripos( $text,'/start ')!==false)
{
    $refid=str_replace("/start ","",$text);
$refid=$telegram->crypt('%^',$refid);
if($refid == $telegram->UserID()){
$telegram->sendMessage(['chat_id'=>$telegram->UserID(),'text'=>'You cannot invite yourself to join🤔']);
}else{
if($refid>0){
$res=$database->query("SELECT * FROM referral WHERE id={$refid}");
if(mysqli_num_rows($res)> 0){
     $ros=$database->query("SELECT * FROM referral WHERE id={$telegram->UserID()}");
     
 if (mysqli_num_rows($ros)== 0) {
$database->query("INSERT INTO referral (id,points,invites) VALUES ({$telegram->UserID()},5,0)");
$database->query("UPDATE referral SET points = points+5 ,invites = invites+1 WHERE id = {$refid}");
 $telegram->sendMessage(['chat_id'=>$telegram->UserID(),'text'=>'Thanks for joining! You and your friend got 5 HTS']);
 $telegram->sendMessage(['chat_id'=>$refid,'text'=>"Thanks for inviting cause your friend just joined! You and your friend got 5 HTS"]);
 goto con;
      }else{$telegram->sendMessage(['chat_id'=>$telegram->UserID(),'text'=>'You have already registered😃']);
         }
         }else{$telegram->sendMessage(['chat_id'=>$telegram->UserID(),'text'=>'Invalid user referral Code😶']);
         }
     }else{$telegram->sendMessage(['chat_id'=>$telegram->UserID(),'text'=>'You cannot create your own referral code😤']);
     }
   }
}
else if ($text == '/start')
 {      
	$query = "INSERT INTO referral (id) VALUES ({$telegram->UserID()})";
    $database->query($query);

    con:
    $reply_markup = $telegram->buildForceReply(false);
    $content = array(
    'chat_id' => $telegram->UserID(),
    'text' =>  "$welcome",
    "parse_mode" => "Html",
    "disable_web_page_preview" => "true",
    "reply_markup" => $reply_markup
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

else if(strstr($data['message']['reply_to_message']['text'],"Reply me with your updated ether wallet address")!=false)
{
    $text  = trim($text," ");
    $query = "update user set ether_address= '{$text}' where chat_id = '{$telegram->UserID()}'";
    echo $query;
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
else if($text == '/reffral' || $text == "/reffral@{$bot_username}")
{
$result=$database->query("select * from referral where id={$telegram->UserID()}");
$data=mysqli_fetch_array($result);
$ctx="Your Stats on Bot : 📊
🆔️ : {$data['id']}  HTX : {$data['points']} 👥 : {$data['invites']}\n";
$secret = $telegram->crypt('EC',$telegram->UserID());
$option = array( 
    array($telegram->buildInlineKeyBoardButton("Share this bot Now ", $url="https://t.me/share/url?url=https://t.me/{$bot_username}?start={$secret}&text={$bot_sharetext}"))
    );
    $keyb = $telegram->buildInlineKeyBoard($option);
$content = array('chat_id' => $telegram->ChatID(), 'parse_mode'=>'Markdown', 'reply_markup' => $keyb, 'text' => $ctx."_Refer Your Friend With Your Link_...*So You And Your Friends Both Will Get 5 HTS!! Its Cool ah!!* ");
$telegram->sendMessage($content);
}
else if(stripos($text,'/vote')!==false)
{
$data=explode(" ",$text);
if($text=='/vote' || $text == "/vote@{$bot_username}")
{
$content=array(
'chat_id'=>$telegram->ChatID(),
'parse_mode'=>'Markdown',
'text'=>$telegram->save()
);
}
else if($data[1]=='reset' && $data[0]=='/vote')
{
    !in_array($telegram->UserID(),$admins)?die:null;
$telegram->save('reset');
$content=array(
'chat_id'=>$telegram->UserID(),
'parse_mode'=>'Markdown',
'text'=>'done reset'
);
}
else if($data[0]=='/vote' && count($data)>1)
{
    !in_array($telegram->UserID(),$admins)?die:null;
unset($data[0]);
$telegram->save(implode(" ",$data));
$content=array(
'chat_id'=>$telegram->UserID(),
'parse_mode'=>'Markdown',
'text'=>'done saved'
);
}
$telegram->sendMessage($content);
}
else if(stripos($text,'/exchange')!==false)
{
$data=explode(" ",$text);
if($text=='/exchange' || $text == "/exchange@{$bot_username}")
{
$content=array(
'chat_id'=>$telegram->ChatID(),
'parse_mode'=>'Markdown',
'text'=>$telegram->saveE()
);
}
else if($data[1]=='reset' && $data[0]=='/exchange')
{
    !in_array($telegram->UserID(),$admins)?die:null;
$telegram->saveE('reset');
$content=array(
'chat_id'=>$telegram->UserID(),
'parse_mode'=>'Markdown',
'text'=>'done reset'
);
}
else if($data[0]=='/exchange' && count($data)>1)
{
    !in_array($telegram->UserID(),$admins)?die:null;
unset($data[0]);
$telegram->saveE(implode(" ",$data));
$content=array(
'chat_id'=>$telegram->UserID(),
'parse_mode'=>'Markdown',
'text'=>'done saved'
);
}
$telegram->sendMessage($content);
}
else if(isset($data['message']['forward_from']))
{   
    
    !in_array($telegram->UserID(),$admins)?die:null;

    $id            = $data['message']['forward_from']['id'];
    $query         = "select * from user where chat_id ='$id'";
    $result        = $database->query($query);
    echo $query;
    $result        = mysqli_fetch_array($result);
    $user_id       = $result['chat_id'];
    $username      = $result['username'];
    $ether_address = $result['ether_address'];
    $balance       = $result['balance'];

    $option = array( 
        array($telegram->buildInlineKeyBoardButton( "Update balance", '' , $callback_data = "Update balance $id" )));
    $keyb = $telegram->buildInlineKeyBoard($option);

    $content = array(
        'chat_id' => $telegram->UserID(),
        'reply_markup' => $keyb,
        'text' =>  "Here are the user details :

Chat Id : <code>$user_id</code>
Username : <code>$username</code>
Ether address : <code>$ether_address</code>
Balance: <code>$balance</code>",
        "parse_mode" => "Html",
        "disable_web_page_preview" => "true"
            );
        $telegram->sendMessage($content);
}

else if (strstr($data['message']['reply_to_message']['text'],"the new balance")!=false)
{   
    $explode = explode(" ",$data['message']['reply_to_message']['text']);
    $id      = $explode[5];
    $text    = trim($text," ");
    $sql     = "Update user set balance='$text' where chat_id = '$id'";
    $result  = $database->query($sql);

    if($result== true)
    {
        $reply_markup = $telegram->buildForceReply(false);
        $content = array(
        'chat_id' => $telegram->UserID(),
        'text' =>  "Updation successfull",
        "parse_mode" => "Html",
        "disable_web_page_preview" => "true",
        "reply_markup" => $reply_markup
        );
        $telegram->sendMessage($content);
    }
    else 
    {
        $content = array(
        'chat_id' => $telegram->UserID(),
        'text' =>  "Some error ",
        "parse_mode" => "Html",
        "disable_web_page_preview" => "true",
        );
        $telegram->sendMessage($content);
    }    
 }



 if($text=='/send')
 {
 	$stop = file_get_contents("code.txt");
if($stop==1){
	$telegram->sendMessage(['chat_id'=>$telegram->UserID(),'text'=>'Sorry either bot or this function has been stopped by admins! It will be Back Soon!']);
exit;
}
    $option = array( 
        array($telegram->buildInlineKeyBoardButton( "Before Voting ", '' , $callback_data = "channel_1" )), 
        array($telegram->buildInlineKeyBoardButton( "After Voting ", '' , $callback_data = "channel_2" )), 
        array($telegram->buildInlineKeyBoardButton( "Your Ether wallet",'' , $callback_data = "Ether" )));
    $keyb = $telegram->buildInlineKeyBoard($option);
    $content = array(
    'chat_id' => $telegram->UserID(),
    'reply_markup' => $keyb,
    'text' =>  'Submit Your *Proofs* Here...*Be Sure While Submitting Screen Shots*',
    "parse_mode" => "markdown",
    "disable_web_page_preview" => "true"
        );
    $telegram->sendMessage($content);
 }

 if($type=='callback_query')
 {   
     $data = $data['callback_query'];
     $callback_data = $data['data'];

     if($callback_data=='channel_1')
     {
      $reply_markup = $telegram->buildForceReply(false);     
      $content = array(
        'chat_id' => $telegram->UserID(),
        'text' =>  "Send Me A SCREEN SHOT Which Taken BEFORE Voting....",
        "parse_mode" => "Html",
        "disable_web_page_preview" => "true",
        "reply_markup" => $reply_markup
        );
     }
     else if($callback_data=='channel_2')
     {
        $reply_markup = $telegram->buildForceReply(false);
        $content = array(
            'chat_id' => $telegram->UserID(),
            'text' =>  "Send Me A SCREEN SHOT Which Taken AFTER Voting....",
            "parse_mode" => "Html",
            "disable_web_page_preview" => "true",
            "reply_markup" => $reply_markup
            );

     }
     else if(strstr($callback_data,'Update balance')!=false)
     {   
        $explode      = explode(" ",$callback_data);
        $id           = $explode[2];
        $reply_markup = $telegram->buildForceReply(false);

        $content = array(
            'chat_id' => $telegram->UserID(),
            'text' =>  "Send the new balance for $id",
            "parse_mode" => "Html",
            "disable_web_page_preview" => "true",
            "reply_markup" => $reply_markup
            );
    }
    else 
     { 
        $chat_id = $data['from']['id'];
        $query   = "select ether_address from user where chat_id ='{$telegram->UserID()}'";

        $ether_address = $database->query($query);
        $ether_address = mysqli_fetch_array($ether_address);
        $ether_address = $ether_address['ether_address'];

        $content = array(
            'chat_id' => $telegram->UserID(),
            'text' =>  "*Your Ether :* $ether_address",
            "parse_mode" => "markdown",
            "disable_web_page_preview" => "true"
            );
     }
    $telegram->sendMessage($content);
    return;
 }

 else if(array_key_exists('reply_to_message',$data['message']))
 {
   if(strstr($data['message']['reply_to_message']['text'],"Send Me A SCREEN SHOT Which Taken BEFORE Voting....")!=false)
   {
       $message_id = $data['message']['message_id'];
       $content = array(
           'chat_id'=>"-1001395866830",
           'from_chat_id'=>$telegram->UserID(),
           'message_id' => $message_id
       );
       $telegram->forwardMessage($content);
       $telegram->sendMessage (array(
    'chat_id' => $telegram->UserID(),
    'text' =>  "Success, Now send after screenshot!"
    ));
   }
   else if(strstr($data['message']['reply_to_message']['text'],"Send Me A SCREEN SHOT Which Taken AFTER Voting....")!=false)
   {
       $message_id = $data['message']['message_id'];
       $content = array(
           'chat_id'=>"-1001354478179",
           'from_chat_id'=>$telegram->UserID(),
           'message_id' => $message_id
       );
       $telegram->forwardMessage($content);
      
       $telegram->sendMessage (array(
    'chat_id' => $telegram->UserID(),
    'text' =>  "Success!"
    ));
    return;
   }
   
else if(strstr($data['message']['reply_to_message']['text'],"Reply this message with your Ether wallet address to continue")!=false)
{
   $username = "@".$data['message']['from']['username'];
   $text     = trim($text," ");
   $query    = "insert into user (chat_id,username,ether_address,balance) values ('{$telegram->UserID()}','{$username}','{$text}','0')";
   $result   = $database->query($query);
   $content;

   if($result == true)
   {
    $content = array(
    'chat_id' => $telegram->UserID(),
    'text' =>  "Registration successfull!😃
👤Here are you details:

Username: $username
Ether address :$text

Wrong info? Use /reset Command to *EDIT* it, it will update !
*To Earn HTS Refer Your Firends....* Use /reffral Command To *Share With Friends*

*Use* /send *Command To Submit Your Proofs*",
    "parse_mode" => "Markdown",
    "disable_web_page_preview" => "true"
    );
   }
   else 
   {
    $content = array(
        'chat_id' => $telegram->UserID(),
        'text' =>  "Seems like you have already set it! Use /reset to edit it.",
        "parse_mode" => "Html",
        "disable_web_page_preview" => "true"
        );
    }
    $telegram->sendMessage($content);
    return;
    } 
}
$database->close();
?>

    
