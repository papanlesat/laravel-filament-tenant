<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class TestModel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test-model';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $user = User::where('email', 'papanlesat@gmail.com')->first();
        dd($user->tenant->first());
    }
}
