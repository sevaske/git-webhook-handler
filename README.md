# Git Webhook Handler
##### The simple way to automatically update your app using a webhook.
This script listens to the bitbucket webhook and updates the current brunch of your project. 

##### What does it do? 
```
git fetch
git pull origin {current_branch_name}
```

### Install
```
composer require sevaske/git-webhook-handler
```

### Example
{your_project_path}/webhook/index.php

If you only need to update:
```php
<?php
require_once __DIR__ . '/../vendor/autoload.php';

// git
$projectRootPath = dirname(__DIR__) . DIRECTORY_SEPARATOR;
$requestContent = file_get_contents("php://input");
$gitAlias = 'git';

$webhook = new \GitWebhookHandler\Webhook\Bitbucket(
    $projectRootPath,
    $requestContent,
    $gitAlias
);

// If you just need to know result
$result = $webhook->handlePull();
// OR
// if you want to have the execution result
$fetchResult = $webhook->git->fetch();
$pullResult = $webhook->git->pull();

// Execution details
$fetchResult->output; // The result of executing the command "git fetch"
$pullResult->output; // The result of executing the command "git pull origin {your_current_branch}"
$noChanges = \GitWebhookHandler\Terminal\Git::catchNoChanges($pullResult); // True if no changes
$pullErrors = \GitWebhookHandler\Terminal\Git::catchErrors($pullResult); // Array of errors

// Errors & the repo details
$webhook->errors; // Array of errors
$webhook->git->branchName; // Current branch of your project
$webhook->requestHandler->authors; // Array of changes authors (full name, email and nickname)
$webhook->requestHandler->request->repository->full_name; // Repository name

// maybe you want to do something else?
$composerUpdateCommand = "export COMPOSER_HOME=/home/sevaske/.composer && cd {$projectRootPath} && composer update";
$composerUpdateResult = \GitWebhookHandler\Terminal\Command::exec($composerUpdateCommand);
$composerUpdateResult->output; // The result of executing the command "composer update"
```
