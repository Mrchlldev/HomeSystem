<?php

namespace Mrchlldev\HomeSystem\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use Mrchlldev\HomeSystem\Main;

class SetHomeCommand extends Command {

    /** @var Main $plugin */
    private Main $plugin;

    /**
     * @param Main $plugin
     */
    public function __construct(Main $plugin) {
        parent::__construct("sethome", "Set your home location.", "/sethome <home_name>");
        $this->setPermission("homesystem.command.sethome");
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

        if (!isset($args[0]) || $args[0] === " ") {
            $this->plugin->sendMessageWithPrefix($sender, "§cUsage: §f/sethome <home_name>");
            return;
        }

        $homeName = $args[0];

        if (!preg_match('/^[a-zA-Z0-9]+$/', $homeName)) {
            $this->plugin->sendMessageWithPrefix($sender, "§cInvalid home name! Only letters and numbers are allowed.");
            return;
        }

        foreach($this->plugin->getConfig()->get("blacklist-world") as $world) {
            if($sender->getWorld()->getFolderName() === $world) {
                $this->plugin->sendMessageWithPrefix($sender, "§cYou can't set home in this world. Please set home in another world!");
                return;
            }
        }

        $homeManager = $this->plugin->getHomeManager();
        $currentHomeCount = $homeManager->getHomeCount($sender->getName());
        $homeLimit = $this->plugin->getHomeLimitForPlayer($sender);

        if($homeManager->existsHome($sender->getName(), $homeName)){
            $this->plugin->sendMessageWithPrefix($sender, "§cYou already have home with name $homeName.");
            return;
        }

        if ($currentHomeCount >= $homeLimit) {
            $this->plugin->sendMessageWithPrefix($sender, "§cYou have reached the maximum number of homes allowed.");
            return;
        }

        $homeManager->setHome($sender->getName(), $homeName, $sender->getLocation());
        $this->plugin->sendMessageWithPrefix($sender, "§aHome '$homeName' has been set!");
    }
}