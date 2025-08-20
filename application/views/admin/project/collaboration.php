<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>

<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="tw-mb-2 sm:tw-mb-4">
                    <h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-neutral-700">
                        <i class="fa-solid fa-users tw-mr-2"></i>Project Collaboration
                    </h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body mail-bg">
                        <!-- Tab Navigation -->
                        <ul class="nav nav-tabs" id="collaborationTabs">
                            <li class="active">
                                <a data-toggle="tab" href="#feedTab">
                                    <i class="fa-solid fa-rss tw-mr-2"></i>Activity Feed
                                </a>
                            </li>
                            <li>
                                <a data-toggle="tab" href="#calendarTab">
                                    <i class="fa-solid fa-calendar tw-mr-2"></i>Calendar View
                                </a>
                            </li>
                        </ul>

                        <!-- Tab Content -->
                        <div class="tab-content" style="padding: 20px 0;">
                            <!-- Feed Tab -->
                            <div id="feedTab" class="tab-pane fade in active">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h4 class="tw-mb-4">
                                            <i class="fa-solid fa-stream tw-mr-2"></i>Recent Activity Stream
                                        </h4>
                                        
                                        <!-- Activity Feed -->
                                        <div class="activity-feed" style="max-height: 600px; overflow-y: auto;">
                                            <?php if (!empty($activity_logs)): ?>
                                                <?php foreach ($activity_logs as $log): ?>
                                                    <div class="activity-item tw-mb-4 tw-border-l-4 tw-border-blue-500 tw-pl-4">
                                                        <div class="activity-header tw-flex tw-items-center tw-mb-2">
                                                            <div class="activity-avatar tw-mr-3">
                                                                <?php if ($log['staffid'] > 0): ?>
                                                                    <?php echo staff_profile_image($log['staffid'], ['tw-h-8 tw-w-8 tw-rounded-full'], 'small'); ?>
                                                                <?php else: ?>
                                                                    <div class="tw-h-8 tw-w-8 tw-rounded-full tw-bg-gray-300 tw-flex tw-items-center tw-justify-center">
                                                                        <i class="fa fa-cog tw-text-gray-600"></i>
                                                                    </div>
                                                                <?php endif; ?>
                                                            </div>
                                                            <div class="activity-meta tw-flex-1">
                                                                <div class="activity-author tw-font-semibold">
                                                                    <?php echo htmlspecialchars($log['full_name'] ?? 'System'); ?>
                                                                </div>
                                                                <div class="activity-time tw-text-sm tw-text-gray-500">
                                                                    <?php echo _dt($log['date']); ?>
                                                                </div>
                                                            </div>
                                                            <div class="activity-type">
                                                                <?php 
                                                                $type_color = '';
                                                                $type_icon = '';
                                                                switch ($log['project_type']) {
                                                                    case 1: 
                                                                        $type_color = 'tw-bg-blue-100 tw-text-blue-800';
                                                                        $type_icon = 'fa-chart-gantt';
                                                                        break;
                                                                    case 2: 
                                                                        $type_color = 'tw-bg-green-100 tw-text-green-800';
                                                                        $type_icon = 'fa-tasks';
                                                                        break;
                                                                    case 3: 
                                                                        $type_color = 'tw-bg-red-100 tw-text-red-800';
                                                                        $type_icon = 'fa-exclamation-triangle';
                                                                        break;
                                                                    case 4: 
                                                                        $type_color = 'tw-bg-yellow-100 tw-text-yellow-800';
                                                                        $type_icon = 'fa-flag';
                                                                        break;
                                                                    default: 
                                                                        $type_color = 'tw-bg-gray-100 tw-text-gray-800';
                                                                        $type_icon = 'fa-info-circle';
                                                                }
                                                                ?>
                                                                <span class="tw-px-2 tw-py-1 tw-rounded-full tw-text-xs tw-font-medium <?php echo $type_color; ?>">
                                                                    <i class="fa-solid <?php echo $type_icon; ?> tw-mr-1"></i>
                                                                    <?php 
                                                                    switch ($log['project_type']) {
                                                                        case 1: echo 'Project'; break;
                                                                        case 2: echo 'Task'; break;
                                                                        case 3: echo 'Issue'; break;
                                                                        case 4: echo 'Milestone'; break;
                                                                        default: echo 'Activity'; break;
                                                                    }
                                                                    ?>
                                                                </span>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="activity-content tw-ml-11 tw-ml-4">
                                                            <div class="activity-description tw-mb-2">
                                                                <strong>!!<?php 
																echo str_replace(",", "<br>", $log['description']);?>!!</strong>
                                                            </div>
                                                            
                                                            <?php if (!empty($log['project_title'])): ?>
                                                                <div class="activity-project tw-text-sm tw-text-gray-600 tw-mb-1">
                                                                    <i class="fa-solid fa-folder tw-mr-1"></i>
                                                                    Project: <strong><?php echo htmlspecialchars($log['project_title']); ?></strong>
                                                                </div>
                                                            <?php endif; ?>
                                                            
                                                            <?php if (!empty($log['project_group_name'])): ?>
                                                                <div class="activity-group tw-text-sm tw-text-gray-600 tw-mb-1">
                                                                    <i class="fa-solid fa-layer-group tw-mr-1"></i>
                                                                    Group: <?php echo htmlspecialchars($log['project_group_name']); ?>
                                                                </div>
                                                            <?php endif; ?>
                                                            
                                                            <?php if (!empty($log['additional_data'])): ?>
                                                                <div class="activity-details tw-text-sm tw-text-gray-500 tw-mt-2 twx-p-2 tw-bg-gray-50 tw-rounded">
                                                                    <i class="fa-solid fa-info-circle tw-mr-1"></i>
                                                                    <?php echo htmlspecialchars($log['additional_data']); ?>
                                                                </div>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <div class="tw-text-center tw-py-8 tw-text-gray-500">
                                                    <i class="fa-solid fa-inbox tw-text-4xl tw-mb-4"></i>
                                                    <p>No activity logs found</p>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Calendar Tab -->
                            <div id="calendarTab" class="tab-pane fade">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h4 class="tw-mb-4">
                                            <i class="fa-solid fa-calendar-alt tw-mr-2"></i>Activity Calendar
                                        </h4>
                                        
                                        <!-- Calendar Container -->
                                        <div id="activityCalendar" style="height: 600px; border: 1px solid #ddd; background: #f9f9f9;">
                                            <div class="tw-text-center tw-py-8" id="calendarLoading">
                                                <i class="fa fa-spinner fa-spin tw-text-2xl tw-mb-2"></i>
                                                <p>Loading calendar...</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.activity-feed::-webkit-scrollbar {
    width: 6px;
}

.activity-feed::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
}

.activity-feed::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 3px;
}

.activity-feed::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

.activity-item:hover {
    background-color: #f8f9fa;
    border-radius: 4px;
    padding: 20px;
    margin: 8px;
}

.nav-tabs > li > a {
    border-radius: 4px 4px 0 0;
    margin-right: 2px;
}

.nav-tabs > li.active > a {
    background-color: #fff;
    border-color: #ddd #ddd #fff;
}

#activityCalendar {
    background: white;
    border: 1px solid #ddd;
    border-radius: 4px;
}

.fc-event {
    cursor: pointer;
    border-radius: 3px;
    padding: 2px 4px;
    font-size: 12px;
}

.fc-toolbar h2 {
    font-size: 1.5em;
    font-weight: 600;
}

.fc-button {
    background-color: #007bff;
    border-color: #007bff;
}

.fc-button:hover {
    background-color: #0056b3;
    border-color: #0056b3;
}
</style>
<?php init_tail(); ?>
<!-- FullCalendar CSS and JS -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.minxx.js"></script>

<script>
$(document).ready(function() {
    var calendar = null;
    
    // Tab switching
    $('#collaborationTabs a').click(function(e) {
        e.preventDefault();
        $(this).tab('show');
        
        // Initialize calendar when calendar tab is shown
        if ($(this).attr('href') === '#calendarTab') {
            setTimeout(function() {
                initializeCalendar();
            }, 100);
        }
    });

    function initializeCalendar() {
        console.log('Initializing calendar...');
        
        // Destroy existing calendar if it exists
        if (calendar) {
            calendar.destroy();
        }
        
        // Check if element exists
        var calendarEl = document.getElementById('activityCalendar');
        if (!calendarEl) {
            console.error('Calendar element not found');
            return;
        }
        
        // Check if FullCalendar is loaded
        if (typeof FullCalendar === 'undefined') {
            $(calendarEl).html('<div class="tw-text-center tw-py-8 tw-text-red-500"><i class="fa fa-exclamation-triangle tw-text-2xl tw-mb-2"></i><p>FullCalendar library not loaded</p></div>');
            return;
        }
        
        // Get events data
        var events = <?php echo json_encode($calendar_events ?? []); ?>;
        console.log('Events data:', events);
        
        // Clear the container and show loading
        $(calendarEl).empty();
        $(calendarEl).html('<div class="tw-text-center tw-py-8"><i class="fa fa-spinner fa-spin tw-text-2xl tw-mb-2"></i><p>Loading calendar...</p></div>');
        
        // Simple test event if no events exist
        if (!events || events.length === 0) {
            events = [{
                id: 'test',
                title: 'No activities found',
                start: new Date(),
                backgroundColor: '#6c757d',
                borderColor: '#6c757d',
                textColor: '#ffffff'
            }];
        }
        
        try {
            // Clear the container again
            $(calendarEl).empty();
            
            calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,listWeek'
                },
                events: events,
                eventClick: function(info) {
                    if (info.event.id !== 'test') {
                        showEventDetails(info.event);
                    }
                },
                eventDidMount: function(info) {
                    if (info.event.id !== 'test') {
                        // Add tooltip
                        $(info.el).tooltip({
                            title: info.event.title + '<br>Project: ' + (info.event.extendedProps?.project_title || 'N/A') + '<br>By: ' + (info.event.extendedProps?.staff_name || 'System'),
                            html: true,
                            placement: 'top',
                            container: 'body'
                        });
                    }
                },
                height: 500,
                aspectRatio: 1.35,
                dayMaxEvents: true,
                moreLinkClick: 'popover',
                eventTimeFormat: {
                    hour: '2-digit',
                    minute: '2-digit',
                    meridiem: 'short'
                },
                eventDisplay: 'block',
                eventColor: '#007bff',
                eventTextColor: '#ffffff',
                loading: function(isLoading) {
                    console.log('Calendar loading:', isLoading);
                },
                eventContent: function(arg) {
                    return {
                        html: '<div class="fc-event-title">' + arg.event.title + '</div>'
                    };
                }
            });
            
            calendar.render();
            console.log('Calendar initialized successfully');
            
        } catch (error) {
            console.error('Error initializing calendar:', error);
            $(calendarEl).html('<div class="tw-text-center tw-py-8 tw-text-red-500"><i class="fa fa-exclamation-triangle tw-text-2xl tw-mb-2"></i><p>Error loading calendar: ' + error.message + '</p></div>');
        }
    }

    function showEventDetails(event) {
        var details = '<div class="event-details">' +
            '<h5><strong>' + event.title + '</strong></h5>' +
            '<p><strong>Project:</strong> ' + event.extendedProps.project_title + '</p>' +
            '<p><strong>Type:</strong> ' + event.extendedProps.project_type + '</p>' +
            '<p><strong>By:</strong> ' + event.extendedProps.staff_name + '</p>' +
            '<p><strong>Date:</strong> ' + moment(event.start).format('MMMM Do YYYY, h:mm a') + '</p>';
        
        if (event.extendedProps.additional_data) {
            details += '<p><strong>Details:</strong> ' + event.extendedProps.additional_data + '</p>';
        }
        
        details += '</div>';
        
        // Show in modal or alert
        if (typeof bootbox !== 'undefined') {
            bootbox.alert({
                title: 'Activity Details',
                message: details,
                size: 'medium'
            });
        } else {
            alert(details.replace(/<[^>]*>/g, ''));
        }
    }

    // Auto-refresh feed every 30 seconds
    setInterval(function() {
        if ($('#feedTab').hasClass('active')) {
            refreshActivityFeed();
        }
    }, 30000);

    function refreshActivityFeed() {
       // $.get('<?php echo admin_url('project/get_activity_feed'); ?>', function(data) {
            //$('.activity-feed').html(data);
        //});
    }
    
    // Debug: Check if calendar events data is available
    console.log('Calendar events data:', <?php echo json_encode($calendar_events ?? []); ?>);
    console.log('FullCalendar available:', typeof FullCalendar !== 'undefined');
    
    // Test calendar initialization after page load
    setTimeout(function() {
        if ($('#calendarTab').hasClass('active')) {
            initializeCalendar();
        }
    }, 1000);
    
    // Also try to initialize when tab is shown
    $('a[href="#calendarTab"]').on('shown.bs.tab', function() {
        console.log('Calendar tab shown, initializing...');
        setTimeout(function() {
            initializeCalendar();
        }, 100);
    });
    
    // Force calendar initialization after 2 seconds regardless of tab state
    setTimeout(function() {
        console.log('Forcing calendar initialization...');
        initializeCalendar();
    }, 2000);
});
</script>


</body></html>