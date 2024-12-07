<?php
include "inc/config.php";

// $options = [
//     'secret' => 'Medicine_Bhumibol'
// ];

// //===== Encode From original Password ====== //
// echo "1";
$sql = "SELECT hpassword,password,id FROM user";
// echo "2";
$result1 = $conn->query($sql);
// echo "3";
echo $result1->num_rows;
echo $sql;
// if($result1 && $result1->num_rows > 0) {
//     echo "4";
//     while ($row1 = $result1->fetch_assoc()) {

        //$afterhash = password_hash($row1["password"],PASSWORD_BCRYPT,$options);
        // $secretkey = 'Medicine_Bhumibol';
        // $pwd = $row1["password"];
        // $pwd_with_secret = hash_hmac("sha256", $pwd, $secretkey);
        // $pwd_hashed = $row1["hpassword"];
        // echo "<br>id :".$row1["id"]."   ";
        // if (password_verify($pwd_with_secret, $pwd_hashed)) {
        //     echo "Password matches.";
        // }
        // else {
        //     echo "Password incorrect.";
        // }

//         $pwd = password_hash($row1["password"],PASSWORD_DEFAULT);
//         $pwd_hashed = $row1["hpassword"];
//         echo "<br>id :".$row1["id"]."   ";
//         if (password_verify($pwd, $pwd_hashed)) {
//             echo "Password matches.";
//         }
//         else {
//             echo "Password incorrect.";
//         }
//     }
// } else {
//     echo "<br>End";
// }


// $sql = "UPDATE user SET hpassword=".password_hash(password,PASSWORD_BCRYPT,$options);
// $stmt = $conn->prepare($sql);
// $stmt->bind_param('sssssi',$topic,$status,$keyword,$image_link,$url,$seq);
// $stmt->execute();
// if ($stmt->affected_rows>0) {
// }
$conn->close();
?>
