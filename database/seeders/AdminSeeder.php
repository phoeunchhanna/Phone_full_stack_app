<?php
namespace Database\Seeders;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AdminSeeder extends Seeder
{
    public function run()
    {
        // Create Admin User
        $admin = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name'      => 'Admin',
                'username'      => 'myadmin',
                'password'  => Hash::make('admin123'),
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
                'name'      => 'Seller',
                'username'  => 'myseller',
                'password'  => Hash::make('seller123'),
                'user_type' => 'seller',
                
            ]
        );

        // Create and assign the Seller role
        $sellerRole = Role::firstOrCreate(['name' => 'seller']);
        $seller->assignRole($sellerRole);
        $this->command->info('Seller user created with role assigned!');

        // Define Admin Permissions
        $adminPermissions = [
            // Products
            'បញ្ជីផលិតផល',
            'បង្កើតផលិតផល',
            'កែប្រែផលិតផល',
            'លុបផលិតផល',
            'ទាញយកផលិតផល',
            // Categories
            'បញ្ជីប្រភេទផលិតផល',
            'បង្កើតប្រភេទផលិតផល',
            'កែប្រែប្រភេទផលិតផល',
            'លុបប្រភេទផលិតផល',
            // Brands
            'បញ្ជីម៉ាកយីហោ',
            'បង្កើតម៉ាកយីហោ',
            'កែប្រែម៉ាកយីហោ',
            'លុបម៉ាកយីហោ',
            // Suppliers
            'បញ្ជីអ្នកផ្គត់ផ្គង់',
            'បង្កើតអ្នកផ្គត់ផ្គង់',
            'កែប្រែអ្នកផ្គត់ផ្គង់',
            'លុបអ្នកផ្គត់ផ្គង់',
            // Customers
            'បញ្ជីអតិថិជន',
            'បង្កើតអតិថិជន',
            'កែប្រែអតិថិជន',
            'លុបអតិថិជន',
            // Expenses
            'បញ្ជីការចំណាយ',
            'បង្កើតការចំណាយ',
            'កែប្រែការចំណាយ',
            'លុបការចំណាយ',
            // Expense Categories
            'បញ្ជីប្រភេទចំណាយ',
            'បង្កើតប្រភេទចំណាយ',
            'កែប្រែប្រភេទចំណាយ',
            'លុបប្រភេទចំណាយ',
            // Purchases
            'បញ្ជីការបញ្ជាទិញ',
            'បង្កើតការបញ្ជាទិញ',
            'កែប្រែការបញ្ជាទិញ',
            'លុបការបញ្ជាទិញ',
            // Sales
            'បញ្ជីការលក់',
            'បង្កើតការលក់',
            'កែប្រែការលក់',
            'លុបការលក់',
            // Sale Returns
            'បញ្ជីការបង្វិលទំនិញចូល',
            'បង្កើតការបង្វិលទំនិញចូល',
            'កែប្រែការបង្វិលទំនិញចូល',
            'លុបការបង្វិលទំនិញចូល',
            // Reports
            'របាយការណ៍ការលក់ផលិតផល',
            'របាយការណ៍ស្តុកទំនិញ',
            'របាយការណ៍ការបង្វិលទំនិញចូល',
            'របាយការណ៍ការទិញផលិតផល',
            'របាយការណ៍ប្រាក់ចំណេញ និងខាត',
        ];

        // Define Seller Permissions (Limited Access)
        $sellerPermissions = [
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
            //Sale Return Payment
            'បញ្ជីការទូទាត់បង្វែចូលទំនិញ',
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
            'name'    => 'អតិថិជនទូទៅ',
            'phone'   => 'N/A',
            'address' => 'N/A',
        ]);
    }
}
