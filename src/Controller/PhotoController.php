<?php

namespace App\Controller;

use App\Entity\ConfigurationList\Element;
use App\Entity\Photo;
use App\Entity\PhotoElement;
use App\Entity\WorldObject\WorldObject;
use App\Event\Events;
use App\Event\PhotoElementTouchEvent;
use App\Form\PhotoElementType;
use App\Form\Type\WorldObjectType;
use CrEOF\Spatial\PHP\Types\Geography\Point;
use Doctrine\DBAL\Driver\Connection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class PhotoController extends AbstractController
{
    protected $eventDispatcher;
    protected $session;
    protected $photoDatabaseService;


    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        SessionInterface $session,
        \App\Services\Database\Photo $photoDatabaseService
    )
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->session = $session;
        $this->photoDatabaseService = $photoDatabaseService;
    }

    /**
     * @Route("/photos/{uuid}", name="app.photo", requirements={"uuid": "[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"}, methods={"GET", "POST"})
     * @ParamConverter("photo", class="App\Entity\Photo", options={"mapping": {"uuid": "uuid"}})
     */
    public function index(Request $request, Photo $photo, \App\Services\Database\Photo $photoDatabaseService): Response
    {


        $photoElement = new PhotoElement();
        $photoElement->setPhoto($photo);

        $elements = $this->getDoctrine()->getRepository(Element::class)->findAll();

        $w = [];

        foreach ($elements as $element) {
            $id = $element->getId();
            $w[$id] = [
                'id' => $element->getId(),
                'color' => $element->getPrimaryColor(),
                'name' => $element->getName(),
            ];
        }

        $marks = $this->transform($photo->getMetadata(), $w);

        $elementsCount = [];

        foreach ($marks as $mark) {
            if (isset($elementsCount[$mark['element_id']])) {
                ++$elementsCount[$mark['element_id']];
            } else {
                $elementsCount[$mark['element_id']] = 1;
            }
        }

        /** @var Connection $conn */
        $conn = $this->getDoctrine()->getConnection();

        $stmt = $conn->prepare('
            SELECT
                sum(st_area(sector)) as area, 
                element_id
            FROM
                arc_photo.element e
                    INNER JOIN
                arc_photo.photo p ON p.id = e.photo_id
            WHERE
                e.photo_id = :photo_id
            GROUP BY e.element_id        
        ');

        $stmt->bindValue('photo_id', $photo->getId());
        $stmt->execute();

        $areas = [];

        $referenceElementForCompare = $this->session->get('compare_area_by');

        foreach ($stmt->fetchAll() as $item) {
            $areas[$item['element_id']] = $item['area'];
        }

        $z = [];

        if ($referenceElementForCompare && isset($areas[$referenceElementForCompare])) {
            $ref = $areas[$referenceElementForCompare];

            foreach ($areas as $key => $value) {
                if ($ref > $value) {
                    $z[$key] = round(($value / $ref) * 100, 1);
                } else {
                    $z[$key] = '';
                }
            }
        }

        $activeElements = [];

        foreach ($elementsCount as $key => $value) {
            $eid = $w[$key]['id'];
            $activeElements[$eid] = $w[$key]['name'];
        }


        $worldObjectForm = $this->createForm(WorldObjectType::class);
        $worldObjectForm->handleRequest($request);

        if ($worldObjectForm->isSubmitted() && $worldObjectForm->isValid()) {
            /** @var WorldObject $worldObject */
            $worldObject = $worldObjectForm->getData();
            $coordinates = $worldObjectForm->get('coordinates')->getData();

            $coordinates = explode(',', $coordinates);

            if (isset($coordinates[0], $coordinates[1])) {
                $lat = (float)$coordinates[0];
                $lng = (float)$coordinates[1];

                $point = new Point($lat, $lng);
                $worldObject->setCoordinates($point);

                $em = $this->getDoctrine()->getManager();
                $em->persist($worldObject);
                $em->flush();

                return $this->redirectToRoute('app.photo.wo', ['uuid' => $photo->getUuid(), 'wo' => $worldObject->getUuid()]);
            }
        }

        $worldObjects = $this->getDoctrine()
            ->getRepository(WorldObject::class)
            ->findAll();

        $conn = $this->getDoctrine()->getConnection();

        $stmt = $conn->prepare('
            SELECT 
                w.uuid 
            FROM 
                arc_world_object.world_object w
                    INNER JOIN
                arc_photo.element pe ON w.id = pe.world_object_id
            WHERE
                pe.photo_id = :photo_id
            GROUP BY 
                w.id
        ');

        $stmt->bindValue('photo_id', $photo->getId());
        $stmt->execute();

        $worldObjectsInPhoto = [];

        while ($row = $stmt->fetch()) {
            $worldObjectsInPhoto[] = $row['uuid'];
        }

        return $this->render('photo/index.html.twig', [
            'photo' => $photo,
            'marks' => $marks,
            'elements' => $elements,
            'elementsCount' => $elementsCount,
            'activeElements' => $activeElements,
            'areas' => $z,
            'referenceElementForCompare' => $referenceElementForCompare,
            'markedElements' => $this->getMarkedElements($elements, $marks),
            'worldObjectForm' => $worldObjectForm->createView(),
            'worldObjects' => $worldObjects,
            'worldObjectsInPhoto' => $worldObjectsInPhoto,
        ]);
    }

    /**
     * @Route("/photos/{uuid}/{wo}", name="app.photo.wo", requirements={"uuid": "[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"}, methods={"POST", "GET"})
     * @ParamConverter("photo", class="App\Entity\Photo", options={"mapping": {"uuid": "uuid"}})
     * @ParamConverter("worldObject", class="App\Entity\WorldObject\WorldObject", options={"mapping": {"wo": "uuid"}})
     */
    public function wo(Request $request, Photo $photo, WorldObject $worldObject): Response
    {


        $photoElement = new PhotoElement();
        $photoElement->setPhoto($photo);
        $photoElement->setWorldObject($worldObject);

        $form = $this->createForm(PhotoElementType::class, $photoElement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $photoElement->setWorldObject($worldObject);

            $em = $this->getDoctrine()->getManager();
            //   $em->detach($photo);

            $em->persist($photoElement);
            $em->flush();

            $coordinates = $form->get('coordinates')->getData();

            $ex = explode(',', $coordinates);

            $z = [];
            $i = 0;

            foreach ($ex as $coord) {
                ++$i;

                if (0 === $i % 2) {
                    $coord .= ',';
                }

                $z[] = $coord;
            }

            $z[] = $z[0];
            $z[] = $z[1];

            $w = implode(' ', $z);
            $w = rtrim($w, ',');

            if ($coordinates) {
                $event = new PhotoElementTouchEvent($photoElement, $w);
                $this->eventDispatcher->dispatch(Events::PHOTO_ELEMENT_INSERT, $event);
            }

            $this->photoDatabaseService->updateMetadata($photo->getId());

            return $this->redirectToRoute('app.photo.wo', ['uuid' => $photo->getUuid(), 'wo' => $worldObject->getUuid()]);
        }

        $elements = $this->getDoctrine()->getRepository(Element::class)->findAll();


        $w = [];

        foreach ($elements as $element) {
            $id = $element->getId();
            $w[$id] = [
                'id' => $element->getId(),
                'color' => $element->getPrimaryColor(),
                'name' => $element->getName(),
            ];
        }

        $marks = $this->transform($photo->getMetadata(), $w, $worldObject->getId());

        $elementsCount = [];

        foreach ($marks as $mark) {
            if (isset($elementsCount[$mark['element_id']])) {
                ++$elementsCount[$mark['element_id']];
            } else {
                $elementsCount[$mark['element_id']] = 1;
            }
        }

        /** @var Connection $conn */
        $conn = $this->getDoctrine()->getConnection();

        $stmt = $conn->prepare('
            SELECT
                sum(st_area(sector)) as area, 
                element_id
            FROM
                arc_photo.element e
                    INNER JOIN
                arc_photo.photo p ON p.id = e.photo_id
            WHERE
                e.photo_id = :photo_id
            GROUP BY e.element_id        
        ');

        $stmt->bindValue('photo_id', $photo->getId());
        $stmt->execute();

        $areas = [];

        $referenceElementForCompare = $this->session->get('compare_area_by');

        foreach ($stmt->fetchAll() as $item) {
            $areas[$item['element_id']] = $item['area'];
        }

        $z = [];

        if ($referenceElementForCompare && isset($areas[$referenceElementForCompare])) {
            $ref = $areas[$referenceElementForCompare];

            foreach ($areas as $key => $value) {
                if ($ref > $value) {
                    $z[$key] = round(($value / $ref) * 100, 1);
                } else {
                    $z[$key] = '';
                }
            }
        }

        $activeElements = [];

        foreach ($elementsCount as $key => $value) {
            $eid = $w[$key]['id'];
            $activeElements[$eid] = $w[$key]['name'];
        }


        $worldObjectForm = $this->createForm(WorldObjectType::class);
        $worldObjectForm->handleRequest($request);

        if ($worldObjectForm->isSubmitted() && $worldObjectForm->isValid()) {
            /** @var WorldObject $worldObject */
            $worldObject = $worldObjectForm->getData();
            $coordinates = $worldObjectForm->get('coordinates')->getData();

            $coordinates = explode(',', $coordinates);

            if (isset($coordinates[0], $coordinates[1])) {
                $lat = (float)$coordinates[0];
                $lng = (float)$coordinates[1];

                $point = new Point($lat, $lng);
                $worldObject->setCoordinates($point);

                $em = $this->getDoctrine()->getManager();
                $em->persist($worldObject);
                $em->flush();
            }
        }

        $worldObjects = $this->getDoctrine()
            ->getRepository(WorldObject::class)
            ->findAll();

        return $this->render('photo/index.html.twig', [
            'photo' => $photo,
            'form' => $form->createView(),
            'marks' => $marks,
            'elements' => $elements,
            'elementsCount' => $elementsCount,
            'activeElements' => $activeElements,
            'areas' => $z,
            'referenceElementForCompare' => $referenceElementForCompare,
            'markedElements' => $this->getMarkedElements($elements, $marks),
            'worldObjectForm' => $worldObjectForm->createView(),
            'worldObjects' => $worldObjects,
            'worldObject' => $worldObject
        ]);
    }


    /**
     * @Route("/photows/{uuid}/change-ref",
     *     name="app.photo.ref",
     *     requirements={"uuid": "[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"},
     *     methods={"POST"}
     * )
     * @ParamConverter("photo", class="App\Entity\Photo", options={"mapping": {"uuid": "uuid"}})
     */
    public function changeRefArea(Request $request, Photo $photo): Response
    {
        if ($request->isMethod('POST') && $request->request->has('element')) {
            $elementId = (int)$request->request->get('element');

            $this->session->set('compare_area_by', $elementId);
        }

        return $this->redirectToRoute('app.photo', ['uuid' => $photo->getUuid()]);
    }

    public function transform(array $metadata, array $elements, int $filterByWorldObjectId = null): array
    {
        $result = [];

        if (!empty($metadata['sectors'])) {
            foreach ($metadata['sectors'] as $k => $v) {
                $v['element'] = $elements[$v['element_id']];
                $v['geo'] = json_decode($v['geo'], true);
                $v['position'] = [
                    'top' => $v['geo']['coordinates'][0][0][1],
                    'left' => $v['geo']['coordinates'][0][0][0],
                    'width' => $v['geo']['coordinates'][0][2][0] - $v['geo']['coordinates'][0][0][0],
                    'height' => $v['geo']['coordinates'][0][2][1] - $v['geo']['coordinates'][0][0][1],
                ];

                if (isset($v['world_object_id']) && $filterByWorldObjectId !== null && $filterByWorldObjectId !== $v['world_object_id']) {
                    continue;
                }

                $result[] = $v;
            }
        }

        return $result;
    }

    private function getMarkedElements(array $elements, array $marks): array
    {
        $elementsCount = [];
        foreach ($marks as $mark) {
            if (isset($elementsCount[$mark['element_id']])) {
                ++$elementsCount[$mark['element_id']];
            } else {
                $elementsCount[$mark['element_id']] = 1;
            }
        }

        arsort($elementsCount);

        $markedElements = [];
        foreach ($elementsCount as $elementId => $count) {
            foreach ($elements as $element) {
                if ($element->getId() === $elementId) {
                    $markedElements[] = [
                        'id' => $element->getId(),
                        'name' => $element->getName(),
                        'color' => $element->getPrimaryColor(),
                        'count' => $count
                    ];
                    break;
                }
            }
        }

        return $markedElements;
    }
}
