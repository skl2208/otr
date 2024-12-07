<?php
include "include/header.php";
include "include/config.php";

$numRecPerPage = 10;
$max_page = 0;
$pageno = 1;
$total_rec = 0;

?>
<script>
    function validate_data() {
        const searchTxt = document.getElementById("searchTxt").value;
        const select_group = document.getElementById("select_group").value;
        const research_year = document.getElementById("research_year").value;
    
        model_View_Research.list(1, searchTxt, select_group, research_year);
    }
    $(function() {

        const research_year = document.getElementById("research_year");

        research_year.addEventListener("change", function() {
            if ((research_year.value).length == 2) {
                research_year.value = 2500 + parseInt(research_year.value);
            }
        });

        model_View_Research.list(1, "", "", "");
    });
</script>
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
                <form>
                    <input type="text" name="searchTxt" id="searchTxt" style="width:10em;" placeholder="คำค้นหา">
                    <input type="text" name="research_year" id="research_year" style="width:10em;" placeholder="ปีที่สิ้นสุดหรือตีพิมพ์งานวิจัย">
                    <select name="select_group" id="select_group" style="width:15em;">
                        <option value="" selected>ทุกประเภท</option>
                        <?php
                        $sql = "SELECT group_name FROM research_group ORDER BY group_name";
                        $result = $conn->query($sql);
                        if ($result && $result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<option value=\"".$row["group_name"]."\">" . $row["group_name"] . "</option>";
                            }
                        }
                        ?>
                    </select>
                    <button class="btn-submit-no-w100" type="button" onclick="javascript:validate_data();">ค้นหา</button>
                </form>
            </div>
        </div>
        <div class="mx-lg-5 mx-md-1 mx-sm-0 my-3" id="displayTotalRec"></div>
        <div class="list-research">
            <div class="row mx-lg-5 mx-md-1 mx-sm-0 shadow-sm list-research-item">
                <div class="col-12 mt-2 mb-2 text-lg-end text-md-end text-center">
                    <span class="topic-header">ชื่อผู้สร้างงานวิจัย</span>
                </div>
                <div class="col-12 mt-2 mb-2">
                    <a href="javascript:void(0)">หัวข้องานวิจัย safsdfsd sdafsadfsdfsdfsafsd afsadfsfsfsfsfsd sadfsdfsf asdfsafsadfsaf asfsdfsfsa afdsafsafsafsf asfsadfsafs asfsadfasfsf afssafdsaf</a>
                </div>
                <div class="col-12 mt-2 mb-2 text-center">
                    เอกสารงานวิจัย
                </div>
            </div>
            <div class="row mx-lg-5 mx-md-1 mx-sm-0 shadow-sm list-research-item">
                <div class="col-12 mt-2 mb-2 text-lg-end text-md-end text-center">
                    <span class="topic-header">สุขุม ลิ้มกลิ่นแก้ว</span>
                </div>
                <div class="col-12 mt-2 mb-2">
                    <a href="#">หัวข้องานวิจัย safsdfsd sdafsadfsdfsdfsafsd </a>
                </div>
                <div class="col-12 mt-2 mb-2 text-center">
                    เอกสารงานวิจัย
                </div>
            </div>
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
<?php include "include/footer.php" ?>