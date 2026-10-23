<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Service;
use App\Models\EventCategory;
use App\Models\EventSubCategory;
use App\Models\EventSubCategoryService;
use App\Models\Customer;
use App\Models\BankTransaction;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Admin & Staff Users
        $admin = User::firstOrCreate(
            ['email' => 'admin@eventpro.com'],
            [
                'name' => 'System Administrator',
                'password' => Hash::make('admin123'),
                'role' => 'Admin',
                'phone' => '+94 77 123 4567',
                'is_active' => true,
            ]
        );

        User::firstOrCreate(
            ['email' => 'manager@eventpro.com'],
            [
                'name' => 'Event Manager',
                'password' => Hash::make('manager123'),
                'role' => 'Manager',
                'phone' => '+94 71 987 6543',
                'is_active' => true,
            ]
        );

        // 2. Services with Cost Prices
        $servicesData = [
            ['name' => 'Welcome Drink', 'cost_price' => 15000, 'description' => 'Fresh fruit juices and welcome mocktails (100 Pax)'],
            ['name' => 'Traditional Dance Crew', 'cost_price' => 35000, 'description' => '6-member traditional dancing troupe for grand entrance'],
            ['name' => 'DJ & Sound System', 'cost_price' => 25000, 'description' => 'Pro sound equipment, microphones and experienced DJ'],
            ['name' => 'Photography & Videography', 'cost_price' => 45000, 'description' => '2 photographers, 1 videographer with drone shots'],
            ['name' => 'Floral Stage Decoration', 'cost_price' => 50000, 'description' => 'Fresh flowers backdrop, entrance arch and table centerpieces'],
            ['name' => 'LED Wall & Dynamic Lighting', 'cost_price' => 40000, 'description' => '12x8 LED backdrop screen with moving heads lighting'],
            ['name' => 'Buffet Catering Service', 'cost_price' => 120000, 'description' => '3-course buffet with desserts and beverage counter (100 Pax)'],
        ];

        $services = [];
        foreach ($servicesData as $item) {
            $services[$item['name']] = Service::firstOrCreate(
                ['name' => $item['name']],
                [
                    'cost_price' => $item['cost_price'],
                    'description' => $item['description'],
                    'is_active' => true,
                ]
            );
        }

        // 3. Event Categories
        $weddingCat = EventCategory::firstOrCreate(
            ['name' => 'Wedding'],
            ['description' => 'Weddings, Receptions and Engagements', 'is_active' => true]
        );

        $birthdayCat = EventCategory::firstOrCreate(
            ['name' => 'Birthday Party'],
            ['description' => 'Milestone birthdays, kids parties and celebrations', 'is_active' => true]
        );

        $corporateCat = EventCategory::firstOrCreate(
            ['name' => 'Corporate Event'],
            ['description' => 'Annual general meetings, launches, gala dinners', 'is_active' => true]
        );

        // 4. Sub-Categories (Packages) with auto cost price calculation
        // Package 01 - Gold Wedding
        $pkg1 = EventSubCategory::firstOrCreate(
            ['name' => 'Package 01 - Gold Wedding', 'event_category_id' => $weddingCat->id],
            [
                'description' => 'Complete elegant wedding package with welcoming and traditional performances.',
                'sell_price' => 180000,
                'cost_price' => 0,
                'is_active' => true,
            ]
        );

        $pkg1Services = [
            ['svc' => $services['Welcome Drink'], 'qty' => 1],
            ['svc' => $services['Traditional Dance Crew'], 'qty' => 1],
            ['svc' => $services['DJ & Sound System'], 'qty' => 1],
            ['svc' => $services['Floral Stage Decoration'], 'qty' => 1],
        ];

        $totalCost1 = 0;
        foreach ($pkg1Services as $pSvc) {
            EventSubCategoryService::firstOrCreate(
                ['event_sub_category_id' => $pkg1->id, 'service_id' => $pSvc['svc']->id],
                ['qty' => $pSvc['qty'], 'cost_price' => $pSvc['svc']->cost_price]
            );
            $totalCost1 += $pSvc['svc']->cost_price * $pSvc['qty'];
        }
        $pkg1->update(['cost_price' => $totalCost1]);

        // Package 02 - Platinum Luxury Wedding
        $pkg2 = EventSubCategory::firstOrCreate(
            ['name' => 'Package 02 - Platinum Luxury Wedding', 'event_category_id' => $weddingCat->id],
            [
                'description' => 'Ultra luxury all-inclusive wedding package with photo/video and LED screen.',
                'sell_price' => 320000,
                'cost_price' => 0,
                'is_active' => true,
            ]
        );

        $pkg2Services = [
            ['svc' => $services['Welcome Drink'], 'qty' => 1],
            ['svc' => $services['Traditional Dance Crew'], 'qty' => 1],
            ['svc' => $services['DJ & Sound System'], 'qty' => 1],
            ['svc' => $services['Floral Stage Decoration'], 'qty' => 1],
            ['svc' => $services['Photography & Videography'], 'qty' => 1],
            ['svc' => $services['LED Wall & Dynamic Lighting'], 'qty' => 1],
        ];

        $totalCost2 = 0;
        foreach ($pkg2Services as $pSvc) {
            EventSubCategoryService::firstOrCreate(
                ['event_sub_category_id' => $pkg2->id, 'service_id' => $pSvc['svc']->id],
                ['qty' => $pSvc['qty'], 'cost_price' => $pSvc['svc']->cost_price]
            );
            $totalCost2 += $pSvc['svc']->cost_price * $pSvc['qty'];
        }
        $pkg2->update(['cost_price' => $totalCost2]);

        // Birthday Package 01
        $bPkg1 = EventSubCategory::firstOrCreate(
            ['name' => 'Package 01 - Standard Birthday', 'event_category_id' => $birthdayCat->id],
            [
                'description' => 'Fun birthday celebration package with sound, decor and drinks.',
                'sell_price' => 135000,
                'cost_price' => 0,
                'is_active' => true,
            ]
        );

        $bPkg1Services = [
            ['svc' => $services['Welcome Drink'], 'qty' => 1],
            ['svc' => $services['DJ & Sound System'], 'qty' => 1],
            ['svc' => $services['Floral Stage Decoration'], 'qty' => 1],
        ];

        $totalCostB1 = 0;
        foreach ($bPkg1Services as $pSvc) {
            EventSubCategoryService::firstOrCreate(
                ['event_sub_category_id' => $bPkg1->id, 'service_id' => $pSvc['svc']->id],
                ['qty' => $pSvc['qty'], 'cost_price' => $pSvc['svc']->cost_price]
            );
            $totalCostB1 += $pSvc['svc']->cost_price * $pSvc['qty'];
        }
        $bPkg1->update(['cost_price' => $totalCostB1]);

        // 5. Sample Customers
        Customer::firstOrCreate(
            ['phone' => '+94 77 555 1234'],
            [
                'name' => 'Kasun Perera',
                'email' => 'kasun.p@example.com',
                'address' => 'No 45, Galle Road, Colombo 03',
                'nic' => '199214502841',
                'is_active' => true,
            ]
        );

        Customer::firstOrCreate(
            ['phone' => '+94 71 888 4321'],
            [
                'name' => 'Nadeeka Senanayake',
                'email' => 'nadeeka.s@example.com',
                'address' => '12/B Kandy Road, Kiribathgoda',
                'nic' => '198865401923',
                'is_active' => true,
            ]
        );

        // 6. Initial Cash/Bank Opening Balances
        if (BankTransaction::count() === 0) {
            BankTransaction::create([
                'type' => 'income',
                'transaction_method' => 'cash',
                'account_type' => 'cash',
                'amount' => 50000,
                'description' => 'Opening Cash Book Balance',
                'transaction_date' => now()->startOfMonth()->format('Y-m-d'),
                'reference_no' => 'OP-CASH-001',
            ]);

            BankTransaction::create([
                'type' => 'income',
                'transaction_method' => 'bank_transfer',
                'account_type' => 'Commercial Bank',
                'bank_name' => 'Commercial Bank of Ceylon',
                'amount' => 250000,
                'description' => 'Opening Bank Balance',
                'transaction_date' => now()->startOfMonth()->format('Y-m-d'),
                'reference_no' => 'OP-BANK-001',
            ]);
        }
    }
}
