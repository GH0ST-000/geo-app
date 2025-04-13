<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class FixStoragePermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'storage:fix-permissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix permissions for storage files in the public directory';

    /**
     * Execute the console command.
     */
    public function handle(Filesystem $files)
    {
        $this->info('Fixing permissions for storage files...');
        
        $publicStoragePath = public_path('storage');
        
        if (!$files->exists($publicStoragePath)) {
            $this->error('Public storage directory does not exist. Please run storage:copy first.');
            return 1;
        }
        
        // Fix public storage directory
        $this->fixPermissionsRecursively($files, $publicStoragePath);
        
        // Also fix main storage directory
        $mainStoragePath = storage_path('app/public');
        $this->fixPermissionsRecursively($files, $mainStoragePath);
        
        $this->info('Storage permissions fixed successfully.');
        return 0;
    }
    
    /**
     * Fix permissions recursively for a directory
     */
    protected function fixPermissionsRecursively(Filesystem $files, $path)
    {
        if (!$files->isDirectory($path)) {
            return;
        }
        
        // Set directory permission
        @chmod($path, 0755);
        $this->line("Set directory permissions (755): $path");
        
        // Create .htaccess file if it doesn't exist
        $htaccessPath = $path . '/.htaccess';
        if (!$files->exists($htaccessPath)) {
            $htaccessContent = "# Allow access to all files\n";
            $htaccessContent .= "<IfModule mod_authz_core.c>\n";
            $htaccessContent .= "    Require all granted\n";
            $htaccessContent .= "</IfModule>\n\n";
            $htaccessContent .= "<IfModule !mod_authz_core.c>\n";
            $htaccessContent .= "    Order allow,deny\n";
            $htaccessContent .= "    Allow from all\n";
            $htaccessContent .= "</IfModule>";
            
            $files->put($htaccessPath, $htaccessContent);
            $this->line("Created .htaccess in: $path");
        }
        
        // Handle all items in the directory
        $items = $files->allFiles($path);
        
        foreach ($items as $item) {
            if ($item->isFile()) {
                // Set file permission
                @chmod($item->getPathname(), 0644);
                $this->line("Set file permissions (644): {$item->getPathname()}");
            }
        }
        
        // Process subdirectories
        $directories = $files->directories($path);
        foreach ($directories as $directory) {
            $this->fixPermissionsRecursively($files, $directory);
        }
    }
} 