<?php

declare(strict_types=1);

namespace RinyVT\Typo3DoctrineOrm\Mapping;

use Attribute;
use Doctrine\Common\Annotations\Annotation\NamedArgumentConstructor;
use Doctrine\ORM\Mapping\MappingAttribute;

/**
 * @Annotation
 * @NamedArgumentConstructor
 * @Target("CLASS")
 */
#[Attribute(Attribute::TARGET_CLASS)]
final class ExtendTable implements MappingAttribute
{
    /** @var string|null */
    public ?string $name = null;

    public function __construct(
        ?string $name = null,
    ) {
        $this->name = $name;
    }
}
