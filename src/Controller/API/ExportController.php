<?php

namespace App\Controller\API;

use App\Entity\ConfigurationList\Element;
use App\Entity\Photo;
use App\Services\ExportService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/export", name="api.export.")
 */
class ExportController extends AbstractController
{
    private $exportService;

    public function __construct(ExportService $exportService)
    {
        $this->exportService = $exportService;
    }

    /**
     * @Route("/dataset-ml/{uuid}", name="photo.dataset-ml", requirements={"uuid": "[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"}, methods={"GET"})
     * @ParamConverter("photo", class="App\Entity\Photo", options={"mapping": {"uuid": "uuid"}})
     */
    public function index(Request $request, Photo $photo): JsonResponse
    {
        if (empty($photo->getMetadata()) || !isset($photo->getMetadata()['sectors'])) {
            return new JsonResponse(['no data']);
        }

        $elementsRepo = $this->getDoctrine()
            ->getRepository(Element::class);

        $elements = $this->exportService->remapElements($elementsRepo->findAll());

        $mediaDirUrl = $request->getSchemeAndHttpHost() . $this->getParameter('media_upload_dir_relative');

        $result[] = $this->exportService->buildItem($photo, $mediaDirUrl, $elements);

        return new JsonResponse($result);
    }

    /**
     * @Route("/dataset-ml", name="dataset-ml", methods={"GET"})
     */
    public function all(Request $request): JsonResponse
    {
        $elementsRepo = $this->getDoctrine()
            ->getRepository(Element::class);

        $elements = $this->exportService->remapElements($elementsRepo->findAll());

        $photos = $this->getDoctrine()
            ->getRepository(Photo::class)
            ->findAll();

        $result = [];

        $mediaDirUrl = $request->getSchemeAndHttpHost() . $this->getParameter('media_upload_dir_relative');

        foreach ($photos as $photo) {
            if (empty($photo->getMetadata()) || !isset($photo->getMetadata()['sectors'])) {
                continue;
            }

            $result[] = $this->exportService->buildItem($photo, $mediaDirUrl, $elements);
        }

        return new JsonResponse($result);
    }
}
