<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Form\ValueTransformer;

use Symfony\Component\Form\FieldInterface;

class MergeCollectionTransformer implements ValueTransformerInterface
{
    private $field;

    public function __construct(FieldInterface $field)
    {
        $this->field = $field;
    }

    public function transform($data)
    {
        return $data;
    }

    public function reverseTransform($data)
    {
        $collection = $this->field->getData();

        if (!$collection) {
            $collection = $data;
        } else if (count($data) === 0) {
            $collection->clear();
        } else {
            // merge $data into $collection
            foreach ($collection as $entity) {
                if (!$data->contains($entity)) {
                    $collection->removeElement($entity);
                } else {
                    $data->removeElement($entity);
                }
            }

            foreach ($data as $entity) {
                $collection->add($entity);
            }
        }

        return $collection;
    }
}