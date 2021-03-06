<?php

namespace Paloma\ShopBundle\Controller\Checkout;

use Paloma\Shop\Checkout\CheckoutInterface;
use Paloma\Shop\Error\CartIsEmpty;
use Paloma\Shop\Security\PalomaSecurityInterface;
use Paloma\ShopBundle\Controller\AbstractPalomaController;
use Paloma\ShopBundle\Serializer\PalomaSerializer;
use Paloma\ShopBundle\Serializer\SerializationConstants;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class CheckoutController extends AbstractPalomaController
{
    public function start(PalomaSecurityInterface $security)
    {
        $state = 'auth';

        if ($security->getCustomer()) {
            $state = 'delivery';
        }

        return $this->redirectToRoute('paloma_checkout_state', ['state' => $state]);
    }

    public function state(string $state, PalomaSerializer $serializer, CheckoutInterface $checkout, PalomaSecurityInterface $security, Request $request, LoggerInterface $log)
    {
        try {
            $order = $checkout->getOrderDraft();
        } catch (CartIsEmpty $e) {
            $log->info("Caught " . get_class($e) . " in checkout state: " . $state);
            return $this->redirectToRoute('paloma_catalog_home');
        }

        $user = $security->getUser();

        return $this->render('@PalomaShop/checkout/index.html.twig', [
            'order_json' => $serializer->serialize($order, SerializationConstants::OPTIONS_ORDER_DRAFT),
            'user_json' => $serializer->serialize($user, SerializationConstants::OPTIONS_USER),
            'errors_json' => $serializer->serialize($this->getFlashMessageErrors($request)),
            'state' => $state,
        ]);
    }

    public function success(Request $request, PalomaSecurityInterface $security)
    {
        $orderNumber = $request->getSession()->get('paloma-order-number');

        if (!$orderNumber) {
            return $this->redirectToRoute('paloma_catalog_home');
        }

        $customer = $security->getCustomer();
        $customerNumber = $customer ? $customer->getCustomerNumber() : null;

        return $this->render('@PalomaShop/checkout/success.html.twig', [
            'order_number' => $orderNumber,
            'customer_number' => $customerNumber,
            'logged_in' => $security->getUser() !== null,
        ]);
    }

    private function getFlashMessageErrors(Request $request)
    {
        /** @var Session $session */
        $session = $request->getSession();

        return $session->getFlashBag()->get('paloma.checkout_errors', []);
    }
}