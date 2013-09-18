<?php

namespace Midnight\Settings\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Midnight\Settings\Entity\Setting;

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
        $exists = $this->findOneBy(['namespace' => $namespace, 'key' => $key]);
        if ($exists) {
            $qb = $this->getEntityManager()->createQueryBuilder();
            $qb->update($this->getEntityName(), 's')
                ->set('s.value', $qb->expr()->literal($value))
                ->where('s.namespace = :namespace')
                ->andWhere('s.key = :key')
                ->setParameters(['namespace' => $namespace, 'key' => $key])
                ->getQuery()
                ->execute();
        } else {
            $setting = new Setting($namespace, $key, $value);
            $em = $this->getEntityManager();
            $em->persist($setting);
            $em->flush();
        }
    }

    public function get($namespace, $key)
    {
        return $this->findOneBy(array('namespace' => $namespace, 'key' => $key));
    }
}