<?php

namespace Source\Framework\Support;

use Monolog\Formatter\LineFormatter;
use Monolog\Handler\BrowserConsoleHandler;
use Monolog\Handler\SendGridHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\TelegramBotHandler;
use Monolog\Logger;
use Monolog\LogRecord;

class Monolog
{
    private Logger $Logger;
    private string $Message;

    /** @var array<string, mixed>|null $fileConfig */
    private array|null $fileConfig;

    /**
     * @param array<string, null>|null $fileConfig
    */
    public function __construct(string $name, string $message, ?array $fileConfig = null)
    {
        $logger = new Logger($name);

        $logger->pushProcessor(function (LogRecord $record): LogRecord {
            $extra = $record->extra;

            $extra['HTTP_HOST'] = $_SERVER['HTTP_HOST'] ?? '';
            $extra['REQUEST_URI'] = $_SERVER['REQUEST_URI'] ?? '';
            $extra['REQUEST_METHOD'] = $_SERVER['REQUEST_METHOD'] ?? '';

            if (!empty($_SERVER["HTTP_USER_AGENT"])) {
                $extra["HTTP_USER_AGENT"] = $_SERVER["HTTP_USER_AGENT"];
            }

            return $record->with(extra: $extra);
        });

        $this->Logger = $logger;
        $this->Message = $message;
        $this->fileConfig = $fileConfig;
    }

    private function levelController(string $level): void
    {
        switch ($level) {
            case "debug":
                $this->Logger->pushHandler(new BrowserConsoleHandler(Logger::DEBUG));
                break;
            case "file":
                $this->Logger->pushHandler(new StreamHandler(
                    (!empty($this->fileConfig) ?
                        __DIR__ . "/../{$this->fileConfig['path']}/{$this->fileConfig['filename']}" :
                        __DIR__ . "/../../../storage/logs/api/log.txt"),
                    Logger::WARNING
                ));
                break;
            case "email":
                $this->Logger->pushHandler(new SendGridHandler(
                    "apikey",
                    $_ENV["SENDGRID_APIKEY"],
                    $_ENV["SENDGRID_EMAIL_FROM"],
                    $_ENV["SENDGRID_EMAIL_TO"],
                    "Erro em bluware.info/api " . date("d/m/Y H:i:s"),
                    Logger::CRITICAL
                ));
                break;
            case "telegram":
                $bot_key = $_ENV["TELEGRAM_BOT_KEY"];
                $bot_channel = $_ENV["TELEGRAM_BOT_CHANNEL"];
                $tele_handler = new TelegramBotHandler($bot_key, $bot_channel, Logger::EMERGENCY);
                $tele_handler->setFormatter(new LineFormatter("%level_name%: %message%"));
                $this->Logger->pushHandler($tele_handler);
                break;
        }
    }

    // DEBUG
    public function debug(): void
    {
        $this->levelController("debug");
        $this->Logger->debug($this->Message, ["logger" => true]);
    }

    public function info(): void
    {
        $this->levelController("debug");
        $this->Logger->info($this->Message, ["logger" => true]);
    }

    public function notice(): void
    {
        $this->levelController("debug");
        $this->Logger->notice($this->Message, ["logger" => true]);
    }

    // FILE
    public function warning(): void
    {
        $this->levelController("file");
        $this->Logger->warning($this->Message, ["logger" => true]);
    }

    public function error(): void
    {
        $this->levelController("file");
        $this->Logger->error($this->Message, ["logger" => true]);
    }

    // E-MAIL
    public function critical(): void
    {
        $this->levelController("email");
        $this->Logger->critical($this->Message, ["logger" => true]);
    }

    public function alert(): void
    {
        $this->levelController("email");
        $this->Logger->alert($this->Message, ["logger" => true]);
    }

    // TELEGRAM
    public function emergency(): void
    {
        $this->levelController("telegram");
        $this->Logger->emergency($this->Message);
    }
}
