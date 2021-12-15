const objectType = 'object';
let orderObject = {};
function changeStatus() {
    let firstValue, lastValue = null;
    let eventTarget = event.target;
    let order = eventTarget.parentElement.parentElement;
    let orderId = order.id;
    if ( !(orderId in orderObject) ) {
        firstValue = Number(eventTarget.value);
        orderObject[orderId] = {
            firstStatus: firstValue
        };
    } else {
        lastValue = Number(eventTarget.value);
        orderObject[orderId].lastStatus = lastValue;
    }
}

function isEmpty(obj) {
    for (let key in obj) {
        return false;
    }
    return true;
}

//Переименовать
function deleteDuplicates(object) {
    if ( typeof(object) !== objectType ) {
        return null;
    }
    for (order in object) {
        if (object[order].lastStatus === object[order].firstStatus) {
            delete object[order];
        } else {
            object[order] = object[order].lastStatus;
        }
    }
}

async function sendChangedStatuses() {
    let eventTarget = event.target;
    eventTarget.removeEventListener('click', sendChangedStatuses);
    deleteDuplicates(orderObject);
    if (isEmpty(orderObject)) {
        showFlashBox('Изменений в заказах не найдено', 'rgba(255, 0, 0, 0.7)');
        eventTarget.addEventListener('click', sendChangedStatuses);
        return null;
    }
    let response = await fetch('/changeOrderStatus', {
        method: 'POST',
        body: JSON.stringify(orderObject)
    });
    if (response.ok) {
        showFlashBox('Изменения внесены. Обновление...', 'rgba(242, 150, 11, 0.7)');
        setTimeout( () => window.location = window.location.href, 3000);
    } else {
        showFlashBox('Техническая проблема, попробуйте позже', 'rgba(255, 0, 0, 0.7)');
    }
    eventTarget.addEventListener('click', sendChangedStatuses);
}

function addEventForAdmin() {
    let orderStatuses = document.getElementsByClassName('status');
    for (orderStatus of orderStatuses) {
        orderStatus.addEventListener('click', changeStatus);
    }
    let saveButtons = document.getElementsByClassName('save_button');
    for (button of saveButtons) {
        button.addEventListener('click', sendChangedStatuses);
    }
}