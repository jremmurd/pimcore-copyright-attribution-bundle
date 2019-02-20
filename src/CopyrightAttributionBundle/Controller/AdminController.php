<?php

namespace JRemmurd\CopyrightAttributionBundle\Controller;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Yaml\Yaml;

/**
 * Class AdminController
 * @Route("/admin")
 */
class AdminController extends \Pimcore\Bundle\AdminBundle\Controller\AdminController
{
    CONST CREDITS_STORE_PATH = PIMCORE_PROJECT_ROOT . "/var/config/copyright-attribution/credits.json";

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
     * @Route("/copyright-attribution/add-flaticon", methods={"GET"})
     */
    public function addFlaticonAction(Request $request)
    {
        if (!$subject = $request->get("subject")) {
            return new Response("Missing param 'subject'.", 400);
        }
        if (!$flaticonText = $request->get("text")) {
            return new Response("Missing param 'subject'.", 400);
        }

        $urlRegex = "/https?:\/\/(www)?\.?[ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789\-._~:\/?#\[\]@!$&'()*+,;=]+\.[a-z]+\/?[ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789\-._~:\/?#\[\]@!$&'()*+,;=]*/";
        $contentRegex = "/>[a-zA-Z. 0-9]+<\//";
        $contentTrimChars = "><\/";

        preg_match_all($urlRegex, $flaticonText, $urlMatches);
        $urlMatches = $urlMatches[0];

        preg_match_all($contentRegex, $flaticonText, $contentMatches);

        $contentMatches = array_map(function ($match) use ($contentTrimChars) {
            return trim($match, $contentTrimChars);
        }, $contentMatches[0]);


        $authorUrl = $urlMatches[0];
        $sourceUrl = $urlMatches[1];
        $licenseUrl = $urlMatches[2];

        $author = $contentMatches[0];
        $source = $contentMatches[1];
        $license = $contentMatches[2];

        $newEntry = [
            "author" => $author,
            "author_url" => $authorUrl,
            "source" => $source,
            "source_url" => $sourceUrl,
            "license" => $license,
            "license_url" => $licenseUrl,
        ];

        if (!$newEntry["author"]) {
            return new JsonResponse(["error" => "copyright_attribution.add-icon-parse-error"]);
        }

        if (!file_exists(dirname(self::CREDITS_STORE_PATH))) {
            mkdir(dirname(self::CREDITS_STORE_PATH));
        }
        if (!file_exists(self::CREDITS_STORE_PATH)) {
            file_put_contents(self::CREDITS_STORE_PATH, "{}");
        }

        $credits = json_decode(file_get_contents(self::CREDITS_STORE_PATH), true);

        if (!array_key_exists($subject, $credits)) {
            $credits[$subject] = ["credits" => [$newEntry]];
        } else {
            $entryExists = false;
            foreach ($credits[$subject]["credits"] as $subjectCredits) {
                if (count(array_intersect($subjectCredits, $newEntry)) == count($subjectCredits)) {
                    $entryExists = true;
                    break;
                }
            }
            if (!$entryExists) {
                $credits[$subject]["credits"][] = $newEntry;
            }
        }

        file_put_contents(self::CREDITS_STORE_PATH, json_encode($credits));

        return new Response();
    }

    /**
     * @Route("/copyright-attribution/content", methods={"GET"})
     */
    public function contentAction()
    {
        $viewParams = [];

        try {
            $saved = json_decode(file_get_contents(self::CREDITS_STORE_PATH), true);
        } catch (\Exception $exception) {
            $saved = [];
        }

        $viewParams["subjects"] = array_merge_recursive($this->subjects, $saved);

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