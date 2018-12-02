<?php

namespace App\Controller;

use App\Entity\ConfigurationList\Element;
use App\Entity\Photo;
use App\Entity\PhotoElement;
use App\Event\Events;
use App\Event\PhotoElementTouchEvent;
use App\Form\PhotoElementType;
use Doctrine\DBAL\Driver\Connection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
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


    public function __construct(EventDispatcherInterface $eventDispatcher, SessionInterface $session)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->session = $session;
    }

    /**
     * @Route("/photos/{uuid}", name="app.photo", requirements={"uuid": "[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"})
     * @ParamConverter("photo", class="App\Entity\Photo", options={"mapping": {"uuid": "uuid"}})
     * @Method({"POST", "GET"})
     */
    public function index(Request $request, Photo $photo, \App\Services\Database\Photo $photoDatabaseService): Response
    {
        $photoElement = new PhotoElement();
        $photoElement->setPhoto($photo);

        $form = $this->createForm(PhotoElementType::class, $photoElement, [
            'action' => $this->generateUrl('app.photo', ['uuid' => $photo->getUuid()]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
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

            $photoDatabaseService->updateMetadata($photo->getId());

            return $this->redirectToRoute('app.photo', [
                'uuid' => $photo->getUuid(),
            ]);
        }

        $elements = $this->getDoctrine()->getRepository(Element::class)->findAll();


        $w = [];

        foreach ($elements as $element) {
            $id = $element->getId();
            $w[$id] = [
                'id'    => $element->getId(),
                'color' => $element->getPrimaryColor(),
                'name'  => $element->getName(),
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

        return $this->render('photo/index.html.twig', [
            'photo'                      => $photo,
            'form'                       => $form->createView(),
            'marks'                      => $marks,
            'elements'                   => $elements,
            'elementsCount'              => $elementsCount,
            'activeElements'             => $activeElements,
            'areas'                      => $z,
            'referenceElementForCompare' => $referenceElementForCompare,
        ]);
    }

    /**
     * @Route("/photows/{uuid}/change-ref", name="app.photo.ref", requirements={"uuid": "[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"})
     * @ParamConverter("photo", class="App\Entity\Photo", options={"mapping": {"uuid": "uuid"}})
     * @Method("POST")
     */
    public function changeRefArea(Request $request, Photo $photo): Response
    {
        if ($request->isMethod('POST') && $request->request->has('element')) {
            $elementId = (int)$request->request->get('element');

            $this->session->set('compare_area_by', $elementId);
        }

        return $this->redirectToRoute('app.photo', ['uuid' => $photo->getUuid()]);
    }

    public function transform(array $metadata, array $elements): array
    {
        $result = [];

        if (!empty($metadata['sectors'])) {
            foreach ($metadata['sectors'] as $k => $v) {

                $v['element'] = $elements[$v['element_id']];
                $v['geo'] = json_decode($v['geo'], true);
                $v['position'] = [
                    'top'    => $v['geo']['coordinates'][0][0][1],
                    'left'   => $v['geo']['coordinates'][0][0][0],
                    'width'  => $v['geo']['coordinates'][0][2][0] - $v['geo']['coordinates'][0][0][0],
                    'height' => $v['geo']['coordinates'][0][2][1] - $v['geo']['coordinates'][0][0][1],
                ];

                $result[] = $v;
            }
        }

        return $result;
    }
}
