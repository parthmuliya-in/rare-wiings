// Show a message box
function showMessage(message, type='info', duration=3000) {
    let msgBox = document.querySelector('.message-box');

    if(!msgBox) {
        msgBox = document.createElement('div');
        msgBox.className = 'message-box';
        document.body.appendChild(msgBox);
    }

    // Set type class
    msgBox.className = `message-box ${type} show`;

    // Set icon
    let iconClass = 'fa-circle-info';
    if(type === 'success') iconClass = 'fa-circle-check';
    if(type === 'error') iconClass = 'fa-circle-xmark';
    if(type === 'warning') iconClass = 'fa-triangle-exclamation';

    msgBox.innerHTML = `<i class="fa-solid ${iconClass}"></i><p>${message}</p>`;

    // Hide after duration
    setTimeout(() => {
        msgBox.classList.remove('show');
    }, duration);
}

// Example usage:
// showMessage("This is a success message!", "success");
