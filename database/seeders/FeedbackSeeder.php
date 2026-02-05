<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Feedback;
use Carbon\Carbon;

class FeedbackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = ['Rasa', 'Pelayanan', 'Suasana', 'Kebersihan', 'Harga', 'Fasilitas'];
        $counter = 1;

        foreach ($categories as $category) {
            // Kita buat 5 data per kategori sebagai sampel
            for ($i = 1; $i <= 5; $i++) {
                
                // Logika Rating Khusus
                if ($category === 'Rasa') {
                    $rating = 4;
                } elseif ($category === 'Pelayanan') {
                    $rating = 2;
                } else {
                    $rating = rand(1, 5);
                }

                // Tentukan status secara acak (Responded atau Pending)
                $status = rand(0, 1) ? 'Responded' : 'Pending';
                $isResponded = $status === 'Responded';

                Feedback::create([
                    'visitor_name' => 'Guest' . $counter,
                    'visitor_email' => 'guest' . $counter . '@example.com',
                    'rating' => $rating,
                    'category' => $category,
                    'comment' => "Komentar untuk $category dengan kualitas rating $rating.",
                    'status' => $status,
                    'admin_response' => $isResponded ? "Terima kasih atas masukannya mengenai $category." : null,
                    'responded_at' => $isResponded ? Carbon::now()->subHours(rand(1, 10)) : null,
                    'created_at' => Carbon::now()->subDays(rand(1, 5)),
                ]);

                $counter++;
            }
        }
    }
}
