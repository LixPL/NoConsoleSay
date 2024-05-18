<?php

/**
 * @author WolfDen133 & Erosion
 * @version 2.0.0
 */

namespace WolfDen133\NoConsoleSay;

use pocketmine\command\Command;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\event\Listener;
use pocketmine\event\server\CommandEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;

class Main extends PluginBase implements Listener {

    public function onEnable() :void
    {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function onCommandEvent(CommandEvent $event): void
    {
        $sender = $event->getSender();
        if ($sender instanceof ConsoleCommandSender) {
            $commandMap = $this->getServer()->getCommandMap();
            $commands = $commandMap->getCommands();
            $commandNames = array_map(function (Command $command) {
                return $command->getName();
            }, $commands);

            $commandName = explode(" ", $event->getCommand())[0];
            if (!in_array($commandName, $commandNames)) {
                $event->cancel();
                
                if ($commandMap->getCommand("say") instanceof Command) {
                    $commandMap->dispatch(new ConsoleCommandSender($this->getServer(), $this->getServer()->getLanguage()), "say " . $event->getCommand());
                    return;
                }

                $this->getServer()->broadcastMessage(TextFormat::LIGHT_PURPLE . "[Server] " . $event->getCommand());
            }
        }
    }
}
