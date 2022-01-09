<?php

/**
 * Part of eva project.
 *
 * @copyright  Copyright (C) 2022 __ORGANIZATION__.
 * @license    __LICENSE__
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
        $workflow->setStateMeta(
            ContactState::PENDING(),
            ContactState::PENDING()->trans($this->lang),
            'fa fa-fw fa-clock',
            'warning'
        );
        $workflow->setStateMeta(
            ContactState::HANDLING(),
            ContactState::HANDLING()->trans($this->lang),
            'fa fa-fw fa-forward',
            'primary'
        );
        $workflow->setStateMeta(
            ContactState::DONE(),
            ContactState::DONE()->trans($this->lang),
            'fa fa-fw fa-check',
            'success'
        );
        $workflow->setStateMeta(
            ContactState::END(),
            ContactState::END()->trans($this->lang),
            'fa fa-fw fa-check-double',
            'secondary'
        );
        $workflow->setStateMeta(
            ContactState::CANCEL(),
            ContactState::CANCEL()->trans($this->lang),
            'fa fa-fw fa-times',
            'danger'
        );
    }
}
