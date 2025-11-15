document.addEventListener('DOMContentLoaded', function() {
    // Remove fade animation from all modals to prevent flickering
    document.querySelectorAll('.modal').forEach(function(modal) {
        modal.classList.remove('fade');
    });
    
    // Handle modal events once using event delegation
    document.body.addEventListener('show.bs.modal', function(event) {
        // Force no padding on body
        document.body.style.paddingRight = '0px';
        document.body.style.overflow = 'hidden';
    });
    
    document.body.addEventListener('shown.bs.modal', function(event) {
        // Ensure no padding after modal is shown
        document.body.style.paddingRight = '0px';
    });
    
    document.body.addEventListener('hidden.bs.modal', function(event) {
        // Clean up when modal closes
        document.body.style.paddingRight = '';
        document.body.style.overflow = '';
        document.body.classList.remove('modal-open');
        
        // Remove any leftover backdrops
        document.querySelectorAll('.modal-backdrop').forEach(function(backdrop) {
            backdrop.remove();
        });
    });
});
