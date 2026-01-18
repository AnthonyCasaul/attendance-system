<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Attendance extends Model
{
    protected $fillable = [
        'user_id',
        'clock_in',
        'clock_out',
        'clock_in_photo',
        'clock_out_photo',
        'hours_worked',
        'date',
        'notes',
    ];

    protected $casts = [
        'clock_in' => 'datetime',
        'clock_out' => 'datetime',
        'date' => 'date',
        'hours_worked' => 'decimal:2',
    ];

    /**
     * Get the user that owns the attendance.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Calculate hours worked when clock_out is set.
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($attendance) {
            if ($attendance->clock_in && $attendance->clock_out) {
                $clockIn = Carbon::parse($attendance->clock_in);
                $clockOut = Carbon::parse($attendance->clock_out);
                $attendance->hours_worked = $clockOut->diffInHours($clockIn, true);
            }
        });
    }

    /**
     * Get formatted hours worked.
     */
    public function getFormattedHoursWorkedAttribute()
    {
        if ($this->hours_worked) {
            $hours = floor($this->hours_worked);
            $minutes = ($this->hours_worked - $hours) * 60;
            return sprintf('%d hours %d minutes', $hours, $minutes);
        }
        return 'N/A';
    }
}
