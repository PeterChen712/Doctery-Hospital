<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/css/calendar/index.css" />
    <link rel="canonical" href="https://vanilla-calendar.mateuszziomekit.com" />
    <script src="/assets/js/calendar/index.js" type="module"></script>
    <title>Vanilla Calendar | Responsive App with HTML, CSS & JavaScript - Mateusz Ziomek</title>
    <meta name="description"
        content="Vanilla Calendar is a fully responsive calendar app built with HTML, CSS, and JavaScript. Create, manage, and view events in real-time, all built from scratch without libraries or frameworks." />
    <link rel="manifest" href="/assets/json/manifest.webmanifest">
    <meta name="author" content="Mateusz Ziomek">
    <meta name="robots" content="index, follow">
    <meta property="og:type" content="website">
    <meta property="og:title" content="Vanilla Calendar | Responsive App with HTML, CSS & JavaScript - Mateusz Ziomek">
    <meta property="og:description"
        content="Vanilla Calendar is a fully responsive calendar app built with HTML, CSS, and JavaScript. Create, manage, and view events in real-time, all built from scratch without libraries or frameworks.">
    <meta property="og:url" content="https://vanilla-calendar.mateuszziomekit.com">
    <meta property="og:image" content="https://vanilla-calendar.mateuszziomekit.com/thumbnail.jpg">
    <meta property="og:image:width" content="1280">
    <meta property="og:image:height" content="720">
    <meta property="og:image:type" content="image/jpeg">
    <meta property="og:image:alt"
        content="A thumbnail of a Vanilla Calendar Youtube video, containing the mobile and the desktop view.">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:creator" content="@mateuszziomekit">
    <meta name="twitter:title" content="Vanilla Calendar | Responsive App with HTML, CSS & JavaScript - Mateusz Ziomek">
    <meta name="twitter:description"
        content="Vanilla Calendar is a fully responsive calendar app built with HTML, CSS, and JavaScript. Create, manage, and view events in real-time, all built from scratch without libraries or frameworks.">
    <meta name="twitter:image" content="https://vanilla-calendar.mateuszziomekit.com/thumbnail.jpg">
    <meta name="twitter:image:alt"
        content="A thumbnail of a Vanilla Calendar Youtube video, containing the mobile and the desktop view.">
</head>

<body>
    <div class="app">
        <div class="sidebar desktop-only">
            <div class="sidebar__logo">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-calendar">
                    <path d="M8 2v4" />
                    <path d="M16 2v4" />
                    <rect width="18" height="18" x="3" y="4" rx="2" />
                    <path d="M3 10h18" />
                </svg>
                <span class="sidebar__title">Vanilla Calendar</span>
            </div>

            <button class="button button--primary button--lg" data-event-create-button>Create event</button>

            <div class="mini-calendar" data-mini-calendar>
                <div class="mini-calendar__header">
                    <time class="mini-calendar__date" data-mini-calendar-date></time>

                    <div class="mini-calendar__controls">
                        <button class="button button--icon button--secondary button--sm"
                            data-mini-calendar-previous-button>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="button__icon">
                                <path d="m15 18-6-6 6-6" />
                            </svg>
                        </button>

                        <button class="button button--icon button--secondary button--sm" data-mini-calendar-next-button>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="button__icon">
                                <path d="m9 18 6-6-6-6" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="mini-calendar__content">
                    <ul class="mini-calendar__day-of-week-list">
                        <li class="mini-calendar__day-of-week">S</li>
                        <li class="mini-calendar__day-of-week">M</li>
                        <li class="mini-calendar__day-of-week">T</li>
                        <li class="mini-calendar__day-of-week">W</li>
                        <li class="mini-calendar__day-of-week">T</li>
                        <li class="mini-calendar__day-of-week">F</li>
                        <li class="mini-calendar__day-of-week">S</li>
                    </ul>

                    <ul class="mini-calendar__day-list" data-mini-calendar-day-list></ul>
                </div>
            </div>
        </div>
        <main class="main">
            <div class="nav">
                <button class="button button--icon button--secondary mobile-only" data-hamburger-button>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="button__icon">
                        <line x1="4" x2="20" y1="12" y2="12" />
                        <line x1="4" x2="20" y1="6" y2="6" />
                        <line x1="4" x2="20" y1="18" y2="18" />
                    </svg>
                </button>

                <div class="nav__date-info">
                    <div class="nav__controls">
                        <button class="button button--secondary desktop-only" data-nav-today-button>Today</button>
                        <button class="button button--icon button--secondary mobile-only" data-nav-today-button>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" class="button__icon">
                                <path d="M8 2v4" />
                                <path d="M16 2v4" />
                                <rect width="18" height="18" x="3" y="4" rx="2" />
                                <path d="M3 10h18" />
                                <path d="M8 14h.01" />
                                <path d="M12 14h.01" />
                                <path d="M16 14h.01" />
                                <path d="M8 18h.01" />
                                <path d="M12 18h.01" />
                                <path d="M16 18h.01" />
                            </svg>
                        </button>

                        <div class="nav__arrows">
                            <button class="button button--icon button--secondary" data-nav-previous-button>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" class="button__icon">
                                    <path d="m15 18-6-6 6-6" />
                                </svg>
                            </button>

                            <button class="button button--icon button--secondary" data-nav-next-button>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" class="button__icon">
                                    <path d="m9 18 6-6-6-6" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    <time class="nav__date" data-nav-date></time>
                </div>

                <div class="select desktop-only">
                    <select class="select__select" data-view-select>
                        <option value="day">Day</option>
                        <option value="week">Week</option>
                        <option value="month" selected>Month</option>
                    </select>

                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="select__icon">
                        <path d="m6 9 6 6 6-6" />
                    </svg>
                </div>
            </div>
            <div class="calendar" data-calendar>
            </div>
        </main>
    </div>

    <button class="fab mobile-only" data-event-create-button>
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
            class="fab__icon">
            <path d="M5 12h14" />
            <path d="M12 5v14" />
        </svg>
    </button>

    <dialog class="dialog" data-dialog="event-form">
        <form class="form" action="{{ route('doctor.events.store') }}" method="POST" data-event-form>
            @csrf
            @method('PUT')
            <div class="dialog__wrapper">
                <div class="dialog__header">
                    <h2 class="dialog__title" data-dialog-title></h2>
                    <button class="button button--icon button--secondary" type="button" data-dialog-close-button>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="button__icon">
                            <path d="M18 6 6 18" />
                            <path d="m6 6 12 12" />
                        </svg>
                    </button>
                </div>

                <div class="dialog__content">
                    <div class="form__fields">
                        <input type="hidden" id="id" name="id" />

                        <div class="form__field">
                            <label class="form__label" for="title">Title</label>
                            <input class="input input--fill" id="title" name="title" type="text"
                                placeholder="My awesome event!" required autofocus />
                        </div>

                        <div class="form__field">
                            <label class="form__label" for="date">Date</label>
                            <input class="input input--fill" id="date" name="date" type="date"
                                required />
                        </div>

                        <div class="form__split">
                            <div class="form__field">
                                <label class="form__label" for="start-time">Start time</label>
                                <div class="select select--fill">
                                    <select class="select__select" id="start-time" name="start-time">
                                        <option value="0">12:00 AM</option>
                                        <option value="30">12:30 AM</option>
                                        <option value="60">1:00 AM</option>
                                        <option value="90">1:30 AM</option>
                                        <option value="120">2:00 AM</option>
                                        <option value="150">2:30 AM</option>
                                        <option value="180">3:00 AM</option>
                                        <option value="210">3:30 AM</option>
                                        <option value="240">4:00 AM</option>
                                        <option value="270">4:30 AM</option>
                                        <option value="300">5:00 AM</option>
                                        <option value="330">5:30 AM</option>
                                        <option value="360">6:00 AM</option>
                                        <option value="390">6:30 AM</option>
                                        <option value="420">7:00 AM</option>
                                        <option value="450">7:30 AM</option>
                                        <option value="480">8:00 AM</option>
                                        <option value="510">8:30 AM</option>
                                        <option value="540">9:00 AM</option>
                                        <option value="570">9:30 AM</option>
                                        <option value="600">10:00 AM</option>
                                        <option value="630">10:30 AM</option>
                                        <option value="660">11:00 AM</option>
                                        <option value="690">11:30 AM</option>
                                        <option value="720">12:00 PM</option>
                                        <option value="750">12:30 PM</option>
                                        <option value="780">1:00 PM</option>
                                        <option value="810">1:30 PM</option>
                                        <option value="840">2:00 PM</option>
                                        <option value="870">2:30 PM</option>
                                        <option value="900">3:00 PM</option>
                                        <option value="930">3:30 PM</option>
                                        <option value="960">4:00 PM</option>
                                        <option value="990">4:30 PM</option>
                                        <option value="1020">5:00 PM</option>
                                        <option value="1050">5:30 PM</option>
                                        <option value="1080">6:00 PM</option>
                                        <option value="1110">6:30 PM</option>
                                        <option value="1140">7:00 PM</option>
                                        <option value="1170">7:30 PM</option>
                                        <option value="1200">8:00 PM</option>
                                        <option value="1230">8:30 PM</option>
                                        <option value="1260">9:00 PM</option>
                                        <option value="1290">9:30 PM</option>
                                        <option value="1320">10:00 PM</option>
                                        <option value="1350">10:30 PM</option>
                                        <option value="1380">11:00 PM</option>
                                        <option value="1410">11:30 PM</option>
                                    </select>

                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" class="select__icon">
                                        <path d="m6 9 6 6 6-6" />
                                    </svg>
                                </div>
                            </div>

                            <div class="form__field">
                                <label class="form__label" for="end-time">End time</label>
                                <div class="select select--fill">
                                    <select class="select__select" id="end-time" name="end-time">
                                        <option value="30">12:30 AM</option>
                                        <option value="60">1:00 AM</option>
                                        <option value="90">1:30 AM</option>
                                        <option value="120">2:00 AM</option>
                                        <option value="150">2:30 AM</option>
                                        <option value="180">3:00 AM</option>
                                        <option value="210">3:30 AM</option>
                                        <option value="240">4:00 AM</option>
                                        <option value="270">4:30 AM</option>
                                        <option value="300">5:00 AM</option>
                                        <option value="330">5:30 AM</option>
                                        <option value="360">6:00 AM</option>
                                        <option value="390">6:30 AM</option>
                                        <option value="420">7:00 AM</option>
                                        <option value="450">7:30 AM</option>
                                        <option value="480">8:00 AM</option>
                                        <option value="510">8:30 AM</option>
                                        <option value="540">9:00 AM</option>
                                        <option value="570">9:30 AM</option>
                                        <option value="600">10:00 AM</option>
                                        <option value="630">10:30 AM</option>
                                        <option value="660">11:00 AM</option>
                                        <option value="690">11:30 AM</option>
                                        <option value="720">12:00 PM</option>
                                        <option value="750">12:30 PM</option>
                                        <option value="780">1:00 PM</option>
                                        <option value="810">1:30 PM</option>
                                        <option value="840">2:00 PM</option>
                                        <option value="870">2:30 PM</option>
                                        <option value="900">3:00 PM</option>
                                        <option value="930">3:30 PM</option>
                                        <option value="960">4:00 PM</option>
                                        <option value="990">4:30 PM</option>
                                        <option value="1020">5:00 PM</option>
                                        <option value="1050">5:30 PM</option>
                                        <option value="1080">6:00 PM</option>
                                        <option value="1110">6:30 PM</option>
                                        <option value="1140">7:00 PM</option>
                                        <option value="1170">7:30 PM</option>
                                        <option value="1200">8:00 PM</option>
                                        <option value="1230">8:30 PM</option>
                                        <option value="1260">9:00 PM</option>
                                        <option value="1290">9:30 PM</option>
                                        <option value="1320">10:00 PM</option>
                                        <option value="1350">10:30 PM</option>
                                        <option value="1380">11:00 PM</option>
                                        <option value="1410">11:30 PM</option>
                                        <option value="1440">12:00 AM</option>
                                    </select>

                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" class="select__icon">
                                        <path d="m6 9 6 6 6-6" />
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="form__field">
                            <label class="form__label" for="color">Color</label>
                            <div class="color-select">
                                <label class="color-select__item" style="--color-select-item-color: #2563eb">
                                    <input class="color-select__input" type="radio" name="color" value="#2563eb"
                                        checked />
                                    <div class="color-select__color">
                                        <div class="color-select__color-inner"></div>
                                    </div>
                                </label>
                                <label class="color-select__item" style="--color-select-item-color: #ea580c">
                                    <input class="color-select__input" type="radio" name="color"
                                        value="#ea580c" />
                                    <div class="color-select__color">
                                        <div class="color-select__color-inner"></div>
                                    </div>
                                </label>
                                <label class="color-select__item" style="--color-select-item-color: #16a34a">
                                    <input class="color-select__input" type="radio" name="color"
                                        value="#16a34a" />
                                    <div class="color-select__color">
                                        <div class="color-select__color-inner"></div>
                                    </div>
                                </label>
                                <label class="color-select__item" style="--color-select-item-color: #7c3aed">
                                    <input class="color-select__input" type="radio" name="color"
                                        value="#7c3aed" />
                                    <div class="color-select__color">
                                        <div class="color-select__color-inner"></div>
                                    </div>
                                </label>
                                <label class="color-select__item" style="--color-select-item-color: #e11d48">
                                    <input class="color-select__input" type="radio" name="color"
                                        value="#e11d48" />
                                    <div class="color-select__color">
                                        <div class="color-select__color-inner"></div>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="dialog__footer">
                    <div class="dialog__actions">
                        <button class="button button--secondary" type="button"
                            data-dialog-close-button>Cancel</button>
                        <button class="button button--primary">Save</button>
                    </div>
                </div>
            </div>
        </form>
    </dialog>

    <dialog class="dialog" data-dialog="event-details">
        <div class="dialog__wrapper">
            <div class="dialog__header">
                <h2 class="dialog__title">Event details</h2>
                <div class="dialog__header-actions">
                    <button class="button button--icon button--secondary" data-event-details-delete-button>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="button__icon">
                            <path d="M3 6h18" />
                            <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" />
                            <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" />
                        </svg>
                    </button>

                    <button class="button button--icon button--secondary" data-event-details-edit-button>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="button__icon">
                            <path
                                d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z" />
                            <path d="m15 5 4 4" />
                        </svg>
                    </button>

                    <div class="dialog__header-actions-divider"></div>

                    <button class="button button--icon button--secondary" data-dialog-close-button autofocus>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="button__icon">
                            <path d="M18 6 6 18" />
                            <path d="m6 6 12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="dialog__content">
                <div class="event-details" data-event-details>
                    <div class="event-details__line"></div>
                    <div class="event-details__content">
                        <div class="event-details__title" data-event-details-title></div>
                        <div class="event-details__time">
                            <time data-event-details-date></time> <br />
                            <time data-event-details-start-time></time> - <time data-event-details-end-time></time>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </dialog>

    <dialog class="dialog" data-dialog="event-delete">
        <div class="dialog__wrapper">
            <div class="dialog__header">
                <h2 class="dialog__title">Delete event</h2>

                <button class="button button--icon button--secondary" data-dialog-close-button autofocus>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="button__icon">
                        <path d="M18 6 6 18" />
                        <path d="m6 6 12 12" />
                    </svg>
                </button>
            </div>

            <div class="dialog__content">
                <p>Do you really want to delete <strong data-event-delete-title></strong>?</p>
            </div>

            <div class="dialog__footer">
                <div class="dialog__actions">
                    <button class="button button--secondary" data-dialog-close-button>Cancel</button>
                    <button class="button button--danger" data-event-delete-button>Delete</button>
                </div>
            </div>
        </div>
    </dialog>

    <dialog class="dialog dialog--sidebar" data-dialog="mobile-sidebar">
        <div class="dialog__wrapper">
            <div class="dialog__content">
                <div class="mini-calendar" data-mini-calendar>
                    <div class="mini-calendar__header">
                        <time class="mini-calendar__date" data-mini-calendar-date></time>

                        <div class="mini-calendar__controls">
                            <button class="button button--icon button--secondary button--sm"
                                data-mini-calendar-previous-button>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" class="button__icon">
                                    <path d="m15 18-6-6 6-6" />
                                </svg>
                            </button>

                            <button class="button button--icon button--secondary button--sm"
                                data-mini-calendar-next-button>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" class="button__icon">
                                    <path d="m9 18 6-6-6-6" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="mini-calendar__content">
                        <ul class="mini-calendar__day-of-week-list">
                            <li class="mini-calendar__day-of-week">S</li>
                            <li class="mini-calendar__day-of-week">M</li>
                            <li class="mini-calendar__day-of-week">T</li>
                            <li class="mini-calendar__day-of-week">W</li>
                            <li class="mini-calendar__day-of-week">T</li>
                            <li class="mini-calendar__day-of-week">F</li>
                            <li class="mini-calendar__day-of-week">S</li>
                        </ul>

                        <ul class="mini-calendar__day-list" data-mini-calendar-day-list></ul>
                    </div>
                </div>
            </div>
        </div>
    </dialog>

    <template data-template="month-calendar">
        <div class="month-calendar" data-month-calendar>
            <ul class="month-calendar__day-of-week-list">
                <li class="month-calendar__day-of-week">Sun</li>
                <li class="month-calendar__day-of-week">Mon</li>
                <li class="month-calendar__day-of-week">Tue</li>
                <li class="month-calendar__day-of-week">Wed</li>
                <li class="month-calendar__day-of-week">Thu</li>
                <li class="month-calendar__day-of-week">Fri</li>
                <li class="month-calendar__day-of-week">Sat</li>
            </ul>

            <div class="month-calendar__day-list-wrapper">
                <ul class="month-calendar__day-list" data-month-calendar-day-list data-calendar-scrollable>
                </ul>
            </div>
        </div>
    </template>

    <template data-template="month-calendar-day">
        <li class="month-calendar__day" data-month-calendar-day>
            <button class="month-calendar__day-label" data-month-calendar-day-label></button>
            <div class="month-calendar__event-list-wrapper" data-month-calendar-event-list-wrapper>
                <ul class="event-list" data-event-list></ul>
            </div>
        </li>
    </template>

    <template data-template="week-calendar">
        <div class="week-calendar" data-week-calendar>
            <ul class="week-calendar__day-of-week-list" data-week-calendar-day-of-week-list>
            </ul>

            <ul class="week-calendar__all-day-list" data-week-calendar-all-day-list>
            </ul>

            <div class="week-calendar__content">
                <div class="week-calendar__content-inner" data-calendar-scrollable>
                    <ul class="week-calendar__time-list">
                        <li class="week-calendar__time-item">
                            <time class="week-calendar__time">12:00 AM</time>
                        </li>
                        <li class="week-calendar__time-item">
                            <time class="week-calendar__time">1:00 AM</time>
                        </li>
                        <li class="week-calendar__time-item">
                            <time class="week-calendar__time">2:00 AM</time>
                        </li>
                        <li class="week-calendar__time-item">
                            <time class="week-calendar__time">3:00 AM</time>
                        </li>
                        <li class="week-calendar__time-item">
                            <time class="week-calendar__time">4:00 AM</time>
                        </li>
                        <li class="week-calendar__time-item">
                            <time class="week-calendar__time">5:00 AM</time>
                        </li>
                        <li class="week-calendar__time-item">
                            <time class="week-calendar__time">6:00 AM</time>
                        </li>
                        <li class="week-calendar__time-item">
                            <time class="week-calendar__time">7:00 AM</time>
                        </li>
                        <li class="week-calendar__time-item">
                            <time class="week-calendar__time">8:00 AM</time>
                        </li>
                        <li class="week-calendar__time-item">
                            <time class="week-calendar__time">9:00 AM</time>
                        </li>
                        <li class="week-calendar__time-item">
                            <time class="week-calendar__time">10:00 AM</time>
                        </li>
                        <li class="week-calendar__time-item">
                            <time class="week-calendar__time">11:00 AM</time>
                        </li>
                        <li class="week-calendar__time-item">
                            <time class="week-calendar__time">12:00 PM</time>
                        </li>
                        <li class="week-calendar__time-item">
                            <time class="week-calendar__time">1:00 PM</time>
                        </li>
                        <li class="week-calendar__time-item">
                            <time class="week-calendar__time">2:00 PM</time>
                        </li>
                        <li class="week-calendar__time-item">
                            <time class="week-calendar__time">3:00 PM</time>
                        </li>
                        <li class="week-calendar__time-item">
                            <time class="week-calendar__time">4:00 PM</time>
                        </li>
                        <li class="week-calendar__time-item">
                            <time class="week-calendar__time">5:00 PM</time>
                        </li>
                        <li class="week-calendar__time-item">
                            <time class="week-calendar__time">6:00 PM</time>
                        </li>
                        <li class="week-calendar__time-item">
                            <time class="week-calendar__time">7:00 PM</time>
                        </li>
                        <li class="week-calendar__time-item">
                            <time class="week-calendar__time">8:00 PM</time>
                        </li>
                        <li class="week-calendar__time-item">
                            <time class="week-calendar__time">9:00 PM</time>
                        </li>
                        <li class="week-calendar__time-item">
                            <time class="week-calendar__time">10:00 PM</time>
                        </li>
                        <li class="week-calendar__time-item">
                            <time class="week-calendar__time">11:00 PM</time>
                        </li>
                    </ul>

                    <div class="week-calendar__columns" data-week-calendar-columns>
                    </div>
                </div>
            </div>
        </div>
    </template>

    <template data-template="week-calendar-day-of-week">
        <li class="week-calendar__day-of-week" data-week-calendar-day-of-week>
            <button class="week-calendar__day-of-week-button" data-week-calendar-day-of-week-button>
                <span class="week-calendar__day-of-week-day" data-week-calendar-day-of-week-day></span>
                <span class="week-calendar__day-of-week-number" data-week-calendar-day-of-week-number></span>
            </button>
        </li>
    </template>

    <template data-template="week-calendar-all-day-list-item">
        <li class="week-calendar__all-day-list-item" data-week-calendar-all-day-list-item>
            <ul class="event-list" data-event-list></ul>
        </li>
    </template>

    <template data-template="week-calendar-column">
        <div class="week-calendar__column" data-week-calendar-column>
            <div class="week-calendar__cell" data-week-calendar-cell="0"></div>
            <div class="week-calendar__cell" data-week-calendar-cell="60"></div>
            <div class="week-calendar__cell" data-week-calendar-cell="120"></div>
            <div class="week-calendar__cell" data-week-calendar-cell="180"></div>
            <div class="week-calendar__cell" data-week-calendar-cell="240"></div>
            <div class="week-calendar__cell" data-week-calendar-cell="300"></div>
            <div class="week-calendar__cell" data-week-calendar-cell="360"></div>
            <div class="week-calendar__cell" data-week-calendar-cell="420"></div>
            <div class="week-calendar__cell" data-week-calendar-cell="480"></div>
            <div class="week-calendar__cell" data-week-calendar-cell="540"></div>
            <div class="week-calendar__cell" data-week-calendar-cell="600"></div>
            <div class="week-calendar__cell" data-week-calendar-cell="660"></div>
            <div class="week-calendar__cell" data-week-calendar-cell="720"></div>
            <div class="week-calendar__cell" data-week-calendar-cell="780"></div>
            <div class="week-calendar__cell" data-week-calendar-cell="840"></div>
            <div class="week-calendar__cell" data-week-calendar-cell="900"></div>
            <div class="week-calendar__cell" data-week-calendar-cell="960"></div>
            <div class="week-calendar__cell" data-week-calendar-cell="1020"></div>
            <div class="week-calendar__cell" data-week-calendar-cell="1080"></div>
            <div class="week-calendar__cell" data-week-calendar-cell="1140"></div>
            <div class="week-calendar__cell" data-week-calendar-cell="1200"></div>
            <div class="week-calendar__cell" data-week-calendar-cell="1260"></div>
            <div class="week-calendar__cell" data-week-calendar-cell="1320"></div>
            <div class="week-calendar__cell" data-week-calendar-cell="1380"></div>
        </div>
    </template>

    <template data-template="event-list-item">
        <li class="event-list__item" data-event-list-item>
        </li>
    </template>

    <template data-template="event">
        <button class="event" data-event>
            <span class="event__color"></span>
            <span class="event__title" data-event-title></span>
            <span class="event__time">
                <time data-event-start-time></time> - <time data-event-end-time></time>
            </span>
        </button>
    </template>

    <template data-template="mini-calendar-day-list-item">
        <li class="mini-calendar__day-list-item" data-mini-calendar-day-list-item>
            <button class="mini-calendar__day button button--sm" data-mini-calendar-day></button>
        </li>
    </template>
</body>

</html>
