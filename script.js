document.addEventListener('DOMContentLoaded', function () {
    // Modal elements
    const successModal = document.getElementById('successModal');
    console.log('Attempting to find successModal element:', successModal ? 'Found' : 'NOT FOUND');
    const closeButton = document.querySelector('.close-button'); // Assuming one close button for this modal
    const countdownTimerSpan = document.getElementById('countdownTimer');

    let countdownInterval;
    let countdownValue = 5; // seconds
    let lastKnownCanvasRect = { width: 0, height: 0 }; // Store last display dimensions used for canvas setup

    // Debounce utility
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
    const canvas = document.getElementById('signature_pad_canvas');
    const signaturePad = new SignaturePad(canvas, {
        backgroundColor: 'rgb(253, 253, 253)' // Slightly off-white, matching CSS
    });

    const clearButton = document.getElementById('clear_signature');
    const registrationForm = document.getElementById('registrationForm');
    const signatureDataInput = document.getElementById('signature_data');
    // formStatusDiv is no longer used, replaced by toast notifications.

    // --- Modal Functions ---
    function showSuccessModal() {
        console.log('showSuccessModal function called.');
        if (!successModal) {
            console.error('CRITICAL: successModal element not found inside showSuccessModal(). Modal cannot be shown.');
            return;
        }
        countdownValue = 5; // Reset countdown
        countdownTimerSpan.textContent = countdownValue;
        successModal.style.display = 'flex'; // Show modal (using flex as per CSS)
        document.body.classList.add('modal-open'); // Prevent background scroll

        // Clear any existing interval before starting a new one
        if (countdownInterval) clearInterval(countdownInterval);

        countdownInterval = setInterval(() => {
            countdownValue--;
            countdownTimerSpan.textContent = countdownValue;
            if (countdownValue <= 0) {
                closeSuccessModal();
            }
        }, 1000);
    }

    function closeSuccessModal(clearCountdownOnly = false) {
        if (!successModal) return;
        clearInterval(countdownInterval); // Stop the countdown
        successModal.style.display = 'none';
        document.body.classList.remove('modal-open');
        if (!clearCountdownOnly) {
            
            window.location.href = window.location.pathname + '?success=1';
        }
    }

    if (closeButton) {
        closeButton.addEventListener('click', function() {
            closeSuccessModal(); // Manually closing reloads immediately
        });
    }

    // Close modal if user clicks outside of the modal-content (optional but good UX)
    if (successModal) {
        window.addEventListener('click', function(event) {
            if (event.target === successModal) {
                closeSuccessModal(); // Clicking on backdrop reloads immediately
            }
        });
    }

    // --- Privacy Notice Modal Elements ---
    const privacyModal = document.getElementById('privacyModal');
    const privacyNoticeLink = document.getElementById('privacyNoticeLink');
    const privacyModalCloseButton = document.querySelector('#privacyModal .privacy-modal-close');

    // --- Privacy Notice Modal Logic ---
    if (privacyNoticeLink && privacyModal && privacyModalCloseButton) {
        privacyNoticeLink.addEventListener('click', function(event) {
            event.preventDefault(); // Prevent default link behavior
            privacyModal.style.display = 'block';
        });

        privacyModalCloseButton.addEventListener('click', function() {
            privacyModal.style.display = 'none';
        });

        // Close privacy modal if user clicks outside of the modal content
        window.addEventListener('click', function(event) {
            if (event.target === privacyModal) {
                privacyModal.style.display = 'none';
            }
        });
    }

    // Adjust canvas width on resize
    function resizeCanvas() {
        // console.log('resizeCanvas called');
        const dpr = Math.max(window.devicePixelRatio || 1, 1);
        const rect = canvas.getBoundingClientRect();

        // Use rounded display dimensions to compare, preventing tiny float differences from triggering resize
        const displayWidth = Math.round(rect.width);
        const displayHeight = Math.round(rect.height);

        // Only resize if the CSS-driven display size has actually changed since last time
        if (lastKnownCanvasRect.width !== displayWidth || lastKnownCanvasRect.height !== displayHeight) {
            // console.log('Display dimensions changed. Resizing canvas buffer.');
            const newCanvasWidth = Math.round(displayWidth * dpr);
            const newCanvasHeight = Math.round(displayHeight * dpr);

            let data = null;
            if (!signaturePad.isEmpty()) {
                data = signaturePad.toDataURL();
            }

            canvas.width = newCanvasWidth;
            canvas.height = newCanvasHeight;
            canvas.getContext('2d').scale(dpr, dpr);

            if (data) {
                signaturePad.fromDataURL(data);
            } else {
                // If canvas was empty, ensure signaturePad internal state is also clear
                signaturePad.clear();
            }

            // Update the last known dimensions that the canvas drawing buffer was set to
            lastKnownCanvasRect.width = displayWidth;
            lastKnownCanvasRect.height = displayHeight;
        } else {
            // console.log('Display dimensions same as last time. No canvas buffer resize needed.');
        }
    }

    window.addEventListener('resize', debounce(resizeCanvas, 150)); // Debounce resize event
    // Call resizeCanvas once on load to set initial size correctly.
    // Use a small timeout to ensure layout is stable before initial resize.
    setTimeout(resizeCanvas, 50);

    clearButton.addEventListener('click', function () {
        signaturePad.clear();
        signatureDataInput.value = '';
        // formStatusDiv.innerHTML = ''; // Clear status message - No longer used
        // formStatusDiv.className = '';
    });

    registrationForm.addEventListener('submit', function (event) {
        event.preventDefault();

        if (signaturePad.isEmpty()) {
            displayMessage('Please provide a signature.', 'error');
            return false;
        }

        signatureDataInput.value = signaturePad.toDataURL('image/png');
        const formData = new FormData(registrationForm);

        displayMessage('Submitting your registration...', 'loading');

        fetch('submit_registration.php', {
            method: 'POST',
            body: formData,
            signal: AbortSignal.timeout(30000)
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`Server responded with status: ${response.status}`);
            }
            return response.json().catch(err => {
                throw new Error('Could not parse server response. Please try again.');
            });
        })
        .then(data => {
            console.log('Response from submit_registration.php:', data);
            if (data.success) {
                registrationForm.reset();
                signaturePad.clear();
                signatureDataInput.value = '';
                showSuccessModal();
            } else {
                displayMessage(data.message || 'An error occurred. Please try again.', 'error');
            }
        })
        .catch(error => {
            console.error('Error during form submission:', error);
            // Only show network or timeout errors, not the generic support message
            if (error.name === 'TimeoutError') {
                displayMessage('The server took too long to respond. Please try again later.', 'error');
            } else if (error.message.includes('NetworkError') || error.message.includes('Failed to fetch')) {
                displayMessage('Network connection issue. Please check your internet connection and try again.', 'error');
            }
            // No generic "Could not complete registration..." message
        });
    });

    function showToast(message, type = 'info', duration = 3000) {
        const container = document.getElementById('toastContainer');
        if (!container) {
            console.error('Toast container not found!');
            return;
        }

        const toast = document.createElement('div');
        toast.classList.add('toast');
        toast.classList.add(`toast-${type}`); // e.g., toast-success, toast-error
        toast.textContent = message;

        container.appendChild(toast);

        // Trigger animation
        // Short delay to allow the element to be added to DOM before adding 'show' class
        setTimeout(() => {
            toast.classList.add('show');
        }, 10); // 10ms delay

        // Remove toast after duration
        setTimeout(() => {
            toast.classList.remove('show');
            // Remove the element from DOM after transition ends
            toast.addEventListener('transitionend', () => {
                if (toast.parentNode) { // Check if it hasn't been removed already
                    toast.remove();
                }
            });
        }, duration);
    }

    function displayMessage(message, type) {
        // Map old types to new toast types if necessary, or use directly
        let toastType = type; // 'success', 'error'
        if (type === 'loading') {
            toastType = 'info'; // Use 'info' for loading messages
        }
        showToast(message, toastType, type === 'error' ? 5000 : 3000); // Show errors a bit longer
    }
});
