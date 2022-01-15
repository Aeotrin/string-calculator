<?php

namespace App\Controller;

use App\Service\StringCalculatorService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StringCalculatorController extends AbstractController
{
    #[Route('/calculator', name: 'string_calculator')]
    public function calculate(StringCalculatorService $stringCalculatorService, Request $request): Response
    {
        $defaultData = ['message' => 'Type your message here'];
        $form = $this->createFormBuilder($defaultData)
            ->add('input', TextType::class, [
                'label' => 'Calculator Input ',
                'required' => false,
                'attr' => [
                    'placeholder' => '//[delimiter]\n[delimiter separated numbers] or [comma separated numbers]',
                ],
            ])
            ->add('calculate', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $input = stripcslashes($data['input']);
            $delimeters = $stringCalculatorService->getDelimeter($input);
            $result = $stringCalculatorService->add($input);

            return $this->renderForm('forms/stringCalculator.html.twig', [
                'input' => $data['input'],
                'delimeters' => $delimeters,
                'result' => $result,
                'form' => $form,
            ]);
        }

        return $this->renderForm('forms/stringCalculator.html.twig', [
            'form' => $form,
        ]);
    }

}
