<?php

namespace App\Security;

use App\Entity\Customer;
use App\Entity\User;
use LogicException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class CustomerVoter extends Voter
{
    const DELETE = 'delete';
    const VIEW = 'view';

    /**
     * Determines if the attribute and subject are supported by this voter.
     *
     * @param string $attribute An attribute
     * @param mixed  $subject   The subject to secure, e.g. an object the user wants to access or any other PHP type
     *
     * @return bool True if the attribute and subject are supported, false otherwise
     */
    protected function supports($attribute, $subject)
    {
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, [self::DELETE, self::VIEW])) {
            return false;
        }

        // only vote on Customer objects inside this voter
        if (!$subject instanceof Customer) {
            return false;
        }

        return true;
    }

    /**
     * Perform a single access check operation on a given attribute, subject and token.
     * It is safe to assume that $attribute and $subject already passed the "supports()" method check.
     *
     * @param string         $attribute
     * @param mixed          $subject
     * @param TokenInterface $token
     *
     * @return bool
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            // the user must be logged in; if not, deny access
            return false;
        }

        // you know $subject is a Customer object, thanks to supports
        /** @var Customer $customer */
        $customer = $subject;

        switch ($attribute) {
            case self::DELETE:
                return $this->canDelete($customer, $user);
            case self::VIEW:
                return $this->canView($customer, $user);
        }

        throw new LogicException('This code should not be reached!');
    }

    private function canDelete(Customer $customer, User $user)
    {
        // to get the entity of the user who owns this data object
        return $user === $customer->getUser();
    }

    private function canView(Customer $customer, User $user)
    {
        return $user === $customer->getUser();
    }
}
