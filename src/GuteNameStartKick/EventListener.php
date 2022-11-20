<?php
namespace GuteNameStartKick;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\utils\Config;

class EventListener implements Listener
{
    public function onJoin(PlayerLoginEvent $event)
    {
        $player = $event->getPlayer();
        $data = new Config(StartKick::getInstance()->getDataFolder() . $player->getName() . ".yml");
        if ($data->get("banned") === true) {
            if ((string)$data->get("time") > time()) {
                $player->kick($data->get("reason"));
        } else {
                $data->set("banned", false);
                $data->save();
            }
        }
    }
}