<?php
// app/Models/Reservation.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Reservation extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    // app/Models/Reservation.php
    protected $fillable = [
        'guest_id',
        'user_id',
        'booking_no',
        'book_by',
        'booking_date',
        'room_numbers',
        'number_of_rooms',
        'number_of_persons',
        'room_type',
        'arrival_date',
        'arrival_time',
        'departure_date',
        'total_nights',
        'company_agent',
        'agent_telp',
        'agent_fax',
        'agent_email',
        'room_rate_net',
        'payment_method',
        'bank_account',
        'bank_account_name',
        'cc_number',
        'cc_holder_name',
        'cc_type',
        'cc_expired',
        'cc_signature',
        'safety_deposit_box',
        'issued_by',
        'issued_date',
        'status',
        'cancellation_number',
        'payment_status', // TAMBAHKAN INI
        'payment_notes',  // TAMBAHKAN INI
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'room_numbers' => 'array',
        'booking_date' => 'date',
        'arrival_date' => 'date',
        'departure_date' => 'date',
        'issued_date' => 'date',
        'arrival_time' => 'string',
        'number_of_rooms' => 'integer',
        'number_of_persons' => 'integer',
        'total_nights' => 'integer',
        'room_rate_net' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'cc_number',
        'cc_signature',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'status' => 'non-guaranteed',
    ];

    /**
     * Get the guest that owns the reservation.
     */
    public function guest(): BelongsTo
    {
        return $this->belongsTo(Guest::class);
    }

    /**
     * Get the user (receptionist) that created the reservation.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get formatted room numbers as string.
     */
    public function getFormattedRoomNumbersAttribute(): string
    {
        if (is_array($this->room_numbers)) {
            return implode(', ', $this->room_numbers);
        }
        
        return $this->room_numbers ?? '-';
    }

    /**
     * Get arrival datetime combined.
     */
    public function getArrivalDateTimeAttribute(): ?Carbon
    {
        if (!$this->arrival_date) {
            return null;
        }
        
        $datetime = $this->arrival_date->format('Y-m-d');
        
        if ($this->arrival_time) {
            $datetime .= ' ' . $this->arrival_time;
        } else {
            $datetime .= ' 14:00:00'; // Default check-in time 2 PM
        }
        
        return Carbon::parse($datetime);
    }

    /**
     * Get departure datetime combined.
     */
    public function getDepartureDateTimeAttribute(): ?Carbon
    {
        if (!$this->departure_date) {
            return null;
        }
        
        return Carbon::parse($this->departure_date->format('Y-m-d') . ' 12:00:00'); // Check-out time 12 PM
    }

    /**
     * Get total stay duration in days.
     */
    public function getStayDurationAttribute(): int
    {
        return $this->arrival_date->diffInDays($this->departure_date);
    }

    /**
     * Calculate total room rate.
     */
    public function getTotalRoomRateAttribute(): int
    {
        return ($this->room_rate_net ?? 0) * ($this->number_of_rooms ?? 1) * ($this->total_nights ?? 0);
    }

    /**
     * Get formatted room rate.
     */
    public function getFormattedRoomRateAttribute(): string
    {
        return 'Rp ' . number_format($this->room_rate_net ?? 0, 0, ',', '.');
    }

    /**
     * Get formatted total rate.
     */
    public function getFormattedTotalRateAttribute(): string
    {
        return 'Rp ' . number_format($this->total_room_rate, 0, ',', '.');
    }

    /**
     * Get status badge class for styling.
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return match($this->status) {
            'guaranteed' => 'bg-green-100 text-green-800',
            'non-guaranteed' => 'bg-yellow-100 text-yellow-800',
            'cancelled' => 'bg-red-100 text-red-800',
            'checked-in' => 'bg-blue-100 text-blue-800',
            'checked-out' => 'bg-gray-100 text-gray-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    /**
     * Get status in Indonesian.
     */
    public function getStatusIndonesianAttribute(): string
    {
        return match($this->status) {
            'guaranteed' => 'Terjamin',
            'non-guaranteed' => 'Belum Terjamin',
            'cancelled' => 'Dibatalkan',
            'checked-in' => 'Check-in',
            'checked-out' => 'Check-out',
            default => $this->status,
        };
    }

    /**
     * Get payment method in Indonesian.
     */
    public function getPaymentMethodIndonesianAttribute(): string
    {
        return match($this->payment_method) {
            'Bank Transfer' => 'Transfer Bank',
            'Credit Card' => 'Kartu Kredit',
            default => '-',
        };
    }

    /**
     * Check if reservation is guaranteed.
     */
    public function getIsGuaranteedAttribute(): bool
    {
        return $this->status === 'guaranteed';
    }

    /**
     * Check if reservation can be cancelled.
     */
    public function getCanBeCancelledAttribute(): bool
    {
        return in_array($this->status, ['non-guaranteed', 'guaranteed']) && 
               $this->arrival_date->isFuture();
    }

    /**
     * Mask credit card number for security.
     */
    public function getMaskedCcNumberAttribute(): ?string
    {
        if (!$this->cc_number) {
            return null;
        }
        
        $length = strlen($this->cc_number);
        
        if ($length <= 4) {
            return $this->cc_number;
        }
        
        $masked = str_repeat('•', $length - 4) . substr($this->cc_number, -4);
        
        // Add spaces every 4 characters for better readability
        return implode(' ', str_split($masked, 4));
    }

    /**
     * Scope a query to filter by date range.
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('arrival_date', [$startDate, $endDate]);
    }

    /**
     * Scope a query to filter by status.
     */
    public function scopeOfStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope a query to get today's check-ins.
     */
    public function scopeTodayCheckIn($query)
    {
        return $query->whereDate('arrival_date', Carbon::today());
    }

    /**
     * Scope a query to get today's check-outs.
     */
    public function scopeTodayCheckOut($query)
    {
        return $query->whereDate('departure_date', Carbon::today());
    }

    /**
     * Scope a query to get upcoming reservations.
     */
    public function scopeUpcoming($query, $days = 7)
    {
        return $query->whereBetween('arrival_date', [
            Carbon::today(),
            Carbon::today()->addDays($days)
        ])->whereNotIn('status', ['cancelled', 'checked-out']);
    }

    /**
     * Scope a query to search by booking number or guest name.
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('booking_no', 'like', "%{$search}%")
              ->orWhereHas('guest', function ($guestQuery) use ($search) {
                  $guestQuery->where('name', 'like', "%{$search}%")
                             ->orWhere('phone', 'like', "%{$search}%")
                             ->orWhere('email', 'like', "%{$search}%");
              });
        });
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Generate booking number if not set
        static::creating(function ($reservation) {
            if (empty($reservation->booking_no)) {
                $reservation->booking_no = self::generateBookingNumber();
            }
            
            if (empty($reservation->booking_date)) {
                $reservation->booking_date = now();
            }
            
            // Calculate total nights if not set
            if (empty($reservation->total_nights) && $reservation->arrival_date && $reservation->departure_date) {
                $reservation->total_nights = $reservation->arrival_date->diffInDays($reservation->departure_date);
            }
        });

        // Update total nights when dates change
        static::saving(function ($reservation) {
            if ($reservation->isDirty(['arrival_date', 'departure_date'])) {
                $reservation->total_nights = $reservation->arrival_date->diffInDays($reservation->departure_date);
            }
        });
    }

    /**
     * Generate unique booking number.
     */
    protected static function generateBookingNumber(): string
    {
        $prefix = 'BOOK';
        $year = date('Y');
        $month = date('m');
        
        $lastReservation = self::whereYear('created_at', $year)
                               ->whereMonth('created_at', $month)
                               ->latest()
                               ->first();
        
        if ($lastReservation) {
            $lastNumber = intval(substr($lastReservation->booking_no, -4));
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }
        
        return "{$prefix}/{$year}{$month}/{$newNumber}";
    }
}