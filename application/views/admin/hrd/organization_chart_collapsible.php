<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); //print_r($employees); 

/* Build Tree */
function buildTree($data, $parentId) {
    $branch = [];
    foreach ($data as $row) {
        if ($row["reporting_manager"] == $parentId && $row["staffid"] != $parentId) {
            $children = buildTree($data, $row["staffid"]);
            if (!empty($children)) {
                $row["children"] = $children;
            }
            $branch[] = $row;
        }
    }
    return $branch;
}

/* Print collapsible tree */
function printTree($tree) {

    echo "<ul>";
    foreach ($tree as $node) {
	$branchname=$node['branch'] ? get_staff_branch_name($node['branch']):"";
    $designation_title=$node['designation_id'] ? get_staff_designations_name($node['designation_id']):"";

        $hasChildren = !empty($node["children"]);
        $class = $hasChildren ? "parent" : "nos";

        echo "<li>";
        echo "<div class='node $class'><span class='cell'>  <i class='fa-regular fa-user'></i> " .
             $node["full_name"] . "</span> (<span class='cell'> " . $designation_title . "</span> ) <span class='cell' style='float: right;'>  <i class='fa-solid fa-location-dot'></i> " .$branchname .
             "</span></div>";

        if ($hasChildren) {
            printTree($node["children"]);
        }
        echo "</li>";
    }
    echo "</ul>";
}

$ids = [];

foreach ($employees as $row) {
    if ($row['staffid'] == $row['reporting_manager']) {
        $ids[] = $row['staffid'];
    }
}

if(isset($ids)&&$ids){
$ids=$ids[0];
}else{
$ids=73;
}
//echo $ids;echo "XXXXXXX";

$tree = buildTree($employees, $ids);
?>
<style>
ul { list-style-type: none; padding-left: 20px; }
.node {
    cursor: pointer;
    padding: 5px 8px;
    display: inline-block;
    background: #f1f1f1;
    border-radius: 5px;
    margin-bottom: 5px;
    border: 1px solid #ccc;
	width: 100%;
}
.parent::before {
    content: "\25BC"; /*  Down */
    color: #444;
    margin-right: 5px;
    font-size: 14px;
}

.parent.open::before {
    content: "\25B2"; /*  Up */
}

.nos::before {
    content: "  -- "; /*  Down */
    color: #444;
    margin-right: 5px;
    font-size: 14px;
}

.nos.open::before {
    content: "  -- "; /*  Up */
}

/* hide children by default */
ul ul { display: none; }
ul.open > ul { display: block; }
</style>
<div id="wrapper">
  <div class="content">
  <h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-neutral-700 tw-mb-2"><span class="pull-left display-block mright5 tw-mb-2"><i class="fa-solid fa-chart-gantt tw-mr-2 "></i>  <?php echo $title;?> [ <?php echo get_staff_company_name(); ?> ] </span></h4>
  
    <div class="row tw-mt-2">
      <div class="col-md-12">
        <div class="panel_s">
          <div class="panel-body panel-table-fullxx">
            <h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-neutral-700"><?php echo $title;?></h4>

<div class="table-responsive">
<div id="orgchart">
    <span class="node parent open">Jyoti Verma</span>
    <?php printTree($tree); ?>
</div>
            </div>

   



          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php init_tail(); ?>
<script>
document.querySelectorAll(".parent").forEach(item => {
    item.addEventListener("click", function (e) {
        e.stopPropagation();
        let li = this.parentElement;
        let subUl = li.querySelector("ul");

        if (subUl) {
            subUl.style.display = subUl.style.display === "block" ? "none" : "block";
            this.classList.toggle("open");
        }
    });
});
</script>
</body></html>


