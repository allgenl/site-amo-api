<?php

namespace App\Model;

use AmoCRM\Collections\CustomFieldsValuesCollection;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Models\ContactModel;
use AmoCRM\Models\CustomFieldsValues\MultitextCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\MultitextCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueModels\MultitextCustomFieldValueModel;

class Contact
{
    public static function createContact($apiClient, $name, $email, $phone)
    {
        // Создание контакта
        $contact = new ContactModel();
        $contact->setName($name);

        // Получим коллекцию значений полей контакта
        $customFields = $contact->getCustomFieldsValues();

        // Если значений нет, инициализируем класс
        if ($customFields === NULL) {
            $customFields = (new CustomFieldsValuesCollection());
        }

        // Если поля нет - создадим его
        if (empty($phoneField)) {
            $phoneField = (new MultitextCustomFieldValuesModel())->setFieldCode('PHONE');
        }

        if (empty($emailField)) {
            $emailField = (new MultitextCustomFieldValuesModel())->setFieldCode('EMAIL');
        }

        // Установим значения
        $phoneField->setValues(
            (new MultitextCustomFieldValueCollection())
                ->add(
                    (new MultitextCustomFieldValueModel())
                        ->setValue($phone)
                )
        );

        $emailField->setValues(
            (new MultitextCustomFieldValueCollection())
                ->add(
                    (new MultitextCustomFieldValueModel())
                        ->setValue($email)
                )
        );

        // Добавим в коллекцию
        $customFields->add($phoneField);
        $customFields->add($emailField);

        // Обновим коллекцию в контакте
        $contact->setCustomFieldsValues($customFields);

        // Добавим контакт в Amo
        try {
            $contactModel = $apiClient->contacts()->addOne($contact);
        } catch (AmoCRMApiException $e) {
            printError($e);
            die;
        }

        return$contact;
    }
}