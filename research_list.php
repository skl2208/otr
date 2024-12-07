<?php
include "include/header.php";
include "include/config.php";
?>
<section class="rootnavigator mx-lg-5 mx-md-1 mx-0 p-3">
    <div class="container-fluid">
        <div class="row">
            <div class="col"><a href="index.php">หน้าแรก</a>/งานวิจัย</div>
        </div>
    </div>
</section>
<section class="researchSection">
    <div class="container-fluid">
        <div class="row mx-lg-5 mx-md-1 mx-sm-0 align-items-center">
            <div class="col-lg-2 col-md-2 col-12  text-sm-center text-center text-lg-start text-md-start">
                <h2 class="topic-header">งานวิจัย</h2>
            </div>
            <div class="col-lg-10 col-md-10 col-12 text-lg-end text-md-end text-center">
                <input type="text" id="searchTxt" style="width:10em;" placeholder="คำค้นหา">
                <input type="text" id="research_year" style="width:10em;" placeholder="ปีที่สิ้นสุดหรือตีพิมพ์งานวิจัย">
                <select id="select_group" style="width:15em;">
                    <option value="" selected>ทุกประเภท</option>
                    <?php
                    $sql = "SELECT group_name FROM research_group ORDER BY group_name";
                    $result = $conn->query($sql);
                    if ($result && $result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value=\"" . $row["group_name"] . "\">" . $row["group_name"] . "</option>";
                        }
                    }
                    ?>
                </select>
                <button class="btn-submit-no-w100" type="button" onclick="javascript:model_View_Research.blind_data(1,document.getElementById('select_group').value,document.getElementById('searchTxt').value,document.getElementById('research_year').value)">ค้นหา</button>
            </div>
        </div>
        <div class="mx-lg-5 mx-md-1 mx-sm-0 my-3" id="displayTotalRec"></div>
        <div class="list-research">
        </div>
    </div>
</section>
<section class="paging">
    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center" id="displayPaging">
        </ul>
    </nav>
</section>
<div class="spinner-border text-info" id="spinner"></div>
<div id="showAlert">
    <div>
        <div>
            <img src="images/icon_ok.png">
        </div>
        <div></div>
        <div>
            <button onclick="javascript:showAlert.hide();" class="btnOK">ปิด</button>
        </div>
    </div>
</div>
<div id="showToast"></div>
<script>
    // function validate_data() {
    //     const searchTxt = document.getElementById("searchTxt").value;
    //     const select_group = document.getElementById("select_group").value;
    //     const research_year = document.getElementById("research_year").value;

    //     model_View_Research.blind_data(1, select_group,searchTxt,  research_year);
    // }
    $(function() {

        // const research_year = document.getElementById("research_year");

        // research_year.addEventListener("change", function() {
        //     if ((research_year.value).length == 2) {
        //         research_year.value = 2500 + parseInt(research_year.value);
        //     }
        // });

        model_View_Research.blind_data(1, '', '', '');
    });
</script>
<?php include "include/footer.php" ?>