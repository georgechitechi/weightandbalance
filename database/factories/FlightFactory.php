<?php

namespace Database\Factories;

use App\Models\Aircraft;
use App\Models\Airline;
use App\Models\Flight;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Flight>
 */
class FlightFactory extends Factory
{
    protected $model = Flight::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $aircraft = Aircraft::where('active', true)->inRandomOrder()->first();
        $airline = $aircraft->airline;

        $scheduled_departure_time = fake()->dateTimeBetween('now', '+1 week');
        $scheduled_arrival_time = clone $scheduled_departure_time;
        $scheduled_arrival_time->modify('+' . fake()->numberBetween(1, 12) . ' hours');

        $airports = [
            'JFK' => 'New York',
            'LAX' => 'Los Angeles',
            'ORD' => 'Chicago',
            'DFW' => 'Dallas',
            'DEN' => 'Denver',
            'SFO' => 'San Francisco',
            'LHR' => 'London',
            'CDG' => 'Paris',
            'FRA' => 'Frankfurt',
            'DXB' => 'Dubai'
        ];

        $departure = fake()->randomElement(array_keys($airports));
        do {
            $arrival = fake()->randomElement(array_keys($airports));
        } while ($arrival === $departure);

        return [
            'airline_id' => $airline->id,
            'aircraft_id' => $aircraft->id,
            'flight_number' => strtoupper($airline->iata_code . fake()->numberBetween(100, 9999)),
            'departure_airport' => $departure,
            'arrival_airport' => $arrival,
            'scheduled_departure_time' => $scheduled_departure_time,
            'scheduled_arrival_time' => $scheduled_arrival_time,
            'status' => fake()->randomElement(['scheduled', 'boarding', 'departed', 'arrived', 'cancelled']),
        ];
    }

    public function forAirline(Airline $airline)
    {
        return $this->state(function (array $attributes) use ($airline) {
            return [
                'airline_id' => $airline->id,
                'aircraft_id' => Aircraft::factory()->forAirline($airline),
            ];
        });
    }

    public function forAircraft(Aircraft $aircraft)
    {
        return $this->state(function (array $attributes) use ($aircraft) {
            return [
                'airline_id' => $aircraft->airline_id,
                'aircraft_id' => $aircraft->id,
            ];
        });
    }

    public function scheduled()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'scheduled',
            ];
        });
    }

    public function boarding()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'boarding',
            ];
        });
    }

    public function departed()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'departed',
            ];
        });
    }

    public function arrived()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'arrived',
            ];
        });
    }

    public function cancelled()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'cancelled',
            ];
        });
    }
}
