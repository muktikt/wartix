<?php
namespace App\Services;

use App\Models\Setting;
use App\Models\Event;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ThreadsService
{
    private string $accessToken;
    private string $userId;
    private string $baseUrl = 'https://graph.threads.net/v1.0';

    public function __construct()
    {
        $this->accessToken = Setting::get('threads_access_token', '');
        $this->userId      = Setting::get('threads_user_id', '');
    }

    public function postEventAnnouncement(Event $event, string $caption): bool
    {
        if (!$this->accessToken || !$this->userId) {
            Log::warning('Threads not configured');
            return false;
        }

        $autoPost = Setting::get('threads_auto_post', '0');
        if ($autoPost !== '1') return false;

        try {
            $mediaIds = [];

            // Upload banner
            if ($event->banner_image) {
                $id = $this->uploadMedia(asset("storage/{$event->banner_image}"), $caption);
                if ($id) $mediaIds[] = $id;
            }

            // Upload seatplan
            if ($event->seatplan_image) {
                $id = $this->uploadMedia(asset("storage/{$event->seatplan_image}"), '');
                if ($id) $mediaIds[] = $id;
            }

            if (empty($mediaIds)) {
                return $this->publishTextPost($caption);
            }

            if (count($mediaIds) === 1) {
                return $this->publishSinglePost($mediaIds[0]);
            }

            return $this->publishCarousel($mediaIds, $caption);

        } catch (\Exception $e) {
            Log::error('Threads post failed: ' . $e->getMessage());
            return false;
        }
    }

    private function uploadMedia(string $imageUrl, string $caption): ?string
    {
        $params = [
            'image_url'    => $imageUrl,
            'media_type'   => 'IMAGE',
            'access_token' => $this->accessToken,
        ];

        if ($caption) {
            $params['text'] = $caption;
        }

        $response = Http::post("{$this->baseUrl}/{$this->userId}/threads", $params);

        if ($response->successful()) {
            return $response->json('id');
        }

        Log::error('Threads upload failed: ' . $response->body());
        return null;
    }

    private function publishSinglePost(string $mediaId): bool
    {
        $response = Http::post("{$this->baseUrl}/{$this->userId}/threads_publish", [
            'creation_id'  => $mediaId,
            'access_token' => $this->accessToken,
        ]);

        return $response->successful();
    }

    private function publishCarousel(array $mediaIds, string $caption): bool
    {
        // Create carousel container
        $container = Http::post("{$this->baseUrl}/{$this->userId}/threads", [
            'media_type'   => 'CAROUSEL',
            'children'     => implode(',', $mediaIds),
            'text'         => $caption,
            'access_token' => $this->accessToken,
        ]);

        if (!$container->successful()) return false;

        $containerId = $container->json('id');

        // Publish carousel
        $response = Http::post("{$this->baseUrl}/{$this->userId}/threads_publish", [
            'creation_id'  => $containerId,
            'access_token' => $this->accessToken,
        ]);

        return $response->successful();
    }

    private function publishTextPost(string $caption): bool
    {
        $container = Http::post("{$this->baseUrl}/{$this->userId}/threads", [
            'media_type'   => 'TEXT',
            'text'         => $caption,
            'access_token' => $this->accessToken,
        ]);

        if (!$container->successful()) return false;

        return $this->publishSinglePost($container->json('id'));
    }
}