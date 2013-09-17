<?php

namespace Midnight\Settings\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

class SettingRepository extends EntityRepository
{
    /**
     * @return Setting[]
     */
    public function getAllAsArray()
    {
        return $this->getEntityManager()->createQueryBuilder()
            ->select('s')
            ->from('Settings\Entity\Setting', 's', 's.namespace')
            ->getQuery()
            ->getResult();
    }

    public function set($namespace, $key, $value)
    {
        $this->getEntityManager()->createQueryBuilder()
            ->update($this->getEntityName(), 's')
            ->set('s.value', $value)
            ->where('s.namespace = :namespace')
            ->andWhere('s.key = :key')
            ->setParameters(['namespace' => $namespace, 'key' => $key])
            ->getQuery()
            ->execute();
    }

    public function get($namespace, $key)
    {
        return $this->findOneBy(['namespace' => $namespace, 'key' => $key]);
    }
}