<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Customer;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;


class AdminSeeder extends Seeder
{
    public function run()
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@gmail.com'], // Check by email
            [
                'name' => 'Admin',
                'password' => Hash::make('admin123'),
                'user_type' => 'admin',
            ]
        );

        // Assign the role if it's a new user or if the role is missing
        $role = Role::firstOrCreate(['name' => 'admin']);
        $admin->assignRole($role);

        $this->command->info('Admin user created or already exists, with role assigned!');


        $this->command->info('Admin user created with role assigned!');


        $permissions = [
        'ផ្ទាំងលក់ផលិតផល',

        // 'បញ្ជីអ្នកប្រើប្រាស់',
        // 'បង្កើតអ្នកប្រើប្រាស់',
        // 'កែប្រែអ្នកប្រើប្រាស់',
        // 'លុបអ្នកប្រើប្រាស់',
        // 'ព័ត៌មានអ្នកប្រើប្រាស់',



        // 'បញ្ជីការអនុញ្ញាត',
        // 'បង្កើតការអនុញ្ញាត',
        // 'កែប្រែការអនុញ្ញាត',
        // 'លុបការអនុញ្ញាត',


        // 'បញ្ជីតួនាទីអ្នកប្រើប្រាស់',
        // 'បង្កើតតួនាទីអ្នកប្រើប្រាស់',
        // 'កែប្រែតួនាទីអ្នកប្រើប្រាស់',
        // 'លុបតួនាទីអ្នកប្រើប្រាស់',


        'បញ្ជីប្រភេទផលិតផល',
        'បង្កើតប្រភេទផលិតផល',
        'កែប្រែប្រភេទផលិតផល',
        'លុបប្រភេទផលិតផល',

        'បញ្ជីម៉ាកយីហោ',
        'បង្កើតម៉ាកយីហោ',
        'កែប្រែម៉ាកយីហោ',
        'លុបម៉ាកយីហោ',

        'បញ្ជីអតិថិជន',
        'បង្កើតអតិថិជន',
        'កែប្រែអតិថិជន',
        'លុបអតិថិជន',

        'បញ្ជីប្រភេទការចំណាយ',
        'បង្កើតប្រភេទការចំណាយ',
        'កែប្រែប្រភេទការចំណាយ',
        'លុបប្រភេទការចំណាយ',

        'បញ្ជីការចំណាយ',
        'បង្កើតការចំណាយ',
        'កែប្រែការចំណាយ',
        'លុបការចំណាយ',


        'បញ្ជីផលិតផល',
        'បង្កើតផលិតផល',
        'កែប្រែផលិតផល',
        'លុបផលិតផល',
        'ទាញយកទិន្នន័យផលិតផល',

        'បញ្ជីការបញ្ជាទិញ',
        'បង្កើតការបញ្ជាទិញ',
        'កែប្រែការបញ្ជាទិញ',
        'លុបការបញ្ជាទិញ',
        'ទាញយកទិន្នន័យការបញ្ជាទិញ',

        'បញ្ជីការទូទាត់ការបញ្ជាទិញ',
        'កែប្រែការទូទាត់ការបញ្ជាទិញ',
        'លុបការទូទាត់ការបញ្ជាទិញ',

        'បញ្ជីអ្នកផ្គត់ផ្គង់',
        'បង្កើតអ្នកផ្គត់ផ្គង់',
        'កែប្រែអ្នកផ្គត់ផ្គង់',
        'លុបអ្នកផ្គត់ផ្គង់',



        'បញ្ជីការលក់',
        'បង្កើតការលក់',
        'កែរប្រែការលក់',
        'លុបការលក់',

        'បញ្ជីការទូទាត់ការលក់',
        'កែរប្រែការទូទាត់ការលក់',
        'លុបការទូទាត់ការលក់',

        'បញ្ជីបង្វែចូលទំនិញ',
        'បង្កើតបង្វែចូលទំនិញ',

        'របាយការណ៍ការលក់ទំនិញ',
        'របាយការណ៍ផលិតផល',
        'របាយការណ៍ការទិញ',
        'របាយការណ៍ប្រាក់ចំណេញ និងខាត',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);

        }
        $this->command->info('Admin permission created!');

        // User::create([
        //     'name' => 'Cashier',
        //     'email' => 'cashier@gmail.com',
        //     'password' => bcrypt('cashier123'),
        //     'user_type' => 'cashier',
        // ]);
        Customer::create([
            'name' => 'អតិថិជនទូទៅ',
            'phone' => '000000000',
            'address' => 'ប្រទេសកម្ពុជា',
        ]);
    }
}
