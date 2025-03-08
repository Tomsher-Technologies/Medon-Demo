<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Search;
use Carbon\Carbon;

class DeleteOldSearchHistories extends Command
{
    protected $signature = 'search-history:cleanup';

    protected $description = 'Delete search histories older than 60 days';

    public function handle()
    {
        $days = 60; // Number of days to keep
        $deleted = Search::where('created_at', '<', Carbon::now()->subDays($days))->delete();

        $this->info("$deleted old search history records deleted successfully.");
    }
}
