<?php

namespace Database\Factories;

use App\Models\Book;
use App\Models\Status;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Note>
 */
class NoteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->first()->id,
            'book_id' => Book::inRandomOrder()->first()->id,
            'status_id' => Status::inRandomOrder()->first()->id,
            'body' => fake()->paragraphs(rand(1, 4), true),
            'page' => fake()->numberBetween(1, 600),
            'created_at' => fake()->dateTimeBetween('-2 years', 'now'),
        ];
    }
}
