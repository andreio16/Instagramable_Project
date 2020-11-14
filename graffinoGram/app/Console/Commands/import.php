<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use File;
use DB;

class import extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:posts {path : JSON file path}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import posts from ".json" file to the project.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    
    private function extractUsers($data)
    {
        $uniqueUsers = array();
        foreach($data as $obj)
        {
            if((empty($uniqueUsers) == true) OR (in_array($obj->user->id, $uniqueUsers) == false))
            {
                DB::insert('insert into users (id, bio, website, username, full_name, profile_picture)
                values (?,?,?,?,?,?)',[
                    $obj->user->id,
                    isset($obj->user->bio) ? $obj->user->bio : NULL,
                    isset($obj->user->website) ? $obj->user->website : NULL,
                    $obj->user->username,
                    isset($obj->user->full_name) ? $obj->user->full_name : NULL,
                    isset($obj->user->profile_picture)? $obj->user->profile_picture : NULL,
                ]);
                array_push($uniqueUsers, $obj->user->id);
            }           
        }
        $this->info('Saving users into db...');
    }

    private function checkExistingUsers($data)
    {
        $existingUsers = DB::table('users')->select('id')->get();
        foreach ($existingUsers as $user) 
            foreach($data as $obj)
                if($obj->user->id != $user->id)
                {
                    return false;
                    break;
                }
        return true;
    }

    private function extractPosts($data)
    {
        //smth
    }

    private function runImport($jsonObj)
    {
        if($this->checkExistingUsers($jsonObj->data) == false)
            $this->info('Users table is already up to date!');
        else
            $this->extractUsers($jsonObj->data);
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        //  php artisan import:posts C:\Users\Andrei\Desktop\g-project\instagram.json
        $srcFile = File::get($this->argument('path'));
        $data = json_decode($srcFile);
        
        if($data->meta->code == 200)
        {
            $this->info("\n".'[STATUS]: OK-'.$data->meta->code ."\n");
            $this->runImport($data);
            $this->info("\n".'JSON File was successfully imported into database!'."\n");
        } 
        else
        {
            $this->info("\n".'JSON File cannot be fetched! [WARNING]: Ambiguous file structure'."\n");
        }
    }



}
