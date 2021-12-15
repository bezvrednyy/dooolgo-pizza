async function ajaxOrder() {
    let eventTarget = event.target;
    eventTarget.removeEventListener('click', ajaxOrder);
    const order = eventTarget.parentElement;
    const orderNumber = order.id;
    let response = await fetch('/delOrder', {
        method: 'POST',
        body: JSON.stringify(orderNumber)
    });

    if (response.ok) {
        let answer = await response.json();
        if (answer) {
            decrementOrdersAmount(order.parentElement);
        } else {
            let status = order.lastElementChild;
            status.style.color = 'red';
            status.style.textDecoration = 'underline';
            setTimeout(() => {
                status.style.color = 'rgb(255, 203, 30)';
                status.style.textDecoration = 'none';
                eventTarget.addEventListener('click', ajaxOrder);
            }, 3000);
        }
    } else if (response.status === 403) {
        //Перенаправление на страничку отказа доступа
        location.assign('https://yandex.ru/');
    } else {
        showFlashBox('Техническая проблема, попробуйте позже', 'rgba(255, 0, 0, 0.7)');
    }
}

function decrementOrdersAmount(order) {
    let count = document.getElementById('countOrders');
    let countOrders = parseInt(count.textContent) - 1;
    count.style.backgroundColor = 'red';
    count.style.boxShadow = '0.3px 0.3px 5px 1px red';
    order.style.color = '#F2960B';
    order.style.opacity = '0';
    setTimeout( () => {
        count.style.backgroundColor = '#fff';
        count.style.boxShadow = 'none';
        order.remove();
    }, 1000);
    count.innerHTML = `${countOrders}`;
}

function noticeUserOrders() {
    let userOrders = document.querySelectorAll('.order[notice="1"], .status[notice="1"]');
    for (order of userOrders) {
        order.style.color = 'rgb(255, 203, 30)';
        order.style.textShadow = '1px 1px 1.9px black';
    }
}

function run() {
    let daggers = document.getElementsByClassName('dagger');
    noticeUserOrders();
    for (submit of daggers) {
        submit.addEventListener('click', ajaxOrder);
    }
    if ( document.getElementsByClassName('save_button') ) {
        addEventForAdmin();
    }
}

window.onload = run;