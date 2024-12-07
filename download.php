<?php
include "include/header.php";
include "include/config.php";

?>

<section class="rootnavigator mx-lg-5 mx-md-1 mx-0 p-3">
    <div class="container-fluid">
        <div class="row">
            <div class="col"><a href="index.php">หน้าแรก</a>/Download</div>
        </div>
    </div>
</section>
<section class="linkSection">
    <div class="container-fluid">
        <div class="row mx-lg-5 mx-md-1 mx-sm-0">
            <div class="col-lg-12 col-md-12 col-sm-12 mt-3">
                <h3>ดาวน์โหลดเอกสาร</h3>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 mt-3 list-download">
                <ul>
                    <?php
                    $sql = "SELECT id,link_name,link_url,description,update_date,hit FROM download WHERE status='Y' order by seq,link_name";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        // output data of each row
                        while ($row = $result->fetch_assoc()) {
                            echo "<li><h4>" . $row["link_name"] . "</h4><p>" . $row["description"] . "</p>
                                <p class=\"text-end\">จำนวน download <span id=\"lastdownload" . $row["id"] . "\">" . $row["hit"] . "</span> | Update : " . convertToThaiDateShort($row["update_date"]) . "</p><p class=\"text-center\">
                                    <a href=\"" . $row["link_url"] . "\" download title=\"" . $row["link_name"] . "\" onclick=\"linkDownload.update(" . $row["id"] . ",'lastdownload" . $row["id"] . "');\">ดาวน์โหลด</a></p></li>";
                        }
                    } else {
                        echo "<div class=\"mb-5\">ไม่มีข้อมูล</div>";
                    }
                    $conn->close();
                    ?>
                </ul>
            </div>
        </div>
    </div>
</section>
<?php include "include/footer.php" ?>