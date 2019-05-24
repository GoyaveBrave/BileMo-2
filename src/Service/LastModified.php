<?php

namespace App\Service;

use App\Output\Interfaces\OutputInterface;
use App\Output\Output;
use DateTime;

class LastModified
{
    public static function getLastModified(OutputInterface $entities)
    {
        $mostRecent = 0;

        /** @var Output $entity */
        foreach ($entities->getData() as $entity) {
            if (isset($entity->getAttributes()['updated_at']) && $updatedDate = $entity->getAttributes()['updated_at']) {
                $date = strtotime($updatedDate);
                if ($date > $mostRecent) {
                    $mostRecent = $date;
                }
            }
        }

        $lastModified = new DateTime();
        $lastModified->setTimestamp($mostRecent);

        return $lastModified;
    }
}
