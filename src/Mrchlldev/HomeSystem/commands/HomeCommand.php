<?php

namespace Mrchlldev\HomeSystem\commands;

use pocketmine\entity\Location;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use Mrchlldev\HomeSystem\Main;

class HomeCommand extends Command {

    /** @var Main $plugin */
    private Main $plugin;

    /**
     * @param Main $plugin
     */
    public function __construct(Main $plugin) {
        parent::__construct("home", "Teleport to a specific home.", "/home <home_name>");
        $this->setPermission("homesystem.command.home");
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

        if (empty($args[0])) {
            $this->plugin->sendMessageWithPrefix($sender, "§cUsage: §f/home <home_name>\nUse §e/getmyhome §fcommand to get your all home!");
            return;
        }

        $homeName = $args[0];
        $homeManager = $this->plugin->getHomeManager();
        $location = $homeManager->getHome($sender->getName(), $homeName);
        $homeWorld = $homeManager->getHomeWorld($sender->getName(), $homeName);

        if ($location === null && $homeWorld === null) {
            $this->plugin->sendMessageWithPrefix($sender, "§cHome '$homeName' not found. Use /getmyhome command to get your all home!");
            return;
        }

        $sender->teleport(Location::fromObject($location, $homeWorld));
        $this->plugin->sendMessageWithPrefix($sender, "§aTeleported to home '$homeName'.");
    }
}