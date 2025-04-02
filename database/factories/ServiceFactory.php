<?php

namespace Database\Factories;

use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Service>
 */
class ServiceFactory extends Factory
{
    protected $model = Service::class;

    public function definition()
    {
        $title = $this->faker->sentence(3);
        return [
            'image' => 'https://picsum.photos/640/480?random=' . rand(1, 1000),
            'icon' => $this->getRandomSvgIcon(), // Generate random SVG
            'short_title' => $this->faker->words(2, true),
            'title' => $title,
            'slug' => Str::slug($title),
            'status' => $this->faker->boolean(90), // 90% chance of being active
            'sub_title' => $this->faker->sentence(6),
            'keywords' => implode(', ', $this->faker->words(5)),
            'meta_description' => $this->faker->sentence(10),
            'description' => $this->faker->paragraph(5),
            'order' => $this->faker->numberBetween(1, 100),
        ];
    }
    private function getRandomSvgIcon()
    {
        $icons = [
            '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="12" cy="12" r="10" stroke="black" stroke-width="2"/></svg>',
            '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><rect x="4" y="4" width="16" height="16" stroke="black" stroke-width="2"/></svg>',
            '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M4 12h16M12 4v16" stroke="black" stroke-width="2"/></svg>',
        ];

        return $icons[array_rand($icons)];
    }
}
