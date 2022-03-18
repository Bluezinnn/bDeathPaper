<?php

namespace DeathPaper;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;

use pocketmine\item\Item;
use pocketmine\item\ItemFactory;

use pocketmine\nbt\tag\CompoundTag;

use pocketmine\event\player\PlayerDeathEvent;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;

use pocketmine\player\Player;

final class Main extends PluginBase implements Listener
{
   
   /**
    * @return Void
    **/
   public function onEnable(): Void
   {
      $this->getServer()->getPluginManager()->registerEvents($this, $this);
      
      $this->getServer()->getCommandMap()->register('teste', 
      new class() extends Command
      {
         
         public function __construct()
         {
            parent::__construct('libertar', 'Liberte a alma de um jogador', null, ['libert']);
         }
         
         /**
          * @param CommandSender $sender
          * @param string $label
          * @param string[] $args
          * 
          * @return Void|mixed
          **/
         public function execute(CommandSender $sender, string $label, array $args)
         {
            if ($sender instanceof Player) {
               if (empty($args)) {
                  $inventory = $sender->getInventory();
                  
                  foreach ($inventory->getContents() as $content) {
                     if ($content instanceof Item) {
                        $item = $content;
                        
                        $namedtag = $item->getNamedTag() ?? new CompoundTag();
                        
                        if ($item->getCustomName() === '§r§ePapel de alma' && $item->getId() === 339 || $item->getTag('death_paper')) {
                           
                           $libertedName = $namedtag->getString('death_player_name');
                           
                           $sender->sendMessage('§aVocê acaba de libertar a alma do jogador §7'.$libertedName.'§e!');
                           
                           $inventory->removeItem($item);
                           
                        }
                     }
                  }
               }
            }
         }
         
      });
   }
   
   /**
    * @param PlayerDeathEvent $evenr
    * 
    * @return Void
    **/
   public function PlayerDeathEvent(PlayerDeathEvent $event): Void
   {
      $player = $event->getPlayer();
      $name = $player->getName();
      
      $item = ItemFactory::getInstance()->get(339);
      $item->setCustomName('§r§ePapel de alma');
      $item->setLore([
         '§r',
         '§r§eAlma do jogador: §7'.$name
      ]);
      
      $namedtag = $item->getNamedTag() ?? new CompoundTag();
      
      $namedtag->setString('death_paper', 'death_paper');
      $namedtag->setString('death_player_name', $name);
      
      $item->setNamedtag($namedtag);
      $event->setDrops([$item]);
   }
   
}