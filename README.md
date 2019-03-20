# Wrapper for adding serialized self-contained Resque jobs to a queue

Built for PHP Resque Ex

# Installation

```bash
composer require crowdstar/job-worker:~1.0.0
```

# Sample Usage

```php
<?php
use CrowdStar\JobWorker\Queue;

$queue = new Queue('redishost:port', 'redispassword');
$result = $queue->addHighPriorityJob(array('CrowdStar\Covet\Debug', 'debug'), array('some output'));
```

# Processing jobs
To process jobs PHP Resque Ex needs to be running in a environment with your codebase
