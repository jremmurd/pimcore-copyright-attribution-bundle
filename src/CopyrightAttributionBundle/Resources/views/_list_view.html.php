<?php
/**
 * Date: 19.02.2019
 * Time: 16:35
 *
 * @var \AppBundle\Templating\PhpEngine $this
 * @var \AppBundle\Templating\PhpEngine $view
 * @var \Pimcore\Templating\GlobalVariables $app
 */

/* @var array $subjectData */
/* @var closure $printLink */
?>

<ul class="pl-4" style="list-style-type: square">

    <?php foreach ($subjectData["credits"] as $i => $credits): ?>

        <?php
        extract($credits);
        /* @var string $author */
        /* @var string $author_url */
        /* @var string $source */
        /* @var string $source_url */
        /* @var string $license */
        /* @var string $license_url */
        ?>

        <li>
            Made by

            <?= $printLink($author, $author, $author_url) ?>

            <?php if ($source_url || $source): ?>
                from
                <?= $printLink($source, $source, $source_url) ?>
            <?php endif ?>

            <?php if ($license || $license_url): ?>
                is licensed by
                <?= $printLink($license, $license, $license_url) ?>.
            <?php endif ?>
        </li>
    <?php endforeach; ?>
</ul>
