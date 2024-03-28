<?php

namespace App\Controller;

use App\Form\BugFormType;
use App\Service\ACoolService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SomeController extends AbstractController
{
    /**
     * When this controller is called the 1st time (To get the form)
     * the Mock object is passed in place of the actual "ACoolService". This
     * is expected.
     *
     * When the form is submitted in the same test, an instance of the actual
     * "ACoolService" is passed instead of the Mock object. This is not the
     * expected behavior.
     */
    #[Route(name: 'app_some')]
    public function index(Request $request, ACoolService $coolService): Response
    {
        $serviceName = $coolService::class;

        $form = $this->createForm(BugFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $coolService->doSomething();

            return $this->render('base.html.twig', [
                'service' => 'Submitted: '.$serviceName,
            ]);
        }

        return $this->render('base.html.twig', [
            'service' => 'Get Form: '.$serviceName,
            'form' => $form,
        ]);
    }
}
