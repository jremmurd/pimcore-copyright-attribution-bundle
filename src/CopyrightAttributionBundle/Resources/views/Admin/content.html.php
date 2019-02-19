<?php
/**
 * @var \Pimcore\Templating\PhpEngine $this
 * @var \Pimcore\Templating\PhpEngine $view
 * @var \Pimcore\Templating\GlobalVariables $app
 * @var array $subjects
 */
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $this->translateAdmin("copyright_attribution.title", [], "admin") ?></title>
    <link rel="stylesheet" href="/bundles/copyrightattribution/css/libs/bootstrap.min.css">
</head>
<body>
<main class="container-fluid">

    <div class="card mt-3">

        <div class="card-header">
            <div class="card-title">
                <h4><?= $this->translateAdmin("copyright_attribution.title", [], "admin") ?></h4>
            </div>
            <div class="card-subtitle"><?= $this->translateAdmin("copyright_attribution.subtitle", [], "admin") ?> </div>
        </div>

        <div class="card-body pt-0">
            <div class="row">
                <?php if (empty($subjects)): ?>
                    <div class="col">
                        <p class="my-3">
                            <?= $this->translateAdmin("copyright_attribution.no-credits") ?>
                        </p>
                    </div>
                <?php else: ?>
                    <?php foreach ($subjects as $subject => $subjectData): ?>
                        <div class="col-auto">
                            <p class="mb-2 mt-4 d-block">
                                <?= $this->translateAdmin("copyright_attribution.subject.title.{$subject}", [], "admin") ?>
                            </p>

                            <?php if ($subjectData["display"] == 'list'): ?>
                                <?= $this->template("CopyrightAttributionBundle:Shared:list.html.php", ["subjectData" => $subjectData]) ?>
                            <?php else: ?>
                                <?= $this->template("CopyrightAttributionBundle:Shared:table.html.php", ["subjectData" => $subjectData]) ?>
                            <?php endif ?>

                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>
</body>
</html>
