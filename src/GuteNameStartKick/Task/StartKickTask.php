<?php
namespace GuteNameStartKick\Task;

use GuteNameStartKick\Commands\StartKickCommand;
use GuteNameStartKick\StartKick;
use pocketmine\scheduler\Task;
use pocketmine\utils\Config;
use Respect\Validation\Rules\Nullable;

class StartKickTask extends Task
{
    public $sek = 0;
    private $player;
    private $reason;
    private $sender;

    public function __construct($player, $reason, $sender)
    {
        $this->player = $player;
        $this->sender = $sender;
        $this->reason = $reason;
    }
    public function onRun(): void
    {
        $player = $this->player;
        $this->sek++;
        $yesVotes = StartKick::getInstance()->yes;
        $noVotes = StartKick::getInstance()->no;
        if ($this->sek === 17) {
            StartKick::getInstance()->getServer()->broadcastMessage(StartKick::getInstance()->getData()->get("prefix") . "§7 Die StartKick Abstimmung endet in §23§7 Sekunden!");
        }
        if ($this->sek === 18) {
            StartKick::getInstance()->getServer()->broadcastMessage(StartKick::getInstance()->getData()->get("prefix") . "§7 Die StartKick Abstimmung endet in §22§7 Sekunden!");
        }
        if ($this->sek === 19) {
            StartKick::getInstance()->getServer()->broadcastMessage(StartKick::getInstance()->getData()->get("prefix") . "§7 Die StartKick Abstimmung endet in §21§7 Sekunden!");
        }
        if ($this->sek === 21) {
            if ($yesVotes > $noVotes) {
                StartKick::getInstance()->getServer()->broadcastMessage("§8--------[§2§lStart§7Kick§8]--------\n");
                StartKick::getInstance()->getServer()->broadcastMessage("§7Der Spieler §2" . $player . " §7wurde §cbestraft§7.");
                StartKick::getInstance()->getServer()->broadcastMessage("§7Wegen: §c" . $this->reason . "\n");
                StartKick::getInstance()->getServer()->broadcastMessage("§aJA §7-§a " . (string)$yesVotes);
                StartKick::getInstance()->getServer()->broadcastMessage("§cNEIN §7-§c " . (string)$noVotes . "\n");
                StartKick::getInstance()->getServer()->broadcastMessage("§8--------[§2§lStart§7Kick§8]--------\n");
                (int)$time = StartKick::getInstance()->getData()->get("Ban-Zeit");
                $t = time() + $time;
                $date = date("d.m.Y - H:i:s", $t);
                (int)$time = StartKick::getInstance()->getData()->get("Ban-Zeit");
                $time = $time / 60;
                $reason ="\n§c You have been banned from the network for §8" . $time . " minutes.\n §cReason:§8 " . $this->reason . "\n§c Until:§8 " . (string)$date;
                $data = new Config(StartKick::getInstance()->getDataFolder() . $player . ".yml");
                $data->set("banned", true);
                $data->set("reason", $reason);
                $data->set("time", $t);
                $data->save();
                if (StartKick::getInstance()->getServer()->getPlayerExact($player) !== null) {
                    $player = StartKick::getInstance()->getServer()->getPlayerExact($player);
                    if (!$player->hasPermission("bypass.startkick")) {
                        $player->kick($reason);
                    }
                }

            } else {
                StartKick::getInstance()->getServer()->broadcastMessage("§8--------[§2§lStart§7Kick§8]--------\n");
                StartKick::getInstance()->getServer()->broadcastMessage("§7Der Spieler §2" . $player . " §7wurde §2nicht bestraft§7.");
                StartKick::getInstance()->getServer()->broadcastMessage("§7Wegen: §c" . $this->reason . "\n");
                StartKick::getInstance()->getServer()->broadcastMessage("§aJA §7-§a " . (string)$yesVotes);
                StartKick::getInstance()->getServer()->broadcastMessage("§cNEIN §7-§c " . (string)$noVotes . "\n");
                StartKick::getInstance()->getServer()->broadcastMessage("§8--------[§2§lStart§7Kick§8]--------\n");
            }
            StartKick::getInstance()->yes = 0;
            StartKick::getInstance()->no = 0;
            StartKick::getInstance()->isrunning = false;
            StartKick::getInstance()->playerVoteList = [];
            $this->getHandler()->cancel();
            $this->sek = 0;
        }
    }
}