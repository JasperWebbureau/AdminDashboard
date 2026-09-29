<?php

declare(strict_types=1);

namespace Flexgrid\Modules\AdminDashboard\Provider;

use Flexgrid\Database\Connection;
use Flexgrid\Modules\AdminCore\ValueObject\Currency;
use Flexgrid\Modules\AdminCore\ValueObject\Money;
use Flexgrid\Modules\AdminCore\ValueObject\TenantId;
use Flexgrid\Utils\_Time;

abstract class AbstractPdoDashboardProvider
{
    /** @var TenantId */
    protected $tenant;

    /** @var _Time */
    protected $clock;

    /** @var \PDO */
    protected $connection;

    public function __construct(TenantId $tenant, _Time $clock, ?\PDO $connection = null)
    {
        $this->tenant = $tenant;
        $this->clock = $clock;
        $this->connection = $connection ?: Connection::getConnections();
    }

    protected function url(string $controller, string $path = ''): string
    {
        $domain = defined('__DOMAIN__') ? rtrim((string)constant('__DOMAIN__'), '/') : '';
        return $domain . '/Flexgrid/' . $controller . ($path === '' ? '' : '/' . ltrim($path, '/'));
    }

    protected function money(int $minorUnits, string $currency): string
    {
        $currency = strtoupper(trim($currency));
        $prefix = $currency === 'EUR' ? '€ ' : $currency . ' ';
        return $prefix . (new Money($minorUnits, new Currency($currency)))->format();
    }

    protected function aggregateMoney(array $rows, string $amountKey): string
    {
        if ($rows === []) { return $this->money(0, 'EUR'); }
        $values = [];
        foreach ($rows as $row) {
            $currency = (string)($row['currency'] ?? 'EUR');
            $values[] = $this->money((int)($row[$amountKey] ?? 0), $currency);
        }
        return implode(' · ', $values);
    }

    protected function snapshotName($snapshot, string $fallback = 'Onbekende klant'): string
    {
        $data = json_decode((string)$snapshot, true);
        if (!is_array($data)) { return $fallback; }
        foreach (['name', 'display_name', 'company_name', 'supplier_name'] as $key) {
            if (isset($data[$key]) && trim((string)$data[$key]) !== '') { return trim((string)$data[$key]); }
        }
        return $fallback;
    }

    protected function dateLabel(string $date): string
    {
        $timestamp = strtotime($date);
        return $timestamp === false ? $date : date('d-m-Y', $timestamp);
    }

    protected function activityTime(int $timestamp): string
    {
        if ($timestamp <= 0) { return ''; }
        return date('Y-m-d', $timestamp) === $this->clock->format('Y-m-d')
            ? date('H:i', $timestamp)
            : date('d-m', $timestamp);
    }

    protected function agendaDate(string $date): array
    {
        $timestamp = strtotime($date . ' 12:00:00');
        if ($timestamp === false) { return ['date_iso' => $date, 'day' => '', 'month' => '', 'timestamp' => 0]; }
        $months = [1 => 'JAN', 2 => 'FEB', 3 => 'MRT', 4 => 'APR', 5 => 'MEI', 6 => 'JUN', 7 => 'JUL', 8 => 'AUG', 9 => 'SEP', 10 => 'OKT', 11 => 'NOV', 12 => 'DEC'];
        return ['date_iso' => $date, 'day' => date('d', $timestamp), 'month' => $months[(int)date('n', $timestamp)], 'timestamp' => $timestamp];
    }
}
