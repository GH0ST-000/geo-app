<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class StorageCopy extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'storage:copy {--force : Overwrite existing files}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Copy the publishable storage files to the public directory (alternative to storage:link)';

    /**
     * Execute the console command.
     */
    public function handle(Filesystem $files)
    {
        $this->info('Copying files from storage/app/public to public/storage...');
        
        // Create the public/storage directory if it doesn't exist
        $publicPath = public_path('storage');
        if (!$files->exists($publicPath)) {
            $files->makeDirectory($publicPath, 0755, true);
        }
        
        $sourcePath = storage_path('app/public');
        
        // Copy all files from storage/app/public to public/storage
        $this->copyDirectory($files, $sourcePath, $publicPath);
        
        $this->info('Storage files have been copied.');
    }
    
    /**
     * Copy a directory and its contents recursively
     */
    protected function copyDirectory(Filesystem $files, $source, $destination)
    {
        if (!$files->isDirectory($source)) {
            return;
        }
        
        // Create destination directory if it doesn't exist
        if (!$files->isDirectory($destination)) {
            $files->makeDirectory($destination, 0755, true);
            $this->line("Created directory: " . $destination);
            
            // Also create .htaccess file in the destination
            $htaccessContent = "# Allow access to all files\n";
            $htaccessContent .= "<IfModule mod_authz_core.c>\n";
            $htaccessContent .= "    Require all granted\n";
            $htaccessContent .= "</IfModule>\n\n";
            $htaccessContent .= "<IfModule !mod_authz_core.c>\n";
            $htaccessContent .= "    Order allow,deny\n";
            $htaccessContent .= "    Allow from all\n";
            $htaccessContent .= "</IfModule>";
            
            $files->put($destination . '/.htaccess', $htaccessContent);
            $this->line("Created .htaccess in: " . $destination);
        }
        
        // Get all files and directories in source
        $items = $files->allFiles($source);
        
        foreach ($items as $item) {
            $relativePathFromSource = str_replace($source . '/', '', $item->getPathname());
            $targetPath = $destination . '/' . $relativePathFromSource;
            
            // Create the directory structure if it doesn't exist
            $targetDir = dirname($targetPath);
            if (!$files->isDirectory($targetDir)) {
                $files->makeDirectory($targetDir, 0755, true);
                
                // Set directory permissions explicitly
                @chmod($targetDir, 0755);
                $this->line("Created directory with permissions 755: " . $targetDir);
            }
            
            // Only copy if target doesn't exist or --force is specified
            if (!$files->exists($targetPath) || $this->option('force')) {
                // Copy the file
                $files->copy($item->getPathname(), $targetPath);
                
                // Set file permissions explicitly - make sure world-readable
                @chmod($targetPath, 0644);
                $this->line("Copied file with permissions 644: " . $relativePathFromSource);
            }
        }
        
        // Create .htaccess files in all subdirectories to ensure access
        $this->createHtaccessInSubdirectories($files, $destination);
    }
    
    /**
     * Create .htaccess files in all subdirectories to ensure proper access
     */
    protected function createHtaccessInSubdirectories(Filesystem $files, $directory) 
    {
        $htaccessContent = "# Allow access to all files\n";
        $htaccessContent .= "<IfModule mod_authz_core.c>\n";
        $htaccessContent .= "    Require all granted\n";
        $htaccessContent .= "</IfModule>\n\n";
        $htaccessContent .= "<IfModule !mod_authz_core.c>\n";
        $htaccessContent .= "    Order allow,deny\n";
        $htaccessContent .= "    Allow from all\n";
        $htaccessContent .= "</IfModule>";
        
        // Get all directories
        $dirs = [];
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($directory, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::SELF_FIRST
        );
        
        foreach ($iterator as $item) {
            if ($item->isDir()) {
                $dirs[] = $item->getPathname();
            }
        }
        
        // Create .htaccess in each directory
        foreach ($dirs as $dir) {
            $htaccessPath = $dir . '/.htaccess';
            if (!$files->exists($htaccessPath) || $this->option('force')) {
                $files->put($htaccessPath, $htaccessContent);
                $this->line("Created .htaccess in: " . $dir);
            }
        }
    }
} 