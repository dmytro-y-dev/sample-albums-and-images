<?php

namespace AppBundle\Repository;

/**
 * AlbumRepository
 *
 * This repository class contains custom methods for Album objects.
 */

class AlbumRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * Get all albums without querying their images.
     *
     * @return array
     */
    public function findAllWithoutQueryingImages()
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT a.id, a.name FROM AppBundle:Album a ORDER BY a.name ASC'
            )
            ->getResult()
        ;
    }

    /**
     * Get album's data without querying its images.
     *
     * @param int $id Album's id
     *
     * @return Album|null
     */
    public function findOneWithoutQueryingImages($id)
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT a.id, a.name FROM AppBundle:Album a WHERE a.id = :id ORDER BY a.name ASC'
            )
            ->setParameter('id', $id)
            ->getSingleResult()
        ;
    }

    /**
     * Get all albums, which contains $maxImagesCount images at max.
     *
     * @param int $maxImagesCount Maximum images count.
     *
     * @return array
     */
    public function findAllWithMaxImages($maxImagesCount)
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT a.id, a.name FROM AppBundle:Album a LEFT JOIN AppBundle:Image i WITH i.album = a
                 GROUP BY a.id HAVING COUNT(i.id) < :maxImagesCount
                 ORDER BY a.name ASC'
            )
            ->setParameter('maxImagesCount', $maxImagesCount)
            ->getResult()
        ;
    }
}
