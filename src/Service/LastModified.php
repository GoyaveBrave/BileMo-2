<?php

namespace App\Service;

use DateTime;

class LastModified
{
    public static function getLastModified(array $entities)
    {
        $mostRecent = 0;

        foreach ($entities as $entity) {
            if (method_exists($entity, 'getUpdatedAt')) {
                $date = $entity->getUpdatedAt()->getTimestamp();
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
