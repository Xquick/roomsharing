<div class="conversation">
    <?php foreach ($conversation as $message): ?>
        <div class="conversation-item">
            <div class="conversation-item-time">
                <?php echo $message->time; ?>
            </div>
            <div class="conversation-item-body">
                <?php echo $message->reply; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>