<section class="paging">
    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center" id="displayPaging">
            <?php
            echo ($max_page > 1 ? "<li class=\"page-item\"><a class=\"page-link\" href=\"?pageno=1&typenews=".$typenews."\" title=\"หน้าแรกสุด\"><span aria-hidden=\"true\">&laquo;</span></a></li>":"");
            for ($i=0;$i<$max_page;$i++) {
                echo "<li class=\"page-item ".(($i+1==$pageno) ? "active":"")."\"><a class=\"page-link\" href=\"?pageno=".($i+1)."&typenews=".$typenews."\">".($i+1)."</a></li>";
            }
            echo ($max_page > 1 ? "<li class=\"page-item\"><a class=\"page-link\" href=\"?pageno=".$max_page."&typenews=".$typenews."\" title=\"หน้าสุดท้าย\"><span aria-hidden=\"true\">&raquo;</span></a></li>":"");
            ?>
            <!-- <li class="page-item disabled"><a class="page-link" href="#" title="หน้าแรกสุด"><span aria-hidden="true">&laquo;</span></a></li>
            <li class="page-item"><a class="page-link" href="#" title="ก่อนหน้า"><span aria-hidden="true">&lt;</span></a></li>
            <li class="page-item active"><a class="page-link" href="#">1</a></li>
            <li class="page-item"><a class="page-link" href="#">2</a></li>
            <li class="page-item"><a class="page-link" href="#">3</a></li>
            <li class="page-item"><a class="page-link" href="#" title="หน้าถัดไป"><span aria-hidden="true">&gt;</span></a></li>
            <li class="page-item"><a class="page-link" href="#" title="หน้าสุดท้าย"><span aria-hidden="true">&raquo;</span></a></li> -->

        </ul>
    </nav>
</section>