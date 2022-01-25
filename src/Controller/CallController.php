<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\CallRepository;
use App\Service\FileUploader;
use App\Service\CallService;

class CallController extends AbstractController {

    public function index(): Response {
        return $this->render('form/file_upload.html.twig', []);
    }

    public function upload(Request $request, FileUploader $fileUploader, CallService $callService, CallRepository $repository): Response {

        $file = $request->files->get('file');
        $callback = 0;

        if ($file) {
            $newFilename = $fileUploader->upload($file);
            $records = $callService->readCsv($newFilename);
            $callback = $repository->store($records);
        }
        return $this->json(['data' => $callback]);
    }

    public function calls(CallRepository $repository, CallService $callService) {
        $calls = $repository->findAll();
        $callback = $callService->filter($calls);
        return $this->json($callback);
    }

}
