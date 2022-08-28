<?php

/**
 * Part of starter project.
 *
 * @copyright  Copyright (C) 2021 __ORGANIZATION__.
 * @license    MIT
 */

declare(strict_types=1);

namespace Lyrasoft\Contact\Enum;

use MyCLabs\Enum\Enum;
use Windwalker\Utilities\Enum\EnumTranslatableInterface;
use Windwalker\Utilities\Enum\EnumTranslatableTrait;
use Windwalker\Utilities\Contract\LanguageInterface;

/**
 * The ContactState enum class.
 * 
 * @method static $this PENDING()
 * @method static $this HANDLING()
 * @method static $this DONE()
 * @method static $this END()
 * @method static $this CANCEL()
 */
class ContactState extends Enum implements EnumTranslatableInterface
{
    use EnumTranslatableTrait;

    public const PENDING = 'pending';
    public const HANDLING = 'handling';
    public const DONE = 'done';
    public const END = 'end';
    public const CANCEL = 'cancel';

    /**
     * Creates a new value of some type
     *
     * @psalm-pure
     *
     * @param  mixed  $value
     *
     * @psalm-param T $value
     * @throws \UnexpectedValueException if incompatible type is given.
     */
    public function __construct(mixed $value)
    {
        parent::__construct($value);
    }

    public function trans(LanguageInterface $lang, ...$args): string
    {
        return $lang->trans('contact.state.' . $this->getKey());
    }
}
