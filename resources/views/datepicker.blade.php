<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hijri DatePicker</title>
    <!-- Fonts -->
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%232563eb' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'><rect x='3' y='4' width='18' height='18' rx='2' ry='2'></rect><line x1='16' y1='2' x2='16' y2='6'></line><line x1='8' y1='2' x2='8' y2='6'></line><line x1='3' y1='10' x2='21' y2='10'></line></svg>">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#2563eb',
                        'primary-light': '#dbeafe',
                        secondary: '#7c3aed',
                        'secondary-light': '#ede9fe',
                        success: '#10b981',
                        dark: '#0f172a',
                        muted: '#64748b',
                    },
                    borderRadius: {
                        'm3': '2rem',
                    },
                }
            }
        }
    </script>
    <style>
        @font-face {
            font-family: 'Al-Kanz';
            src: url('/fonts/AL-KANZ.TTF') format('truetype');
            font-weight: normal;
            font-style: normal;
        }
        body { font-family: 'Helvetica', 'Arial', sans-serif; }
        .font-kanz { font-family: 'Al-Kanz', serif; }
        [x-cloak] { display: none !important; }
        .card { border-radius: 1.5rem; transition: all 0.3s ease; }
    </style>
</head>
<body class="min-h-screen bg-[#F1F5F9] pb-20 pt-12 flex flex-col items-center gap-8 px-6" x-data="datepickerApp()" x-init="init()">

    <div class="w-full max-w-xl bg-white rounded-[40px] shadow-2xl border border-gray-100 relative">
        <div class="bg-primary p-12 text-white relative overflow-hidden rounded-t-[40px]">
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -mr-32 -mt-32 blur-3xl"></div>
            <h2 class="text-3xl font-black relative z-10">Hijri DatePicker</h2>
            <p class="opacity-70 text-sm font-medium relative z-10">Select Hijri or Gregorian — Fetch both.</p>
        </div>

        <div class="p-10 space-y-8">
            <div class="space-y-6">
                <!-- DatePicker Trigger -->
                <div class="space-y-3" @click.away="isOpen = false">
                    <label class="text-xs font-black text-muted uppercase tracking-[0.2em] flex items-center gap-2">
                        <i data-lucide="info" class="w-3.5 h-3.5 text-primary"></i>
                        Select Date (Hijri/Gregorian)
                    </label>
                    <div class="relative">
                        <button 
                            @click="isOpen = !isOpen"
                            class="w-full px-6 py-4 bg-white border-2 border-gray-100 rounded-2xl flex items-center justify-between hover:border-primary/30 transition-all group"
                        >
                            <span class="font-bold text-dark" x-text="selectedDateLabel || 'Select a date...'"></span>
                            <i data-lucide="calendar" class="w-5 h-5 text-muted group-hover:text-primary transition-colors"></i>
                        </button>

                        <!-- Popover Calendar -->
                        <div 
                            x-show="isOpen" 
                            x-cloak
                            x-transition
                            class="absolute top-full left-0 right-0 mt-4 bg-white rounded-3xl shadow-2xl border border-gray-100 z-[100]"
                        >
                            <div class="p-5 border-b border-gray-50 flex items-center justify-between bg-gray-50/50">
                                <div class="flex items-center gap-2">
                                    <button @click="jumpMode = 'hijri'" :class="jumpMode === 'hijri' ? 'bg-primary text-white shadow-md' : 'text-muted hover:bg-gray-100'" class="px-3 py-1.5 text-[10px] font-black uppercase tracking-wider rounded-lg transition-all">Hijri</button>
                                    <button @click="jumpMode = 'gregorian'" :class="jumpMode === 'gregorian' ? 'bg-secondary text-white shadow-md' : 'text-muted hover:bg-gray-100'" class="px-3 py-1.5 text-[10px] font-black uppercase tracking-wider rounded-lg transition-all">Gregorian</button>
                                </div>
                                <div class="flex items-center gap-1">
                                    <button @click="changeMonth(-1)" class="p-1.5 hover:bg-white rounded-lg text-muted transition-all border border-transparent hover:border-gray-100"><i data-lucide="chevron-left" class="w-4 h-4"></i></button>
                                    <button @click="changeMonth(1)" class="p-1.5 hover:bg-white rounded-lg text-muted transition-all border border-transparent hover:border-gray-100"><i data-lucide="chevron-right" class="w-4 h-4"></i></button>
                                </div>
                            </div>

                            <div class="p-6">
                                <div class="flex items-center justify-between mb-6">
                                    <template x-if="jumpMode === 'hijri'">
                                        <div class="flex items-center gap-2">
                                            <span class="text-2xl font-black text-dark" x-text="MONTH_NAMES[viewMonth]"></span>
                                            <span class="text-base font-bold text-primary bg-primary-light px-3 py-1.5 rounded-lg" x-text="viewYear + ' H'"></span>
                                        </div>
                                    </template>
                                    <template x-if="jumpMode === 'gregorian'">
                                        <div class="flex items-center gap-2">
                                            <span class="text-2xl font-black text-dark" x-text="GREGORIAN_MONTHS[viewGregMonth]"></span>
                                            <span class="text-base font-bold text-secondary bg-secondary-light px-3 py-1.5 rounded-lg" x-text="viewGregYear"></span>
                                        </div>
                                    </template>
                                </div>

                                <div class="grid grid-cols-7 mb-2">
                                    <template x-for="day in ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa']" :key="day">
                                        <div class="text-xs font-black text-muted/40 text-center uppercase tracking-widest" x-text="day"></div>
                                    </template>
                                </div>

                                <div class="grid grid-cols-7 gap-1">
                                    <template x-for="day in calendarData" :key="day.id">
                                        <button 
                                            @click="!day.isFiller && selectDate(day)"
                                            :disabled="day.isFiller"
                                            :class="{
                                                'aspect-square flex flex-col items-center justify-center rounded-xl transition-all relative': true,
                                                'hover:bg-gray-50 text-dark': !day.isFiller && !day.isSelected,
                                                'bg-primary text-white shadow-lg shadow-primary/20': !day.isFiller && day.isSelected && jumpMode === 'hijri',
                                                'bg-secondary text-white shadow-lg shadow-secondary/20': !day.isFiller && day.isSelected && jumpMode === 'gregorian',
                                                'opacity-0 cursor-default': day.isFiller
                                            }"
                                            <div class="flex flex-col items-center">
                                                <span 
                                                    :class="{
                                                        'font-kanz font-black text-xl': true,
                                                        'text-white': day.isSelected,
                                                        'text-primary': !day.isSelected && jumpMode === 'hijri',
                                                        'text-muted/40': !day.isSelected && jumpMode === 'gregorian'
                                                    }" 
                                                    x-text="day.hijriDay"></span>
                                                <span 
                                                    :class="{
                                                        'font-black text-base -mt-1': true,
                                                        'text-white': day.isSelected,
                                                        'text-secondary': !day.isSelected && jumpMode === 'gregorian',
                                                        'text-muted/40': !day.isSelected && jumpMode === 'hijri'
                                                    }" 
                                                    x-text="day.gregDay"></span>
                                            </div>
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-3">
                        <label class="text-xs font-black text-muted uppercase tracking-[0.2em] flex items-center gap-2">
                            <i data-lucide="moon" class="w-3.5 h-3.5 text-primary"></i>
                            Hijri Format
                        </label>
                        <input
                            type="text" readonly x-model="formData.hijri"
                            placeholder="DD-MM-YYYY"
                            class="w-full px-5 py-4 bg-gray-50 rounded-2xl border border-gray-100 font-bold text-dark outline-none"
                        >
                    </div>
                    <div class="space-y-3">
                        <label class="text-xs font-black text-muted uppercase tracking-[0.2em] flex items-center gap-2">
                            <i data-lucide="globe" class="w-3.5 h-3.5 text-secondary"></i>
                            Gregorian Format
                        </label>
                        <input
                            type="text" readonly x-model="formData.gregorian"
                            placeholder="DD-MM-YYYY"
                            class="w-full px-5 py-4 bg-gray-50 rounded-2xl border border-gray-100 font-bold text-dark outline-none"
                        >
                    </div>
                </div>

                <div class="space-y-3">
                    <label class="text-xs font-black text-muted uppercase tracking-[0.2em] flex items-center gap-2">
                        <i data-lucide="globe" class="w-3.5 h-3.5 text-secondary"></i>
                        Name in English
                    </label>
                    <input
                        type="text" readonly x-model="formData.englishName"
                        placeholder="Auto-filled English date..."
                        class="w-full px-5 py-4 bg-gray-50 rounded-2xl border border-gray-100 font-bold text-dark outline-none"
                    >
                </div>

                <div class="space-y-3">
                    <label class="text-xs font-black text-muted uppercase tracking-[0.2em] flex items-center gap-2">
                        <i data-lucide="languages" class="w-3.5 h-3.5 text-success"></i>
                        Name in Arabic (RTL)
                    </label>
                    <input
                        type="text" readonly dir="rtl" x-model="formData.arabicName"
                        placeholder="اسم التاريخ بالعربي..."
                        class="w-full px-5 py-4 bg-gray-50 rounded-2xl border border-gray-100 text-4xl text-dark outline-none text-right font-kanz"
                    >
                </div>
            </div>
        </div>
    </div>

    <!-- Selection Summary -->
    <div 
        x-show="formData.hijri" 
        x-cloak
        x-transition
        class="w-full max-w-xl bg-white/50 backdrop-blur-md p-6 rounded-[32px] border border-white/20 grid grid-cols-2 gap-4 text-center"
    >
        <div>
            <p class="text-[10px] font-black text-muted uppercase tracking-widest mb-1">Hijri</p>
            <p class="font-bold text-primary" x-text="formData.hijri"></p>
        </div>
        <div>
            <p class="text-[11px] font-black text-muted uppercase tracking-widest mb-1">Gregorian</p>
            <p class="font-bold text-secondary" x-text="formData.gregorian"></p>
        </div>
    </div>

    <script>
        // Hijri Logic (Same as calendar)
        const KABISA_YEAR_REMAINDERS = [2, 5, 8, 10, 13, 16, 19, 21, 24, 27, 29];
        const DAYS_IN_YEAR = [30, 59, 89, 118, 148, 177, 207, 236, 266, 295, 325];
        const DAYS_IN_30_YEARS = [
            354, 708, 1063, 1417, 1771, 2126, 2480, 2834, 3189, 3543,
            3898, 4252, 4606, 4961, 5315, 5669, 6024, 6378, 6732, 7087,
            7441, 7796, 8150, 8504, 8859, 9213, 9567, 9922, 10276, 10631,
        ];

        function isJulian(date) {
            const year = date.getFullYear();
            if (year < 1582) return true;
            if (year === 1582) {
                if (date.getMonth() < 9) return true;
                if (date.getMonth() === 9 && date.getDate() < 5) return true;
            }
            return false;
        }

        function gregorianToAJD(date) {
            let year = date.getFullYear();
            let month = date.getMonth() + 1;
            const day = date.getDate();
            if (month < 3) { year--; month += 12; }
            let b = 0;
            if (!isJulian(date)) {
                const a = Math.floor(year / 100);
                b = 2 - a + Math.floor(a / 4);
            }
            return Math.floor(365.25 * (year + 4716)) + Math.floor(30.6001 * (month + 1)) + day + b - 1524.5;
        }

        function ajdToGregorian(ajd) {
            const z = Math.floor(ajd + 0.5);
            const f = ajd + 0.5 - z;
            let a = z;
            if (z >= 2299161) {
                const alpha = Math.floor((z - 1867216.25) / 36524.25);
                a = z + 1 + alpha - Math.floor(0.25 * alpha);
            }
            const b = a + 1524;
            const c = Math.floor((b - 122.1) / 365.25);
            const d = Math.floor(365.25 * c);
            const e = Math.floor((b - d) / 30.6001);
            const dayWithFraction = b - d - Math.floor(30.6001 * e) + f;
            const month = e < 14 ? e - 2 : e - 14;
            const year = month < 2 ? c - 4715 : c - 4716;
            return new Date(year, month, Math.floor(dayWithFraction));
        }

        class HijriDate {
            constructor(year, month, day) { this.year = year; this.month = month; this.day = day; }
            static isKabisa(year) { return KABISA_YEAR_REMAINDERS.includes(year % 30); }
            static daysInMonth(year, month) { return (month === 11 && this.isKabisa(year)) || month % 2 === 0 ? 30 : 29; }
            dayOfYear() { return this.month === 0 ? this.day : DAYS_IN_YEAR[this.month - 1] + this.day; }
            toAJD() {
                const y30 = Math.floor(this.year / 30.0);
                let ajd = 1948083.5 + y30 * 10631 + this.dayOfYear();
                if (this.year % 30 !== 0) ajd += DAYS_IN_30_YEARS[(this.year % 30) - 1];
                return ajd;
            }
            static fromAJD(ajd) {
                let left = Math.floor(ajd - 1948083.5);
                const y30 = Math.floor(left / 10631.0);
                left -= y30 * 10631;
                let i = 0;
                while (i < 30 && left > DAYS_IN_30_YEARS[i]) i++;
                const year = Math.round(y30 * 30 + i);
                if (i > 0) left -= DAYS_IN_30_YEARS[i - 1];
                i = 0;
                while (i < 12 && left > (DAYS_IN_YEAR[i] || 355)) i++;
                const month = i;
                const date = i > 0 ? Math.round(left - DAYS_IN_YEAR[i - 1]) : Math.round(left);
                return new HijriDate(year, month, date);
            }
            static fromGregorian(date) { return this.fromAJD(gregorianToAJD(date)); }
            toGregorian() { return ajdToGregorian(this.toAJD()); }
        }

        function datepickerApp() {
            return {
                isOpen: false,
                jumpMode: 'hijri',
                viewYear: 1447,
                viewMonth: 0,
                viewGregYear: new Date().getFullYear(),
                viewGregMonth: new Date().getMonth(),
                selectedDateLabel: '',
                formData: {
                    hijri: '',
                    gregorian: '',
                    englishName: '',
                    arabicName: ''
                },
                MONTH_NAMES: @json($monthNames),
                ARABIC_MONTH_NAMES: @json($arabicMonthNames),
                GREGORIAN_MONTHS: Array.from({length: 12}, (_, i) => new Date(0, i).toLocaleString('default', { month: 'long' })),
                calendarData: [],

                init() {
                    const today = new Date();
                    const hToday = HijriDate.fromGregorian(today);
                    this.viewYear = hToday.year;
                    this.viewMonth = hToday.month;
                    this.viewGregYear = today.getFullYear();
                    this.viewGregMonth = today.getMonth();

                    // Pre-select today
                    this.selectDate({day: hToday.day, isFiller: false});

                    this.refreshCalendar();
                    this.$nextTick(() => lucide.createIcons());
                },

                refreshCalendar() {
                    const days = [];
                    if (this.jumpMode === 'hijri') {
                        const firstDay = new HijriDate(this.viewYear, this.viewMonth, 1);
                        const startDay = firstDay.toGregorian().getDay();
                        const totalDays = HijriDate.daysInMonth(this.viewYear, this.viewMonth);
                        for (let i = 0; i < startDay; i++) days.push({ isFiller: true, id: 'f'+i });
                        for (let i = 1; i <= totalDays; i++) {
                            const hDate = new HijriDate(this.viewYear, this.viewMonth, i);
                            const gDate = hDate.toGregorian();
                            days.push({ 
                                day: i,
                                hijriDay: i,
                                gregDay: gDate.getDate(),
                                isFiller: false, 
                                id: i,
                                isSelected: this.checkSelected(i)
                            });
                        }
                    } else {
                        const firstDay = new Date(this.viewGregYear, this.viewGregMonth, 1);
                        const startDay = firstDay.getDay();
                        const totalDays = new Date(this.viewGregYear, this.viewGregMonth + 1, 0).getDate();
                        for (let i = 0; i < startDay; i++) days.push({ isFiller: true, id: 'f'+i });
                        for (let i = 1; i <= totalDays; i++) {
                            const gDate = new Date(this.viewGregYear, this.viewGregMonth, i);
                            const hDate = HijriDate.fromGregorian(gDate);
                            days.push({ 
                                day: i,
                                gregDay: i,
                                hijriDay: hDate.day,
                                isFiller: false, 
                                id: i,
                                isSelected: this.checkSelected(i)
                            });
                        }
                    }
                    this.calendarData = days;
                },

                checkSelected(day) {
                    if (!this.formData.hijri) return false;
                    const parts = this.formData.hijri.split('-');
                    if (this.jumpMode === 'hijri') {
                        return parseInt(parts[0]) === day && parseInt(parts[1]) === (this.viewMonth + 1) && parseInt(parts[2]) === this.viewYear;
                    } else {
                        const gParts = this.formData.gregorian.split('-');
                        return parseInt(gParts[0]) === day && parseInt(gParts[1]) === (this.viewGregMonth + 1) && parseInt(gParts[2]) === this.viewGregYear;
                    }
                },

                changeMonth(offset) {
                    if (this.jumpMode === 'hijri') {
                        this.viewMonth += offset;
                        if (this.viewMonth > 11) { this.viewMonth = 0; this.viewYear++; }
                        else if (this.viewMonth < 0) { this.viewMonth = 11; this.viewYear--; }
                    } else {
                        this.viewGregMonth += offset;
                        const date = new Date(this.viewGregYear, this.viewGregMonth, 1);
                        this.viewGregYear = date.getFullYear();
                        this.viewGregMonth = date.getMonth();
                    }
                    this.refreshCalendar();
                },

                selectDate(day) {
                    let hDate, gDate;
                    if (this.jumpMode === 'hijri') {
                        hDate = new HijriDate(this.viewYear, this.viewMonth, day.day);
                        gDate = hDate.toGregorian();
                    } else {
                        gDate = new Date(this.viewGregYear, this.viewGregMonth, day.day);
                        hDate = HijriDate.fromGregorian(gDate);
                    }

                    const hDay = hDate.day.toString().padStart(2, '0');
                    const hMonth = (hDate.month + 1).toString().padStart(2, '0');
                    this.formData.hijri = `${hDay}-${hMonth}-${hDate.year}`;
                    
                    const gDay = gDate.getDate().toString().padStart(2, '0');
                    const gMonth = (gDate.getMonth() + 1).toString().padStart(2, '0');
                    this.formData.gregorian = `${gDay}-${gMonth}-${gDate.getFullYear()}`;
                    
                    this.formData.englishName = `${hDate.day} ${this.MONTH_NAMES[hDate.month]} ${hDate.year} H`;
                    this.formData.arabicName = `${hDate.day} من ${this.ARABIC_MONTH_NAMES[hDate.month]} ${hDate.year}هـ`;
                    
                    this.selectedDateLabel = this.formData.englishName;
                    this.isOpen = false;
                }
            }
        }
    </script>
</body>
</html>
