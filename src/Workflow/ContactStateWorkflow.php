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

/**
 * The ContactStateWorkflow class.
 */
#[StateMachine(
    field: 'state',
    enum: ContactState::class
)]
class ContactStateWorkflow extends AbstractWorkflow
{
    public function configure(WorkflowController $workflow): void
    {

    }
}
