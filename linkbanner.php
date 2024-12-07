<?php include "include/header.php" ?>
<section class="rootnavigator mx-lg-5 mx-md-1 mx-0 p-3">
        <div class="container-fluid">
            <div class="row">
                <div class="col"><a href="index.php">หน้าแรก</a>/LINK</div>
            </div>
        </div>
    </section>
    <section class="linkSection">
        <div class="container-fluid">
            <div class="row mx-lg-5 mx-md-1 mx-sm-0">
                <div class="col-lg-12 col-md-12 col-sm-12 mt-3">
                    <h3>แนะนำเวปไซต์</h3>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 mt-3 list-linksite">
                    <ul>
                    <?php 
                        include "include/config.php";

                        $sql = "SELECT link_name,link_url FROM link WHERE status='Y' order by seq,link_name";
                        $result = $conn->query($sql);
                        if($result) {
                            if ($result->num_rows > 0) {
                                // output data of each row
                                while($row = $result->fetch_assoc()) {
                                    echo "<li><a href=\"".$row["link_url"]."\" target=\"_blank\"
                                    title=\"".$row["link_name"]."\">".$row["link_name"]."</a></li>";
                                }
                            } else {
                                echo "<div class=\"mb-5\">ไม่มีข้อมูล</div>";
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