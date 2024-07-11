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
 */
enum ContactState: string implements EnumTranslatableInterface
{
    use EnumTranslatableTrait;

    case PENDING = 'pending';
    case HANDLING = 'handling';
    case DONE = 'done';
    case END = 'end';
    case CANCEL = 'cancel';

    public function trans(LanguageInterface $lang, ...$args): string
    {
        return $lang->trans('contact.state.' . $this->getKey());
    }
}
