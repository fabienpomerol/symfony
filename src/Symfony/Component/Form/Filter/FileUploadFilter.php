<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Form\Filter;

use Symfony\Component\Form\FieldInterface;
use Symfony\Component\Form\Filters;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\TemporaryStorage;

/**
 * Moves uploaded files to a temporary location
 *
 * @author Bernhard Schussek <bernhard.schussek@symfony-project.com>
 */
class FileUploadFilter implements FilterInterface
{
    private $field;

    private $storage;

    public function __construct(FieldInterface $field, TemporaryStorage $storage)
    {
        $this->field = $field;
        $this->storage = $storage;
    }

    public function getSupportedFilters()
    {
        return Filters::filterBoundDataFromClient;
    }

    public function filterBoundDataFromClient($data)
    {
        // TESTME
        $data = array_merge(array(
            'file' => '',
            'token' => '',
            'name' => '',
        ), $data);

        // Newly uploaded file
        if ($data['file'] instanceof UploadedFile && $data['file']->isValid()) {
            $data['token'] = (string)rand(100000, 999999);
            $directory = $this->storage->getTempDir($data['token']);

            if (!file_exists($directory)) {
                // Recursively create directories
                mkdir($directory, 0777, true);
            }

            $data['file']->move($directory);
            $data['name'] = $data['file']->getName();
        }

        // Existing uploaded file
        if (!$data['file'] && $data['token'] && $data['name']) {
            $path = $this->storage->getTempDir($data['token']) . DIRECTORY_SEPARATOR . $data ['name'];

            if (file_exists($path)) {
                $data['file'] = new File($path);
            }
        }

        // Clear other fields if we still don't have a file, but keep
        // possible existing files of the field
        if (!$data['file']) {
            $data = $this->field->getNormalizedData();
        }

        return $data;
    }
}