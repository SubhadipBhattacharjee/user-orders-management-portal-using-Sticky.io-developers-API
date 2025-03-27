const monthYearElement = document.getElementById('month-year');
const calendarDatesElement = document.getElementById('calendar-dates');
const prevMonthButton = document.getElementById('prev-month');
const nextMonthButton = document.getElementById('next-month');
const selectedDateElement = document.getElementById('selected-date');

const recDate = document.getElementById('recDate');

let currentDate = new Date();

function renderCalendar(date) {
    const year = date.getFullYear();
    const month = date.getMonth();

    // Set the current month and year in the header
    monthYearElement.textContent = `${date.toLocaleString('default', { month: 'long' })} ${year}`;

    // Get the first day of the month and the number of days in the month
    const firstDay = new Date(year, month, 1).getDay();
    const lastDate = new Date(year, month + 1, 0).getDate();

    // Clear the previous dates in the calendar
    calendarDatesElement.innerHTML = '';

    // Add empty cells before the first day of the month
    for (let i = 0; i < firstDay; i++) {
        const emptyCell = document.createElement('div');
        calendarDatesElement.appendChild(emptyCell);
    }

    // Add the dates of the month
    for (let i = 1; i <= lastDate; i++) {
        const dateCell = document.createElement('div');
        dateCell.textContent = i;
        dateCell.classList.add('date');
        dateCell.addEventListener('click', () => selectDate(i, month, year));
        calendarDatesElement.appendChild(dateCell);
    }
}

function selectDate(day, month, year) {
    const selectedDate = new Date(year, month, day);
    const selectedDateString = selectedDate.toLocaleDateString('en-US');
    selectedDateElement.textContent = `Selected Date: ${selectedDateString}`;
    recDate.value = `${selectedDateString}`;


    // Add the "selected" class to the clicked date
    const dateCells = document.querySelectorAll('.date');
    dateCells.forEach(cell => {
        if (parseInt(cell.textContent) === day) {
            cell.classList.add('selected');
        } else {
            cell.classList.remove('selected');
        }
    });
}

prevMonthButton.addEventListener('click', () => {
    currentDate.setMonth(currentDate.getMonth() - 1);
    renderCalendar(currentDate);
});

nextMonthButton.addEventListener('click', () => {
    currentDate.setMonth(currentDate.getMonth() + 1);
    renderCalendar(currentDate);
});

// Initialize the calendar with the current date
renderCalendar(currentDate);