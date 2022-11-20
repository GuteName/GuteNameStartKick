<?php
namespace GuteNameStartKick\Forms;
use GuteNameStartKick\StartKick;
use GuteNameStartKick\Task\StartKickTask;
use jojoe77777\FormAPI\CustomForm;
use pocketmine\player\Player;
use SuchtiGamesLobby\Listener\transaction;

class StartKickForm
{
    protected $onlinePlayer = [];

    public function startkickForm(Player $player){
        $form = new CustomForm(function (Player $player, $data = null ){
            if($data == null){
                return false;
            }
            $index = $data[0];
            $user = $this->onlinePlayer[$index];
            if($data[1] == null){
                $player->sendMessage(StartKick::getInstance()->getData()->get("prefix") . "§c Fehler:§7 Du hast keine Begründung für diesen Startkick angegeben.");
                return false;
            } else {
                if (StartKick::getInstance()->getServer()->getPlayerExact($user) !== null) {
                    $sender = StartKick::getInstance()->getServer()->getPlayerExact($user);
                    if ($sender->hasPermission("bypass.startkick")) {
                        $player->sendMessage(StartKick::getInstance()->getData()->get("prefix") . "§c Fehler:§7 Gegen dem Spieler §c" . $user . "§7 kann keine Startkick Abstimmung gestartet werden!");
                        return false;
                    }
                }
                if (StartKick::getInstance()->isrunning === true) {
                    $player->sendMessage(StartKick::getInstance()->getData()->get("prefix") . " §cFehler:§7 Es läuft derzeit schon eine Abstimmung.");
                    return false;
                }
                $reason = $data[1];
                StartKick::getInstance()->isrunning = true;
                (int)$time = StartKick::getInstance()->getData()->get("Ban-Zeit");
                $time = $time / 60;
                StartKick::getInstance()->getServer()->broadcastMessage("§8--------[§2§lStart§7Kick§8]--------\n");
                StartKick::getInstance()->getServer()->broadcastMessage("§7Soll dieser Spieler für " . (string)$time . " Minuten gebannt werden.");
                StartKick::getInstance()->getServer()->broadcastMessage("§7Spieler: §2" . $user . "\n");
                StartKick::getInstance()->getServer()->broadcastMessage("§7Wegen: §2" . $reason . "\n");
                StartKick::getInstance()->getServer()->broadcastMessage("§7Ersteller: §2" . $player->getName() . "\n");
                StartKick::getInstance()->getServer()->broadcastMessage("§7Dauer: §230 Sekunden.\n");
                StartKick::getInstance()->getServer()->broadcastMessage("§7Stimme ab mit §a/ja §7oder§c /nein§7.");
                StartKick::getInstance()->getServer()->broadcastMessage("§8--------[§2§lStart§7Kick§8]--------\n");
                $player->sendMessage(StartKick::getInstance()->getData()->get("prefix") . "§7 Du hast erfolgreich einen Startkick gegen dem Spieler §2" . $user . "§7 erstellt.");
                StartKick::getInstance()->getScheduler()->scheduleRepeatingTask(new StartKickTask($user, $reason, $player->getName()), 20);
            }
            return true;
        });
        if(!empty($this->onlinePlayer)) {
            $this->onlinePlayer = [];
        }
        $players = StartKick::getInstance()->getServer()->getOnlinePlayers();
        foreach ($players as $item){
            $this->onlinePlayer[] = $item->getName();
        }
        $form->setTitle("§8§l → §7StartKick §8 ←");
        $form->addDropdown("Spieler auswählen", $this->onlinePlayer);
        $form->addInput("Begründung");
        $player->sendForm($form);
        return $form;
    }
}