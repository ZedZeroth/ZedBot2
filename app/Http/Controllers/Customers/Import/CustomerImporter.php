<?php

declare(strict_types=1);

namespace App\Http\Controllers\Customers\Import;

use App\Models\Customer;

class CustomerImporter// implements \App\Http\Controllers\MultiDomain\Interfaces\ImporterInterface
{
    /**
     * Uses the DTOs to create Customers for
     * any that do not already exist.
     *
     * @param array $modelDTOs
     * @param CustomerUpdater $customerUpdater
     * @param AccountUpdater $accountUpdater
     * @param ContactUpdater $contactUpdater
     * @param IdentityDocumentUpdater $identityDocumentUpdater
     * @param RiskAssessmentUpdater $riskAssessmentUpdater
     */
    public function import(
        array $modelDTOs,
        \App\Http\Controllers\Customers\Update\CustomerUpdater $customerUpdater,
        \App\Http\Controllers\Accounts\Update\AccountUpdater $accountUpdater,
        \App\Http\Controllers\Contacts\Update\ContactUpdater $contactUpdater,
        \App\Http\Controllers\IdentityDocuments\Update\IdentityDocumentUpdater $identityDocumentUpdater,
        \App\Http\Controllers\RiskAssessments\Update\RiskAssessmentUpdater $riskAssessmentUpdater
    ): bool {
        foreach ($modelDTOs as $customerDTO) {
            //Validate DTOs
            (new \App\Http\Controllers\MultiDomain\Validators\DtoValidator())
                ->validate(
                    dto: $customerDTO,
                    dtoName: 'customerDTO',
                    requiredProperties: [
                        'state',
                        'identifier',
                        'type',
                        'familyName',
                        'givenName1',
                        'givenName2',
                        'companyName',
                        'preferredName',
                        'dateOfBirth',
                        'placeOfBirth',
                        'nationality',
                        'residency',
                        'volumeSnapshot',
                        'sourceOfFiatFundsType',
                        'sourceOfFiatFundsQuote',
                        'sourceOfCvcFundsType',
                        'sourceOfCvcFundsQuote',
                        'destinationOfFiatFundsType',
                        'destinationOfFiatFundsQuote',
                        'destinationOfCvcFundsType',
                        'destinationOfCvcFundsQuote',
                        'accountDTOs',
                        'contactDTOs',
                        'identityDocumentDTOs',
                        'riskAssessmentDTOs'
                    ]
                );

            // Create customers
            $customer = $customerUpdater->update($customerDTO);

            // Create and assign accounts
            foreach ($customerDTO->accountDTOs as $accountDTO) {
                $accountDTO->customer_id = $customer->id;
                $account = $accountUpdater->update($accountDTO);
                if ($customer->id < 10) {
                    /*💬*/ // echo $customer->identifier . ' ' . $account->identifier . PHP_EOL;
                }
            }

            // Create and assign contacts
            foreach ($customerDTO->contactDTOs as $contactDTO) {
                $contactDTO->customer_id = $customer->id;
                $contactUpdater->update($contactDTO);
            }

            // Create and assign identity documents
            foreach ($customerDTO->identityDocumentDTOs as $identityDocumentDTO) {
                $identityDocumentDTO->customer_id = $customer->id;
                $identityDocumentUpdater->update($identityDocumentDTO);
            }

            // Create and assign risk assessments
            foreach ($customerDTO->riskAssessmentDTOs as $riskAssessmentDTO) {
                $riskAssessmentDTO->customer_id = $customer->id;
                $riskAssessmentUpdater->update($riskAssessmentDTO);
            }
        }
        return true;
    }
}
