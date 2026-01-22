<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\{ShortUrl, Company, User};
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ShortUrl>
 */
class ShortUrlFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = ShortUrl::class;

    public function definition()
    {
        return [
            'original_url' => fake()->url(),
            'short_code' => substr(md5(uniqid()), 0, 6),
            'click_count' => 0,
            'user_id' => User::factory(),     
            'company_id' => Company::factory() 
        ];
    }
}
