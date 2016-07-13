<?php

namespace AppBundle\Repository;

/**
 * ImageRepository
 *
 * This repository class contains custom methods for Image objects.
 */

class ImageRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * Get query, which selects all images of album with id equal to $albumId.
     *
     * @param int $albumId Album's id
     *
     * @return \Doctrine\ORM\Query
     */
    public function queryAllByAlbum($albumId)
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT i.id, i.filename, i.description FROM AppBundle:Image i
                 WHERE i.album = :albumId
                 ORDER BY i.filename ASC'
            )
            ->setParameter('albumId', $albumId)
        ;
    }
}
