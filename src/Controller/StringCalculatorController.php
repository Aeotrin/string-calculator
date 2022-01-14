<?php

namespace App\Controller;

use App\Service\StringCalculatorService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StringCalculatorController extends AbstractController
{
    #[Route('/calculator/{input}', name: 'string_calculator')]
    public function index(StringCalculatorService $stringCalculatorService, String $input): Response
    {
        $result = $stringCalculatorService->add($input);

        return $this->json([
            'input' => $input,
            'result' => $result,
        ]);
    }

}
