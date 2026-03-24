<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="row mb-2">
      <div class="panel_s">
        <div class="panel-body panel-table-full">
		  <div class="row ">
<h4>Disk Space (Total / Free / Used)</h4>
<?php
$totalDisk = disk_total_space("/");
$freeDisk  = disk_free_space("/");
$usedDisk  = $totalDisk - $freeDisk;
?>
<table border="1" cellpadding="8" cellspacing="0" width="100%" class="table table-clients number-index-2 dataTable no-footer">
  <thead>
    <tr>
      <th>Total Disk</th>
      <th><?php echo round($totalDisk / 1024 / 1024 / 1024, 2);?> GB</th>
    </tr>
	<tr>
      <th>Free Disk</th>
      <th><?php echo round($freeDisk  / 1024 / 1024 / 1024, 2);?> GB</th>
    </tr>
	<tr>
      <th>Used Disk</th>
      <th><?php echo round($freeDisk  / 1024 / 1024 / 1024, 2);?> GB</th>
    </tr>
  </thead>
  
</table>

<h4>RAM Details (Linux Only)</h4>
<?php
$mem = file_get_contents("/proc/meminfo");

preg_match('/MemTotal:\s+(\d+)/', $mem, $total);
preg_match('/MemAvailable:\s+(\d+)/', $mem, $available);

$totalRAM = $total[1] / 1024 / 1024;
$freeRAM  = $available[1] / 1024 / 1024;
$usedRAM  = $totalRAM - $freeRAM;
?>
<table border="1" cellpadding="8" cellspacing="0" width="100%" class="table table-clients number-index-2 dataTable no-footer">
  <thead>
    <tr>
      <th>Total RAM:</th>
      <th><?php echo round($totalRAM, 2);?> GB</th>
    </tr>
	<tr>
      <th>Free RAM:</th>
      <th><?php echo round($freeRAM, 2);?> GB</th>
    </tr>
	<tr>
      <th>Used RAM:</th>
      <th><?php echo round($usedRAM, 2);?> GB</th>
    </tr>
  </thead>
  
</table>

<h4>CPU Info</h4>

<table border="1" cellpadding="8" cellspacing="0" width="100%" class="table table-clients number-index-2 dataTable no-footer">
  <thead>
    <tr>
      <th>CPU Cores:</th>
      <th><?php echo shell_exec("nproc");?></th>
    </tr>
	<tr>
      <th>CPU Model:</th>
      <th><?php echo shell_exec("cat /proc/cpuinfo | grep 'model name' | head -1");?></th>
    </tr>
  </thead>
</table>

<h4>Server Load (CPU Load)</h4>
<table border="1" cellpadding="8" cellspacing="0" width="100%" class="table table-clients number-index-2 dataTable no-footer">
  <thead>
    <tr>
      <th>Load Average:</th>
      <th><?php $load = sys_getloadavg(); echo implode(", ", $load);?></th>
    </tr>
  </thead>
  
</table>
		
		
		
		</div>

          
        </div>
      </div>
    </div>
  </div>
</div>
</body>
<?php init_tail(); ?>
</html>