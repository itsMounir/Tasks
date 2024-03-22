<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;

class DeleteRejectedProducts extends Command
{


    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:rejected-products';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    //protected $timeout = 600;


    /**
     * Execute the console command.
     */
    public function handle()
    {
        Product::where('status','rejected')->delete();
    }
}
