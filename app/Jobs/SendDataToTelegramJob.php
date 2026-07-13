<?php

namespace App\Jobs;

use App\Models\Category;
use App\Services\Admin\TelegramService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendDataToTelegramJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public Category $category)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        app(TelegramService::class)->sendCategory($this->category);
    }
}
