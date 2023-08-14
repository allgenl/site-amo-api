<?php

namespace App\Model;

use AmoCRM\Collections\ContactsCollection;
use AmoCRM\Collections\Leads\LeadsCollection;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Models\LeadModel;

class Lead
{

    public static function createLead($apiClient, $contact, $price)
    {
        // Подключение
        $leadsService = $apiClient->leads();

        // Создание сделки
        $lead = new LeadModel();
        $lead->setName('Сделка: ' . $contact->getName() . ' — ' . $price . ' рублей')
            ->setPrice($price)
            ->setContacts(
                (new ContactsCollection())
                    ->add($contact)
            );

        // Добавление сделки
        $leadsCollection = new LeadsCollection();
        $leadsCollection->add($lead);

        try {
            $leadsCollection = $leadsService->add($leadsCollection);
        } catch (AmoCRMApiException $e) {
            printError($e);
            die;
        }

        return $leadsCollection;
    }
}