<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <!-- Chat Header -->
                        <div class="chat-header">
                            <div class="row">
                                <div class="col-md-8">
                                    <h4 class="no-margin">
                                        <i class="fa fa-comments text-info"></i>
                                        <?php echo htmlspecialchars($conversation['title']); ?>
                                    </h4>
                                    <small class="text-muted">
                                        Project: <strong><?php echo htmlspecialchars($conversation['project_name']); ?></strong>
                                    </small>
                                </div>
                                <div class="col-md-4 text-right">
                                    <a href="<?php echo admin_url('project_chat'); ?>" class="btn btn-default btn-sm">
                                        <i class="fa fa-arrow-left"></i> Back to Conversations
                                    </a>
                                </div>
                            </div>
                            
                            <!-- Participants -->
                            <div class="row" style="margin-top: 10px;">
                                <div class="col-md-12">
                                    <strong>Participants:</strong>
                                    <?php foreach ($participants as $participant): ?>
                                        <span class="label label-info participant-badge">
                                            <?php echo htmlspecialchars($participant['firstname'] . ' ' . $participant['lastname']); ?>
                                        </span>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                        
                        <hr>

                        <!-- Chat Messages Container -->
                        <div class="chat-container">
                            <div class="chat-messages" id="chatMessages">
                                <?php if (!empty($messages)): ?>
                                    <?php foreach ($messages as $message): ?>
                                        <?php echo $this->load->view('admin/project_chat/message_item', ['message' => $message], true); ?>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="no-messages text-center text-muted">
                                        <i class="fa fa-comments fa-3x"></i>
                                        <p>No messages yet. Start the conversation!</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Message Input -->
                        <div class="chat-input">
                            <form id="messageForm">
                                <input type="hidden" id="conversationId" value="<?php echo $conversation['id']; ?>">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="messageInput" 
                                           placeholder="Type your message..." autocomplete="off">
                                    <span class="input-group-btn">
                                        <button type="submit" class="btn btn-info" id="sendButton">
                                            <i class="fa fa-paper-plane"></i> Send
                                        </button>
                                    </span>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php init_tail(); ?>

<script>
$(document).ready(function() {
    var conversationId = $('#conversationId').val();
    var lastMessageId = <?php echo !empty($messages) ? end($messages)['id'] : 0; ?>;
    var currentUserId = <?php echo get_staff_user_id(); ?>;
    var pollingInterval;

    // Auto-scroll to bottom
    function scrollToBottom() {
        var chatMessages = $('#chatMessages');
        chatMessages.scrollTop(chatMessages[0].scrollHeight);
    }

    // Initial scroll to bottom
    scrollToBottom();

    // Send message
    $('#messageForm').on('submit', function(e) {
        e.preventDefault();
        
        var message = $('#messageInput').val().trim();
        if (!message) return;

        var $sendButton = $('#sendButton');
        var $messageInput = $('#messageInput');
        
        // Disable input while sending
        $sendButton.prop('disabled', true);
        $messageInput.prop('disabled', true);

        $.ajax({
            url: '<?php echo admin_url("project_chat/send_message"); ?>',
            type: 'POST',
            data: {
                conversation_id: conversationId,
                message: message,
                '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // Clear input
                    $messageInput.val('');
                    
                    // Add message to chat
                    $('#chatMessages .no-messages').remove();
                    $('#chatMessages').append(response.html);
                    
                    // Update last message ID
                    lastMessageId = response.message.id;
                    
                    // Scroll to bottom
                    scrollToBottom();
                } else {
                    alert('Failed to send message: ' + response.message);
                }
            },
            error: function() {
                alert('Error sending message. Please try again.');
            },
            complete: function() {
                // Re-enable input
                $sendButton.prop('disabled', false);
                $messageInput.prop('disabled', false);
                $messageInput.focus();
            }
        });
    });

    // Poll for new messages
    function pollMessages() {
        $.ajax({
            url: '<?php echo admin_url("project_chat/get_messages/"); ?>' + conversationId + '/' + lastMessageId,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.success && response.messages.length > 0) {
                    // Remove no messages placeholder
                    $('#chatMessages .no-messages').remove();
                    
                    // Add new messages
                    $('#chatMessages').append(response.html);
                    
                    // Update last message ID
                    lastMessageId = response.last_message_id;
                    
                    // Scroll to bottom if user is near bottom
                    var chatMessages = $('#chatMessages');
                    var isNearBottom = chatMessages.scrollTop() + chatMessages.outerHeight() >= chatMessages[0].scrollHeight - 100;
                    
                    if (isNearBottom) {
                        scrollToBottom();
                    }
                }
            }
        });
    }

    // Start polling every 3 seconds
    pollingInterval = setInterval(pollMessages, 3000);

    // Focus on message input
    $('#messageInput').focus();

    // Handle Enter key
    $('#messageInput').on('keypress', function(e) {
        if (e.which === 13 && !e.shiftKey) {
            e.preventDefault();
            $('#messageForm').submit();
        }
    });

    // Stop polling when leaving page
    $(window).on('beforeunload', function() {
        if (pollingInterval) {
            clearInterval(pollingInterval);
        }
    });
});
</script>

<style>
.chat-header {
    border-bottom: 1px solid #e5e5e5;
    padding-bottom: 15px;
}

.participant-badge {
    margin-right: 5px;
    margin-bottom: 5px;
}

.chat-container {
    height: 500px;
    display: flex;
    flex-direction: column;
}

.chat-messages {
    flex: 1;
    overflow-y: auto;
    padding: 15px 0;
    border: 1px solid #e5e5e5;
    border-radius: 4px;
    background: #fafafa;
    max-height: 400px;
}

.chat-input {
    margin-top: 15px;
}

.no-messages {
    padding: 50px 20px;
}

.no-messages i {
    margin-bottom: 15px;
    opacity: 0.5;
}

/* Message styles */
.message-item {
    margin-bottom: 15px;
    padding: 0 15px;
}

.message-bubble {
    max-width: 70%;
    padding: 10px 15px;
    border-radius: 18px;
    position: relative;
    word-wrap: break-word;
}

.message-own {
    margin-left: auto;
    background: #007bff;
    color: white;
}

.message-other {
    background: white;
    border: 1px solid #e5e5e5;
}

.message-sender {
    font-size: 12px;
    font-weight: bold;
    margin-bottom: 5px;
}

.message-time {
    font-size: 11px;
    opacity: 0.7;
    margin-top: 5px;
}

.message-own .message-time {
    text-align: right;
}

/* Scrollbar styling */
.chat-messages::-webkit-scrollbar {
    width: 6px;
}

.chat-messages::-webkit-scrollbar-track {
    background: #f1f1f1;
}

.chat-messages::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 3px;
}

.chat-messages::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}
</style>
