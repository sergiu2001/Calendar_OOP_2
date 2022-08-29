date = new Date();
currentD = date.getDate();
currentM = date.getMonth();
currentY = date.getFullYear();
monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

let calendarM = currentM;
let calendarY = currentY;

function createCalendar() {

    let febDays;
    if (calendarY % 4 === 0) {
        febDays = 29;
    } else {
        febDays = 28;
    }
    let monthDays = ["31", "" + febDays + "", "31", "30", "31", "30", "31", "31", "30", "31", "30", "31"];

    let i = 1;
    let wd = 0;
    let num = monthDays[calendarM];
    let tableHTML = "";

    tableHTML += "<thead><tr><th>Mon</th><th>Tue</th><th>Wen</th><th>Thu</th><th>Fri</th><th>Sat</th><th>Sun</th></tr></thead>";
    tableHTML += "<tbody><tr>";
    while (i <= num) {
        if (wd > 6) {
            wd = 0;
            tableHTML += "</tr><tr>";
        }

        if (i === currentD && calendarM === currentM && calendarY === currentY) {
            tableHTML += `<td onclick='clickDay()' data-date=${createDate(i, calendarM + 1, calendarY)} class="day today" >${i}</td>`;
        } else {
            tableHTML += `<td onclick='clickDay()' data-date=${createDate(i, calendarM + 1, calendarY)} class="day" >${i}</td>`;
        }
        wd++;
        i++;
    }
    tableHTML += "</tr></tbody>";
    document.querySelector(".calendar__content").insertAdjacentHTML("beforeend", tableHTML);
}

document.addEventListener('DOMContentLoaded', createCalendar, false);

function loadMY() {
    const divM = document.querySelector(".calendar__m");
    divM.innerHTML = monthNames[calendarM];
    const divY = document.querySelector(".calendar__y");
    divY.innerHTML = calendarY;
}

document.addEventListener('DOMContentLoaded', loadMY, false);

function prevMonth() {
    calendarY = (calendarM === 0) ? calendarY - 1 : calendarY;
    calendarM = (calendarM === 0) ? 11 : calendarM - 1;
    loadMY();

    const tHTML = document.querySelector(".calendar__content");
    tHTML.innerHTML = "";
    createCalendar();
}

function nextMonth() {
    calendarY = (calendarM === 11) ? calendarY + 1 : calendarY;
    calendarM = (calendarM + 1) % 12;
    loadMY();

    const tHTML = document.querySelector(".calendar__content");
    tHTML.innerHTML = "";
    createCalendar();
}

function clickDay() {
    document.querySelectorAll('.day').forEach(item => {
        item.addEventListener('click', () => {
            let dateParam = item.getAttribute('data-date');
            axios({
                method: 'get',
                url: '/calendar',
                params: {
                    date: dateParam,
                }
            }).then(function (response) {
                printData(response.data, dateParam)
                console.log(dateParam, response.data)
            }).catch(function (error) {
                console.log(error);
            })
        })
    });
}

function printData(reservations, date) {
    document.querySelector(".events__list").innerHTML = "";
    let eventHTML = `<li>${date}</li>`;
    if (reservations.length === 0) {
        eventHTML += '<li>No reservations today!</li>';
        document.querySelector(".events__list").innerHTML = eventHTML;
    } else {
        for (let reservation of reservations) {
            eventHTML += `<li><img src="${reservation.profile}"> ${reservation.user_name} ${reservation.time} ${reservation.location}</li>`;
            document.querySelector(".events__list").innerHTML = eventHTML;
        }
    }
}

function createDate(day, month, year) {
    if (day < 10) {
        day = "0" + day;
    }
    if (month < 10) {
        month = "0" + month;
    }
    return year + "-" + month + "-" + day;
}

