<?php

declare(strict_types=1);

namespace Flexgrid\Modules\AdminDashboard\Service;

use Flexgrid\Modules\AdminCore\ValueObject\TenantId;
use Flexgrid\Modules\AdminDashboard\Contract\DashboardProviderInterface;
use Flexgrid\Utils\_Time;

final class DashboardProviderLoader
{
    private $modulesRoot;
    private $tenant;
    private $clock;
    private $activeModules;
    private $providerClasses;
    private $appRoot;

    public function __construct(string $modulesRoot, TenantId $tenant, _Time $clock, array $activeModules, array $providerClasses = [], string $appRoot = '')
    {
        $this->modulesRoot = rtrim(str_replace('\\', '/', $modulesRoot), '/');
        $this->tenant = $tenant;
        $this->clock = $clock;
        $this->activeModules = array_fill_keys(array_values(array_unique(array_filter($activeModules, 'is_string'))), true);
        $this->providerClasses = $providerClasses;
        $this->appRoot = rtrim(str_replace('\\', '/', $appRoot), '/');
    }

    public function getProviders(): array
    {
        $providers = [];
        foreach ($this->classes() as $class) {
            if (!class_exists($class)) { continue; }
            $provider = new $class($this->tenant, $this->clock);
            if (!$provider instanceof DashboardProviderInterface) {
                throw new \LogicException($class . ' moet DashboardProviderInterface implementeren.');
            }
            $providers[] = $provider;
        }
        return $providers;
    }

    private function classes(): array
    {
        if ($this->providerClasses !== []) {
            return array_values(array_unique(array_filter($this->providerClasses, 'is_string')));
        }
        $classes = [];
        if ($this->modulesRoot !== '' && is_dir($this->modulesRoot)) {
            foreach (glob($this->modulesRoot . '/*/src/Integration/Dashboard/DashboardProvider.php') ?: [] as $file) {
                $module = basename(dirname($file, 4));
                if (!isset($this->activeModules[$module]) || preg_match('/^[A-Za-z][A-Za-z0-9]*$/D', $module) !== 1 || $module === 'AdminDashboard') {
                    continue;
                }
                require_once $file;
                $classes[] = 'Flexgrid\\Modules\\' . $module . '\\Integration\\Dashboard\\DashboardProvider';
            }
        }
        if ($this->appRoot !== '' && is_dir($this->appRoot)) {
            foreach (glob($this->appRoot . '/*/Integration/Dashboard/DashboardProvider.php') ?: [] as $file) {
                $module = basename(dirname($file, 3));
                if (preg_match('/^[A-Za-z][A-Za-z0-9]*$/D', $module) !== 1) {
                    continue;
                }
                require_once $file;
                $classes[] = 'App\\' . $module . '\\Integration\\Dashboard\\DashboardProvider';
            }
        }
        sort($classes);
        return array_values(array_unique($classes));
    }
}
