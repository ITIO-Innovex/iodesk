<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="message-item">
    <div class="message-bubble <?php echo ($message['sender_id'] == get_staff_user_id()) ? 'message-own' : 'message-other'; ?>">
        <?php if ($message['sender_id'] != get_staff_user_id()): ?>
            <div class="message-sender">
                <?php echo htmlspecialchars($message['firstname'] . ' ' . $message['lastname']); ?>
            </div>
        <?php endif; ?>
        
        <div class="message-content">
            <?php echo nl2br(htmlspecialchars($message['message'])); ?>
        </div>
        
        <div class="message-time">
            <?php echo date('M j, Y g:i A', strtotime($message['sent_at'])); ?>
        </div>
    </div>
</div>
