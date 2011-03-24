<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Tests\Component\Form\Type;

require_once __DIR__ . '/LocalizedTestCase.php';

use Symfony\Component\Form\UrlField;

class UrlTypeTest extends LocalizedTestCase
{
    public function testSubmitAddsDefaultProtocolIfNoneIsIncluded()
    {
        $field = $this->factory->create('url', 'name');

        $field->bind('www.domain.com');

        $this->assertSame('http://www.domain.com', $field->getData());
        $this->assertSame('http://www.domain.com', $field->getClientData());
    }

    public function testSubmitAddsNoDefaultProtocolIfAlreadyIncluded()
    {
        $field = $this->factory->create('url', 'name', array(
            'default_protocol' => 'http',
        ));

        $field->bind('ftp://www.domain.com');

        $this->assertSame('ftp://www.domain.com', $field->getData());
        $this->assertSame('ftp://www.domain.com', $field->getClientData());
    }

    public function testSubmitAddsNoDefaultProtocolIfEmpty()
    {
        $field = $this->factory->create('url', 'name', array(
            'default_protocol' => 'http',
        ));

        $field->bind('');

        $this->assertSame(null, $field->getData());
        $this->assertSame('', $field->getClientData());
    }

    public function testSubmitAddsNoDefaultProtocolIfSetToNull()
    {
        $field = $this->factory->create('url', 'name', array(
            'default_protocol' => null,
        ));

        $field->bind('www.domain.com');

        $this->assertSame('www.domain.com', $field->getData());
        $this->assertSame('www.domain.com', $field->getClientData());
    }
}