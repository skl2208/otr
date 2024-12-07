<?php

function convertDBToThai($inDateTime) {

    $nameTh = array('มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฏาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'
    );
    $nameabbrTh = array('ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.'
    );
    $dt = explode(" ",$inDateTime);
    $myDate = explode("-",$dt[0]);
    return (int) $myDate[2]." ".$nameabbrTh[$myDate[1]-1]." ".($myDate[0]+543)." ".substr($dt[1],0,5);

}

function get_client_ip() {
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
       $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}

function display_items_menu($conn,$parent_id) {

    $sql = "SELECT menu_name,id,status FROM menu WHERE status='Y' AND is_item='Y' AND parent_id=$parent_id ORDER BY seq";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<li>";
            echo "<a class=\"dropdown-item\" href=\"contentpage.php?menuitem_id=".$row["id"]."\">".$row["menu_name"]."</a>";
            echo "</li>";
        }
    }
}

function convertToThaiDateShort($inDate)
{
    $outTime = "";
    $shortMonth = array('ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.');

    $inDate = str_replace("/","-",trim($inDate));
    $inDate = str_replace(" ","T",$inDate);
    $beforeOutDate = explode("T",$inDate);
    $outDate = explode("-", $beforeOutDate[0]);
    if(count($beforeOutDate)>1) {
        $outTimeArray = explode(":",$beforeOutDate[1]);
        $outTime = $outTimeArray[0].":".$outTimeArray[1];
    }

    return intval($outDate[2]) . " " . $shortMonth[intval($outDate[1]) - 1] . " " . (intval($outDate[0]) + 543)." ".$outTime;
}


function encryptmsg($string) {
    $key="bhumibol20242567";
    $result = '';
    for($i=0; $i<strlen($string); $i++) {
      $char = substr($string, $i, 1);
      $keychar = substr($key, ($i % strlen($key))-1, 1);
      $char = chr(ord($char)+ord($keychar));
      $result.=$char;
    }
   
    //return base64_encode($result);
    return bin2hex($result);
  }
   
  function decryptmsg($string) {
    $key="bhumibol20242567";
    $result = '';
    //$string = base64_decode($string);
    $string = pack("H*", $string);
   
    for($i=0; $i<strlen($string); $i++) {
      $char = substr($string, $i, 1);
      $keychar = substr($key, ($i % strlen($key))-1, 1);
      $char = chr(ord($char)-ord($keychar));
      $result.=$char;
    }
   
    return $result;
  }
?>
