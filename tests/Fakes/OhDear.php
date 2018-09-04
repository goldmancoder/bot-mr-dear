<?php

namespace Tests\Fakes;

use App\OhDear\Downtime;
use App\OhDear\Site;
use Illuminate\Support\Collection;

class OhDear extends \App\OhDear\Services\OhDear
{

    /** @var Collection */
    private $sites;

    public function __construct()
    {
        $this->sites = $this->collect($this->getFakeSites(), Site::class);
    }

    private function getFakeSites()
    {
        return json_decode(file_get_contents(base_path('tests/Fakes/responses/sites_list.json')), true)['data'];
    }

    public function sites(): Collection
    {
        return $this->sites;
    }

    public function createSite(string $url): Site
    {
        $siteData = $this->getFakeSites()[0];

        $siteData['url'] = $this->validatedUrl($url);

        $siteData['sort_url'] = str_replace('http://', '', $siteData['url']);
        $siteData['sort_url'] = str_replace('https://', '', $siteData['sort_url']);

        return new Site($siteData, $this);
    }

    public function findSiteByUrl(string $url): ?Site
    {
        return $this->sites->first(function (Site $site) use ($url) {
            return stripos($site->url, $url) !== false;
        }, null);
    }

    public function deleteSite($siteId)
    {
        $this->sites = $this->sites->reject(function (Site $site) use ($siteId) {
            return $site->id === $siteId;
        });

        return ! $this->sites->firstWhere('id', $siteId);
    }

    public function getSiteDowntime($siteId)
    {
        $downtimes = [
            '1111' => [
                [
                    'started_at' => now()->subDay()->subMonths(3)->format('Y-m-d H:i:s'),
                    'ended_at' => now()->subDay()->subMonths(3)->addMinute()->addSeconds(34)->format('Y-m-d H:i:s'),
                ],
                [
                    'started_at' => now()->subDay()->subMonths(5)->format('Y-m-d H:i:s'),
                    'ended_at' => now()->subDay()->subMonths(5)->addHour()->format('Y-m-d H:i:s'),
                ],

            ],
            '2222' => [
                [
                    'started_at' => now()->subDays(22)->format('Y-m-d H:i:s'),
                    'ended_at' => now()->subDays(22)->addMinutes(8)->addSeconds(10)->format('Y-m-d H:i:s'),
                ],
                [
                    'started_at' => now()->subDays(28)->format('Y-m-d H:i:s'),
                    'ended_at' => now()->subDays(28)->addHour()->format('Y-m-d H:i:s'),
                ],
            ],
            '3333' => [
                [
                    'started_at' => now()->subDays(4)->format('Y-m-d H:i:s'),
                    'ended_at' => now()->subDays(2)->addHours(5)->addMinutes(3)->format('Y-m-d H:i:s'),
                ],
                [
                    'started_at' => now()->subDays(7)->format('Y-m-d H:i:s'),
                    'ended_at' => now()->subDays(7)->addHour()->format('Y-m-d H:i:s'),
                ],
            ],
            '4444' => [
                [
                    'started_at' => now()->subHours(8)->format('Y-m-d H:i:s'),
                    'ended_at' => now()->subHours(8)->addMinutes(6)->format('Y-m-d H:i:s'),
                ],
                [
                    'started_at' => now()->subHours(15)->format('Y-m-d H:i:s'),
                    'ended_at' => now()->subHours(14)->format('Y-m-d H:i:s'),
                ],

            ],
            '5555' => [
                [
                    'started_at' => now()->subMinutes(34)->format('Y-m-d H:i:s'),
                    'ended_at' => now()->subMinutes(34)->addSeconds(55)->format('Y-m-d H:i:s'),
                ],
                [
                    'started_at' => now()->subMinutes(55)->subMonth()->format('Y-m-d H:i:s'),
                    'ended_at' => now()->subMinutes(54)->format('Y-m-d H:i:s'),
                ],
            ],
        ];

        return $this->collect(json_decode(json_encode($downtimes[$siteId]), true), Downtime::class);
    }
}