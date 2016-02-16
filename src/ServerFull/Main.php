<?php

  namespace ServerFull;

  use pocketmine\plugin\PluginBase;
  use pocketmine\event\Listener;
  use pocketmine\event\player\PlayerPreLoginEvent;
  use pocketmine\utils\TextFormat as TF;
  use pocketmine\utils\Config;
  use pocketmine\command\Command;
  use pocketmine\command\CommandSender;

  class Main extends PluginBase implements Listener {

    public function getDataPath() {

      return $this->getDataFolder();

    }

    public function onEnable() {

      $this->getServer()->getPluginManager()->registerEvents($this, $this);

      @mkdir($this->getDataPath());

      $this->cfg = new Config($this->getDataPath() . "config.yml", Config::YAML, array("server_full_message" => "Sorry, This Server Is Full! Please Come Back Later."));

    }

    public function onPreLogin(PlayerPreLoginEvent $event) {

      $player = $event->getPlayer();

      $serverFullMessage = $this->cfg->get("server_full_message");

      if(count($this->getServer()->getOnlinePlayers()) >= $this->getServer()->getMaxPlayers()) {

        $player->close("", $serverFullMessage);

        $event->setCancelled();

      }

    }

    public function onCommand(CommandSender $sender, Command $cmd, $label, array $args) {

      if(strtolower($cmd->getName()) === "serverfull") {

        if(!(isset($args[0]))) {

          $sender->sendMessage(TF::RED . "Error: not enough args. Usage: /serverfull < reason >");

          return true;

        } else {

          $newServerFullMessage = implode(" ", $args);

          $this->cfg->set("server_full_message", $newServerFullMessage);

          $this->cfg->save();

          $sender->sendMessage(TF::GREEN . "Successfully updated ServerFull message!");

          return true;

        }

      }

    }

  }

?>
