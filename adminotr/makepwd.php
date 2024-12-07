<?php
include "inc/config.php";

$options = [
    'secret' => 'Medicine_Bhumibol'
];

//===== Encode From original Password ====== //
$sql = "SELECT password,id FROM user";
$result1 = $conn->query($sql);

if($result1 && $result1->num_rows > 0) {
    
    while ($row1 = $result1->fetch_assoc()) {

        //$afterhash = password_hash($row1["password"],PASSWORD_BCRYPT,$options);
        $secrtekey = "Medicine_Bhumibol";
        $pwd_with_secret = hash_hmac("sha256",$row1["password"],$secrtekey);
        $afterhash = password_hash($pwd_with_secret,PASSWORD_DEFAULT);

        echo "<br>ID:".$row1["id"]." password:".$row1["password"]." encode to ".$afterhash."<br>";
        echo "Updating...";

        $sql2 = "UPDATE user SET hpassword=? WHERE id=?";

        $conn->query($sql2);
        $result = $conn->affected_rows;
        echo $result;
        if($result>0) {
            echo " Updated !";
        } else {
            echo " No Updating :";
        }
    }
}

$conn->close();
?>
