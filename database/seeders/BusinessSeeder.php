<?php

namespace Database\Seeders;

use App\Models\Business\Business;
use App\Models\Business\BusinessMedia;
use App\Models\Business\BusinessService;
use App\Models\User;
use Illuminate\Database\Seeder;

class BusinessSeeder extends Seeder
{
    public function run(): void
    {
        $owner = User::query()->where('email', 'contact@al-infotech.cm')->first()
            ?? User::factory()->create();

        $business = Business::query()->firstOrCreate(
            ['slug' => 'al-infotech-sarl'],
            [
                'user_id' => $owner->id,
                'name' => 'AL-INFOTECH SARL',
                'description' => 'Entreprise spécialisée dans les solutions informatiques, la maintenance et la formation en TIC dans le Moungo.',
                'whatsapp' => '650000000',
                'website_url' => 'https://al-infotech.example.cm',
                'address' => 'Nkongsamba, Cameroun',
                'is_published' => true,
            ],
        );

        BusinessService::factory(3)->create(['business_id' => $business->id]);
        BusinessMedia::factory(4)->create(['business_id' => $business->id]);

        Business::factory(5)->create()->each(function (Business $b) {
            BusinessService::factory(2)->create(['business_id' => $b->id]);
            BusinessMedia::factory(3)->create(['business_id' => $b->id]);
        });
    }
}
