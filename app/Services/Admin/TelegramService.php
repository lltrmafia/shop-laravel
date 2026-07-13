<?php

namespace App\Services\Admin;

use App\Models\Category;
use Illuminate\Support\Facades\Http;

class TelegramService
{
    public function sendMessage(string $message): void
    {
        $token = config('services.telegram.bot_token');
        $chatId = config('services.telegram.chat_id');

        Http::post("https://api.telegram.org/bot{$token}/sendMessage", [
            'chat_id' => $chatId,
            'text' => $message,
        ]);
    }

    public function sendCategory(Category $category): void
    {
        $message = "Создана категория:\n";
        $message .= "ID: {$category->id}\n";
        $message .= "Название: {$category->title}\n";
        $message .= "Parent id: {$category->parent_id}";

        $this->sendMessage($message);
    }
}
