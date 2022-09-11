<?php

declare(strict_types=1);

/*
 * Copyright Humbly Arrogant Ltd 2020-2022, all rights reserved.
 */

namespace Parthenon\User\Repository;

use Parthenon\Athena\Repository\DoctrineCrudRepository;
use Parthenon\Common\Exception\NoEntityFoundException;
use Parthenon\User\Entity\TeamInterface;
use Parthenon\User\Entity\User;

class UserRepository extends DoctrineCrudRepository implements UserRepositoryInterface, ActiveMembersRepositoryInterface
{
    /**
     * @throws NoEntityFoundException
     */
    public function findByEmail($username): User
    {
        $user = $this->entityRepository->findOneBy(['email' => $username, 'isDeleted' => false]);

        if (!$user || $user->isDeleted()) {
            throw new NoEntityFoundException();
        }

        return $user;
    }

    /**
     * {@inheritdoc}
     */
    public function findByConfirmationCode(string $confirmationCode): User
    {
        $user = $this->entityRepository->findOneBy(['confirmationCode' => $confirmationCode]);

        if (!$user || $user->isDeleted()) {
            throw new NoEntityFoundException();
        }

        return $user;
    }

    public function getUserSignupStats(): array
    {
        $twentyFourHours = new \DateTime('-24 hours');
        $fourtyEightHours = new \DateTime('-48 hours');

        $oneWeek = new \DateTime('-1 week');
        $twoWeeks = new \DateTime('-2 weeks');

        $todayCount = $this->entityRepository->createQueryBuilder('u')
            ->select('COUNT(u.id) as user_count')
            ->where('u.createdAt >= :twentyFourHours')
            ->setParameter(':twentyFourHours', $twentyFourHours)->getQuery()
            ->getSingleResult();
        $yesterdayCount = $this->entityRepository->createQueryBuilder('u')
            ->select('COUNT(u.id) as user_count')
            ->where('u.createdAt < :twentyFourHours')
            ->andWhere('u.createdAt >= :fourtyEightHours')
            ->setParameter(':twentyFourHours', $twentyFourHours)
            ->setParameter(':fourtyEightHours', $fourtyEightHours)
            ->getQuery()
            ->getSingleResult();

        $weekCount = $this->entityRepository->createQueryBuilder('u')
            ->select('COUNT(u.id) as user_count')
            ->where('u.createdAt >= :oneWeek')
            ->setParameter(':oneWeek', $oneWeek)
            ->getQuery()
            ->getSingleResult();

        $lastWeekCount = $this->entityRepository->createQueryBuilder('u')
            ->select('COUNT(u.id) as user_count')
            ->where('u.createdAt < :oneWeek')
            ->andWhere('u.createdAt >= :twoWeeks')
            ->setParameter(':oneWeek', $oneWeek)
            ->setParameter(':twoWeeks', $twoWeeks)
            ->getQuery()
            ->getSingleResult();

        return [
            'twenty_four_hour_count' => current($todayCount),
            'previous_twenty_four_hour_count' => current($yesterdayCount),
            'this_week_count' => count($weekCount),
            'last_week_count' => count($lastWeekCount),
        ];
    }

    public function getEntity()
    {
        $className = $this->entityRepository->getClassName();

        return new $className();
    }

    public function getCountForActiveTeamMemebers(TeamInterface $team): int
    {
        return $this->entityRepository->count(['team' => $team, 'isDeleted' => false]);
    }

    public function getMembers(TeamInterface $team): array
    {
        return $this->entityRepository->findBy(['team' => $team]);
    }
}
