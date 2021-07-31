<?php

namespace Fastwf\Core\Engine\Run;

use Fastwf\Core\Engine\Context;

/**
 * Interface that define behaviour required by the runner to handle correctly the request.
 */
interface IRunnerEngine extends Context, IMatcher {}
