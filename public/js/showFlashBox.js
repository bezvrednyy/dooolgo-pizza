let numberOfQueryFlashBox = 0;
function showFlashBox(message, borderColor) {
    numberOfQueryFlashBox ++;
    if (numberOfQueryFlashBox === 1) {
        let flashBox = document.getElementById('flash_box');
        flashBox.innerHTML = message;
        flashBox.style.opacity = '1';
        flashBox.style.borderColor = borderColor;
        setTimeout(() => {
            flashBox.style.opacity = '0';
            numberOfQueryFlashBox = 0;
        }, 5000);
    }
}