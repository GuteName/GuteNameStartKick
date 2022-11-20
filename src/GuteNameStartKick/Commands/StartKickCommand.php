<?php
namespace GuteNameStartKick\Commands;
use GuteNameStartKick\Forms\StartKickForm;
use GuteNameStartKick\StartKick;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\Config;

class StartKickCommand extends Command
{
    public function __construct()
    {
        parent::__construct("startkick", "", "voteban");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        if ($sender instanceof Player) {
            if (count($args) > 1) {
                if ($args[0] === "unban") {
                    if ($sender->hasPermission("command.startkick.unban")) {
                        $player = $args[1];
                        $data = new Config(StartKick::getInstance()->getDataFolder() . $player . ".yml");
                        if ($data->get("banned") === true) {
                            $data->set("banned", false);
                            $data->save();
                            $sender->sendMessage(StartKick::getInstance()->getData()->get("prefix") . " §7Der Spieler §2" . $player . "§7 wurde erfolgreich unbanned.");
                            return true;
                        } else {
                            $sender->sendMessage(StartKick::getInstance()->getData()->get("prefix") . " §cFehler:§7 Der Spieler §c" . $player . "§7 ist nicht gebannt.");
                            return false;

                        }
                    } else {
                        $sender->sendMessage(StartKick::getInstance()->getData()->get("prefix") . " §cFehler:§7 Dir fehlen folgende Berechtigungen: §ccommand.startkick.unban§7!");
                        return false;
                    }
                }
            } else {

                if ($sender->hasPermission("command.startkick")) {
                    if (StartKick::getInstance()->isrunning === false) {
                        $form = new StartKickForm();
                        $form->startkickForm($sender);
                    } else {
                        $sender->sendMessage(StartKick::getInstance()->getData()->get("prefix") . " §cFehler:§7 Es läuft derzeit schon eine Abstimmung.");
                    }
                } else {
                    $sender->sendMessage(StartKick::getInstance()->getData()->get("prefix") . " §cFehler:§7 Dir fehlen folgende Berechtigungen: §ccommand.startkick§7!");
                }
                return true;
            }
        }
        return true;
    }
}