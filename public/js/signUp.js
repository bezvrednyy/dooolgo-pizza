let numberOfClicks = 0;
function validateForm() {
    numberOfClicks++;
    if (numberOfClicks === 1) {
        let sendForm = true;
        let name = document.getElementById('user_name');
        let email = document.getElementById('user_email');
        let password = document.getElementById('user_password');
        let address = document.getElementById('user_address');
        toggleError(name, 0);
        toggleError(email, 0);
        toggleError(password, 0);
        toggleError(address, 0);

        if (!validateName(name.value)) {
            toggleError(name, 1);
            sendForm = false;
        }

        if (!validateEmail(email.value)) {
            toggleError(email, 1, 'Некорректный e\'mail');
            sendForm = false;
        }

        if (!validatePassword(password.value)) {
            toggleError(password, 1);
            sendForm = false;
        }

        if (!validateAddress(address.value)) {
            toggleError(address, 1);
            sendForm = false;
        }

        if (sendForm) {
            checkUserRegistered(email.value)
        }
        numberOfClicks = 0;
    }
}

function validateName(name) {
    return name.match(/^[a-zа-яё]+[a-zа-яё\s\-]*$/i);
}

function validateEmail(email) {
    return email.match(/.+@.+\..+/i)
}

function validatePassword(password) {
    return password.match(/^[a-zA-Z0-9]+[a-zA-Z0-9]{7,}$/);
}

function validateAddress(address) {
    return address.match(/^[a-zа-яё]+[a-zа-яё0-9\s\-,\.]*$/i)
}

async function checkUserRegistered(email) {
    const response = await fetch('/signup', {
        method: 'POST',
        body: JSON.stringify({'checkUserRegistered': email})
    });

    if (response.ok) {
        let answer = await response.json();
        if (!answer) {
            let form = document.querySelector('form');
            form.submit();
        } else {
            let email = document.getElementById('user_email');
            toggleError(email, 1, 'Пользователь с таким e\'mail уже существует')
        }
    } else {
        showFlashBox('Техническая проблема, попробуйте позже', 'rgba(255, 0, 0, 0.7)');
    }
}

function addErrors() {
    let name = document.getElementById('user_name');
    let email = document.getElementById('user_email');
    let password = document.getElementById('user_password');
    let address = document.getElementById('user_address');
    addError(password, 'Пароль должен состоять из 8-и английских букв и цифр');
    addError(name, 'Некорректное ФИО');
    addError(email,'Некорректный e\'mail');
    addError(address, 'Используйте формат: улица, дом, подъезд, этаж, квартира');
}

function addError(elem, errorMessage) {
    let error = document.createElement('div')
    error.className = 'error';
    error.style.color = 'red';
    error.style.marginLeft = '74px';
    error.style.opacity = '0';
    error.style.WebkitTransitionDuration = '500ms';
    error.innerHTML = errorMessage;
    elem.style.marginBottom = '0';
    elem.parentElement.append(error);
}

function toggleError(elem, value, message = null) {
    let error = elem.parentElement.querySelector('.error');
    if ( message != null) {
        error.innerHTML = message;
    }
    error.style.opacity = `${value}`;
}
window.onload = addErrors;