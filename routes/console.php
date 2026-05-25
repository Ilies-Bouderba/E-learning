<?php

use Illuminate\Support\Facades\Schedule;

// Run exam reminders every 5 minutes
Schedule::command('exams:send-reminders')->everyFiveMinutes();
