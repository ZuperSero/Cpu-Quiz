<?php
/**
 * Created by PhpStorm.
 * User: ZuperZero
 * Date: 02/06/2018
 * Time: 20.31
 */

//generate a random string of 12 letters and numbers
function generateRandomString($length = 12) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}


//get information from POST request
if($_SERVER["REQUEST_METHOD"] == "POST"){

    $email  = trim($_POST['email']);
    $passwd = trim($_POST['password']);


//figure out a way to involve a salt (fetch it from the database)
    $passwdhash = hash("sha512", $passwd);


    try{
//change this line if the ip changes
        $conn = new PDO("mysql:host=192.168.43.127;dbname=quiz;", "root","" );
    }
    catch(PDOException $e){
        die("connection failed: $e");
    }

    $sql    =   $conn-> prepare("SELECT * FROM users WHERE email = '".$email."' and password = '".$passwdhash."'");
    $sql    ->  execute();
    $result =   $sql->fetchAll(PDO::FETCH_ASSOC);

//if the account is found in the table, generate a token and
    if(count($result)> 0) {
        $token = hash("sha256", $randomString);
        $updateToken = $conn->prepare("UPDATE users SET token='" . $token . "' where email='" . $email . "'");

//I actually have no clue?
        if ($updateToken->execute()) {
            $result[0]["token"] = $token;
        }
        $obj = ["status" => "200", "statusDescription" => "OK"];
        $jsonObj = "[" . json_encode($obj) . "]";
    }
    else
    {
        echo "sorry the credentials given does not exist.";
    }
    echo $jsonObj;
}
?>