<?php

declare(strict_types=1);

namespace Flexgrid\Modules\AdminDashboard\Service;

use Flexgrid\Modules\AdminDashboard\Contract\DashboardProviderInterface;
use Flexgrid\Utils\_Time;

final class DashboardService
{
    private const COLLECTIONS = [
        'summary_cards',
        'activities',
        'quick_actions',
        'attention',
        'tables',
        'modules',
        'agenda',
        'panels',
    ];

    /** @var DashboardProviderInterface[] */
    private $providers;

    /** @var _Time */
    private $time;

    /** @param DashboardProviderInterface[] $providers */
    public function __construct(array $providers, _Time $time)
    {
        foreach ($providers as $provider) {
            if (!$provider instanceof DashboardProviderInterface) {
                throw new \InvalidArgumentException('DashboardService accepteert alleen dashboardproviders.');
            }
        }

        $this->providers = array_values($providers);
        $this->time = $time;
    }

    public function getViewModel(): array
    {
        $collections = [];
        foreach (self::COLLECTIONS as $collection) {
            $collections[$collection] = [];
        }

        foreach ($this->providers as $provider) {
            $contribution = $provider->getDashboardContribution();

            foreach (self::COLLECTIONS as $collection) {
                if (!isset($contribution[$collection])) {
                    continue;
                }

                if (!is_array($contribution[$collection])) {
                    throw new \UnexpectedValueException('Dashboardbijdrage ' . $collection . ' moet een array zijn.');
                }

                $collections[$collection] = $this->mergeById(
                    $collections[$collection],
                    $contribution[$collection],
                    $collection
                );
            }
        }

        $collections['activities'] = $this->sortAndLimit($collections['activities'], 'timestamp', 6, true);
        $collections['attention'] = $this->sortAndLimit($collections['attention'], 'priority', 6, false);
        $collections['quick_actions'] = $this->sortAndLimit($collections['quick_actions'], 'priority', 8, false);
        $collections['modules'] = $this->sortAndLimit($collections['modules'], 'priority', 12, false);
        $collections['agenda'] = $this->sortAndLimit($collections['agenda'], 'timestamp', 6, false);
        $collections['tables'] = $this->sortAndLimit($collections['tables'], 'priority', 4, false);
        $collections['panels'] = $this->sortAndLimit($collections['panels'], 'priority', 8, false);

        $collections['updated_at'] = $this->time->format('d-m-Y H:i');

        return $collections;
    }

    private function sortAndLimit(array $items, string $key, int $limit, bool $descending): array
    {
        usort($items, function (array $left, array $right) use ($key, $descending): int {
            $leftValue = (int)($left[$key] ?? 0);
            $rightValue = (int)($right[$key] ?? 0);
            if ($leftValue === $rightValue) { return strcmp((string)($left['id'] ?? ''), (string)($right['id'] ?? '')); }
            return $descending ? $rightValue <=> $leftValue : $leftValue <=> $rightValue;
        });
        return array_slice($items, 0, $limit);
    }

    private function mergeById(array $current, array $additional, string $collection): array
    {
        $indexed = [];

        foreach ($current as $item) {
            if (!is_array($item) || empty($item['id']) || !is_string($item['id'])) {
                throw new \UnexpectedValueException('Ieder item in ' . $collection . ' heeft een string-id nodig.');
            }

            $indexed[$item['id']] = $item;
        }

        foreach ($additional as $item) {
            if (!is_array($item) || empty($item['id']) || !is_string($item['id'])) {
                throw new \UnexpectedValueException('Ieder item in ' . $collection . ' heeft een string-id nodig.');
            }

            $indexed[$item['id']] = $item;
        }

        return array_values($indexed);
    }
}
