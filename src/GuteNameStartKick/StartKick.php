<?php
namespace GuteNameStartKick;
use GuteNameStartKick\Commands\JaCommand;
use GuteNameStartKick\Commands\NeinCommand;
use GuteNameStartKick\Commands\StartKickCommand;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class StartKick extends PluginBase
{
    public $isrunning = false;
    private static $instance;
    public $yes = 0;
    public $no = 0;
    public $playerVoteList = [];
    public function onEnable(): void
    {
        if (!static::$instance instanceof StartKick) {
            static::$instance = $this;
        }
        $this->getServer()->getCommandMap()->register("startkick", new StartKickCommand());
        $this->getServer()->getCommandMap()->register("ja", new JaCommand());
        $this->getServer()->getCommandMap()->register("nein", new NeinCommand());
        $this->getServer()->getPluginManager()->registerEvents(new EventListener(), $this);
        $this->saveResource("config.yml");
    }
    public function getData()
    {
        return new Config($this->getDataFolder(). "config.yml");
    }
    /**
     * @return StartKick
     */
    public static function getInstance()
    {
        return static::$instance;
    }
}