async function ajaxOrder() {
    let eventTarget = event.target;
    eventTarget.removeEventListener('click', ajaxOrder);
    let name = eventTarget.name;
    let response = await fetch('/addOrder', {
        method: 'POST',
        body: JSON.stringify(name)
    });

    if (response.ok) {
        let answer = await response.json();
        if ('addOrder' in answer) {
            incrementOrdersAmount(eventTarget);
        } else if ('redirectToRoute' in answer) {
            location.replace(answer['redirectToRoute']);
        } else {
            eventTarget.style.backgroundColor = '#A5A5A5';
            setTimeout(() => {
                eventTarget.style.backgroundColor = '#F2960B';
            }, 3000);
        }
    } else {
        showFlashBox('Техническая проблема, попробуйте позже', 'rgba(255, 0, 0, 0.7)');
    }
    eventTarget.addEventListener('click', ajaxOrder);
}


function getParameter(key) {
    let p = window.location.search;
    p = p.match(new RegExp(key + '=([^&=]+)'));
    return p ? p[1] : null;
}

function checkUserRegistered () {
    let success = getParameter('success');
    if (success) {
        showFlashBox('Вы добавлены в систему!', 'rgba(242, 150, 11, 0.7)');
    }
}

function incrementOrdersAmount(button) {
    let count = document.getElementById('countOrders');
    let countOrders = parseInt(count.textContent) + 1;
    count.style.backgroundColor = 'red';
    count.style.boxShadow = '0.3px 0.3px 5px 1px red';
    button.style.transitionDuration = '1000ms';
    button.style.background =  '#fff';
    button.style.color = '#000';
    setTimeout( () => {
        count.style.backgroundColor = '#fff'
        count.style.boxShadow = 'none';
        button.style.background =  '#F2960B';
        button.style.color = '#fff';
        setTimeout( () => button.style.transitionDuration = '120ms', 1000);
    }, 1000);
    count.innerHTML = `${countOrders}`;
}

function run() {
    const buttons = document.getElementsByClassName('button_pay');
    for (submit of buttons) {
        submit.addEventListener('click', ajaxOrder);
    }
    checkUserRegistered();
}

window.onload = run;