<?php

namespace App\Serializer;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

/**
 * Entity denormalizer
 */
class EntityDenormalizer implements DenormalizerInterface
{
    /** @var EntityManagerInterface **/
    protected $em;

    public function __construct(EntityManagerInterface $em)
    {
        // On aura besoin de l'EM pour récupérer notre entité en base
        $this->em = $em;
    }

    /**
     * $data => the id of the the entity 
     * $type => the data from the class we look to denormalize
     * 
     * @inheritDoc
     */
    public function supportsDenormalization($data, $type, $format = null)
    {
        return strpos($type, 'App\\Entity\\') === 0 && (is_numeric($data));
    }

    /**
     * If supportsDenormalization is valid, this method is called
     * 
     * @inheritDoc
     */
    public function denormalize($data, $class, $format = null, array $context = [])
    {
        return $this->em->find($class, $data);
    }
}
