# Credits
##### All credits goes to Sevak https://github.com/sevaske


# Git Webhook Handler
##### The simple way to automatically update your app using a webhook.
This script listens to the bitbucket/github webhook and updates the current brunch of your project. 

##### What does it do? 
```
git fetch
git pull origin {current_branch_name}
```

### Install
```
composer require h-ishak/git-webhook-handler
```

### Examples
You can create a file: {your_project_path}/webhook/index.php
### Handlers
You have 2 Existing Handlers :
 - BitbucketHandler
 - GithubHandler


need more handlers ? you can add any handler you want just by extending AbstractHandler class
##### Just update the project
```php
<?php
require_once __DIR__ . '/../vendor/autoload.php';

$projectRootPath = dirname(__DIR__) . DIRECTORY_SEPARATOR;
$requestContent = file_get_contents("php://input");
$gitAlias = 'git';

$webhook = new \GitWebhookHandler\Webhook(
    new BitbucketHandler($requestContent),
    $projectRootPath,
    $gitAlias
);

$result = $webhook->handlePull(); // boolean
```

##### Update and process the results
```php
<?php
require_once __DIR__ . '/../vendor/autoload.php';

$projectRootPath = dirname(__DIR__) . DIRECTORY_SEPARATOR;
$requestContent = file_get_contents("php://input");
$gitAlias = 'git';

$webhook = new \GitWebhookHandler\Webhook(
    new BitbucketHandler($requestContent),
    $projectRootPath,
    $gitAlias
);

// update and get results of executions
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

### Errors?
If you have any errors related to accessing the repository, you can run the following command:
```
git config --global --add safe.directory {project_path}
sudo git remote set-url origin https://{bitbucket_user}:{auth_pass}@bitbucket.org/{project_name}/{repo_name}.git
```
Also, the script must have access to all the files of your project otherwise you may have an error.
A quick solution (*but unsafe, so be careful*):
```
sudo chown -R www-data:www-data {project_path}
```

### What about security?
The webhook checks the branch name and checks if such a branch exists in the repository. The execution command is fixed and does not contain anything dynamic.

You should add your server's ip to the webhook whitelist .
