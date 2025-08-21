<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <h4 class="no-margin"><?php echo $title; ?></h4>
                                <hr class="hr-panel-heading" />
                            </div>
                        </div>

                        <?php if (!empty($log_files)): ?>
                        <div class="row">
                            <div class="col-md-4">
                                <h5>Available Log Files</h5>
                                <div class="list-group">
                                    <?php foreach ($log_files as $file): ?>
                                    <a href="<?php echo admin_url('logs?file=' . urlencode($file['name'])); ?>" 
                                       class="list-group-item <?php echo ($selected_file == $file['name']) ? 'active' : ''; ?>">
                                        <h6 class="list-group-item-heading"><?php echo $file['name']; ?></h6>
                                        <p class="list-group-item-text">
                                            Size: <?php echo formatBytes($file['size']); ?> | 
                                            Modified: <?php echo date('Y-m-d H:i:s', $file['modified']); ?>
                                        </p>
                                    </a>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            
                            <div class="col-md-8">
                                <?php if ($selected_file): ?>
                                <div class="row">
                                    <div class="col-md-12">
                                        <h5>Log Content: <?php echo $selected_file; ?></h5>
                                        <div class="btn-group pull-right" style="margin-bottom: 15px;">
                                            <a href="<?php echo admin_url('logs/download/' . urlencode($selected_file)); ?>" 
                                               class="btn btn-info btn-sm">
                                                <i class="fa fa-download"></i> Download
                                            </a>
                                            <a href="<?php echo admin_url('logs/clear/' . urlencode($selected_file)); ?>" 
                                               class="btn btn-danger btn-sm" 
                                               onclick="return confirm('Are you sure you want to clear this log file?');">
                                                <i class="fa fa-trash"></i> Clear
                                            </a>
                                        </div>
                                        <div class="clearfix"></div>
                                        
                                        <div style="background: #f8f9fa; border: 1px solid #dee2e6; border-radius: 4px; padding: 15px; max-height: 600px; overflow-y: auto; font-family: 'Courier New', monospace; font-size: 12px; white-space: pre-wrap;">
                                            <?php if (!empty($log_content)): ?>
                                                <?php echo htmlspecialchars($log_content); ?>
                                            <?php else: ?>
                                                <em>Log file is empty or could not be read.</em>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <?php else: ?>
                                <div class="alert alert-info">
                                    <i class="fa fa-info-circle"></i> Select a log file from the left to view its contents.
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php else: ?>
                        <div class="alert alert-warning">
                            <i class="fa fa-exclamation-triangle"></i> No log files found in the logs directory.
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php init_tail(); ?>

<?php
function formatBytes($bytes, $precision = 2) {
    $units = array('B', 'KB', 'MB', 'GB', 'TB');
    
    for ($i = 0; $bytes > 1024; $i++) {
        $bytes /= 1024;
    }
    
    return round($bytes, $precision) . ' ' . $units[$i];
}
?>
