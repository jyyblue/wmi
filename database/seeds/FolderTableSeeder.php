<?php

use Illuminate\Database\Seeder;

class FolderTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /* Folders  */
        $root = DB::table('folder')->where('name', 'root')->first();
        if($root === null){
            DB::table('folder')->insert([  /* without this folder, nothing works */
                'name' => 'root',
                'folder_id' => NULL,
            ]);
            $rootId = DB::getPdo()->lastInsertId();
        }else{
            $rootId = $root->id;
        }

        $resource = DB::table('folder')->where('name', 'resource')->first();
        if($resource === null){
            DB::table('folder')->insert([   /* without this folder, nothing works - only this folder have column `resource` = 1 */
                'name' => 'resource',
                'folder_id' => $rootId,
                'resource' => 1
            ]);
        }

        $documents = DB::table('folder')->where('name', 'documents')->first();
        if($documents === null){
            DB::table('folder')->insert([
                'name' => 'documents',
                'folder_id' => $rootId,
            ]);
        }

        $graphics = DB::table('folder')->where('name', 'graphics')->first();
        if($graphics === null){
            DB::table('folder')->insert([
                'name' => 'graphics',
                'folder_id' => $rootId,
            ]);
        }

        $other = DB::table('folder')->where('name', 'other')->first();
        if($other === null){
            DB::table('folder')->insert([
                'name' => 'other',
                'folder_id' => $rootId,
            ]);
        }

        $id = DB::getPdo()->lastInsertId();
    }
}
