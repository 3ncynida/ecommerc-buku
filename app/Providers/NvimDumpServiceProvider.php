<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Symfony\Component\VarDumper\VarDumper;
use Symfony\Component\VarDumper\Cloner\VarCloner;
use Symfony\Component\VarDumper\Dumper\CliDumper;

class NvimDumpServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        if (!app()->environment('local')) {
            return;
        }

        // Set up custom dump handler for Neovim
        VarDumper::setHandler(function ($var) {
            $cloner = new VarCloner();
            $dumper = new NvimCliDumper();

            // Get caller information - we need to look deeper in the stack
            $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 20);
            $caller = null;

            // Multi-pass approach: prioritize route files, then controllers, then any user code
            $routeCaller = null;
            $controllerCaller = null;
            $userCodeCaller = null;
            $fallbackCaller = null;

            foreach ($trace as $frame) {
                $file = $frame['file'] ?? '';
                $function = $frame['function'] ?? '';
                $class = $frame['class'] ?? '';

                // Skip internal frames we don't want to show
                $skipConditions = [
                    // Skip framework internals
                    strpos($file, 'laravel/framework') !== false,
                    strpos($file, 'symfony/var-dumper') !== false,

                    // Skip this service provider
                    strpos($file, 'NvimDumpServiceProvider.php') !== false,

                    // Skip VarDumper internal functions
                    strpos($class, 'Symfony\\Component\\VarDumper') !== false,

                    // Skip closure/anonymous functions (our handler)
                    $function === '{closure}',

                    // Skip call_user_func type calls
                    in_array($function, ['call_user_func', 'call_user_func_array']),
                ];

                // Special case: Don't skip dump functions if they're in user code
                // (This is where the actual dump() call happened)
                $isDumpFunction = in_array($function, ['dump', 'dd', 'ddd', 'ray']);
                $isUserCodeFile = (
                    strpos($file, 'routes/') !== false ||
                    strpos($file, 'app/Http/Controllers/') !== false ||
                    strpos($file, 'app/Models/') !== false ||
                    strpos($file, 'app/Services/') !== false ||
                    (strpos($file, 'app/') !== false &&
                     strpos($file, 'vendor/') === false &&
                     strpos($file, 'app/Http/Middleware/') === false)
                );

                // If it's a dump function in user code, don't skip it
                if ($isDumpFunction && $isUserCodeFile) {
                    // This is exactly what we want - the user's dump() call
                } else if ($isDumpFunction) {
                    // Skip dump functions in framework/vendor code
                    $skipConditions[] = true;
                }

                $shouldSkip = false;
                foreach ($skipConditions as $condition) {
                    if ($condition) {
                        $shouldSkip = true;
                        break;
                    }
                }

                if (!$shouldSkip && !empty($file)) {
                    // Always store as fallback
                    if (!$fallbackCaller) {
                        $fallbackCaller = $frame;
                    }

                    // Check for route files (highest priority)
                    if (strpos($file, 'routes/') !== false) {
                        $routeCaller = $frame;
                        break; // Routes have highest priority
                    }

                    // Check for controllers (second priority)
                    if (strpos($file, 'app/Http/Controllers/') !== false) {
                        if (!$controllerCaller) {
                            $controllerCaller = $frame;
                        }
                    }

                    // Check for other user code (third priority)
                    if ($isUserCodeFile && !$userCodeCaller) {
                        $userCodeCaller = $frame;
                    }
                }
            }

            // Priority order: routes > controllers > user code > fallback
            $caller = $routeCaller ?: $controllerCaller ?: $userCodeCaller ?: $fallbackCaller;

            $dumper->setCallerInfo($caller);
            $dumper->dump($cloner->cloneVar($var));
        });
    }
}

class NvimCliDumper extends CliDumper
{
    private static $logFile;
    private $callerInfo;

    public function __construct()
    {
        parent::__construct();

        if (!self::$logFile) {
            self::$logFile = storage_path('logs/nvim-dumps.log');

            // Ensure log directory exists
            $logDir = dirname(self::$logFile);
            if (!is_dir($logDir)) {
                mkdir($logDir, 0755, true);
            }
        }
    }

    public function setCallerInfo($callerInfo)
    {
        $this->callerInfo = $callerInfo;
    }

    public function dump($data, $output = null, array $extraDisplayOptions = []): string
    {
        // Create a string output stream to capture dump content
        $outputStream = fopen('php://memory', 'r+');

        // Dump to the stream to capture content
        $originalOutput = parent::dump($data, $outputStream, $extraDisplayOptions);

        // Get the captured content
        rewind($outputStream);
        $capturedOutput = stream_get_contents($outputStream);
        fclose($outputStream);

        // Use the caller info if available, otherwise fallback to debug_backtrace
        $caller = $this->callerInfo ?? $this->findRelevantCaller(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 10));

        $timestamp = date('Y-m-d H:i:s');
        $file = $caller['file'] ?? 'unknown';
        $line = $caller['line'] ?? 0;

        // Make file path relative to project root
        $projectRoot = base_path();
        if (strpos($file, $projectRoot) === 0) {
            $file = substr($file, strlen($projectRoot) + 1);
        }

        // Use captured output or fallback to a string representation
        $dumpContent = $capturedOutput ?: var_export($data, true);

        // Clean and format the dump output
        $cleanOutput = $this->cleanDumpOutput($dumpContent);

        // Create a structured log entry that can be easily parsed
        $logEntry = json_encode([
            'timestamp' => $timestamp,
            'file' => $file,
            'line' => $line,
            'content' => $cleanOutput,
            'type' => gettype($data)
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        // Write to Neovim log file with a special delimiter
        $logLine = "NVIM_DUMP:" . $logEntry . "\n";
        file_put_contents(self::$logFile, $logLine, FILE_APPEND | LOCK_EX);

        // Also output to console like normal dump()
        echo $capturedOutput;

        return $originalOutput ?? '';
    }

    private function findRelevantCaller(array $trace): array
    {
        foreach ($trace as $frame) {
            $file = $frame['file'] ?? '';
            $function = $frame['function'] ?? '';

            // Skip internal Laravel/Symfony dump functions and this service provider
            if (
                strpos($file, 'laravel/framework') === false &&
                strpos($file, 'symfony/var-dumper') === false &&
                strpos($file, 'NvimDumpServiceProvider.php') === false &&
                !in_array($function, ['dump', 'dd', 'ddd', 'ray'])
            ) {
                return $frame;
            }
        }

        return $trace[0] ?? [];
    }

    private function cleanDumpOutput($output): string
    {
        // Convert to string if needed
        $output = (string) $output;

        // Remove ANSI color codes and control characters
        $output = preg_replace('/\x1b\[[0-9;]*m/', '', $output);

        // Remove excessive whitespace
        $output = preg_replace('/\n\s*\n/', "\n", $output);
        $output = trim($output);

        return $output;
    }
}
