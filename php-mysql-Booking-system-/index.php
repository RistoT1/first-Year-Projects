<?php
session_start();

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Generate 64-char random token
}
$errors = $_SESSION['errors'] ?? [];
$old = $_SESSION['old'] ?? ['nimi' => '', 'email' => ''];

// Clear errors after showing once
unset($_SESSION['errors'], $_SESSION['old']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <div class="container">
        <div class="calendar-container">
            <div class="calendar-header">
                <h2 id="monthYear"></h2>
                <div class="calendar-header-button">
                    <button class="nav-button" id="prevMonth">&lt; Previous</button>
                    <button class="nav-button" id="thisDay">Today</button>
                    <button class="nav-button" id="nextMonth">Next &gt;</button>

                </div>
            </div>

            <div class="calendar" id="calendar">
                <div class="dayname">Mon</div>
                <div class="dayname">Tue</div>
                <div class="dayname">Wed</div>
                <div class="dayname">Thu</div>
                <div class="dayname">Fri</div>
                <div class="dayname">Sat</div>
                <div class="dayname">Sun</div>
            </div>
        </div>
        <div class="book">
            <div class="book-title">
                <h3>Bookings</h3>
            </div>
            <div class="times" id="times">
                <div class="no-bookings">Select the day so reveal times!</div>
            </div>
        </div>

        <div class="fill-booking-info" id="booking-info">
            <button class="close-btn">X</button>
            <form action="info_sender.php" method="POST">
                <div class="name-cont">
                    <label for="nimi">Nimi</label>
                    <input type="text" name="nimi" id="nimi" autocomplete="name">
                </div>
                <div class="email-cont">
                    <label for="email">Sähköposti</label>
                    <input type="email" name="email" id="email" autocomplete="email" required>
                </div>
                <div class="error-messages"></div>
                <input type="hidden" name="slot_id" id="slot_id" value="1" autocomplete="off">

                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <div class="submit-cont">
                    <input type="submit">
                </div>
            </form>
        </div>
    </div>

    <script>
        let currentDate = new Date();
        let selectedDay = null;
        let clicked = false;

        // Month names for display
        const monthNames = [
            'January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December'
        ];
        const booking_info = document.getElementById('booking-info');

        function generateCalendar(year, month) {
            const calendar = document.getElementById('calendar');
            const monthYear = document.getElementById('monthYear');

            // Clear existing days (keep day name headers)
            const dayElements = calendar.querySelectorAll('.day');
            dayElements.forEach(day => day.remove());

            // Update month/year display
            monthYear.textContent = `${monthNames[month]} ${year}`;

            // Get first day of month and number of days
            const firstDay = new Date(year, month, 1);
            const lastDay = new Date(year, month + 1, 0); // ask day before next months first day.
            const daysInMonth = lastDay.getDate();

            // Get first day of week (0 = Sunday, 1 = Monday, etc.)
            // Adjust so Monday = 0
            let startDay = firstDay.getDay();
            startDay = startDay === 0 ? 6 : startDay - 1;


            // Get previous month's last days
            const prevMonth = new Date(year, month, 0);
            const daysInPrevMonth = prevMonth.getDate();

            // Get today's date for highlighting
            const today = new Date();
            const isCurrentMonth = today.getFullYear() === year && today.getMonth() === month;
            const todayDate = today.getDate();

            // Adds the days needed to fill the start of the calendar
            for (let i = startDay - 1; i >= 0; i--) {
                const dayElement = document.createElement('div');
                dayElement.className = 'day other-month';
                dayElement.textContent = daysInPrevMonth - i;
                calendar.appendChild(dayElement);
            }

            // Add current month's days
            for (let day = 1; day <= daysInMonth; day++) {
                const dayElement = document.createElement('div');
                dayElement.className = 'day';
                dayElement.textContent = day;

                // Highlight today
                if (isCurrentMonth && day === todayDate) {
                    dayElement.classList.add('today');
                }

                // Add click event listener
                dayElement.addEventListener('click', (e) => {
                    handleDayClick(dayElement, day, e);
                });

                calendar.appendChild(dayElement);

                // Load bookings for this day
                loadBookings(dayElement, day);
            }

            // Add next month's leading days only if needed to complete the current week
            const currentCells = calendar.children.length - 7; // Subtract day name headers
            const currentWeeks = Math.ceil(currentCells / 7);
            const targetCells = currentWeeks * 7;
            const remainingCells = targetCells - currentCells;

            for (let day = 1; day <= remainingCells; day++) {
                const dayElement = document.createElement('div');
                dayElement.className = 'day other-month';
                dayElement.textContent = day;
                calendar.appendChild(dayElement);
            }
        }

        let isLoading = false;
        function handleDayClick(dayElement, dayNumber, event) {
            if (isLoading) return;
            clicked = true;
            event.stopPropagation();

            // Don't handle clicks on other month days
            if (dayElement.classList.contains('other-month')) {
                return;
            }

            // Deselect previous day
            if (selectedDay && selectedDay !== dayElement) {
                selectedDay.classList.remove('selected');
            }

            // Clear previous bookings
            document.getElementById('times').innerHTML = '';

            // Toggle current day
            if (dayElement.classList.contains('selected')) {
                dayElement.classList.remove('selected');
                selectedDay = null;
            } else {
                dayElement.classList.add('selected');
                selectedDay = dayElement;
                loadBookings(dayElement, dayNumber);
                isLoading = true;
            }
        }
        function loadBookings(dayElement, dayNumber) {
            const timesContainer = document.getElementById('times');
            fetch(`booking.php?day=${dayNumber}&month=${currentDate.getMonth() + 1}&year=${currentDate.getFullYear()}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    updateDayType(dayElement, data);
                    if (clicked && selectedDay === dayElement) {
                        if (data.success && data.times && data.times.length > 0) {
                            data.times.forEach(time => {
                                if (time && time.start && time.end) {
                                    const bookableTime = document.createElement('div');
                                    bookableTime.className = 'bookable';
                                    bookableTime.textContent = `${time.start}–${time.end} ${time.description}`;

                                    const bookButton = document.createElement('button');
                                    bookButton.textContent = 'Book';
                                    bookButton.className = 'bookbutton';
                                    bookButton.addEventListener('click', (event) => {
                                        createRippleEffect(bookButton, event);
                                        setTimeout(() => {
                                            booking_info.style.display = "block";
                                            document.getElementById("slot_id").value = time.id;
                                        }, 20);
                                    });

                                    bookableTime.appendChild(bookButton);
                                    timesContainer.appendChild(bookableTime);
                                }
                            });
                        } else {
                            const noBookingsDiv = document.createElement('div');
                            noBookingsDiv.className = 'no-bookings';
                            noBookingsDiv.textContent = data.message || 'No bookings available for this day';
                            timesContainer.appendChild(noBookingsDiv);
                        }
                    }
                })
                .catch(error => {
                    console.error('Error fetching bookings:', error);
                    timesContainer.innerHTML = '';

                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'error';
                    errorDiv.textContent = 'Error loading bookings. Please try again.';
                    timesContainer.appendChild(errorDiv);
                })
                .finally(() => {
                    isLoading = false;
                });

        }

        function updateDayType(dayElement, data) {
            const count = Array.isArray(data.times) ? data.times.length : 0;

            dayElement.classList.remove('type-0', 'type-1', 'type-2', 'type-3');

            const normalizedCount = Math.min(count, 3);
            dayElement.classList.add(`type-${normalizedCount}`);
        }

        function createRippleEffect(button, event) {
            const rect = button.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = event.clientX - rect.left - size / 2;
            const y = event.clientY - rect.top - size / 2;

            const ripple = document.createElement('div');
            ripple.className = 'ripple';
            ripple.style.width = ripple.style.height = size + 'px';
            ripple.style.left = x + 'px';
            ripple.style.top = y + 'px';

            button.appendChild(ripple);

            setTimeout(() => {
                ripple.remove();
            }, 600);
        }

        // Navigation eventlisteners
        document.getElementById('prevMonth').addEventListener('click', () => {
            currentDate.setMonth(currentDate.getMonth() - 1);
            generateCalendar(currentDate.getFullYear(), currentDate.getMonth());
            selectedDay = null;
            document.getElementById('times').innerHTML = '';
        });
        document.getElementById('thisDay').addEventListener('click', () => {
            currentDate = new Date();
            generateCalendar(currentDate.getFullYear(), currentDate.getMonth());
            selectedDay = null;
            document.getElementById('times').innerHTML = '';
        });

        document.getElementById('nextMonth').addEventListener('click', () => {
            currentDate.setMonth(currentDate.getMonth() + 1);
            generateCalendar(currentDate.getFullYear(), currentDate.getMonth());
            selectedDay = null;
            document.getElementById('times').innerHTML = '';
        });

        // Click outside to deselect
        document.body.addEventListener('click', (e) => {
            if (e.target === document.body && selectedDay) {
                selectedDay.classList.remove('selected');
                selectedDay = null;
                document.getElementById('times').innerHTML = '';
            }
        });

        // Initialize calendar
        generateCalendar(currentDate.getFullYear(), currentDate.getMonth());

        document.querySelector('.close-btn').addEventListener('click', () => {
            booking_info.style.display = "none";
        })

        $(document).ready(function () {
            $("form").on("submit", function (e) {
                e.preventDefault();
                const form = $(this);

                $.ajax({
                    url: "info_sender.php",
                    method: "POST",
                    data: form.serialize(),
                    success: function (response) {
                        if (response.success) {
                            $("#booking-info").hide();
                            window.location.href = response.redirect;
                            // would need the time booked
                        } else {
                            // Näytä virheet lomakkeen yläpuolella
                            showErrors(response.errors);
                        }
                    },
                    error: function (xhr) {
                        try {
                            const res = JSON.parse(xhr.responseText);
                            if (res.errors) {
                                showErrors(res.errors);
                            } else {
                                alert("Tuntematon virhe!");
                            }
                        } catch {
                            alert("Virhe lähetyksessä.");
                        }
                    }
                });
            });

            function showErrors(errors) {
                const errorContainer = $("#booking-info .error-messages");

                errorContainer.empty();

                let errorHtml = "<ul class='form-errors'>";
                errors.forEach(err => {
                    errorHtml += `<li>${err}</li>`;
                });
                errorHtml += "</ul>";

                errorContainer.html(errorHtml);
                $("#booking-info").show(); // Varmista että ikkuna pysyy näkyvillä
            }
        });

    </script>
</body>

</html>