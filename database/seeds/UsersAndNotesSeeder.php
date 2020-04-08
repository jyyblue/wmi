<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use App\User;
use App\Models\RoleHierarchy;

class UsersAndNotesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $numberOfUsers = 10;
        $numberOfNotes = 100;
        $usersIds = array();
        $statusIds = array();
        $faker = Faker::create();
        /* Create roles */
        $devRole = Role::where(['name'=>'dev'])->first();
        if($devRole === null){
            $devRole = Role::create(['name' => 'dev']); 
            RoleHierarchy::create([
                'role_id' => $devRole->id,
                'hierarchy' => 1
            ]);
        }

        $adminRole = Role::where(['name'=>'admin'])->first();
        if($adminRole === null){
            $adminRole = Role::create(['name' => 'admin']); 
            RoleHierarchy::create([
                'role_id' => $adminRole->id,
                'hierarchy' => 2,
            ]);
        }
        $userRole = Role::where(['name'=>'user'])->first();
        if($userRole === null){
            $userRole = Role::create(['name' => 'user']);
            RoleHierarchy::create([
                'role_id' => $userRole->id,
                'hierarchy' => 3,
            ]);
        }

        $guestRole = Role::where(['name'=>'guest'])->first();
        if($guestRole === null){
            $guestRole = Role::create(['name' => 'guest']); 
            RoleHierarchy::create([
                'role_id' => $guestRole->id,
                'hierarchy' => 4,
            ]);
        }


        
        /*  insert status  */
        $s_ongoing = DB::table('status')->where('name','ongoing')->first();
        if($s_ongoing === null){
            DB::table('status')->insert([
                'name' => 'ongoing',
                'class' => 'badge badge-pill badge-primary',
            ]);
            array_push($statusIds, DB::getPdo()->lastInsertId());
        }else{
            array_push($statusIds, $s_ongoing->id);
        }

        $s_stopped = DB::table('status')->where('name','stopped')->first();
        if($s_stopped === null){
            DB::table('status')->insert([
                'name' => 'stopped',
                'class' => 'badge badge-pill badge-secondary',
            ]);
            array_push($statusIds, DB::getPdo()->lastInsertId());
        }else{
            array_push($statusIds, $s_stopped->id);
        }

        $s_completed = DB::table('status')->where('name','completed')->first();
        if($s_completed === null){
            DB::table('status')->insert([
                'name' => 'completed',
                'class' => 'badge badge-pill badge-success',
            ]);
            array_push($statusIds, DB::getPdo()->lastInsertId());
        }else{
            array_push($statusIds, $s_completed->id);
        }

        $s_expired = DB::table('status')->where('name','expired')->first();
        if($s_expired === null){
            DB::table('status')->insert([
                'name' => 'expired',
                'class' => 'badge badge-pill badge-warning',
            ]);
            array_push($statusIds, DB::getPdo()->lastInsertId());
        }else{
            array_push($statusIds, $s_expired->id);
        }


        /*  insert users   */
        $adminuser = User::where('name','admin')->first();
        if($adminuser === null){
            $user = User::create([ 
                'name' => 'admin',
                'email' => 'admin@admin.com',
                'email_verified_at' => now(),
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                'remember_token' => Str::random(10),
                'menuroles' => 'user,admin' 
            ]);
            $user->assignRole('admin');
            $user->assignRole('user');
        }

        $devuser = User::where('name','dev')->first();
        if($devuser === null){
            $user2 = User::create([ 
                'name' => 'dev',
                'email' => 'dev@dev.com',
                'email_verified_at' => now(),
                'password' => Hash::make('123123'), // password
                'remember_token' => Str::random(10),
                'menuroles' => 'user,admin,dev' 
            ]);
            $user2->assignRole('admin');
            $user2->assignRole('user');
            $user2->assignRole('dev');
            $user2->assignRole('guest');
        }

        for($i = 0; $i<$numberOfUsers; $i++){
            $user = User::create([ 
                'name' => $faker->name(),
                'email' => $faker->unique()->safeEmail(),
                'email_verified_at' => now(),
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                'remember_token' => Str::random(10),
                'menuroles' => 'user'
            ]);
            $user->assignRole('user');
            array_push($usersIds, $user->id);
        }
        /*  insert notes  */
        for($i = 0; $i<$numberOfNotes; $i++){
            $noteType = $faker->word();
            if(random_int(0,1)){
                $noteType .= ' ' . $faker->word();
            }
            DB::table('notes')->insert([
                'title'         => $faker->sentence(4,true),
                'content'       => $faker->paragraph(3,true),
                'status_id'     => $statusIds[random_int(0,count($statusIds) - 1)],
                'note_type'     => $noteType,
                'applies_to_date' => $faker->date(),
                'users_id'      => $usersIds[random_int(0,$numberOfUsers-1)]
            ]);
        }
    }
}