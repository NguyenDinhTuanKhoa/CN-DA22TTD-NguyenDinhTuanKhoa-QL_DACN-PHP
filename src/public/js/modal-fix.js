// Override Bootstrap's modal scrollbar adjustment to prevent layout shift
(function() {
    'use strict';
    
    document.addEventListener('DOMContentLoaded', function() {
        // Override Bootstrap's scrollbar compensation methods
        if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
            const ModalPrototype = bootstrap.Modal.prototype;
            
            // Disable the methods that cause layout shift
            ModalPrototype._adjustDialog = function() {};
            ModalPrototype._resetAdjustments = function() {};
        }
    });
})();
