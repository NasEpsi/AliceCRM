<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Entity\Partner;
use App\Form\CustomerType;
use App\Form\PartnerType;
use App\Repository\PartnerRepository;
use App\Repository\CustomerRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


#[Route('/admin/partenaire')]
class PartnerController extends AbstractController
{
    private $partnerRepository;
    private $customerRepository;

    public function __construct(PartnerRepository $partnerRepository, CustomerRepository $customerRepository)
    {
        $this->partnerRepository = $partnerRepository;
        $this->customerRepository = $customerRepository;
    }


    #[Route('/', name: 'app_partner_list', methods: ['GET'])]
    public function partnerList(): Response
    {
        $partners = $this->partnerRepository->findAll();
        $customers = $this->partnerRepository->findCustomersByPartners($partners);
    
        return $this->render('partner/list.html.twig', [
            'partners' => $partners,
            'customers' => $customers
        ]);
    }
    
    #[Route('/ajouter', name: 'app_partner_add', methods: ['GET', 'POST'])]
    public function partnerAdd(Request $request): Response
    {
        $partner = new Partner();
        $form = $this->createForm(PartnerType::class, $partner);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->partnerRepository->save($partner, true);

            $this->addFlash(
                'success',
                'La création du nouveau partenariat est bien enregistrée.'
            );

            return $this->redirectToRoute('app_customer_add', ['partnerId' => $partner->getId()]);
        }

        return $this->render('partner/new.html.twig', [
            'partner' => $partner,
            'form' => $form,
            'flash' => $this
        ]);
    }

    #[Route('/{id}', name: 'app_partner_show', methods: ['GET'])]
    public function partnerShow(Partner $partner): Response
    {
        $customers = $this->customerRepository->findBy(['partner' => $partner]);
    
        return $this->render('partner/show.html.twig', [
            'partner' => $partner,
            'customers' => $customers
        ]);
    }

    #[Route('/{id}/modifier', name: 'app_partner_edit', methods: ['GET', 'POST'])]
    public function partnerEdit(Request $request, Partner $partner): Response
    {
        $form = $this->createForm(PartnerType::class, $partner);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->partnerRepository->save($partner, true);

            return $this->redirectToRoute('app_partner_list', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('partner/edit.html.twig', [
            'partner' => $partner,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_partner_delete', methods: ['POST'])]
    public function partnerDelete(Request $request, Partner $partner): Response
    {
        if ($this->isCsrfTokenValid('delete'.$partner->getId(), $request->request->get('_token'))) {
            $this->partnerRepository->remove($partner, true);
        }

        return $this->redirectToRoute('app_partner_list', [], Response::HTTP_SEE_OTHER);
    }
    
    #[Route('/ajouter/clients/{partnerId}', name: 'app_customer_add', methods: ['GET', 'POST'])]
    public function customerAdd(Request $request,?string $partnerId = null ): Response
    {
        if ($partnerId) {
            $partner = $this->partnerRepository->find($partnerId);
    
            if (!$partner) {
                throw $this->createNotFoundException("Le partenariat avec l'ID $partnerId n'existe pas.");
            }
        } else {
            $partner = null;
        }

        // Si un client existant est sélectionné
        if ($request->get('existingCustomer')) {
            $existingCustomer = $this->customerRepository->find($request->get('existingCustomer'));
            if ($existingCustomer) {
                $existingCustomer->setPartner($partner);  // Associer le client au partenariat
                $this->customerRepository->save($existingCustomer, true);

                $this->addFlash('success', 'Le client a bien été associé au partenariat.');
                return $this->redirectToRoute('app_partner_list');
            }
        }

        // Formulaire pour créer un nouveau client
        $customer = new Customer();
        $customer->setPartner($partner); // Lier le client au partenaire
        $customerForm = $this->createForm(CustomerType::class, $customer);
        $customerForm->handleRequest($request);

        if ($customerForm->isSubmitted() && $customerForm->isValid()) {
            $this->customerRepository->save($customer, true);
            $this->addFlash('success', 'Le client a été créé avec succès.');

            return $this->redirectToRoute('app_partner_list');
        }

        return $this->render('partner/add.html.twig', [
            'form' => $customerForm->createView(),
            'partner' => $partner,
            'existingCustomers' => $this->customerRepository->findBy([]), 
        ]);
    }


}
