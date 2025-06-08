import './bootstrap';

// Auto-scroll chat messages to bottom
document.addEventListener('DOMContentLoaded', () => {
    const scrollToBottom = () => {
        setTimeout(() => {
            const chatMessages = document.getElementById('chat-messages');
            if (chatMessages) {
                chatMessages.scrollTop = chatMessages.scrollHeight;
                // Force a reflow to ensure scroll takes effect
                chatMessages.scrollTop = chatMessages.scrollHeight;
            }
        }, 100); // Small delay to ensure content is rendered
    };

    // Scroll on initial load
    scrollToBottom();

    // Scroll when new messages are added (Livewire updates)
    document.addEventListener('livewire:initialized', () => {
        Livewire.hook('morph.updated', ({ el }) => {
            if (el.id === 'chat-messages' || el.contains(document.getElementById('chat-messages'))) {
                scrollToBottom();
            }
        });
    });
});
