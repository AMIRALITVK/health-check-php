<!-- HEY FOLKS -->
<?php
class Health {
   public $data;

   function defaultHealth($data){
      // var_dump($data);
      $unallocated = 0;
      $pending = 0;
      if (isset($data->type) && (($data->type == 'HDD') || ($data->type == 'HD') || ($data->type == 'SATA') || ($data->type == 'ATA'))) {
         if (isset($data->smartData->ata_smart_attributes)) {
            for ($i = 0; $i < count($data->smartData->ata_smart_attributes->table); $i++) {
               if ($data->smartData->ata_smart_attributes->table[$i]->name == 'Reallocated_Sector_Ct') {
                  $unallocated = $data->smartData->ata_smart_attributes->table[$i]->raw->value;
               } else if ($data->smartData->ata_smart_attributes->table[$i]->name == 'Current_Pending_Sector') {
                  $pending = $data->smartData->ata_smart_attributes->table[$i]->raw->value;
               }
            }
            return round((((100 - $unallocated) * (100 - $pending * 0.6)) / 100), 0);
         } else {
            return null;
         }
      } elseif (!isset($data->type) || ($data->type == 'SSD')) {
         if (preg_match('/nvme/i', json_encode($data)) >= 1) {
            // NVMe
            $used = 0;
            if (isset($data->smartData)) {
               $used = $data->smartData->nvme_smart_health_information_log->percentage_used;
               return (100 - $used);
            } else {
               $used = $data->nvme_smart_health_information_log->percentage_used;
               return (100 - $used);
            }
         } elseif (preg_match("/M.2/i", json_encode($data)) >= 1) {
            // M.2
            $reallocated = 0;
            $wearing = 0;
            if (isset($data->ata_smart_attributes->table)) {
               for ($i = 0; $i < count($data->ata_smart_attributes->table); $i++) {
                  if ($data->ata_smart_attributes->table[$i]->name == 'Reallocate_NAND_Blk_Cnt') {
                     $reallocated = $data->ata_smart_attributes->table[$i]->raw->value;
                  } else if ($data->ata_smart_attributes->table[$i]->name == 'Reallocated_Sector_Ct') {
                     $reallocated = $data->ata_smart_attributes->table[$i]->raw->value;
                  } else if ($data->ata_smart_attributes->table[$i]->name == 'Wear_Leveling_Count') {
                     $wearing = $data->ata_smart_attributes->table[$i]->raw->value;
                  }
               }
               return round(((100 - $reallocated) * (100 - (100 - $wearing) * 0.2) / 100), 0);
            } else {
               return null;
            }
         } else {
            // SSD
            $unallocated = 0;
            $pending = 0;
            if (isset($data->smartData->ata_smart_attributes)) {
               for ($i = 0; $i < count($data->smartData->ata_smart_attributes->table); $i++) {
                  if ($data->smartData->ata_smart_attributes->table[$i]->name == 'Reallocated_Sector_Ct') {
                     $unallocated = $data->smartData->ata_smart_attributes->table[$i]->raw->value;
                  } elseif ($data->smartData->ata_smart_attributes->table[$i]->name == 'Current_Pending_Sector') {
                     $pending = $data->smartData->ata_smart_attributes->table[$i]->raw->value;
                  }
               }
               return round((((100 - $unallocated) * (100 - $pending * 0.6)) / 100), 0);
            } else {
               return null;
            }
         }
      }
   }
   // ############# STRICT #############

   function strictHealth($data)
   {
      // var_dump($data);
      $unallocated = 0;
      $pending = 0;
      if (isset($data->type) && (($data->type == 'HDD') || ($data->type == 'HD') || ($data->type == 'SATA') || ($data->type == 'ATA'))) {
         if (isset($data->smartData->ata_smart_attributes)) {
            for ($i = 0; $i < count($data->smartData->ata_smart_attributes->table); $i++) {
               if ($data->smartData->ata_smart_attributes->table[$i]->name == 'Reallocated_Sector_Ct') {
                  $unallocated = $data->smartData->ata_smart_attributes->table[$i]->raw->value;
               } else if ($data->smartData->ata_smart_attributes->table[$i]->name == 'Current_Pending_Sector') {
                  $pending = $data->smartData->ata_smart_attributes->table[$i]->raw->value;
               }
            }
            return round((((100 - $unallocated * 6) * (100 - $pending * 4)) / 100), 0);
         } else {
            return null;
         }
      } elseif (!isset($data->type) || ($data->type == 'SSD')) {
         if (preg_match('/nvme/i', json_encode($data)) >= 1) {
            // NVMe
            $used = 0;
            if (isset($data->smartData)) {
               $used = $data->smartData->nvme_smart_health_information_log->percentage_used;
               return (100 - $used);
            } else {
               $used = $data->nvme_smart_health_information_log->percentage_used;
               return (100 - $used);
            }
         } elseif (preg_match("/M.2/i", json_encode($data)) >= 1) {
            // M.2
            $reallocated = 0;
            $wearing = 0;
            if (isset($data->ata_smart_attributes)) {
               for ($i = 0; $i < count($data->ata_smart_attributes->table); $i++) {
                  if ($data->ata_smart_attributes->table[$i]->name == 'Reallocate_NAND_Blk_Cnt') {
                     $reallocated = $data->ata_smart_attributes->table[$i]->raw->value;
                  } else if ($data->ata_smart_attributes->table[$i]->name == 'Reallocated_Sector_Ct') {
                     $reallocated = $data->ata_smart_attributes->table[$i]->raw->value;
                  } else if ($data->ata_smart_attributes->table[$i]->name == 'Wear_Leveling_Count') {
                     $wearing = $data->ata_smart_attributes->table[$i]->raw->value;
                  }
               }
               return round(((100 - $reallocated * 6) * (100 - (100 - $wearing) * 0.3) / 100), 0);
            } else {
               return null;
            }
         } else {
            // SSD
            $unallocated = 0;
            $pending = 0;
            if (isset($data->smartData->ata_smart_attributes)) {
               for ($i = 0; $i < count($data->smartData->ata_smart_attributes->table); $i++) {
                  if ($data->smartData->ata_smart_attributes->table[$i]->name == 'Reallocated_Sector_Ct') {
                     $unallocated = $data->smartData->ata_smart_attributes->table[$i]->raw->value;
                  } elseif ($data->smartData->ata_smart_attributes->table[$i]->name == 'Current_Pending_Sector') {
                     $pending = $data->smartData->ata_smart_attributes->table[$i]->raw->value;
                  }
               }
               return round((((100 - $unallocated) * (100 - $pending * 0.6)) / 100), 0);
            } else {
               return null;
            }
         }
      }
   }
}

$health = new Health();

// $jsonString = file_get_contents('nvme.json');
$jsonString = file_get_contents('m2-88.json');
// $jsonString = file_get_contents('HDD.json');
// $jsonString = file_get_contents('SSD.json');

$jsonData = json_decode($jsonString);

echo 'Normal ' . $health->defaultHealth($jsonData);
echo '<br>';
echo 'Strict ' . $health->strictHealth($jsonData);

// var_dump('Normal ' . $health->defaultHealth($jsonData));
// var_dump('Strict ' . $health->strictHealth($jsonData));

?>