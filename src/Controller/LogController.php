<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\LogRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/count", name="count_")
 */
class LogController extends AbstractController
{
    public function __construct(private LogRepository $logs)
    {
    }

    /**
     * @Route("", name="count_index", methods={"GET"})
     */
    public function index(Request $request): JsonResponse
    {
        $queries = explode('&', $request->server->get('QUERY_STRING'));
        $params = [];

        foreach ($queries as $query) {
            if ($query != '') {
                list($name, $value) = explode('=', $query);
                $params[urldecode($name)][] = urldecode($value);
            }
        }

        return new JsonResponse(['counter' => $this->logs->queryByParameter($params)], JsonResponse::HTTP_OK);
    }

}
