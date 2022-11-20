<?php


namespace GuteNameStartKick\Commands;

use GuteNameStartKick\StartKick;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player\Player;

class NeinCommand extends Command
{
    public function __construct()
    {
        parent::__construct("nein", "", "no");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        if ($sender instanceof Player) {
            if (StartKick::getInstance()->isrunning === true) {
                if(in_array($sender->getName(), StartKick::getInstance()->playerVoteList)){
                    $sender->sendMessage(StartKick::getInstance()->getData()->get("prefix") . " §cFehler:§7 Du hast bereits abgestimmt");
                } else {
                    array_push(StartKick::getInstance()->playerVoteList, $sender->getName());
                    StartKick::getInstance()->no = StartKick::getInstance()->no + 1;
                    $sender->sendMessage(StartKick::getInstance()->getData()->get("prefix") . "§7 Du hast erfolgreich für §4Nein §7abgestimmt");
                }
            } else {
                $sender->sendMessage(StartKick::getInstance()->getData()->get("prefix") . "§c Fehler:§7 Es läuft derzeit keine Abstimmung.");
            }
        }
        return false;
    }
}