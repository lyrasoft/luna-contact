<?php

/**
 * Part of eva project.
 *
 * @copyright  Copyright (C) 2022 __ORGANIZATION__.
 * @license    MIT
 */

declare(strict_types=1);

namespace Lyrasoft\Contact\Workflow;

use Lyrasoft\Contact\Enum\ContactState;
use Unicorn\Attributes\StateMachine;
use Unicorn\Workflow\AbstractWorkflow;
use Unicorn\Workflow\WorkflowController;
use Windwalker\Core\Language\TranslatorTrait;

/**
 * The ContactStateWorkflow class.
 */
#[StateMachine(
    field: 'state',
    enum: ContactState::class
)]
class ContactStateWorkflow extends AbstractWorkflow
{
    use TranslatorTrait;

    public function configure(WorkflowController $workflow): void
    {
        // show(ContactState::PENDING->trans($this->lang));exit(' @Checkpoint');

        $workflow->setStateMeta(
            ContactState::PENDING->getValue(),
            'ssss',
            'fa fa-fw fa-clock',
            'warning'
        );
        $workflow->setStateMeta(
            ContactState::HANDLING->getValue(),
            ContactState::HANDLING->trans($this->lang),
            'fa fa-fw fa-forward',
            'primary'
        );
        $workflow->setStateMeta(
            ContactState::DONE->getValue(),
            ContactState::DONE->trans($this->lang),
            'fa fa-fw fa-check',
            'success'
        );
        $workflow->setStateMeta(
            ContactState::END->getValue(),
            ContactState::END->trans($this->lang),
            'fa fa-fw fa-check-double',
            'secondary'
        );
        $workflow->setStateMeta(
            ContactState::CANCEL->getValue(),
            ContactState::CANCEL->trans($this->lang),
            'fa fa-fw fa-times',
            'danger'
        );
    }
}
