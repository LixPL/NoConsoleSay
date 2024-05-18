<?php

namespace WolfDen133\NoConsoleSay;

use pocketmine\command\Command;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\event\Listener;
use pocketmine\event\server\CommandEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;
use pocketmine\Server;

class Main extends PluginBase implements Listener {

    public function onEnable() : void
    {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function onCommandEvent(CommandEvent $event)
    {
        if ($event->getSender() instanceof ConsoleCommandSender) {
            $commandMap = $this->getServer()->getCommandMap();
            $commandName = explode(" ", $event->getCommand())[0];

            if (!$commandMap->getCommand($commandName)) {
                $event->cancel();

                if ($commandMap->getCommand("say") instanceof Command) {
                    $this->getServer()->dispatchCommand(
                        new ConsoleCommandSender(Server::getInstance()),
                        "say " . $event->getCommand()
                    );
                    return;
                }

                $this->getServer()->broadcastMessage(TextFormat::LIGHT_PURPLE . "[Server] " . $event->getCommand());
            }
        }
    }
}
