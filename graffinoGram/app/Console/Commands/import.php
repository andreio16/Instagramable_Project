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
        $counter = 0;
        $existingUsers = DB::table('users')->select('id')->get();
        foreach ($existingUsers as $users) 
        {
            foreach($data as $obj)
                if($obj->user->id == $users->id)
                    $counter++;
         }
        if($counter == count($existingUsers) AND $counter != 0) return true;
        else return false; 
    }


    private function concatenatePostTags($tagVector)
    {
        $tagResult = '';
        foreach($tagVector as $tagElement)
            $tagResult = $tagResult . $tagElement . ' ';
        return $tagResult;
    }

    private function extractPosts($data)
    {
        $uniquePosts = array();
        foreach($data as $obj)
        {
            if((empty($uniquePosts) == true) OR (in_array($obj->id, $uniquePosts) == false))
             {

                $postHashtags = $this->concatenatePostTags($obj->tags);

                DB::insert('insert into posts (id, type, link, filter, created_time, tags, likes, images, user_id)
                values (?,?,?,?,?,?,?,?,?)',[
                    $obj->id,
                    isset($obj->type) ? $obj->type : NULL,
                    isset($obj->link) ? $obj->link : NULL,
                    isset($obj->filter) ? $obj->filter : NULL,
                    isset($obj->created_time) ? $obj->created_time : NULL,
                    isset($obj->tags)? $postHashtags : NULL,
                    isset($obj->likes->count)? $obj->likes->count : NULL,
                    isset($obj->images->standard_resolution->url)? $obj->images->standard_resolution->url : NULL,
                    $obj->user->id ,
                ]);

                array_push($uniquePosts, $obj->id);
            }           
        }
        $this->info('Saving posts into db...');
    }

    private function checkExistingPosts($data)
    {
        $counter = 0;
        $existingPosts = DB::table('posts')->select('id')->get();
        foreach ($existingPosts as $posts) 
        {
            foreach($data as $obj)
                if($obj->id == $posts->id)
                    $counter++;
         }
        if($counter == count($existingPosts) AND $counter != 0) return true;
        else return false;        
    }

    private function runImport($jsonObj)
    {
        if($this->checkExistingUsers($jsonObj->data) == true)
            $this->info('Users table is already up to date!');
        else
            $this->extractUsers($jsonObj->data);


        if($this->checkExistingPosts($jsonObj->data) == true)
            $this->info('Posts table is already up to date!');
        else
            $this->extractPosts($jsonObj->data);
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
