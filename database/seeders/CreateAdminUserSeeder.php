<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CreateAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // // إنشاء دور الأدمن إذا لم يكن موجوداً
        // $role = Role::firstOrCreate(['name' => 'owner']);

        // // إنشاء المستخدم الأدمن
        // $user = User::firstOrCreate(
        //     ['email' => 'admin@gmail.com'], // البحث أو الإنشاء
        //     [
        //         'name' => 'Admin',
        //         'password' => bcrypt('admin123'),
        //         'Status' => 'مفعل',
        //     ]
        // );

        // // إعطاء الدور كل الصلاحيات
        // $permissions = Permission::all();
        // $role->syncPermissions($permissions);

        // // ربط المستخدم بالدور
        // $user->assignRole('owner');


        $user = User::create([
        'name' => 'Admin',
        'email' => 'admin@gmail.com',
        'password' => bcrypt('admin123'),
        'roles_name' => ["owner"],
        'Status' => 'مفعل',
]);

$role = Role::create(['name' => 'owner']);

$permissions = Permission::pluck('id','id')->all();
$role->syncPermissions($permissions);

$user->assignRole([$role->id]); 
    }
}
