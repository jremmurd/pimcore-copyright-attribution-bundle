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
<table class="table table-striped table-hover table-responsive mb-5">
    <tr>
        <th><?= $this->translateAdmin("copyright_attribution.author") ?> </th>
        <th><?= $this->translateAdmin("copyright_attribution.source") ?> </th>
        <th><?= $this->translateAdmin("copyright_attribution.license") ?> </th>
    </tr>
    <?php foreach ($subjectData["credits"] as $i => $credits): ?>

        <tr>
            <?php
            extract($credits);
            /* @var string $author */
            /* @var string $author_url */
            /* @var string $source */
            /* @var string $source_url */
            /* @var string $license */
            /* @var string $license_url */
            ?>

            <td>
                <?= $printLink($author, $author, $author_url) ?>
            </td>

            <td>
                <?php if ($source_url || $source): ?>
                    <?= $printLink($source, $source, $source_url) ?>
                <?php endif ?>
            </td>

            <td>
                <?php if ($license || $license_url): ?>
                    <?= $printLink($license, $license, $license_url) ?>
                <?php endif ?>
            </td>
        </tr>
    <?php endforeach; ?>

</table>