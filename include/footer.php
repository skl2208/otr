    <?php
    //include "config.php";
    ?>
    <section class="footer-sect shadow-sm">
        <div class="container-fluid mt-4 mb-4">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-12 order-lg-first order-md-first order-last text-lg-start text-md-start text-center mt-2">
                    <img src="images/logo_MED_white.png" title="MED" alt="MED logo" style="width:100%;height:auto;max-width:150px;">
                </div>
                <div class="col-lg-6 col-md-6 col-12 text-center">
                    <a href="https://web.facebook.com/%E0%B8%81%E0%B8%AD%E0%B8%87%E0%B8%AD%E0%B8%B2%E0%B8%A2%E0%B8%B8%E0%B8%A3%E0%B8%81%E0%B8%A3%E0%B8%A3%E0%B8%A1-%E0%B8%A3%E0%B8%9E%E0%B8%A0%E0%B8%B9%E0%B8%A1%E0%B8%B4%E0%B8%9E%E0%B8%A5%E0%B8%AD%E0%B8%94%E0%B8%B8%E0%B8%A5%E0%B8%A2%E0%B9%80%E0%B8%94%E0%B8%8A-109144221900926/?_rdc=1&_rdr" target="_blank"><img src="images/fb_logo.png" title="Facebook" alt="Facebook Logo"></a><a href="#"><img src="images/line_logo.png" title="Line" alt="Line Logo"></a><a href="#"><img src="images/twitter_logo.png" title="Twitter" alt="Twitter logo"></a><a href="#"><img src="images/youtube_logo.png" title="Youtube" alt="Youtube Logo"></a><br>&copy; โรงพยาบาลภูมิพลอดุลยเดช
                    กรมแพทย์ทหารอากาศ All
                    Rights Reserved<br>
                    171 กองอายุรกรรม อาคารคุ้มเกล้า ชั้น 4 โรงพยาบาลภูมิพลอดุยเดช ถ. พหลโยธิน แขวงคลองถนน เขตสายไหม กทม. 10220<br>
                    e-mail : BAH.medicine@gmail.com
                </div>
                <div class="col-lg-3"></div>
                <?php
                $today = date("Y-m-d");
                $today_visitor = 0;
                $acc_visitor = 0;

                $sql = "SELECT visitor,lastvisitor FROM visitor WHERE indate='$today'";
                $result0 = $conn->query($sql);
                if($result0 && $result0->num_rows > 0) {
                    //======== กรณีวันนั้นมีการสร้าง record ไว้แล้ว ============
                    $row0 = $result0->fetch_assoc();
                    $today_visitor = $row0["visitor"] + 1;
                    $acc_visitor = $row0["lastvisitor"] + 1;

                } else {
                    //========= กรณีเป็นวันใหม่ ไม่มี record มาก่อน ก็ให้ดึงจากวันที่ล่าสุดที่มีการเข้าเวป ========
                    $sql = "SELECT visitor,lastvisitor FROM visitor ORDER BY indate DESC LIMIT 1";
                    $result = $conn->query($sql);
                    if ($result && $result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        $today_visitor = 1;
                        $acc_visitor = $row["lastvisitor"] + 1;
                    } else {
                        //======= เกิดขึ้นเฉพาะเมื่อสร้างเวปใหม่ ยังไม่มีคนเข้าชม ========
                        $today_visitor = 1;
                        $acc_visitor = 1;
                    }
                }
                $conn->close();
                ?>
                <div class="col-12 text-center mt-2 mb-2">VISITOR <?php echo $acc_visitor ?> : Today <?php echo $today_visitor ?></div>
            </div>
        </div>
    </section>
    </body>
    </html>