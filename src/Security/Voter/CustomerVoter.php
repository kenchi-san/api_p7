<?php

namespace App\Security\Voter;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class CustomerVoter extends Voter
{
    const CUSTOMER_VIEW = 'CUSTOMER_VIEW';
    const CUSTOMER_EDIT = 'CUSTOMER_EDIT';
    const CUSTOMER_DELETE = 'CUSTOMER_DELETE';
    protected function supports(string $attribute, $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::CUSTOMER_EDIT, self::CUSTOMER_DELETE,self::CUSTOMER_VIEW])
            && $subject instanceof \App\Entity\Customer;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }
        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::CUSTOMER_VIEW:
            case self::CUSTOMER_EDIT:
            case self::CUSTOMER_DELETE:
            return $this->owner($subject, $user);
        }

        return false;
    }

    /**
     * @param $customer
     * @param User $user
     * @return bool
     */
    private function owner($customer, User $user): bool
    {

        if ($user->getId() == $customer->getUser()->getId()) {
            return true;
        }
        return false;
    }
}
