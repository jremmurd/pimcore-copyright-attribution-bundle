<?php
namespace JRemmurd\CopyrightAttributionBundle\Controller;

use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AdminController
 * @Route("/admin")
 */
class AdminController extends \Pimcore\Bundle\AdminBundle\Controller\AdminController
{
    /* @var string $contentRoute */
    private $contentRoute;

    /* @var array $subjects */
    private $subjects;

    /**
     * AdminController constructor.
     * @param string $route
     * @param array $subjects
     */
    public function __construct($route = "", $subjects = [])
    {
        $this->contentRoute = $route;
        $this->subjects = $subjects;
    }

    /**
     * @Route("/copyright-attribution/content", methods={"GET"})
     */
    public function contentAction()
    {
        $viewParams = [];
        $viewParams["subjects"] = $this->subjects;

        if ($this->contentRoute) {
            return $this->redirectToRoute($this->contentRoute, $viewParams);
        }

        $printLink = function (string $content, string $title, $url = "") {
            $content = $content ?: $url;
            if ($url) {
                return <<<HTML
<a href="{$url}" target="_blank" title="{$title}">{$content}</a>
HTML;
            }
            return $content;
        };

        $viewParams["printLink"] = $printLink;

        return $this->render("CopyrightAttributionBundle:Admin:content.html.php", $viewParams);
    }

}