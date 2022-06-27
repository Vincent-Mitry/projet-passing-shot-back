<?php

namespace App\Controller\Api\V1;

use Twig\Environment;
use App\Entity\Contact;
use App\Service\Api\ApiProblem;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mailer\Mailer;
use App\Repository\ContactRepository;
use Symfony\Component\Mailer\Transport;
use App\Service\Api\ApiConstraintErrors;
use App\Service\Api\ApiProblemException;
use App\Service\SendEmail;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Twig\Mime\BodyRenderer;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Contact class
 * @Route("api/v1", name="api_v1_")
 */
class ContactApiController extends AbstractController
{

    /**
     * @Route("/contacts", name="contact_list", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function contactList(ContactRepository $contactRepository): Response
    {
        //looking to find all contact in ContactRepository
        $contactsList = $contactRepository->findAll();

        //expecting a json format response grouping "contact_List" collection tag
        return $this->json(['contactsList' => $contactsList], Response::HTTP_OK, ['groups' => 'contact_list'], );
    }

    /**
     * @Route ("/contacts/{id}", name="contact_detail", methods={"GET"}, requirements={"id"="\d+"})
     * @return JsonResponse Json data
     */
    public function contactDetail(Contact $contact = null): JsonResponse
    {
        // 404 (not found) personalized response
        if ($contact === null) {
            $apiProblem = new ApiProblem(Response::HTTP_NOT_FOUND, ApiProblem::TYPE_USER_NOT_FOUND);
            throw new ApiProblemException($apiProblem);
        }

        //expecting a json format response grouping "Contact_detail" and User_see_reservations collection tag
        return $this->json(
            [
            'contact' => $contact
        ],
            Response::HTTP_OK,
            [
            'groups' => ['user_detail']
        ]
        );
    }

    public function __construct(
        MailerInterface $mailer
    ) {
        $this->mailer = $mailer;
    }


    /**
     * @Route("/contacts", name="contact_post", methods={"POST"})
     */
    public function contactPost(
        Request $request,
        SerializerInterface $serializer,
        ManagerRegistry $doctrine,
        ApiConstraintErrors $apiConstraintErrors,
        MailerInterface $mailer,
        SendEmail $sendEmail
    ) {
        // Gathering Json content from $request
        $jsonContent = $request->getContent();

        // We deserialize Json content in $contact variable
        $contact = $serializer->deserialize($jsonContent, Contact::class, 'json');

        // Check Validation Constraint Errors
        $constraintErrors = $apiConstraintErrors->constraintErrorsList($contact);
        if ($constraintErrors !== null) {
            $apiProblem = new ApiProblem(Response::HTTP_UNPROCESSABLE_ENTITY, ApiProblem::TYPE_VALIDATION_ERROR, $constraintErrors);
            throw new ApiProblemException($apiProblem);
        }
      
        // we save it in DB
        $em = $doctrine->getManager();
        $em->persist($contact);
        $em->flush();

        
        // Send email with SendEmail service
        $adressFrom = 'contact.passingshot@gmail.com';
        $addressTo = 'contact.passingshot@gmail.com';
        $replyTo = $contact->getEmail();
        $subject = 'Formulaire de contact : '.  $contact->getLastname() . ' ' . $contact->getFirstname();
        $htmlTemplate = '/email/contact.html.twig' ;
        $context = ['contact' => $contact];

        $sendEmail->execute($adressFrom, $addressTo, $replyTo, $subject, $htmlTemplate, $context, $mailer);
            
        return $this->json(
            //ID of created Contact
            ['id' => $contact->getId()],
            //status code 201 = created
            Response::HTTP_CREATED,
            [
                'Location' => $this->generateUrl('api_v1_contact_detail', ['id' => $contact->getId()])
                ]
        );
    }
}