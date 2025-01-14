<?php

namespace Mrchlldev\HomeSystem\commands;

use Mrchlldev\HomeSystem\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class RemoveHomeCommand extends Command {

    /** @var Main $plugin */
    private Main $plugin;

    /**
     * @param Main $plugin
     */
    public function __construct(Main $plugin) {
        parent::__construct("removehome", "Remove a specific home", "/removehome <home_name>");
        $this->setPermission("homesystem.command.removehome");
        $this->plugin = $plugin;
    }

    /**
     * @param CommandSender $sender
     * @param string $label
     * @param array $args
     * @return void
     */
    public function execute(CommandSender $sender, string $label, array $args): void {
        if (!$sender instanceof Player) {
            $sender->sendMessage("This command can only be used in-game.");
            return;
        }

        if (!isset($args[0])) {
            $this->plugin->sendMessageWithPrefix($sender, "§cUsage: §f/removehome <home_name>");
            return;
        }

        $homeName = $args[0];
        $homeManager = $this->plugin->getHomeManager();

        if ($homeManager->removeHome($sender->getName(), $homeName)) {
            $this->plugin->sendMessageWithPrefix($sender, "§aHome '$homeName' succesfully removed.");
        } else {
            $this->plugin->sendMessageWithPrefix($sender, "§cHome '$homeName' not found!");
        }
    }
}