<?php
include 'common.php';
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET,PUT,POST,DELETE,PATCH,OPTIONS");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');

$postdata = file_get_contents("php://input");
	if (isset($postdata)) {
		$request = json_decode($postdata);
	} else {
		return;
	}

// print_r($request);
// exit;

$array_respond = array();

if($request->option == 1){
    $msg1 = "login";
    $array_respond["msg1"] = $msg1;
    $email = $request->inputs->email;
    $password = $request->inputs->password;
    
     $get_details = mfa(mq("SELECT email , password , id , username FROM users WHERE email = '$email' "));
     
      if (password_verify($password, $get_details['password'])) {
          
        $skey1 = rand();
        $skey2 = sha1($skey1);
        $set_session = mq("UPDATE users set skey ='$skey2' WHERE id = " . $get_details["id"]);
        $username = $get_details["username"];
        $id = $get_details["id"];
        
        if($set_session){
        $msg2 = "Successfull login to your account . Please wait .. ";
        $array_respond["msg2"] = $msg2;
        $array_respond["skey"] = $skey2;
        $array_respond["username"] = $username;
        $array_respond["id"] = $id;
        $array_respond["email"] = $email;
        $array_respond["msg_type"] = 1;    
        }else{
        $msg2 = "Failed to set up session . Please try again ";
        $array_respond["msg2"] = $msg2;
        $array_respond["msg_type"] = 2;  
            
        }
        
          
          
      }else{
          
        $msg2 = "Sorry , wrong password . Please try again";
        $array_respond["msg2"] = $msg2;
        $array_respond["msg_type"] = 2;
          
      }

    
}elseif($request->option == 2){
    
    $msg1 = "register";
    $array_respond["msg1"] = $msg1;
    $email = $request->inputs->email;
    $username = $request->inputs->username;
    $password = $request->inputs->password;
    $password2 = $request->inputs->repeat_password;
    
    
    if($password != $password2){
        $msg2 = "Password not match , please try again";
        $array_respond["msg2"] = $msg2;
        $array_respond["msg_type"] = 2;
        $error = 1;
    }
    
    $get_email = mfa(mq("SELECT email FROM users WHERE email = '$email' "));
    
    
    if(!empty($get_email["email"])){
        $msg2 = "Email already exist , please use another email instead";
        $array_respond["msg2"] = $msg2;
        $array_respond["msg_type"] = 2;
        $error = 1;
    }
    
    if(empty($error)){
         $password = password_hash($password, PASSWORD_BCRYPT);
        $insert  = mq("INSERT INTO users(email , password , username) VALUES ('$email' , '$password' , '$username')");
        if($insert){
            $msg2 = "Successfull registered your account . Please login to your account ";
        $array_respond["msg2"] = $msg2;
        $array_respond["msg_type"] = 1;
        }else{
            
             $msg2 = "Something went error , please try again";
        $array_respond["msg2"] = $msg2;
        $array_respond["msg_type"] = 2;
            
        }
    }
    
    

    //echo "register";
    
}elseif($request->option == 3){
    echo "reset";
}
elseif($request->option == 4){

    $new_ans = str_replace("-", "", $request->answer);
    $puzzleData[$new_ans] = array(
        "direction" => $request->direction,
        "number" => $request->number,
        "answer" => $request->answer,
    );
    
   //print_r(json_encode($puzzleData));
    print_r($puzzleData);
    print_r($request->otherData);
    
    $userid = $request->otherData->id;
    $puzzleType =  $request->otherData->type;
    
    $puzzleData1 = $puzzleData;
    $puzzleData = json_encode($puzzleData);
    
    if($puzzleType == "intro"){
       $get_data = mfa(mq("SELECT data FROM puzzle WHERE userid = '$userid' AND type = 'intro' ")); 
       if(!empty($get_data["data"])){
           
           $old = json_decode($get_data["data"] , true);
           print_r($old);
           
           $new = array_merge($old , $puzzleData1);
           
           print_r($new);
           
           $puzzleData = json_encode($new);
           
        //   print_r($get_data["data"]);
        mq("UPDATE puzzle set data ='$puzzleData' WHERE type = 'intro' AND userid = " . $userid);
           
       }else{
           
           $AnswerFirst = $request->answer;
           $puzzleData = array(); 

            $puzzleData['CONVEX'] = array(
                    "direction" => "accross",
                    "number" => "1",
                    "answer" => $AnswerFirst == "CONVEX" ? $AnswerFirst : "NULL",
            );
            
            $puzzleData['MICROECONOMICS'] = array(
                    "direction" => "accross",
                    "number" => "3",
                    "answer" => $AnswerFirst == "MICROECONOMICS" ? $AnswerFirst : "NULL",
            );
            
            $puzzleData['QUESTION'] = array(
                    "direction" => "accross",
                    "number" => "5",
                    "answer" => $AnswerFirst == "QUESTION" ? $AnswerFirst : "NULL",
            );
            
            $puzzleData['CAPITAL'] = array(
                    "direction" => "accross",
                    "number" => "6",
                    "answer" => $AnswerFirst == "CAPITAL" ? $AnswerFirst : "NULL",
            );
            
            $puzzleData['UNEMPLOYMENT'] = array(
                    "direction" => "accross",
                    "number" => "8",
                    "answer" => $AnswerFirst == "UNEMPLOYMENT" ? $AnswerFirst : "NULL",
            );
            
            $puzzleData['CHOICE'] = array(
                    "direction" => "accross",
                    "number" => "1",
                    "answer" => $AnswerFirst == "CHOICE" ? $AnswerFirst : "NULL",
            );
            
            $puzzleData['OPPORTUNITYCOST'] = array(
                    "direction" => "accross",
                    "number" => "2",
                    "answer" => $AnswerFirst == "OPPORTUNITYCOST" ? $AnswerFirst : "NULL",
            );
            
            
            $puzzleData['SCARCITY'] = array(
                    "direction" => "accross",
                    "number" => "4",
                    "answer" => $AnswerFirst == "SCARCITY" ? $AnswerFirst : "NULL",
            );
            
            
            $puzzleData['WANTS'] = array(
                    "direction" => "accross",
                    "number" => "7",
                    "answer" => $AnswerFirst == "WANTS" ? $AnswerFirst : "NULL",
            );
            
            $puzzleData['MIXED'] = array(
                    "direction" => "accross",
                    "number" => "9",
                    "answer" => $AnswerFirst == "MIXED" ? $AnswerFirst : "NULL",
            );
            
            $puzzleData = json_encode($puzzleData);
    

            mq("INSERT INTO puzzle(data , userid , type) VALUES ('$puzzleData' , '$userid' , '$puzzleType')");
           
           
       }
    }
    
    if($puzzleType == "demand"){
       $get_data = mfa(mq("SELECT data FROM puzzle WHERE userid = '$userid' AND type = 'demand' ")); 
       if(!empty($get_data["data"])){
           
           $old = json_decode($get_data["data"] , true);
           print_r($old);
           
           $new = array_merge($old , $puzzleData1);
           
           print_r($new);
           
           $puzzleData = json_encode($new);
           
        //   print_r($get_data["data"]);
        mq("UPDATE puzzle set data ='$puzzleData' WHERE type = 'demand' AND userid = " . $userid);
           
       }else{
           
           $AnswerFirst = $request->answer;
           $puzzleData = array(); 

            $puzzleData['DEMAND'] = array(
                    "direction" => "accross",
                    "number" => "1",
                    "answer" => $AnswerFirst == "DEMAND" ? $AnswerFirst : "NULL",
            );
            
            $puzzleData['INVERSE'] = array(
                    "direction" => "accross",
                    "number" => "3",
                    "answer" => $AnswerFirst == "INVERSE" ? $AnswerFirst : "NULL",
            );
            
            $puzzleData['DOWNWARDSLOPING'] = array(
                    "direction" => "accross",
                    "number" => "5",
                    "answer" => $AnswerFirst == "DOWNWARD-SLOPING" ? $AnswerFirst : "NULL",
            );
            
            $puzzleData['SUBSTITUTE'] = array(
                    "direction" => "accross",
                    "number" => "6",
                    "answer" => $AnswerFirst == "SUBSTITUTE" ? $AnswerFirst : "NULL",
            );
            
            $puzzleData['MARKETDEMAND'] = array(
                    "direction" => "accross",
                    "number" => "8",
                    "answer" => $AnswerFirst == "MARKET-DEMAND" ? $AnswerFirst : "NULL",
            );
            
            $puzzleData['COMPLEMENTARY'] = array(
                    "direction" => "accross",
                    "number" => "1",
                    "answer" => $AnswerFirst == "COMPLEMENTARY" ? $AnswerFirst : "NULL",
            );
            
            $puzzleData['LOWER'] = array(
                    "direction" => "accross",
                    "number" => "2",
                    "answer" => $AnswerFirst == "LOWER" ? $AnswerFirst : "NULL",
            );
            
            
            $puzzleData['DEMANDSCHEDULE'] = array(
                    "direction" => "accross",
                    "number" => "4",
                    "answer" => $AnswerFirst == "DEMAND-SCHEDULE" ? $AnswerFirst : "NULL",
            );
            
            
            $puzzleData['NORMAL'] = array(
                    "direction" => "accross",
                    "number" => "7",
                    "answer" => $AnswerFirst == "NORMAL" ? $AnswerFirst : "NULL",
            );
            
            $puzzleData['INFERIOR'] = array(
                    "direction" => "accross",
                    "number" => "9",
                    "answer" => $AnswerFirst == "INFERIOR" ? $AnswerFirst : "NULL",
            );
            $puzzleData = json_encode($puzzleData);
    

            mq("INSERT INTO puzzle(data , userid , type) VALUES ('$puzzleData' , '$userid' , '$puzzleType')");
           
           
       }
    }
   
}
elseif($request->option == 5){
    
    $userid = $request->id;
    $puzzleType =  $request->type;
    if($puzzleType == "intro"){
    $get_data = mfa(mq("SELECT data FROM puzzle WHERE userid = '$userid' AND type = 'intro' "));
    }
    
    $existing = json_decode($get_data["data"] , true);
    $array_respond["existing_data"] = $existing;
    
    
}
elseif($request->option == 6){
    
    $userid = $request->id;
    $puzzleType =  $request->type;
    if($puzzleType == "demand"){
    $get_data = mfa(mq("SELECT data FROM puzzle WHERE userid = '$userid' AND type = 'demand' "));
    }
    
    $existing = json_decode($get_data["data"] , true);
    $array_respond["existing_data"] = $existing;
    
    
}
elseif($request->option == 7){
    
    $userid = $request->id;
    $puzzleType =  $request->type;
    if($puzzleType == "demand"){
    $get_data = mfa(mq("SELECT data FROM puzzle WHERE userid = '$userid' AND type = 'demand' "));
    }
    
    $existing = json_decode($get_data["data"] , true);
    $array_respond["existing_data"] = $existing;
    
    
}


echo json_encode($array_respond);
