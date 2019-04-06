<?php

namespace App\Controller;

use App\Entity\Photo;
use App\Form\PictureUploadType;
use App\Services\Upload\UploadService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UploadController extends AbstractController
{
    protected $uploadService;

    public function __construct(UploadService $uploadService)
    {
        $this->uploadService = $uploadService;
    }

    /**
     * @Route("/upload", name="app.upload")
     */
    public function index(Request $request)
    {
        $photo = new Photo();

        $form = $this->createForm(PictureUploadType::class, $photo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($photo);
            $em->flush();

         #   return $this->redirectToRoute('app.index');
        }

        return $this->render('upload/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
