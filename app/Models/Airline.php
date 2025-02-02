<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Airline extends Model
{
    use HasFactory;
    protected $table = 'airlines';

    protected $fillable = [
        'name',
        'iata_code',
        'icao_code',
        'country',
        'address',
        'phone',
        'email',
        'description',
        'active'
    ];

    protected $casts = [
        'active' => 'boolean'
    ];

    public const STANDARD_PASSENGER_WEIGHT = 'standard_passenger_weight';
    public const STANDARD_COCKPIT_CREW_WEIGHT = 'standard_cockpit_crew_weight';
    public const STANDARD_CABIN_CREW_WEIGHT = 'standard_cabin_crew_weight';
    public const STANDARD_PANTRY_WEIGHT = 'standard_pantry_weight';

    public function aircraft(): HasMany
    {
        return $this->hasMany(Aircraft::class);
    }

    public function flights(): HasMany
    {
        return $this->hasMany(Flight::class);
    }

    public function settings(): HasMany
    {
        return $this->hasMany(Setting::class);
    }

    public function aircraftTypes(): HasMany
    {
        return $this->hasMany(AircraftType::class);
    }

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function getStandardPassengerWeight(): float
    {
        return (float) ($this->settings()
            ->where('key', self::STANDARD_PASSENGER_WEIGHT)
            ->first()
            ->value ?? 75);
    }

    public function getStandardCockpitCrewWeight(): float
    {
        return (float) ($this->settings()
            ->where('key', self::STANDARD_COCKPIT_CREW_WEIGHT)
            ->first()
            ->value ?? 85);
    }

    public function getStandardCabinCrewWeight(): float
    {
        return (float) ($this->settings()
            ->where('key', self::STANDARD_CABIN_CREW_WEIGHT)
            ->first()
            ->value ?? 75);
    }

    public function getStandardPantryWeight(): float
    {
        return (float) ($this->settings()
            ->where('key', self::STANDARD_PANTRY_WEIGHT)
            ->first()
            ->value ?? 250);
    }
}
