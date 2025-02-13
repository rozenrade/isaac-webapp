<?php

declare(strict_types=1);

namespace App\Controller;

use App\Document\ContactMessage;
use App\Form\ContactType;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function contact(Request $request, DocumentManager $dm): Response
    {
        $contactMessage = new ContactMessage();
        $form = $this->createForm(ContactType::class, $contactMessage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $dm->persist($contactMessage);
            $dm->flush();

            $this->addFlash('success', 'Your message has been sent successfully !');

            return $this->redirectToRoute('app_contact');
        }

        return $this->render('contact/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/messages', name: 'app_admin_messages')]
    public function adminMessages(DocumentManager $dm): Response
    {
        $messages = $dm->getRepository(ContactMessage::class)->findAll();

        return $this->render('contact/admin_messages.html.twig', [
            'messages' => $messages,
        ]);
    }

    #[Route('/admin/messages/delete/{id}', name: 'app_contact_delete')]
    public function deleteMessage(DocumentManager $dm, int $id): Response
    {
        $contactMessage = $dm->getRepository(ContactMessage::class)->find($id);
        if (!$contactMessage) {
            throw $this->createNotFoundException('Message not found');
        }
        $dm->remove($contactMessage);
        $dm->flush();

        return $this->redirectToRoute('app_admin_messages');
    }
}

