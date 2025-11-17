<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php
// Ensure required data exists
$cal           = isset($calendar) && is_array($calendar) ? $calendar : null;
$shiftDetails  = isset($shift_details) && is_array($shift_details) ? $shift_details : [];
$statusCounter = isset($status_counter) && is_array($status_counter) ? $status_counter : [];

$shift_id        = $shiftDetails[0]['shift_id'] ?? '-';
$get_shift_code  = $shiftDetails[0]['shift_code'] ?? '-';
$get_shift_in    = $shiftDetails[0]['shift_in'] ?? '-';
$get_shift_out   = $shiftDetails[0]['shift_out'] ?? '-';
$get_saturday_rule = $shiftDetails[0]['saturday_rule'] ?? '-';

$staff_type = (isset($GLOBALS['current_user']->staff_type) && $GLOBALS['current_user']->staff_type)
    ? $GLOBALS['current_user']->staff_type
    : null;

if (empty($shiftDetails)) {
    echo 'Shift Not Mapped. Contact web admin.';
    return;
}

// Simple styles for PDF table
?>
<style>
    table {
        border-collapse: collapse;
        width: 100%;
        font-size: 10px;
    }
    table, th, td {
        border: 1px solid #ddd;
    }
    th, td {
        padding: 4px 6px;
        text-align: left;
    }
    .bg-sunday {
        background-color: #f8d7da;
    }
    .bg-saturday {
        background-color: #fff3cd;
    }
    .header-table th {
        background-color: #f0f0f0;
        font-weight: bold;
    }
</style>

<h3><?php echo e(get_staff_full_name()); ?> - Attendance</h3>
<?php if ($cal) { ?>
    <p>
        Month: <strong><?php echo date('F Y', strtotime(sprintf('%04d-%02d-01', (int) $cal['year'], (int) $cal['month']))); ?></strong>
    </p>
<?php } ?>

<table class="header-table">
    <tr>
        <th>Month for : <?php echo $cal ? date('F Y', strtotime(sprintf('%04d-%02d-01', (int) $cal['year'], (int) $cal['month']))) : '-'; ?></th>
        <th><?php echo e(get_staff_full_name()); ?></th>
        <th>Employee Code : <?php echo get_staff_fields('', 'employee_code'); ?></th>
        <th>Shift Code : <?php echo e($get_shift_code); ?></th>
        <th>Shift InTime : <?php echo e($get_shift_in); ?></th>
        <th>Shift OutTime : <?php echo e($get_shift_out); ?></th>
        <th>Staff Type : <?php if ($staff_type) { echo '[ ' . get_staff_staff_type($staff_type) . ' ]'; } ?></th>
        <th>Saturday Rule : <?php echo get_saturday_rule($get_saturday_rule); ?></th>
    </tr>
</table>

<p>
    <?php
    foreach ($statusCounter as $sc) {
        $fhTitle = '';
        if (isset($sc['first_half']) && is_numeric($sc['first_half'])) {
            $fhTitle = get_attendance_status_title((int) $sc['first_half']);
        }
        if (isset($sc['second_half']) && is_numeric($sc['second_half']) && ($sc['second_half'] == 8 || $sc['second_half'] == 4)) {
            $fhTitle = get_attendance_status_title((int) $sc['second_half']);
        }

        $label = $fhTitle !== '' ? $fhTitle : (isset($sc['first_half']) ? e($sc['first_half']) : '-');
        echo '<span style="border:1px solid #ccc;padding:2px 4px;margin-right:4px;">' . $label . ' (' . $sc['total_count'] . ')</span>';
    }
    ?>
</p>

<?php
// Helper functions for totals (same logic as in HTML view)
$sumPortion = 0.0;
$sumTotSecs = 0;
$sumLateSecs = 0;

$parseHms = function ($hms) {
    if (!$hms || $hms === '-') {
        return 0;
    }
    $parts = explode(':', $hms);
    if (count($parts) !== 3) {
        return 0;
    }
    return ((int) $parts[0]) * 3600 + ((int) $parts[1]) * 60 + (int) $parts[2];
};

$fmtHms = function ($secs) {
    if ($secs < 0) {
        $secs = 0;
    }
    $h = floor($secs / 3600);
    $m = floor(($secs % 3600) / 60);
    $s = $secs % 60;
    return sprintf('%02d:%02d:%02d', $h, $m, $s);
};
?>

<table>
    <thead>
    <tr>
        <th style="width:90px;">Date</th>
        <th>Day</th>
        <th>InTime</th>
        <th>OutTime</th>
        <th>First Half</th>
        <th>Second Half</th>
        <th>Portion</th>
        <th>Tot. Hrs.</th>
        <th>LateMark</th>
        <th>Remark</th>
    </tr>
    </thead>
    <tbody>
    <?php if ($cal && isset($cal['days']) && is_array($cal['days'])): ?>
        <?php foreach ($cal['days'] as $cell): ?>
            <?php
            $staffid    = get_staff_user_id();
            $company_id = get_staff_company_id();

            if (strtotime($cell['date']) > time()) {
                continue;
            }

            $bgClass = '';
            $dayName = date('l', strtotime($cell['date']));
            if ($dayName == 'Sunday') {
                $bgClass = 'bg-sunday';
            } elseif ($dayName == 'Saturday') {
                $bgClass = 'bg-saturday';
            }
            ?>
            <tr class="<?php echo $bgClass; ?>">
                <td><strong><?php echo date('d-m-Y', strtotime($cell['date'])); ?></strong></td>
                <td><?php echo $dayName; ?></td>
                <?php
                if (!empty($cell['items'])) {
                    $it = $cell['items'][0];

                    // In time
                    $inTimeRaw = $it['in_time'] ?? null;
                    if ($inTimeRaw) {
                        if (strpos($inTimeRaw, ' ') !== false) {
                            $inTime = date('H:i:s', strtotime($inTimeRaw));
                        } else {
                            $inTime = $inTimeRaw;
                        }
                        $inTime = e($inTime);
                    } else {
                        $inTime = '-';
                    }

                    // Out time
                    $outTimeRaw = $it['out_time'] ?? null;
                    if ($outTimeRaw) {
                        if (strpos($outTimeRaw, ' ') !== false) {
                            $outTime = date('H:i:s', strtotime($outTimeRaw));
                        } else {
                            $outTime = $outTimeRaw;
                        }
                        $outTime = e($outTime);
                    } else {
                        $outTime = '-';
                    }

                    // Calculate statuses/portion via helper
                    $result     = getAttendanceStatus($staffid, $shift_id, $cell['date'], $staff_type, $it['attendance_id']);
                    $firstHalf  = get_attendance_status_title($result['status']);
                    $secondHalf = get_attendance_status_title($result['substatus']);
                    $position   = number_format($result['position'], 2);
                    $remarks    = $result['remarks'];

                    $totals = e($it['total_hours'] ?? '');
                    $lates  = e($it['late_mark'] ?? '');

                    echo '<td>' . $inTime . '</td>';
                    echo '<td>' . $outTime . '</td>';
                    echo '<td>' . $firstHalf . '</td>';
                    echo '<td>' . $secondHalf . '</td>';
                    echo '<td>' . ($position ?: '-') . '</td>';
                    echo '<td>' . ($totals ?: '-') . '</td>';
                    echo '<td>' . ($lates ?: '-') . '</td>';
                    echo '<td>' . ($remarks ?: '-') . '</td>';

                    $portionVal = 0.0;
                    if ($position !== '-' && $position !== '') {
                        $portionVal = (float) $position;
                    }
                    $sumPortion += $portionVal;
                    $sumTotSecs += $parseHms($totals ?: '00:00:00');
                    $sumLateSecs += $parseHms($lates ?: '00:00:00');
                } else {
                    $result     = getAttendanceStatus($staffid, $shift_id, $cell['date'], $staff_type);
                    $firstHalf  = $result['status'];
                    $secondHalf = $result['substatus'];
                    $position   = number_format($result['position'], 2);
                    $remarks    = $result['remarks'];

                    $inTime  = '00:00';
                    $outTime = '00:00';

                    echo '<td>' . $inTime . '</td>';
                    echo '<td>' . $outTime . '</td>';
                    echo '<td>' . get_attendance_status_title($firstHalf) . '</td>';
                    echo '<td>' . get_attendance_status_title($secondHalf) . '</td>';
                    echo '<td>' . $position . '</td>';
                    echo '<td>00:00:00</td>';
                    echo '<td>00:00:00</td>';
                    echo '<td>' . ($remarks ?: '-') . '</td>';

                    $sumPortion += (float) $position;
                }
                ?>
            </tr>
        <?php endforeach; ?>
        <?php
        $sumTotStr  = $fmtHms($sumTotSecs);
        $sumLateStr = $fmtHms($sumLateSecs);
        ?>
        <tr>
            <td colspan="6" style="text-align:right;"><strong>Totals:</strong></td>
            <td title="Portion"><strong><?php echo number_format($sumPortion, 2); ?></strong></td>
            <td title="Tot. Hrs."><strong><?php echo $sumTotStr; ?></strong></td>
            <td title="LateMark"><strong><?php echo $sumLateStr; ?></strong></td>
            <td>&nbsp;</td>
        </tr>
    <?php endif; ?>
    </tbody>
</table>


