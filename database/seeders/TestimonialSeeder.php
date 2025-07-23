<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Testimonial;
use App\Models\Team;
use App\Models\Partner;

class TestimonialSeeder extends Seeder
{
    public function run(): void
    {
        $testimonials = [
            [
                'name' => 'Ruwan Perera',
                'designation' => 'CEO, Tech Innovations Lanka',
                'message' => 'Ritz Accounting has been instrumental in our company\'s financial success. Their professional approach and attention to detail have made tax compliance seamless for us. Highly recommended!',
                'status' => 1,
            ],
            [
                'name' => 'Samantha De Silva',
                'designation' => 'Managing Director, Export House Ltd',
                'message' => 'We\'ve been working with Ritz Accounting for over 3 years. Their expertise in VAT and international trade taxation has saved us significant time and money. Excellent service!',
                'status' => 1,
            ],
            [
                'name' => 'Ahmed Hassan',
                'designation' => 'Founder, Digital Marketing Solutions',
                'message' => 'As a startup, we needed reliable accounting support. Ritz Accounting provided not just bookkeeping but valuable business advice that helped us grow. They\'re more than accountants - they\'re business partners.',
                'status' => 1,
            ],
            [
                'name' => 'Nadeeka Fernando',
                'designation' => 'Director, Fashion Boutique Chain',
                'message' => 'The team at Ritz Accounting transformed our financial processes. Their cloud-based solutions gave us real-time insights into our business performance. Outstanding support and expertise!',
                'status' => 1,
            ],
            [
                'name' => 'Marcus Wong',
                'designation' => 'CFO, Manufacturing Corp',
                'message' => 'Professional, responsive, and knowledgeable. Ritz Accounting handles our complex payroll and tax requirements effortlessly. They\'ve become an integral part of our finance team.',
                'status' => 1,
            ],
        ];

        foreach ($testimonials as $testimonial) {
            Testimonial::create($testimonial);
        }
    }
}

