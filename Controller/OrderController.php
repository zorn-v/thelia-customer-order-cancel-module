<?php

namespace CustomerOrderCancel\Controller;

use Thelia\Controller\Front\BaseFrontController;
use Thelia\Model\OrderQuery;
use Thelia\Model\OrderStatusQuery;
use Thelia\Core\Event\Order\OrderEvent;
use Thelia\Core\Event\TheliaEvents;
use Thelia\Log\Tlog;

class OrderController extends BaseFrontController
{
    public function cancelOrder($orderId)
    {
        try {
            $this->getTokenProvider()->checkToken(
                $this->getRequest()->query->get('_token')
            );

            $order = OrderQuery::create()->findPk($orderId);
            if ($order === null) {
                throw new \InvalidArgumentException('Cannot find order with id='.$orderId);
            }

            if ($order->getStatusId() != OrderStatusQuery::getNotPaidStatus()->getId()) {
                throw new \InvalidArgumentException('Order status must be "not paid"');
            }

            $event = new OrderEvent($order);
            $event->setStatus(OrderStatusQuery::getCancelledStatus()->getId());

            $this->getDispatcher()->dispatch(TheliaEvents::ORDER_UPDATE_STATUS, $event);
        } catch (\Exception $e) {
            Tlog::getInstance()->error(sprintf("error during canceling order %d with message : %s", $orderId, $e->getMessage()));
            $this->getParserContext()->setGeneralError($e->getMessage());
        }

        return $this->generateRedirectFromRoute('customer.home');
    }
}
