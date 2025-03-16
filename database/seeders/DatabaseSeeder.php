<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Customer;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Create Admin User
        $admin = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('admin123'),
                'user_type' => 'admin',
            ]
        );

        // Create and assign the Admin role
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $admin->assignRole($adminRole);
        $this->command->info('Admin user created with role assigned!');

        // Create Seller User
        $seller = User::firstOrCreate(
            ['email' => 'seller@gmail.com'],
            [
                'name' => 'Seller',
                'password' => Hash::make('seller123'),
                'user_type' => 'seller',
            ]
        );

        // Create and assign the Seller role
        $sellerRole = Role::firstOrCreate(['name' => 'seller']);
        $seller->assignRole($sellerRole);
        $this->command->info('Seller user created with role assigned!');

        // Define Admin Permissions
        $adminPermissions = [
            //Dashboard
            'ផ្ទាំងគ្រប់គ្រងសម្រាប់អ្នកលក់',
            //seller

            //product
            'បញ្ជីប្រភេទផលិតផល',
            'បង្កើតប្រភេទផលិតផល',
            'កែប្រែប្រភេទផលិតផល',
            'លុបប្រភេទផលិតផល',
            //brand
            'បញ្ជីម៉ាកយីហោ',
            'បង្កើតម៉ាកយីហោ',
            'កែប្រែម៉ាកយីហោ',
            'លុបម៉ាកយីហោ',
            //supplier
            'បញ្ជីអ្នកផ្គត់ផ្គង់',
            'បង្កើតអ្នកផ្គត់ផ្គង់',
            'កែប្រែអ្នកផ្គត់ផ្គង់',
            'លុបអ្នកផ្គត់ផ្គង់',
            //customer
            'បញ្ជីអតិថិជន',
            'បង្កើតអតិថិជន',
            'កែប្រែអតិថិជន',
            'លុបអតិថិជន',
            //expense
            'បញ្ជីការចំណាយ',
            'បង្កើតការចំណាយ',
            'កែប្រែការចំណាយ',
            'លុបការចំណាយ',
            //purchase
            'បញ្ជីការបញ្ជាទិញ',
            'បង្កើតការបញ្ជាទិញ',
            'កែប្រែការបញ្ជាទិញ',
            'លុបការបញ្ជាទិញ',
            //sale
            'បញ្ជីការលក់',
            'បង្កើតការលក់',
            'កែរប្រែការលក់',
            'លុបការលក់',
            //sale_return
            'បញ្ជីការបង្វែចូលទំនិញ',
            'បង្កើតការបង្វែចូលទំនិញ',
            'កែរប្រែការបង្វែចូលទំនិញ',
            'លុបការបង្វែចូលទំនិញ',
            //report
            'របាយការណ៍ការលក់ផលិតផល',
            'របាយការណ៍ស្តុកទំនិញ',
            'របាយការណ៍ការបង្វែចូលទំនិញ',
            'របាយការណ៍ការទិញផលិតផល',
            'របាយការណ៍ប្រាក់ចំណេញ និងខាត',
        ];

        // Define Seller Permissions (Limited Access)
        $sellerPermissions = [
            //Dashboard
            'ផ្ទាំងគ្រប់គ្រងសម្រាប់អ្នកលក់',

            //sale
            'បញ្ជីការលក់',
            'បង្កើតការលក់',
            'កែរប្រែការលក់',
            'លុបការលក់',
            //sale_return
            'បញ្ជីការបង្វែចូលទំនិញ',
            'បង្កើតការបង្វែចូលទំនិញ',
            'កែរប្រែការបង្វែចូលទំនិញ',
            'លុបការបង្វែចូលទំនិញ',
            //customer
            'បញ្ជីអតិថិជន',
            'បង្កើតអតិថិជន',
            'កែប្រែអតិថិជន',
        ];

        // Create Admin Permissions and Assign to Admin Role
        foreach ($adminPermissions as $permission) {
            $perm = Permission::firstOrCreate(['name' => $permission]);
            $adminRole->givePermissionTo($perm);
        }
        $this->command->info('Admin permissions created and assigned!');

        // Create Seller Permissions and Assign to Seller Role
        foreach ($sellerPermissions as $permission) {
            $perm = Permission::firstOrCreate(['name' => $permission]);
            $sellerRole->givePermissionTo($perm);
        }
        $this->command->info('Seller permissions created and assigned!');

        // Create General Customer
        Customer::firstOrCreate([
            'name' => 'អតិថិជនទូទៅ',
            'phone' => 'N/A',
            'address' => 'N/A',
        ]);
    }
}
