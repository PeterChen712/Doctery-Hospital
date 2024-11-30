<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Medicine;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class MedicineSeeder extends Seeder
{
    public function run()
    {
        $medicines = [
            [
                'name' => "Paracetamol",
                'description' => "Pain reliever and fever reducer",
                'type' => "Tablet",
                'stock' => 100,
                'price' => 5000.00,
                'expiry_date' => Carbon::now()->addYears(2),
                'manufacturer' => "Generic Pharma",
                'category' => "Pain Relief",
                'is_available' => true
            ],
            [
                'name' => "Amoxicillin",
                'description' => "Antibiotic for bacterial infections",
                'type' => "Capsule",
                'stock' => 50,
                'price' => 15000.00,
                'expiry_date' => Carbon::now()->addYears(1),
                'manufacturer' => "MedPharma",
                'category' => "Antibiotics",
                'is_available' => true
            ],
            [
                'name' => "Omeprazole",
                'description' => "For acid reflux and ulcers",
                'type' => "Capsule",
                'stock' => 75,
                'price' => 12000.00,
                'expiry_date' => Carbon::now()->addMonths(18),
                'manufacturer' => "HealthCare Inc",
                'category' => "Gastrointestinal",
                'is_available' => true
            ],
            [
                'name' => "Ibuprofen",
                'description' => "Anti-inflammatory pain reliever",
                'type' => "Tablet",
                'stock' => 80,
                'price' => 8000.00,
                'expiry_date' => Carbon::now()->addYears(2),
                'manufacturer' => "Generic Pharma",
                'category' => "Pain Relief",
                'is_available' => true
            ],
            [
                'name' => "Cetirizine",
                'description' => "Antihistamine for allergies",
                'type' => "Tablet",
                'stock' => 60,
                'price' => 6000.00,
                'expiry_date' => Carbon::now()->addYears(1),
                'manufacturer' => "AllergyMed",
                'category' => "Allergy", 
                'is_available' => true
            ]
        ];

        foreach ($medicines as $medicine) {
            try {
                Medicine::create($medicine);
            } catch (\Exception $e) {
                // Log any errors that occur during seeding
                Log::error("Error seeding medicine: " . $e->getMessage());
                throw $e;
            }
        }
    }
}