<?php

namespace Install\Services;

class EnvironmentService
{
    public function updateEnvFile($config)
    {
        $envPath = dirname(__DIR__, 2) . '/.env';
        $envContent = file_get_contents($envPath);

        // تحديث المتغيرات
        $envContent = preg_replace('/APP_NAME=.*/', 'APP_NAME="' . $config['app_name'] . '"', $envContent);
        $envContent = preg_replace('/APP_URL=.*/', 'APP_URL=' . $config['app_url'], $envContent);
        $envContent = preg_replace('/DB_HOST=.*/', 'DB_HOST=' . $config['db_host'], $envContent);
        $envContent = preg_replace('/DB_PORT=.*/', 'DB_PORT=' . $config['db_port'], $envContent);
        $envContent = preg_replace('/DB_DATABASE=.*/', 'DB_DATABASE=' . $config['db_name'], $envContent);
        $envContent = preg_replace('/DB_USERNAME=.*/', 'DB_USERNAME=' . $config['db_user'], $envContent);
        $envContent = preg_replace('/DB_PASSWORD=.*/', 'DB_PASSWORD=' . $config['db_pass'], $envContent);

        file_put_contents($envPath, $envContent);
    }
} 